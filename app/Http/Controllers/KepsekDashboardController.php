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
        $title = auth()->user()->role == 'waka' ? 'Dashboard Waka Kurikulum' : 'Dashboard Kepala Sekolah';
        
        $totalBuku = Buku::sum('stok'); // Atau count()
        $totalKategori = Kategori::count();
        $totalPeminjaman = Peminjaman::count();
        
        $dipinjam = Peminjaman::whereIn('status', ['Disetujui', 'Menunggu'])->count();
        $dikembalikan = Peminjaman::where('status', 'Dikembalikan')->count();

        // 3. Data Keterlambatan
        $keterlambatan = Peminjaman::with('buku')
            ->where('status', 'Disetujui')
            ->whereNotNull('tanggal_kembali')
            ->whereDate('tanggal_kembali', '<', Carbon::today())
            ->get();

        // 4. Buku Terpopuler
        $bukuPopuler = DB::table('peminjaman')
            ->join('buku', 'peminjaman.buku_id', '=', 'buku.id')
            ->select('buku.id', 'buku.judul as nama_buku', DB::raw('count(*) as total'))
            ->groupBy('buku.id', 'buku.judul')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        // 4b. Kategori Terpopuler (FIX: menggunakan tabel kategoris)
        $kategoriPopuler = DB::table('peminjaman')
            ->join('buku', 'peminjaman.buku_id', '=', 'buku.id')
            ->join('kategoris', 'buku.kategori_id', '=', 'kategoris.id')
            ->select('kategoris.id', 'kategoris.nama_kategori', DB::raw('count(*) as total'))
            ->groupBy('kategoris.id', 'kategoris.nama_kategori')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        // 4c. Insight Sederhana
        $insightBuku = "";
        $insightKategori = "";
        if ($bukuPopuler->count() > 0) {
            $insightBuku = "Buku \"" . $bukuPopuler[0]->nama_buku . "\" paling sering dipinjam oleh warga sekolah.";
        }
        if ($kategoriPopuler->count() > 0) {
            $insightKategori = "Kategori \"" . $kategoriPopuler[0]->nama_kategori . "\" paling aktif digunakan dalam pembelajaran.";
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

        // 5. Persentase
        $persentaseDipinjam = 0;
        $persentaseKembali = 0;
        if ($totalPeminjaman > 0) {
            $persentaseDipinjam = round(($dipinjam / $totalPeminjaman) * 100, 1);
            $persentaseKembali = round(($dikembalikan / $totalPeminjaman) * 100, 1);
        }

        return view('perpustakaan.kepsek.dashboard', compact(
            'title',
            'totalBuku',
            'totalKategori', 
            'totalPeminjaman',
            'dipinjam',
            'dikembalikan',
            'keterlambatan',
            'bukuPopuler',
            'grafikBulan',
            'grafikData',
            'recentActivity',
            'persentaseDipinjam',
            'persentaseKembali',
            'kategoriPopuler',
            'insightBuku',
            'insightKategori'
        ));
    }

    public function peminjaman(Request $request)
    {
        $title = 'Data Peminjaman';

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
        $title = 'Laporan Perpustakaan';
        return view('perpustakaan.kepsek.laporan', compact('title'));
    }
}
