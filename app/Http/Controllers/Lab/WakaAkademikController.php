<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Lab\ActivityLog;
use App\Models\Lab\JadwalLaboratorium;
use App\Models\Lab\LaporanKerusakan;
use App\Models\Labor;
use App\Models\User;

class WakaAkademikController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:waka_akademik,super_admin']);
    }

    /**
     * Main Dashboard — KPI cards, charts, conflict detection, alerts
     */
    public function index()
    {
        // === KPI Stats ===
        $totalLab = Labor::count();

        $today = now()->locale('id')->isoFormat('dddd'); // e.g. "Senin"
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        // Map isoFormat day to our hari values
        $todayHari = \Carbon\Carbon::now()->locale('id')->isoFormat('dddd');

        $jadwalAktif = JadwalLaboratorium::where('status_validasi', 'disetujui')->count();
        $jadwalHariIni = JadwalLaboratorium::where('hari', $todayHari)->count();
        $guruMengajar = JadwalLaboratorium::where('status_validasi', 'disetujui')
            ->distinct('guru_id')->count('guru_id');

        // Utilisasi: (jadwal aktif / max slot) * 100
        $maxSlot = max($totalLab * 6, 1);
        $utiliasasiPersen = min(100, round(($jadwalAktif / $maxSlot) * 100, 1));

        // === Pending Validasi ===
        $pendingValidasi = JadwalLaboratorium::where('status_validasi', 'menunggu')
            ->with(['labor', 'guru'])
            ->latest()
            ->take(5)
            ->get();

        $totalPending = JadwalLaboratorium::where('status_validasi', 'menunggu')->count();

        // === Conflict Detection ===
        // Guru bentrok: same guru, same hari, overlapping time
        $bentrokGuru = DB::table('jadwal_laboratorium as a')
            ->join('jadwal_laboratorium as b', function ($join) {
                $join->on('a.guru_id', '=', 'b.guru_id')
                     ->on('a.hari', '=', 'b.hari')
                     ->where('a.id', '<', DB::raw('b.id'));
            })
            ->where(function ($q) {
                $q->whereRaw('a.jam_mulai < b.jam_selesai')
                  ->whereRaw('a.jam_selesai > b.jam_mulai');
            })
            ->select('a.guru_id', 'a.hari', 'a.jam_mulai as a_mulai', 'a.jam_selesai as a_selesai',
                     'b.jam_mulai as b_mulai', 'b.jam_selesai as b_selesai',
                     'a.kelas as a_kelas', 'b.kelas as b_kelas')
            ->limit(5)
            ->get();

        // Lab bentrok: same lab, same hari, overlapping time
        $bentrokLab = DB::table('jadwal_laboratorium as a')
            ->join('jadwal_laboratorium as b', function ($join) {
                $join->on('a.labor_id', '=', 'b.labor_id')
                     ->on('a.hari', '=', 'b.hari')
                     ->where('a.id', '<', DB::raw('b.id'));
            })
            ->where(function ($q) {
                $q->whereRaw('a.jam_mulai < b.jam_selesai')
                  ->whereRaw('a.jam_selesai > b.jam_mulai');
            })
            ->select('a.labor_id', 'a.hari', 'a.kelas as a_kelas', 'b.kelas as b_kelas',
                     'a.jam_mulai', 'a.jam_selesai')
            ->limit(5)
            ->get();

        // === Guru Overload (> 4 jadwal) ===
        $guruOverload = JadwalLaboratorium::select('guru_id', DB::raw('count(*) as total'))
            ->groupBy('guru_id')
            ->having('total', '>', 4)
            ->with('guru')
            ->get();

        // === Lab Idle (no schedule at all) ===
        $labDenganJadwal = JadwalLaboratorium::pluck('labor_id')->unique();
        $labIdle = Labor::whereNotIn('id', $labDenganJadwal)->get();

        // === Weekly Chart Data (jadwal per hari) ===
        $weeklyData = [];
        foreach ($hariList as $hari) {
            $weeklyData[] = JadwalLaboratorium::where('hari', $hari)->count();
        }

        // === Lab Usage Rank for donut (top 5) ===
        $labUsage = JadwalLaboratorium::select('labor_id', DB::raw('count(*) as total'))
            ->groupBy('labor_id')
            ->with('labor')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        // === Alert Count ===
        $alertBentrokGuru = DB::table('jadwal_laboratorium as a')
            ->join('jadwal_laboratorium as b', function ($join) {
                $join->on('a.guru_id', '=', 'b.guru_id')
                     ->on('a.hari', '=', 'b.hari')
                     ->where('a.id', '<', DB::raw('b.id'));
            })
            ->where(function ($q) {
                $q->whereRaw('a.jam_mulai < b.jam_selesai')
                  ->whereRaw('a.jam_selesai > b.jam_mulai');
            })
            ->count();

        $alertKerusakanBelumDitangani = LaporanKerusakan::where('status_perbaikan', 'Menunggu')
            ->orWhere('status_perbaikan', 'menunggu')
            ->count();

        $alertCount = ($alertBentrokGuru > 0 ? 1 : 0)
            + (count($bentrokLab) > 0 ? 1 : 0)
            + ($alertKerusakanBelumDitangani > 0 ? 1 : 0)
            + (count($labIdle) > 0 ? 1 : 0);

        // === Recent Logs ===
        $logs = ActivityLog::with('user')->latest()->take(8)->get();

        return view('lab.waka_akademik.dashboard', compact(
            'totalLab', 'jadwalAktif', 'jadwalHariIni', 'guruMengajar',
            'utiliasasiPersen', 'pendingValidasi', 'totalPending',
            'bentrokGuru', 'bentrokLab', 'guruOverload', 'labIdle',
            'weeklyData', 'hariList', 'labUsage', 'alertCount',
            'alertBentrokGuru', 'alertKerusakanBelumDitangani', 'logs'
        ));
    }

    /**
     * Schedule Validation — list all jadwal by status with approve/reject actions
     */
    public function validasiJadwal(Request $request)
    {
        $status = $request->get('status', 'menunggu');

        $jadwal = JadwalLaboratorium::with(['labor', 'guru'])
            ->when($status !== 'semua', fn($q) => $q->where('status_validasi', $status))
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->paginate(20)
            ->appends(['status' => $status]);

        $counts = [
            'draft'     => JadwalLaboratorium::where('status_validasi', 'draft')->count(),
            'menunggu'  => JadwalLaboratorium::where('status_validasi', 'menunggu')->count(),
            'disetujui' => JadwalLaboratorium::where('status_validasi', 'disetujui')->count(),
            'ditolak'   => JadwalLaboratorium::where('status_validasi', 'ditolak')->count(),
        ];

        return view('lab.waka_akademik.validasi_jadwal', compact('jadwal', 'status', 'counts'));
    }

    /**
     * Approve a jadwal
     */
    public function approveJadwal(Request $request, $id)
    {
        $jadwal = JadwalLaboratorium::findOrFail($id);
        $jadwal->update([
            'status_validasi'  => 'disetujui',
            'catatan_validasi' => null,
        ]);

        ActivityLog::create([
            'user_id'      => auth()->id(),
            'action'       => 'approved',
            'description'  => "Jadwal {$jadwal->mata_pelajaran} ({$jadwal->kelas}) pada hari {$jadwal->hari} disetujui oleh Waka Kurikulum.",
            'subject_type' => JadwalLaboratorium::class,
            'subject_id'   => $jadwal->id,
            'ip_address'   => $request->ip(),
        ]);

        return back()->with('success', 'Jadwal berhasil disetujui.');
    }

    /**
     * Reject a jadwal with optional notes
     */
    public function rejectJadwal(Request $request, $id)
    {
        $request->validate([
            'catatan_validasi' => 'nullable|string|max:500',
        ]);

        $jadwal = JadwalLaboratorium::findOrFail($id);
        $jadwal->update([
            'status_validasi'  => 'ditolak',
            'catatan_validasi' => $request->catatan_validasi,
        ]);

        ActivityLog::create([
            'user_id'      => auth()->id(),
            'action'       => 'rejected',
            'description'  => "Jadwal {$jadwal->mata_pelajaran} ({$jadwal->kelas}) ditolak oleh Waka Kurikulum.",
            'subject_type' => JadwalLaboratorium::class,
            'subject_id'   => $jadwal->id,
            'ip_address'   => $request->ip(),
        ]);

        return back()->with('error', 'Jadwal telah ditolak dengan catatan revisi.');
    }

    /**
     * Lab Monitoring — usage ranking, idle labs, utilization
     */
    public function monitoringLab()
    {
        $labs = Labor::withCount(['jadwalTetap as total_jadwal'])->get();
        $totalJadwal = JadwalLaboratorium::count();

        // Sort by jadwal count
        $labsByUsage = $labs->sortByDesc('total_jadwal');

        // Lab idle (no jadwal)
        $labIdle = $labs->where('total_jadwal', 0);

        // Lab with jadwal: calculate utilization %
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $maxSlotPerLab = 6; // 6 days × 1 slot = basic metric

        $labStats = [];
        foreach ($labs as $lab) {
            $jadwalPerHari = [];
            foreach ($hariList as $hari) {
                $jadwalPerHari[$hari] = JadwalLaboratorium::where('labor_id', $lab->id)
                    ->where('hari', $hari)->count();
            }
            $labStats[] = [
                'lab'          => $lab,
                'total_jadwal' => $lab->total_jadwal,
                'per_hari'     => $jadwalPerHari,
                'utilisasi'    => $maxSlotPerLab > 0
                    ? min(100, round(($lab->total_jadwal / $maxSlotPerLab) * 100))
                    : 0,
            ];
        }

        // Sort by utilisasi desc
        usort($labStats, fn($a, $b) => $b['utilisasi'] - $a['utilisasi']);

        return view('lab.waka_akademik.monitoring_lab', compact(
            'labs', 'labStats', 'labIdle', 'hariList', 'totalJadwal'
        ));
    }

    /**
     * Alerts Center
     */
    public function alerts()
    {
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        // Guru conflicts
        $bentrokGuru = DB::table('jadwal_laboratorium as a')
            ->join('jadwal_laboratorium as b', function ($join) {
                $join->on('a.guru_id', '=', 'b.guru_id')
                     ->on('a.hari', '=', 'b.hari')
                     ->where('a.id', '<', DB::raw('b.id'));
            })
            ->where(function ($q) {
                $q->whereRaw('a.jam_mulai < b.jam_selesai')
                  ->whereRaw('a.jam_selesai > b.jam_mulai');
            })
            ->join('users as u', 'a.guru_id', '=', 'u.id')
            ->select('u.nama as guru_nama', 'a.hari', 'a.jam_mulai as a_mulai',
                     'a.jam_selesai as a_selesai', 'a.kelas as a_kelas',
                     'b.jam_mulai as b_mulai', 'b.jam_selesai as b_selesai',
                     'b.kelas as b_kelas')
            ->get();

        // Lab conflicts
        $bentrokLab = DB::table('jadwal_laboratorium as a')
            ->join('jadwal_laboratorium as b', function ($join) {
                $join->on('a.labor_id', '=', 'b.labor_id')
                     ->on('a.hari', '=', 'b.hari')
                     ->where('a.id', '<', DB::raw('b.id'));
            })
            ->where(function ($q) {
                $q->whereRaw('a.jam_mulai < b.jam_selesai')
                  ->whereRaw('a.jam_selesai > b.jam_mulai');
            })
            ->join('labor as l', 'a.labor_id', '=', 'l.id')
            ->select('l.nama_labor as lab_nama', 'a.hari',
                     'a.kelas as a_kelas', 'b.kelas as b_kelas',
                     'a.jam_mulai', 'a.jam_selesai')
            ->get();

        // Guru overload
        $guruOverload = JadwalLaboratorium::select('guru_id', DB::raw('count(*) as total'))
            ->groupBy('guru_id')
            ->having('total', '>', 4)
            ->with('guru')
            ->get();

        // Lab idle
        $labDenganJadwal = JadwalLaboratorium::pluck('labor_id')->unique();
        $labIdle = Labor::whereNotIn('id', $labDenganJadwal)->get();

        // Kerusakan belum ditangani
        $kerusakanPending = LaporanKerusakan::whereIn('status_perbaikan', ['Menunggu', 'menunggu', 'pending'])
            ->with('inventaris')
            ->latest()
            ->get();

        // Jadwal pending > 3 days old
        $jadwalMenggantung = JadwalLaboratorium::where('status_validasi', 'menunggu')
            ->where('created_at', '<', now()->subDays(3))
            ->with(['labor', 'guru'])
            ->get();

        return view('lab.waka_akademik.alerts', compact(
            'bentrokGuru', 'bentrokLab', 'guruOverload', 'labIdle',
            'kerusakanPending', 'jadwalMenggantung'
        ));
    }

    /**
     * Export schedule report as CSV
     */
    public function exportLaporan()
    {
        $jadwal = JadwalLaboratorium::with(['labor', 'guru'])->orderBy('hari')->get();

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="laporan_jadwal_' . now()->format('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($jadwal) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No', 'Mata Pelajaran', 'Laboratorium', 'Guru', 'Kelas', 'Hari', 'Jam Mulai', 'Jam Selesai', 'Status Validasi', 'Keterangan']);

            foreach ($jadwal as $i => $j) {
                fputcsv($file, [
                    $i + 1,
                    $j->mata_pelajaran,
                    $j->labor->nama_labor ?? '-',
                    $j->guru->nama ?? '-',
                    $j->kelas,
                    $j->hari,
                    $j->jam_mulai,
                    $j->jam_selesai,
                    strtoupper($j->status_validasi ?? 'draft'),
                    $j->keterangan ?? '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Enhanced Activity Log with role filter and daily summary
     */
    public function monitoring(Request $request)
    {
        $roleFilter = $request->get('role', '');
        $tanggal    = $request->get('tanggal', '');

        $query = ActivityLog::with('user')->latest();

        if ($roleFilter) {
            $query->whereHas('user', fn($q) => $q->where('role', $roleFilter));
        }

        if ($tanggal) {
            $query->whereDate('created_at', $tanggal);
        }

        $activities = $query->paginate(25)->appends([
            'role'    => $roleFilter,
            'tanggal' => $tanggal,
        ]);

        // Today summary
        $todaySummary = [
            'total'    => ActivityLog::whereDate('created_at', today())->count(),
            'created'  => ActivityLog::whereDate('created_at', today())->where('action', 'created')->count(),
            'updated'  => ActivityLog::whereDate('created_at', today())->where('action', 'updated')->count(),
            'deleted'  => ActivityLog::whereDate('created_at', today())->where('action', 'deleted')->count(),
            'approved' => ActivityLog::whereDate('created_at', today())->where('action', 'approved')->count(),
            'rejected' => ActivityLog::whereDate('created_at', today())->where('action', 'rejected')->count(),
        ];

        $roles = ['admin_lab', 'kepala_lab', 'waka_akademik', 'kepala_sekolah', 'guru', 'siswa', 'super_admin'];

        return view('lab.waka_akademik.monitoring', compact(
            'activities', 'roleFilter', 'tanggal', 'todaySummary', 'roles'
        ));
    }
}
