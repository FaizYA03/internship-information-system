<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Mapel;
use App\Models\Jurusan;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\BukuKurikulum;
use App\Models\RekomendasiBuku;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class WakaKurikulumLibraryController extends Controller
{
    public function dashboard()
    {
        $title = 'Smart Curriculum Dashboard';
        $header = 'Dashboard Kurikulum Perpustakaan';

        // 1. Statistik Kategori Buku (Produktif, Adaptif, dll)
        $kategoriStats = DB::table('buku')
            ->join('kategoris', 'buku.kategori_id', '=', 'kategoris.id')
            ->select('kategoris.jenis_kategori', DB::raw('SUM(buku.stok) as total_buku'))
            ->groupBy('kategoris.jenis_kategori')
            ->get();

        // 2. Jumlah Buku Per Jurusan (Dari Mapping)
        $bukuPerJurusan = DB::table('buku_kurikulum')
            ->join('jurusans', 'buku_kurikulum.jurusan_id', '=', 'jurusans.id')
            ->select('jurusans.nama_jurusan', DB::raw('COUNT(DISTINCT buku_kurikulum.buku_id) as total_buku'))
            ->groupBy('jurusans.id', 'jurusans.nama_jurusan')
            ->get();

        // 3. Insight Otomatis
        $insights = $this->generateInsights();

        // 4. Instruksi EWS dari Kepsek (Tindakan: Evaluasi)
        $instruksiEws = \App\Models\InstruksiKepalaSekolah::with('buku')
            ->where('tujuan', 'waka_kurikulum')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('perpustakaan.waka.dashboard', compact('title', 'header', 'kategoriStats', 'bukuPerJurusan', 'insights', 'instruksiEws'));
    }

    public function prosesEvaluasiEws(Request $request)
    {
        $request->validate([
            'id_instruksi' => 'required|exists:instruksi_kepala_sekolah,id_instruksi',
            'hasil' => 'required|in:relevan,usang',
        ]);

        $instruksi = \App\Models\InstruksiKepalaSekolah::findOrFail($request->id_instruksi);

        DB::beginTransaction();
        try {
            // 1. Update instruksi saat ini (Waka menyelesaikan tugas)
            $instruksi->update([
                'status' => 'selesai',
                'hasil_evaluasi' => $request->hasil,
            ]);

            // 2. Jika hasil = usang (Tidak Relevan), kirim instruksi baru ke Admin untuk Pemutihan
            if ($request->hasil == 'usang') {
                \App\Models\InstruksiKepalaSekolah::create([
                    'id_buku' => $instruksi->id_buku,
                    'jenis_tindakan' => 'Pemutihan',
                    'tujuan' => 'admin_perpus',
                    'status' => 'belum_diproses',
                    'catatan' => 'Hasil evaluasi Waka Kurikulum: Buku sudah tidak relevan/usang.',
                    'created_at' => \Carbon\Carbon::now(),
                ]);
            }

            DB::commit();
            
            $msg = $request->hasil == 'relevan' 
                ? 'Buku dinyatakan relevan dan tetap digunakan dalam kurikulum.' 
                : 'Buku akan diteruskan ke Admin untuk proses pemutihan.';

            return redirect()->back()->with('success', 'Evaluasi berhasil disimpan. ' . $msg);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memproses evaluasi: ' . $e->getMessage());
        }
    }

    private function generateInsights()
    {
        $insights = [];
        
        // Cek Jurusan tanpa buku ter-mapping
        $allJurusan = Jurusan::all();
        $mappedJurusanIds = BukuKurikulum::select('jurusan_id')->distinct()->pluck('jurusan_id')->toArray();
        foreach($allJurusan as $jurusan) {
            if(!in_array($jurusan->id, $mappedJurusanIds)) {
                $insights[] = [
                    'type' => 'warning',
                    'message' => "Jurusan {$jurusan->nama_jurusan} belum memiliki referensi buku yang terhubung."
                ];
            }
        }

        // Cek Buku paling sering dipinjam per jurusan (Simulasi by map)
        $popularBook = DB::table('peminjaman')
            ->join('buku', 'peminjaman.buku_id', '=', 'buku.id')
            ->join('buku_kurikulum', 'buku.id', '=', 'buku_kurikulum.buku_id')
            ->join('jurusans', 'buku_kurikulum.jurusan_id', '=', 'jurusans.id')
            ->select('jurusans.nama_jurusan', 'buku.judul', DB::raw('COUNT(peminjaman.id) as total_pinjam'))
            ->groupBy('jurusans.id', 'jurusans.nama_jurusan', 'buku.judul')
            ->orderByDesc('total_pinjam')
            ->first();

        if ($popularBook) {
            $insights[] = [
                'type' => 'success',
                'message' => "Buku referensi jurusan {$popularBook->nama_jurusan} '{$popularBook->judul}' sangat aktif dipinjam."
            ];
        }

        return $insights;
    }

    public function rekomendasiIndex()
    {
        $title = 'Rekomendasi Buku Waka Kurikulum';
        $header = 'Daftar Rekomendasi Buku';
        $rekomendasi = RekomendasiBuku::with(['mapel', 'jurusan'])->latest()->get();
        return view('perpustakaan.waka.rekomendasi.index', compact('title', 'header', 'rekomendasi'));
    }

    public function rekomendasiCreate()
    {
        $title = 'Ajukan Rekomendasi Buku';
        $header = 'Form Pengajuan Rekomendasi';
        $mapels = Mapel::orderBy('nama_mapel')->get();
        $jurusans = Jurusan::orderBy('nama_jurusan')->get();
        return view('perpustakaan.waka.rekomendasi.create', compact('title', 'header', 'mapels', 'jurusans'));
    }

    public function rekomendasiStore(Request $request)
    {
        $request->validate([
            'judul_buku' => 'required|string|max:255',
            'prioritas'  => 'required|in:High,Medium,Low',
            'alasan'     => 'required|string',
        ]);

        // Cek apakah buku sudah ada di sistem
        $existingBuku = Buku::where('judul', 'like', '%' . $request->judul_buku . '%')->first();

        RekomendasiBuku::create([
            'judul_buku' => $request->judul_buku,
            'pengarang'  => $request->pengarang,
            'penerbit'   => $request->penerbit,
            'mapel_id'   => $request->mapel_id,
            'jurusan_id' => $request->jurusan_id,
            'prioritas'  => $request->prioritas,
            'alasan'     => $request->alasan,
            'status'     => $existingBuku ? 'Tersedia' : 'Draft',
            'waka_id'    => Auth::id()
        ]);

        $msg = $existingBuku ? 'Rekomendasi dicatat. Buku rupanya sudah tersedia di perpustakaan.' : 'Rekomendasi berhasil diajukan dan masuk ke draft admin.';
        return redirect()->route('perpustakaan.waka.rekomendasi.index')->with('success', $msg);
    }

    public function mappingIndex()
    {
        $title = 'Mapping Buku Kurikulum';
        $header = 'Relasi Buku ke Kurikulum';
        
        $bukuMaps = BukuKurikulum::with(['buku', 'mapel', 'jurusan'])->latest()->paginate(10);
        $bukuBelumMapping = Buku::doesntHave('kurikulum')->orderBy('judul')->take(50)->get();
        $mapels = Mapel::orderBy('nama_mapel')->get();
        $jurusans = Jurusan::orderBy('nama_jurusan')->get();

        return view('perpustakaan.waka.mapping.index', compact('title', 'header', 'bukuMaps', 'bukuBelumMapping', 'mapels', 'jurusans'));
    }

    public function mappingStore(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:buku,id',
            'mapel_id' => 'required_without:jurusan_id',
            'jurusan_id' => 'required_without:mapel_id',
        ]);

        BukuKurikulum::create($request->only(['buku_id', 'mapel_id', 'jurusan_id', 'kompetensi_dasar']));

        return redirect()->route('perpustakaan.waka.mapping.index')->with('success', 'Buku berhasil di-mapping ke kurikulum.');
    }

    public function relevansiKoleksi()
    {
        $title = 'Monitoring Relevansi Koleksi';
        $header = 'Relevansi Buku Kurikulum';

        $bukuRelevanIds = BukuKurikulum::pluck('buku_id')->toArray();
        $bukuTidakRelevan = Buku::whereNotIn('id', $bukuRelevanIds)->paginate(20);

        return view('perpustakaan.waka.relevansi', compact('title', 'header', 'bukuTidakRelevan'));
    }

    public function literasiAkademik()
    {
        $title = 'Aktivitas Literasi Akademik';
        $header = 'Analisis Peminjaman berbasis Pembelajaran';

        // Join peminjaman to users (via nama) to get roles -> then to siswa/guru
        // Since peminjaman only has 'nama', we do a raw/builder join query
        $peminjamanOlehGuru = DB::table('peminjaman')
            ->join('users', 'peminjaman.nama', '=', 'users.nama')
            ->where('users.role', 'guru')
            ->count();
        
        $peminjamanOlehSiswa = DB::table('peminjaman')
            ->join('users', 'peminjaman.nama', '=', 'users.nama')
            ->where('users.role', 'siswa')
            ->count();

        $aktivitasPerJurusan = DB::table('peminjaman')
            ->join('users', 'peminjaman.nama', '=', 'users.nama')
            ->join('siswa', 'users.id', '=', 'siswa.user_id')
            ->select('siswa.jurusan', DB::raw('count(peminjaman.id) as total'))
            ->groupBy('siswa.jurusan')
            ->get();

        return view('perpustakaan.waka.literasi', compact('title', 'header', 'peminjamanOlehGuru', 'peminjamanOlehSiswa', 'aktivitasPerJurusan'));
    }
}
