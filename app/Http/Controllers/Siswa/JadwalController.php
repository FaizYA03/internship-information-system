<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Lab\JadwalLaboratorium;
use App\Models\Labor;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Jadwal Kelas Saya';
        $header = 'Jadwal Penggunaan Laboratorium';
        
        $user = Auth::user();
        $siswa = $user->siswa;
        
        $query = JadwalLaboratorium::with(['labor', 'guru', 'kelas_relation']);
        $studentClass = null;

        if ($user->role == 'guru') {
            $title = 'Jadwal Mengajar Saya';
            $header = 'Jadwal Mengajar Laboratorium';
            $query->where('guru_id', $user->id);
        } else {
            if (!$siswa || !$siswa->kelas_id) {
                $daysOrder = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                $todayIndo = $this->getIndoDay(Carbon::now());
                return view('siswa.main.jadwal.index', [
                    'title' => $title,
                    'header' => $header,
                    'jadwalGrouped' => collect(),
                    'stats' => $this->getEmptyStats(),
                    'error' => 'Data kelas tidak ditemukan. Silakan hubungi admin.',
                    'daysOrder' => $daysOrder,
                    'todayIndo' => $todayIndo
                ]);
            }
            
            $kelas_id = $siswa->kelas_id;
            $studentClass = $siswa->kelas;
            $query->where('kelas_id', $kelas_id);
        }
            
        // Get all schedules for the list (sorted by day and time)
        // Note: 'hari' is 'Senin', 'Selasa', etc. Sorting by 'hari' needs a custom order.
        $daysOrder = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $daysOrderString = implode("','", $daysOrder);
        
        $allSchedules = $query->orderByRaw("FIELD(hari, '$daysOrderString')")
            ->orderBy('jam_mulai', 'asc')
            ->get();
            
        // Calculate Stats
        $stats = $this->calculateStats($allSchedules);
        
        // Add status and colors to each item
        $now = Carbon::now();
        $currentTime = $now->format('H:i:s');
        $todayIndo = $this->getIndoDay($now);
        
        foreach ($allSchedules as $item) {
            $this->processItemStatus($item, $todayIndo, $currentTime);
        }
        
        // Group by day for the accordion or just by lab as requested?
        // User asked for "CARD + ACCORDION (bukan table)".
        // "Setiap jadwal tampil sebagai mini card".
        // Usually grouping by Day is more intuitive for a "Jadwal" page.
        // But the previous layout grouped by Labor.
        // Let's group by Day as it's a "Student Schedule".
        $jadwalGrouped = $allSchedules->groupBy('hari');

        // Formatter untuk Kalender
        $dayNumberMap = [
            'Senin' => 1,
            'Selasa' => 2,
            'Rabu' => 3,
            'Kamis' => 4,
            'Jumat' => 5,
            'Sabtu' => 6,
            'Minggu' => 0,
        ];

        $calendarEvents = [];
        foreach ($allSchedules as $schedule) {
            if (isset($dayNumberMap[$schedule->hari])) {
                $statusColor = $schedule->status_class == 'ongoing' ? '#10b981' : 
                              ($schedule->status_class == 'upcoming' ? '#3b82f6' : '#94a3b8');
                              
                $calendarEvents[] = [
                    'title' => $schedule->mata_pelajaran,
                    'daysOfWeek' => [ $dayNumberMap[$schedule->hari] ],
                    'startTime' => $schedule->jam_mulai,
                    'endTime' => $schedule->jam_selesai,
                    'color' => $statusColor,
                    'labor' => $schedule->labor->nama_labor ?? 'N/A',
                    'guru' => $schedule->guru->nama ?? '-',
                    'kelas' => $schedule->kelas_relation->nama_kelas ?? $schedule->kelas,
                    'status' => $schedule->status_label,
                    'status_class' => $schedule->status_class
                ];
            }
        }

        return view('siswa.main.jadwal.index', compact(
            'title', 
            'header', 
            'jadwalGrouped', 
            'stats',
            'studentClass',
            'daysOrder',
            'todayIndo',
            'calendarEvents'
        ));
    }

    private function calculateStats($schedules)
    {
        $now = Carbon::now();
        $todayIndo = $this->getIndoDay($now);
        $currentTime = $now->format('H:i:s');
        
        $stats = [
            'total_minggu' => $schedules->count(),
            'hari_ini' => $schedules->where('hari', $todayIndo)->count(),
            'sedang_berlangsung' => $schedules->filter(function($item) use ($todayIndo, $currentTime) {
                return $item->hari == $todayIndo && 
                       $currentTime >= $item->jam_mulai && 
                       $currentTime <= $item->jam_selesai;
            })->count(),
            'lab_digunakan' => $schedules->pluck('labor_id')->unique()->count(),
        ];
        
        return $stats;
    }

    private function getEmptyStats()
    {
        return [
            'total_minggu' => 0,
            'hari_ini' => 0,
            'sedang_berlangsung' => 0,
            'lab_digunakan' => 0,
        ];
    }

    private function processItemStatus($item, $todayIndo, $currentTime)
    {
        if ($item->hari !== $todayIndo) {
            // Check if upcoming or past in the week?
            // For simplicity, if not today, it's just "Akan Datang" if we only show current week.
            // But we don't have a date, only day name.
            // Let's just say "Jadwal Terdaftar" or something, but the user asked for specific statuses.
            // "Akan Datang (Biru)", "Selesai (Abu)".
            
            // Logic for day comparison
            $daysOrder = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
            $todayIndex = array_search($todayIndo, $daysOrder);
            $itemIndex = array_search($item->hari, $daysOrder);
            
            if ($itemIndex < $todayIndex) {
                $item->status_label = 'Selesai';
                $item->status_class = 'finished';
                $item->status_color = 'gray';
            } else {
                $item->status_label = 'Akan Datang';
                $item->status_class = 'upcoming';
                $item->status_color = 'blue';
            }
        } else {
            // It is TODAY
            if ($currentTime < $item->jam_mulai) {
                $item->status_label = 'Akan Datang';
                $item->status_class = 'upcoming';
                $item->status_color = 'blue';
            } elseif ($currentTime >= $item->jam_mulai && $currentTime <= $item->jam_selesai) {
                $item->status_label = 'Sedang Berlangsung';
                $item->status_class = 'ongoing';
                $item->status_color = 'green';
            } else {
                $item->status_label = 'Selesai';
                $item->status_class = 'finished';
                $item->status_color = 'gray';
            }
        }
    }

    public static function getIndoDay($date)
    {
        $daysMap = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];
        return $daysMap[$date->format('l')];
    }
}