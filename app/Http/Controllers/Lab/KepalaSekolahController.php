<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lab\PinjamEksternal;
use App\Models\Lab\Pengadaan;
use App\Models\Lab\LaporanKerusakan;
use App\Models\Lab\ActivityLog;
use App\Models\Lab\JadwalLaboratorium;
use App\Models\Labor;
use App\Models\Inventaris;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Lab\KategoriAlat;
use Carbon\Carbon;


class KepalaSekolahController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:kepala_sekolah,super_admin']);
    }

    public function index()
    {
        // === KPI UTAMA ===
        $totalLab        = Labor::count();
        $totalInventaris = Inventaris::count();
        $jumlahRusak     = Inventaris::whereIn('kondisi', ['Rusak Ringan', 'Rusak Sedang', 'Rusak Berat'])->count();
        $labAktif        = Labor::where('status_penggunaan', '!=', 'nonaktif')->count();
        $persenAktif     = $totalLab > 0 ? round(($labAktif / $totalLab) * 100) : 0;

        // === ANGGARAN ===
        $currentYear     = Carbon::now()->year;
        $totalAnggaran   = Pengadaan::where('status', 'approved')
                            ->selectRaw('SUM(estimasi_harga * jumlah) as total')
                            ->value('total') ?? 0;
        $anggaranTerpakai = Pengadaan::where('status', 'approved')
                            ->whereYear('approved_at', $currentYear)
                            ->selectRaw('SUM(estimasi_harga * jumlah) as total')
                            ->value('total') ?? 0;
        $sisaAnggaran    = $totalAnggaran - $anggaranTerpakai;

        // === APPROVAL PENDING ===
        $approvalEksternal  = PinjamEksternal::where('status', 'recommended')->count();
        $approvalPengadaan  = Pengadaan::where('status', 'pending')->count();

        // === RISK ALERTS ===
        $riskAlerts = [];

        $kerusakanBulanIni = LaporanKerusakan::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        if ($kerusakanBulanIni >= 5) {
            $riskAlerts[] = [
                'level'   => 'danger',
                'icon'    => 'bi-exclamation-triangle-fill',
                'message' => "{$kerusakanBulanIni} laporan kerusakan masuk bulan ini — perlu perhatian segera.",
            ];
        }

        $labTakAktif30Hari = Labor::whereDoesntHave('jadwalTetap', function ($q) {
            $q->where('created_at', '>=', now()->subDays(30));
        })->count();
        if ($labTakAktif30Hari > 0) {
            $riskAlerts[] = [
                'level'   => 'warning',
                'icon'    => 'bi-building-slash',
                'message' => "{$labTakAktif30Hari} laboratorium tidak memiliki jadwal dalam 30 hari terakhir.",
            ];
        }

        $pengadaanBerulang = Pengadaan::select('nama_barang', DB::raw('count(*) as total'))
            ->where('status', 'pending')
            ->groupBy('nama_barang')
            ->having('total', '>', 1)
            ->count();
        if ($pengadaanBerulang > 0) {
            $riskAlerts[] = [
                'level'   => 'warning',
                'icon'    => 'bi-arrow-repeat',
                'message' => "Terdapat {$pengadaanBerulang} item pengadaan yang muncul berulang kali.",
            ];
        }

        if ($approvalPengadaan >= 3) {
            $riskAlerts[] = [
                'level'   => 'info',
                'icon'    => 'bi-clock-history',
                'message' => "{$approvalPengadaan} pengajuan pengadaan menunggu keputusan Anda.",
            ];
        }

        // === RINGKASAN OPERASIONAL LAB ===
        $labPalingAktif = Labor::withCount(['jadwalTetap as jadwal_count' => function ($q) {
                $q->whereMonth('created_at', now()->month);
            }])
            ->orderByDesc('jadwal_count')
            ->first();

        $labTidakAktif = Labor::withCount(['jadwalTetap as jadwal_count' => function ($q) {
                $q->where('created_at', '>=', now()->subDays(30));
            }])
            ->having('jadwal_count', '=', 0)
            ->get();

        // Lab dengan kerusakan terbanyak berdasarkan inventaris
        $labKerusakanTerbanyak = Labor::with('inventaris')
            ->withCount(['inventaris as rusak_count' => function ($q) {
                $q->whereIn('kondisi', ['Rusak Ringan', 'Rusak Sedang', 'Rusak Berat']);
            }])
            ->orderByDesc('rusak_count')
            ->first();

        // === CHART DATA ===
        $chartMonths = [];
        $chartKerusakan = [];
        $chartPengadaan = [];
        $chartPenggunaan = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $chartMonths[]    = $month->isoFormat('MMM YY');
            $chartKerusakan[] = LaporanKerusakan::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            $chartPengadaan[] = Pengadaan::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            // Estimasi penggunaan (contoh menggunakan jadwal)
            $chartPenggunaan[] = JadwalLaboratorium::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }

        // === LOG AKTIVITAS TERBARU ===
        $recentActivity = ActivityLog::with('user')
            ->whereIn('action', ['approved', 'rejected', 'eskalasi', 'damage_reported', 'pengadaan_submitted'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $stats = [
            'total_lab'            => $totalLab,
            'total_inventaris'     => $totalInventaris,
            'jumlah_rusak'         => $jumlahRusak,
            'persen_aktif'         => $persenAktif,
            'total_anggaran'       => $totalAnggaran,
            'anggaran_terpakai'    => $anggaranTerpakai,
            'sisa_anggaran'        => $sisaAnggaran,
            'approval_eksternal'   => $approvalEksternal,
            'approval_pengadaan'   => $approvalPengadaan,
        ];

        return view('lab.kepala_sekolah.dashboard', compact(
            'stats',
            'riskAlerts',
            'labPalingAktif',
            'labTidakAktif',
            'labKerusakanTerbanyak',
            'chartMonths',
            'chartKerusakan',
            'chartPengadaan',
            'chartPenggunaan',
            'recentActivity'
        ));
    }

    // --- Data Inventaris (Read-only) ---
    public function inventarisIndex(Request $request)
    {
        $query = Inventaris::with('labor')->orderBy('nama_inventaris');

        // Filter Jenis (Alat / Bahan)
        if ($request->filled('jenis') && $request->jenis !== 'semua') {
            $query->where('jenis', $request->jenis);
        }

        // Search: nama, kode, atau kategori
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_inventaris', 'like', "%{$search}%")
                  ->orWhere('kode_inventaris', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        // Filter Laboratorium
        if ($request->filled('labor_id') && $request->labor_id !== 'semua') {
            $query->where('labor_id', $request->labor_id);
        }

        // Filter Kondisi
        if ($request->filled('kondisi') && $request->kondisi !== 'semua') {
            $query->where('kondisi', $request->kondisi);
        }

        // Filter Status
        if ($request->filled('status') && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        $inventaris  = $query->paginate(20)->withQueryString();
        $laborList   = Labor::orderBy('nama_labor')->get();
        $kategoris   = KategoriAlat::orderBy('nama')->pluck('nama');

        // Summary stats for header
        $totalAlat  = Inventaris::where('jenis', 'Alat')->count();
        $totalBahan = Inventaris::where('jenis', 'Bahan')->count();
        $totalRusak = Inventaris::whereIn('kondisi', ['Rusak Ringan', 'Rusak Sedang', 'Rusak Berat'])->count();

        return view('lab.kepala_sekolah.inventaris', compact(
            'inventaris', 'laborList', 'kategoris', 'totalAlat', 'totalBahan', 'totalRusak'
        ));
    }

    // --- Activity Log ---
    public function activityLog(Request $request)

    {
        $query = ActivityLog::with('user')->orderByDesc('created_at');

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('created_at', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        }
        if ($request->filled('role') && $request->role !== 'semua') {
            $query->whereHas('user', function ($q) use ($request) { $q->where('role', $request->role); });
        }
        if ($request->filled('kategori') && $request->kategori !== 'semua') {
            $query->where('action', $request->kategori);
        }

        if ($request->export === 'csv') {
            $allLogs = $query->get();
            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=log_aktivitas_" . date('Y-m-d') . ".csv",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];
            $callback = function() use($allLogs) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['No', 'Aktivitas', 'Keterangan', 'User', 'Role', 'Tanggal']);
                foreach ($allLogs as $index => $log) {
                    fputcsv($file, [
                        $index + 1,
                        ucfirst(str_replace('_', ' ', $log->action)),
                        $log->description ?? $log->action,
                        $log->user->nama ?? 'System',
                        $log->user->role ?? '-',
                        \Carbon\Carbon::parse($log->created_at)->isoFormat('D MMM Y HH:mm:ss')
                    ]);
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }

        $logs = $query->paginate(20)->withQueryString();

        return view('lab.kepala_sekolah.activity_log', compact('logs'));
    }

    // --- Approval Peminjaman Eksternal ---
    public function approvalEksternalIndex()
    {
        $requests = PinjamEksternal::where('status', 'recommended')
            ->with(['inventaris', 'rekomendasiBy'])
            ->latest()
            ->get();

        return view('lab.kepala_sekolah.approval.eksternal', compact('requests'));
    }

    public function approveEksternal($id)
    {
        $pinjam = PinjamEksternal::findOrFail($id);

        $inventaris = $pinjam->inventaris;
        if ($inventaris->jumlah < $pinjam->jumlah) {
            return back()->with('error', 'Stok barang tidak mencukupi untuk disetujui');
        }

        $pinjam->update([
            'status'              => 'approved',
            'approved_kepsek_by'  => Auth::id(),
            'approved_kepsek_at'  => now(),
        ]);

        $inventaris->decrement('jumlah', $pinjam->jumlah);

        return back()->with('success', 'Peminjaman eksternal disetujui');
    }

    public function rejectEksternal(Request $request, $id)
    {
        $pinjam = PinjamEksternal::findOrFail($id);
        $pinjam->update([
            'status'             => 'rejected',
            'approved_kepsek_by' => Auth::id(),
            'approved_kepsek_at' => now(),
        ]);

        return back()->with('success', 'Peminjaman eksternal ditolak');
    }

    // --- Approval Pengadaan ---
    public function approvalPengadaanIndex()
    {
        $requests = Pengadaan::where('status', 'pending')
            ->with('user')
            ->latest()
            ->get();

        return view('lab.kepala_sekolah.approval.pengadaan', compact('requests'));
    }

    public function approvePengadaan(Request $request, $id)
    {
        $pengadaan = Pengadaan::findOrFail($id);
        $pengadaan->update([
            'status'           => 'approved',
            'approved_by'      => Auth::id(),
            'approved_at'      => now(),
            'catatan_approval' => $request->catatan,
        ]);

        return back()->with('success', 'Pengadaan disetujui');
    }

    public function rejectPengadaan(Request $request, $id)
    {
        $pengadaan = Pengadaan::findOrFail($id);
        $pengadaan->update([
            'status'           => 'rejected',
            'approved_by'      => Auth::id(),
            'approved_at'      => now(),
            'catatan_approval' => $request->catatan,
        ]);

        return back()->with('success', 'Pengadaan ditolak');
    }

    // --- Export Laporan ---
    public function exportLaporan(Request $request)
    {
        $type = $request->get('type', 'bulanan'); // bulanan, akreditasi, csv

        if ($type === 'csv') {
            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=ringkasan_lab_" . date('Y-m-d') . ".csv",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];
            $callback = function() {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['Data', 'Jumlah']);
                fputcsv($file, ['Total Laboratorium', Labor::count()]);
                fputcsv($file, ['Total Inventaris', Inventaris::count()]);
                fputcsv($file, ['Alat Rusak', Inventaris::whereIn('kondisi', ['Rusak Ringan', 'Rusak Sedang', 'Rusak Berat'])->count()]);
                fputcsv($file, ['Pengadaan Disetujui', Pengadaan::where('status', 'approved')->count()]);
                fputcsv($file, ['Peminjaman Eksternal Disetujui', PinjamEksternal::where('status', 'approved')->count()]);
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }

        $stats = [
            'total_lab' => Labor::count(),
            'total_inventaris' => Inventaris::count(),
            'jumlah_rusak' => Inventaris::whereIn('kondisi', ['Rusak Ringan', 'Rusak Sedang', 'Rusak Berat'])->count(),
            'pengadaan_approved' => Pengadaan::where('status', 'approved')->count(),
            'peminjaman_eksternal' => PinjamEksternal::where('status', 'approved')->count()
        ];
        
        $data = [
            'stats' => $stats,
            'type' => $type,
            'date' => now()->isoFormat('D MMMM Y')
        ];

        // Jika view belum ada, redirect kembali
        if (!view()->exists('lab.kepala_sekolah.pdf_laporan')) {
            return back()->with('error', 'Template PDF belum tersedia.');
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('lab.kepala_sekolah.pdf_laporan', $data);
        return $pdf->download('laporan_lab_'.$type.'_'.date('Ymd').'.pdf');
    }
}
