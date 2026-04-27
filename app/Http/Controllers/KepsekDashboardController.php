<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KepsekDashboardController extends Controller
{
    public function index(Request $request)
    {
        $title = auth()->user()->role == 'waka' ? 'Dashboard Waka Kurikulum' : 'SDSS Kepala Sekolah';
        
        $totalBuku = Buku::sum('stok'); 
        $totalKategori = Kategori::count();
        $totalPeminjaman = Peminjaman::count();
        $anggotaAktif = Peminjaman::select('nama')->distinct()->count();
        
        $dipinjam = Peminjaman::whereIn('status', ['Disetujui', 'Menunggu'])->count();
        $dikembalikan = Peminjaman::where('status', 'Dikembalikan')->count();

        // Keterlambatan
        $keterlambatan = Peminjaman::with('buku')
            ->where('status', 'Disetujui')
            ->whereNotNull('tanggal_kembali')
            ->whereDate('tanggal_kembali', '<', Carbon::today())
            ->get();
        
        $jumlahTerlambat = $keterlambatan->count();

        // ------------------ AI-DRIVEN INSIGHT ENGINE ------------------
        
        // 1. Tren Peminjaman (Bulan ini vs Bulan lalu)
        $bulanIni = Peminjaman::whereMonth('tanggal_pinjam', Carbon::now()->month)
                               ->whereYear('tanggal_pinjam', Carbon::now()->year)->count();
        $bulanLalu = Peminjaman::whereMonth('tanggal_pinjam', Carbon::now()->subMonth()->month)
                               ->whereYear('tanggal_pinjam', Carbon::now()->subMonth()->year)->count();
        
        $trenPeminjaman = [
            'status' => 'stabil',
            'persentase' => 0,
            'pesan' => 'Minat baca stabil.'
        ];
        
        if ($bulanLalu > 0) {
            $selisih = $bulanIni - $bulanLalu;
            $persentaseSelisih = round(abs($selisih) / $bulanLalu * 100);
            
            if ($selisih > 0) {
                $trenPeminjaman = [
                    'status' => 'naik',
                    'persentase' => $persentaseSelisih,
                    'pesan' => "Minat baca meningkat $persentaseSelisih% dibanding bulan lalu."
                ];
            } elseif ($selisih < 0) {
                $trenPeminjaman = [
                    'status' => 'turun',
                    'persentase' => $persentaseSelisih,
                    'pesan' => "Waspada: Minat baca menurun $persentaseSelisih% dibanding bulan lalu."
                ];
            }
        }

        // 2. Kategori Paling Aktif & Terlantar
        $kategoriStats = DB::table('peminjaman')
            ->join('buku', 'peminjaman.buku_id', '=', 'buku.id')
            ->join('kategoris', 'buku.kategori_id', '=', 'kategoris.id')
            ->select('kategoris.nama_kategori', DB::raw('count(*) as total'))
            ->groupBy('kategoris.id', 'kategoris.nama_kategori')
            ->orderByDesc('total')
            ->get();

        $kategoriAktif = "Belum Ada Data";
        $kategoriTerlantar = "Belum Ada Data";
        if ($kategoriStats->count() > 0) {
            $kategoriAktif = $kategoriStats->first()->nama_kategori;
            $kategoriTerlantar = $kategoriStats->last()->nama_kategori;
        }

        // 3. Early Warning: Buku usang/tidak pernah dipinjam
        $bukuTidakDipinjam = DB::table('buku')
            ->leftJoin('peminjaman', 'buku.id', '=', 'peminjaman.buku_id')
            ->whereNull('peminjaman.buku_id')
            ->where('buku.created_at', '<', Carbon::now()->subMonths(6)) // lebih dari 6 bulan gk dipinjam
            ->count();

        // ------------------ HASIL ANALITIK UNTUK VIEW ------------------
        $aiInsights = [];
        $aiInsights[] = [
            'icon' => 'bi-graph-' . ($trenPeminjaman['status'] == 'turun' ? 'down-arrow text-danger' : 'up-arrow text-success'),
            'title' => 'Tren Minat Baca',
            'desc' => $trenPeminjaman['pesan']
        ];
        
        $aiInsights[] = [
            'icon' => 'bi-star text-warning',
            'title' => 'Fokus Kategori',
            'desc' => "Kategori <b>$kategoriAktif</b> paling dominan, sedangkan <b>$kategoriTerlantar</b> nyaris tidak tersentuh."
        ];

        if ($jumlahTerlambat > ($dipinjam * 0.1)) {
            $aiInsights[] = [
                'icon' => 'bi-exclamation-triangle text-danger',
                'title' => 'Risiko Keterlambatan',
                'desc' => 'Tingkat keterlambatan pengembalian buku <b>Cukup Tinggi</b>. Evaluasi kebijakan denda diperlukan.'
            ];
        }

        $earlyWarnings = [];
        if ($bukuTidakDipinjam > 0) {
            $earlyWarnings[] = "Terdapat $bukuTidakDipinjam judul buku yang tidak pernah dipinjam selama lebih dari 6 bulan.";
        }
        if ($jumlahTerlambat > 5) {
            $earlyWarnings[] = "Ada $jumlahTerlambat transaksi peminjaman melewati batas waktu yang belum dikembalikan.";
        }

        // Grafik Bulanan (Tahun ini)
        $monthlyPeminjaman = Peminjaman::select(
            DB::raw('MONTH(tanggal_pinjam) as bulan'),
            DB::raw('COUNT(*) as total')
        )
        ->whereYear('tanggal_pinjam', Carbon::now()->year)
        ->groupBy('bulan')
        ->orderBy('bulan')
        ->pluck('total', 'bulan')->toArray();

        $grafikBulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $grafikData = [];
        for ($i = 1; $i <= 12; $i++) {
            $grafikData[] = $monthlyPeminjaman[$i] ?? 0;
        }

        // Aktivitas Terbaru
        $recentActivity = Peminjaman::with('buku')
            ->latest('updated_at')
            ->take(5)
            ->get();

        return view('perpustakaan.kepsek.dashboard', compact(
            'title', 'totalBuku', 'totalKategori', 'totalPeminjaman', 'anggotaAktif',
            'dipinjam', 'dikembalikan', 'keterlambatan', 'jumlahTerlambat',
            'grafikBulan', 'grafikData', 'recentActivity',
            'aiInsights', 'earlyWarnings'
        ));
    }

    public function peminjaman(Request $request)
    {
        $title = 'Analisis Peminjaman';

        $queryPeminjaman = Peminjaman::with('buku.category')->orderBy('created_at', 'desc');
        
        if ($request->has('dari_tanggal') && $request->dari_tanggal) {
            $queryPeminjaman->whereDate('tanggal_pinjam', '>=', $request->dari_tanggal);
        }
        if ($request->has('sampai_tanggal') && $request->sampai_tanggal) {
            $queryPeminjaman->whereDate('tanggal_pinjam', '<=', $request->sampai_tanggal);
        }
        if ($request->has('status') && $request->status) {
            if ($request->status == 'Dipinjam') {
                $queryPeminjaman->where('status', 'Disetujui');
            } else {
                $queryPeminjaman->where('status', $request->status);
            }
        }
        
        if ($request->has('kategori_id') && $request->kategori_id) {
            $queryPeminjaman->whereHas('buku', function($q) use ($request) {
                $q->where('kategori_id', $request->kategori_id);
            });
        }

        $daftarpeminjaman = $queryPeminjaman->get();
        $kategoris = Kategori::orderBy('nama_kategori')->get();

        return view('perpustakaan.kepsek.peminjaman', compact('title', 'daftarpeminjaman', 'kategoris'));
    }

    public function laporan(Request $request)
    {
        $title = 'Laporan Eksekutif Perpustakaan';
        
        // Peminjaman Semester 1 (Jan - Jun)
        $sem1 = Peminjaman::whereYear('tanggal_pinjam', Carbon::now()->year)
                ->whereMonth('tanggal_pinjam', '<=', 6)->count();
                
        // Peminjaman Semester 2 (Jul - Des)
        $sem2 = Peminjaman::whereYear('tanggal_pinjam', Carbon::now()->year)
                ->whereMonth('tanggal_pinjam', '>', 6)->count();

        // Kategori Paling Laris Tahun Ini
        $kategoriLaris = DB::table('peminjaman')
            ->join('buku', 'peminjaman.buku_id', '=', 'buku.id')
            ->join('kategoris', 'buku.kategori_id', '=', 'kategoris.id')
            ->select('kategoris.nama_kategori', DB::raw('count(*) as total'))
            ->whereYear('peminjaman.tanggal_pinjam', Carbon::now()->year)
            ->groupBy('kategoris.id', 'kategoris.nama_kategori')
            ->orderByDesc('total')
            ->take(3)
            ->get();

        return view('perpustakaan.kepsek.laporan', compact('title', 'sem1', 'sem2', 'kategoriLaris'));
    }

    public function evaluasiEws()
    {
        $title = 'Evaluasi Koleksi Pasif (EWS)';

        // Ambil buku yang tidak pernah dipinjam ATAU terakhir dipinjam > 6 bulan lalu
        $bukuPasif = \App\Models\Buku::with('category')
            ->where(function($query) {
                $query->whereDoesntHave('peminjaman')
                    ->orWhereHas('peminjaman', function($q) {
                        $q->where('tanggal_pinjam', '<', \Carbon\Carbon::now()->subMonths(6))
                          ->havingRaw('MAX(tanggal_pinjam) < ?', [\Carbon\Carbon::now()->subMonths(6)]);
                    }, '<', 1);
            })
            ->get();

        return view('perpustakaan.kepsek.ews.evaluasi', compact('title', 'bukuPasif'));
    }

    public function storeEvaluasiEws(Request $request)
    {
        $request->validate([
            'id_buku' => 'required|array',
            'tindakan' => 'required|array',
        ]);

        $ids = $request->id_buku;
        $tindakans = $request->tindakan;

        foreach ($ids as $index => $id) {
            $tindakan = $tindakans[$index] ?? null;
            if ($tindakan && \App\Models\Buku::where('id', $id)->exists()) {
                // Tentukan tujuan berdasarkan jenis tindakan
                $tujuanTarget = 'admin_perpus'; // Default
                if ($tindakan == 'Evaluasi') {
                    $tujuanTarget = 'waka_kurikulum'; // Waka Kurikulum khusus urus kurikulum
                }

                \App\Models\InstruksiKepalaSekolah::create([
                    'id_buku' => $id,
                    'jenis_tindakan' => $tindakan,
                    'tujuan' => $tujuanTarget,
                    'status' => 'belum_diproses',
                    'created_at' => \Carbon\Carbon::now(),
                ]);
            }
        }

        return redirect()->back()->with('success', 'Semua instruksi berhasil disimpan dan telah didistribusikan ke Admin dan Waka Kurikulum.');
    }
}
