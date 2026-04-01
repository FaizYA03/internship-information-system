<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Labor;
use App\Models\Laboratorium; // For status check (Usage)
use App\Models\Lab\JadwalLaboratorium; // For student schedule count
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class LaborController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = 'Laboratorium';
        $header = 'Daftar Laboratorium';

        // Get current logged-in student
        $user = auth()->user();
        $siswa = $user->siswa;
        
        // === SUMMARY STATISTICS ===
        $now = Carbon::now();
        
        // 1. Total Laboratorium
        $totalLaboratorium = Labor::count();
        
        // 2. Peminjaman Aktif (Active Borrowings by this student)
        $peminjamanAktif = \App\Models\Lab\PinjamAlat::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->count();
        
        // 3. Laporan Kerusakan
        $laporanSaya = \App\Models\Laporan::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'process'])
            ->count();
        
        // 4. Jadwal Hari Ini & Minggu Ini
        $todayIndo = \App\Http\Controllers\Siswa\JadwalController::getIndoDay($now);
        $jadwalHariIni = 0;
        $totalJadwalMingguIni = 0;
        $todaySchedules = collect();
        
        if ($user->role == 'guru') {
            // Teaching stats
            $jadwalHariIni = JadwalLaboratorium::where('guru_id', $user->id)
                ->where('hari', $todayIndo)
                ->count();
            
            $totalJadwalMingguIni = JadwalLaboratorium::where('guru_id', $user->id)->count();

            // Peminjaman Aktif (Tools + Rooms merged for stats)
            $peminjamanRuanganAktif = \App\Models\PinjamLabor::where('user_id', $user->id)
                ->whereIn('status', ['pending', 'approved'])
                ->count();
            $peminjamanAktif += $peminjamanRuanganAktif;

            // Laporan Kerusakan dari Kelas
            // Classes taught by this guru
            $taughtClasses = JadwalLaboratorium::where('guru_id', $user->id)->pluck('kelas_id')->unique();
            // Students in these classes
            $studentsInClasses = \App\Models\Siswa::whereIn('kelas_id', $taughtClasses)->pluck('user_id');
            // Damage reports from these students
            $laporanKelas = \App\Models\Laporan::whereIn('user_id', $studentsInClasses)
                ->where('status_perbaikan', '!=', 'Selesai')
                ->count();
            
            // For mini-cards
            $todaySchedules = JadwalLaboratorium::where('guru_id', $user->id)
                ->where('hari', $todayIndo)
                ->with(['labor'])
                ->orderBy('jam_mulai', 'asc')
                ->get();

        } elseif ($user->role == 'siswa' && $siswa && isset($siswa->kelas_id)) {
            $jadwalHariIni = JadwalLaboratorium::where('kelas_id', $siswa->kelas_id)
                ->where('hari', $todayIndo)
                ->count();
            
            $totalJadwalMingguIni = JadwalLaboratorium::where('kelas_id', $siswa->kelas_id)->count();
            
            // For mini-cards
            $todaySchedules = JadwalLaboratorium::where('kelas_id', $siswa->kelas_id)
                ->where('hari', $todayIndo)
                ->with(['labor'])
                ->orderBy('jam_mulai', 'asc')
                ->get();
            
            $laporanKelas = 0;
        }

        $stats = [
            'jadwal_hari_ini' => $jadwalHariIni,
            'jadwal_minggu_ini' => $totalJadwalMingguIni,
            'peminjaman_aktif' => $peminjamanAktif,
            'laporan_kelas' => $laporanKelas ?? 0,
            'laporan_saya' => $laporanSaya,
            'total_laboratorium' => $totalLaboratorium
        ];

        // === RECENT DATA FOR QUICK ACTIONS ===
        
        // Recent Active Borrowings (max 3)
        $recentBorrowings = \App\Models\Lab\PinjamAlat::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->with(['inventaris.labor'])
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
        
        // Recent Damage Reports (max 3)
        $recentReports = \App\Models\Laporan::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        // === EXISTING LOGIC ===
        
        // 1. Base Query with Relationships & Count
        // Count available equipment (excluding heavily damaged items)
        $query = Labor::with(['penanggungJawabUser', 'teknisiUser', 'jenisData'])
            ->withCount(['inventaris as alat_tersedia_count' => function ($q) {
                $q->where('jenis', 'Alat')
                  ->where('kondisi', '!=', 'Rusak Berat')
                  ->where('status', '!=', 'dihapus');
            }]);

        // 2. Search (Nama Laboratorium)
        if ($request->filled('search')) {
            $query->where('nama_labor', 'like', '%' . $request->search . '%');
        }

        // 3. Filter by Jenis Laboratorium
        if ($request->filled('jenis')) {
             $query->where('jenis_labor', $request->jenis);
        }

        // 4. Sort
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'name_asc':
                    $query->orderBy('nama_labor', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('nama_labor', 'desc');
                    break;
                case 'tools_desc':
                    $query->orderBy('alat_tersedia_count', 'desc');
                    break;
                case 'tools_asc':
                    $query->orderBy('alat_tersedia_count', 'asc');
                    break;
                default:
                    $query->orderBy('nama_labor', 'asc');
            }
        } else {
             $query->orderBy('nama_labor', 'asc');
        }

        // 5. Pagination & Caching
        // Generate a unique cache key based on request parameters
        $page = $request->get('page', 1);
        $cacheKey = 'siswa_labor_index_' . md5(json_encode($request->all()));

        $labor = Cache::remember($cacheKey, 60, function () use ($query, $request) {
            // Execute Query
            $labor = $query->paginate(9)->withQueryString();

            // 6. Calculate Status for current page items
            $now = Carbon::now();
            
            foreach ($labor as $lab) {
                // Check active session
                $isActive = Laboratorium::where('labor', $lab->kode)
                    ->whereDate('start', $now->toDateString())
                    ->whereTime('start', '<=', $now->format('H:i'))
                    ->whereTime('end', '>=', $now->format('H:i'))
                    ->exists();

                $lab->status_usage = $isActive ? 'digunakan' : 'tersedia';
            }
            
            return $labor;
        });

        // Get unique types for filter dropdown (Cached separately)
        $jenisLaborList = Cache::remember('jenis_labor_list', 600, function () {
            return \App\Models\Lab\JenisLaboratorium::orderBy('nama')->pluck('nama');
        });

        return view('siswa.main.labor.index', compact(
            'title', 
            'header', 
            'labor', 
            'jenisLaborList',
            'stats',
            'recentBorrowings',
            'recentReports',
            'todaySchedules'
        ));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $title = 'Detail Laboratorium';
        $header = 'Informasi Laboratorium';

        $labor = Labor::with(['penanggungJawabUser', 'teknisiUser', 'jenisData'])->findOrFail($id);

        // Jadwal hari ini
        $today = Carbon::now();
        $jadwalToday = Laboratorium::where('labor', $labor->kode)
            ->whereDate('start', $today->toDateString())
            ->orderBy('start', 'asc')
            ->get();

        // Jadwal mendatang
        $jadwalFuture = Laboratorium::where('labor', $labor->kode)
            ->whereDate('start', '>', $today->toDateString())
            ->orderBy('start', 'asc')
            ->limit(5)
            ->get();

        return view('siswa.main.labor.show', compact('title', 'header', 'labor', 'jadwalToday', 'jadwalFuture'));
    }
}
