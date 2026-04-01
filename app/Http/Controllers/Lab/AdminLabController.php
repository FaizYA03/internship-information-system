<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lab\PinjamAlat;
use App\Models\Lab\PinjamEksternal;
use App\Models\Lab\Pengadaan;
use App\Models\Lab\LaporanKerusakan;
use App\Models\Inventaris;
use App\Models\Labor;
use App\Models\PinjamLabor;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Services\Lab\BorrowingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Exception;

class AdminLabController extends Controller
{
    private $borrowingService;

    public function __construct(BorrowingService $borrowingService)
    {
        $this->borrowingService = $borrowingService;
        $this->middleware(['auth', 'role:admin_lab,super_admin,kepala_lab,kepala_sekolah,waka_akademik']);
    }

    public function index()
    {
        // Statistics for dashboard
        $stats = [
            'total_laboratorium' => Labor::count(),
            'pinjam_pending' => PinjamAlat::where('status', 'pending')->count(),
            'pinjam_ruangan_pending' => PinjamLabor::where('status', 'pending')->count(),
            'pinjam_eksternal_pending' => PinjamEksternal::where('status', 'pending')->count(),
            'barang_rusak' => Inventaris::rusak()->count(),
            'alat_tersedia' => Inventaris::baik()->where('status', 'tersedia')->count(),
            'total_barang' => Inventaris::count(),
            'kerusakan_aktif' => LaporanKerusakan::where('status_perbaikan', '!=', 'selesai')->count()
        ];
        
        return view('lab.admin-new.dashboard', compact('stats'));
    }

    // --- Peminjaman Internal ---
    public function pinjamInternalIndex()
    {
        $peminjaman = PinjamAlat::with(['user.siswa', 'user.guru', 'inventaris.labor'])->latest()->get();
        $peminjamanRuangan = PinjamLabor::with(['user.siswa', 'user.guru', 'labor'])->latest()->get();
        return view('lab.admin-new.peminjaman.index', compact('peminjaman', 'peminjamanRuangan'));
    }

    public function approveInternal($id)
    {
        try {
            $pinjam = PinjamAlat::findOrFail($id);
            $this->borrowingService->approveInternal($pinjam);
            return back()->with('success', 'Peminjaman disetujui');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function rejectInternal(Request $request, $id)
    {
        try {
            $pinjam = PinjamAlat::findOrFail($id);
            $this->borrowingService->rejectInternal($pinjam, $request->reason);
            return back()->with('success', 'Peminjaman ditolak');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function returnInternal(Request $request, $id)
    {
        try {
            $pinjam = PinjamAlat::findOrFail($id);
            $this->borrowingService->returnInternal($pinjam, $request->kondisi_akhir, $request->catatan);
            return back()->with('success', 'Barang dikembalikan');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function pinjamAlatEdit($id)
    {
        $pinjam = PinjamAlat::with(['user', 'inventaris'])->findOrFail($id);
        $inventaris = Inventaris::orderBy('nama_inventaris')->get();
        return view('lab.admin-new.peminjaman.edit_alat', compact('pinjam', 'inventaris'));
    }

    public function pinjamAlatUpdate(Request $request, $id)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date',
            'jam_pinjam' => 'required',
            'jam_kembali' => 'required',
            'keperluan' => 'required|string',
        ]);

        try {
            $pinjam = PinjamAlat::findOrFail($id);
            $pinjam->update($request->all());
            return redirect()->route('lab.admin_new.peminjaman.internal.index')->with('success', 'Peminjaman alat berhasil diperbarui');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function pinjamAlatDestroy($id)
    {
        try {
            $pinjam = PinjamAlat::findOrFail($id);
            $pinjam->delete();
            return back()->with('success', 'Peminjaman alat berhasil dihapus');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function pinjamRuanganEdit($id)
    {
        $pinjam = PinjamLabor::with(['user', 'labor'])->findOrFail($id);
        $laboratories = Labor::orderBy('nama_labor')->get();
        return view('lab.admin-new.peminjaman.edit_ruangan', compact('pinjam', 'laboratories'));
    }

    public function pinjamRuanganUpdate(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'waktu' => 'required|string',
            'keperluan' => 'required|string'
        ]);

        try {
            $pinjam = PinjamLabor::findOrFail($id);
            $pinjam->update($request->all());
            return redirect()->route('lab.admin_new.peminjaman.internal.index')->with('success', 'Peminjaman ruangan berhasil diperbarui');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function pinjamRuanganDestroy($id)
    {
        try {
            $pinjam = PinjamLabor::findOrFail($id);
            $pinjam->delete();
            return back()->with('success', 'Peminjaman ruangan berhasil dihapus');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // --- Laboratory Management ---
    public function laboratoryIndex()
    {
        $laboratories = Labor::with(['jenisData'])
            ->withCount('inventaris')
            ->get();
        return view('lab.admin-new.laboratorium.index', compact('laboratories'));
    }



    public function laboratoryManualUsage($id)
    {
        $labor = Labor::with('inventaris')->findOrFail($id);
        $gurus = \App\Models\Guru::orderBy('nama')->get();
        return view('lab.admin-new.laboratorium.manual_usage', compact('labor', 'gurus'));
    }

    public function laboratoryManualUsageStore(Request $request, $id)
    {
        $labor = Labor::findOrFail($id);
        
        $request->validate([
            'guru_id' => 'required|exists:gurus,id',
            'kelas' => 'required|string',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'alat_digunakan' => 'nullable|array',
            'keterangan' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();
            
            // Create laboratorium schedule entry
            $laboratorium = new \App\Models\Laboratorium();
            $laboratorium->labor = $labor->kode;
            $laboratorium->status = 'digunakan';
            $laboratorium->start = $request->tanggal . ' ' . $request->jam_mulai;
            $laboratorium->end = $request->tanggal . ' ' . $request->jam_selesai;
            $laboratorium->penanggung_jawab = $request->guru_id;
            $laboratorium->teknisi = Auth::id();
            $laboratorium->keterangan = "Kelas: {$request->kelas}. " . ($request->keterangan ?? '');
            $laboratorium->save();

            DB::commit();
            return redirect()->route('lab.admin_new.laboratorium.show', $id)
                ->with('success', 'Penggunaan laboratorium berhasil dicatat');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Ekstrak akronim dari nama laboratorium.
     * Contoh: "Teknik Konstruksi Perumahan" => "TKP"
     *         "Komputer dan Jaringan"       => "KJ"  ("dan" diabaikan)
     *         "Kimia"                       => "KIM" (1 kata -> 3 huruf)
     */
    private function extractAkronimDariNama(string $namaLab): string
    {
        // Kata yang diabaikan
        $stopwords = ['laboratorium', 'lab', 'dan', 'atau', 'the', 'of', 'di', 'ke', 'dari', 'untuk', 'dan', 'dengan', 'pada'];

        // Bersihkan nama: hapus tanda baca, jadikan lowercase
        $nama = preg_replace('/[^a-zA-Z\s]/', ' ', $namaLab);
        $kata = array_filter(
            array_map('trim', explode(' ', $nama)),
            function($k) use ($stopwords) {
                return strlen($k) > 0 && !in_array(strtolower($k), $stopwords);
            }
        );

        if (empty($kata)) {
            return 'LAB';
        }

        // Ambil huruf pertama tiap kata, uppercase
        $akronim = implode('', array_map(function($k) {
            return strtoupper($k[0]);
        }, array_values($kata)));

        // Batas max 5 karakter untuk akronim
        $akronim = substr($akronim, 0, 5);

        // Jika hanya 1 kata, ambil 3 huruf pertama
        if (count(array_values($kata)) === 1) {
            $akronim = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', array_values($kata)[0]), 0, 3));
        }

        return $akronim ?: 'LAB';
    }

    /**
     * Generate kode lab otomatis.
     * Prioritas prefix: namaLab (akronim) > prefixKode dari DB jenis > singkatan statis
     * Format: LAB-[AKRONIM]-[NOMOR_URUT 3 digit]
     * Contoh: LAB-TKP-001, LAB-KOM-002, LAB-KIM-003
     */
    private function generateKodeLab(string $jenisLab, ?int $excludeId = null, ?string $prefixKode = null, ?string $namaLab = null): string
    {
        if ($namaLab && trim($namaLab) !== '') {
            // Prioritas 1: akronim dari nama lab
            $akronim = $this->extractAkronimDariNama($namaLab);
            $prefix  = 'LAB-' . $akronim . '-';
        } elseif ($prefixKode) {
            // Prioritas 2: prefix dari tabel jenis_laboratoria
            $prefix = rtrim($prefixKode, '-') . '-';
        } else {
            // Prioritas 3: singkatan statis fallback
            $singkatan = [
                'Komputer'   => 'KOM',
                'Kimia'      => 'KIM',
                'Fisika'     => 'FIS',
                'Bahasa'     => 'BHS',
                'Multimedia' => 'MUL',
                'Lainnya'    => 'LAB',
            ];
            $sing   = $singkatan[$jenisLab] ?? strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $jenisLab), 0, 3));
            $prefix = 'LAB-' . $sing . '-';
        }

        // Hitung berapa lab dengan prefix ini sudah ada
        $query = Labor::where('kode', 'LIKE', $prefix . '%');
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        $count = $query->count() + 1;

        // Pastikan kode unik dengan increment jika sudah ada
        do {
            $kode   = $prefix . str_pad($count, 3, '0', STR_PAD_LEFT);
            $query2 = Labor::where('kode', $kode);
            if ($excludeId) {
                $query2->where('id', '!=', $excludeId);
            }
            $exists = $query2->exists();
            if ($exists) $count++;
        } while ($exists);

        return $kode;
    }

    public function laboratoryCreate()
    {
        $jenisOptions = \App\Models\Lab\JenisLaboratorium::orderBy('nama')->get();
        $kodeSuggestion = $this->generateKodeLab(
            $jenisOptions->first()->nama ?? 'Lainnya',
            null,
            $jenisOptions->first()->prefix_kode ?? null
        );
        return view('lab.admin-new.laboratorium.create', compact('jenisOptions', 'kodeSuggestion'));
    }

    /**
     * AJAX endpoint: generate kode lab unik berdasarkan nama lab
     * GET /lab/admin-new/laboratorium/generate-kode
     *   ?nama=Teknik+Konstruksi+Perumahan   → LAB-TKP-001
     *   &jenis=Komputer                      (fallback jika nama kosong)
     *   &exclude_id=5                        (untuk edit agar tidak dihitung sendiri)
     */
    public function ajaxGenerateKode(Request $request)
    {
        $jenis     = $request->get('jenis', 'Lainnya');
        $namaLab   = $request->get('nama', '');
        $excludeId = $request->get('exclude_id') ? (int) $request->get('exclude_id') : null;

        // Cari prefix dari DB jenis (dipakai jika nama kosong)
        $jenisModel = \App\Models\Lab\JenisLaboratorium::where('nama', $jenis)->first();
        $prefixDB   = $jenisModel?->prefix_kode ?? null;

        // Generate kode: nama lab → akronim (prioritas) / prefix jenis (fallback)
        $kode    = $this->generateKodeLab($jenis, $excludeId, $prefixDB, $namaLab);
        $akronim = $namaLab ? $this->extractAkronimDariNama($namaLab) : null;

        return response()->json([
            'kode'    => $kode,
            'jenis'   => $jenis,
            'akronim' => $akronim,
        ]);
    }

    public function laboratoryStore(Request $request)
    {
        // Ambil nama jenis lab yang valid dari DB
        $validJenis = \App\Models\Lab\JenisLaboratorium::pluck('nama')->toArray();

        $validator = Validator::make($request->all(), [
            'nama_labor'      => 'required|string|max:255',
            'kode'            => 'nullable|string|max:50|unique:labor,kode',
            'jenis_labor'     => 'required|in:' . implode(',', $validJenis),
            'penanggung_jawab'=> 'nullable|string|max:255',
            'teknisi'         => 'nullable|string|max:255',
            'deskripsi'       => 'nullable|string',
            'fasilitas'       => 'nullable|string',
            'kapasitas'       => 'nullable|integer|min:1|max:500',
            'lokasi'          => 'nullable|string|max:255',
            'status_penggunaan' => 'nullable|in:kosong,digunakan',
            'foto'            => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', 'Validasi gagal');
        }

        $data = $request->except('foto');

        // Auto-generate kode jika tidak diisi
        if (empty($data['kode'])) {
            $jenisModel = \App\Models\Lab\JenisLaboratorium::where('nama', $request->jenis_labor)->first();
            $data['kode'] = $this->generateKodeLab($request->jenis_labor, null, $jenisModel?->prefix_kode);
        }

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('labor_foto', 'public');
        }

        Labor::create($data);

        return redirect()->route('lab.admin_new.laboratorium.index')
            ->with('success', 'Laboratorium berhasil ditambahkan');
    }

    public function laboratoryShow($id)
    {
        $labor = Labor::with(['jenisData', 'penanggungJawabUser', 'teknisiUser', 'inventaris', 'jadwalPenggunaan'])
            ->findOrFail($id);

        // Get damage reports related to this lab
        $laporanKerusakan = LaporanKerusakan::with(['inventaris', 'user'])
            ->whereHas('inventaris', function($query) use ($id) {
                $query->where('labor_id', $id);
            })
            ->latest()
            ->take(10)
            ->get();

        return view('lab.admin-new.laboratorium.show', compact('labor', 'laporanKerusakan'));
    }

    public function laboratoryEdit($id)
    {
        $labor = Labor::findOrFail($id);
        $jenisOptions = \App\Models\Lab\JenisLaboratorium::orderBy('nama')->get();
        return view('lab.admin-new.laboratorium.edit', compact('labor', 'jenisOptions'));
    }

    public function laboratoryUpdate(Request $request, $id)
    {
        $labor = Labor::findOrFail($id);
        // Ambil nama jenis lab yang valid dari DB
        $validJenis = \App\Models\Lab\JenisLaboratorium::pluck('nama')->toArray();

        $validator = Validator::make($request->all(), [
            'nama_labor'      => 'required|string|max:255',
            'kode'            => 'nullable|string|max:50|unique:labor,kode,' . $id,
            'jenis_labor'     => 'required|in:' . implode(',', $validJenis),
            'penanggung_jawab'=> 'nullable|string|max:255',
            'teknisi'         => 'nullable|string|max:255',
            'deskripsi'       => 'nullable|string',
            'fasilitas'       => 'nullable|string',
            'kapasitas'       => 'nullable|integer|min:1|max:500',
            'lokasi'          => 'nullable|string|max:255',
            'status_penggunaan' => 'nullable|in:kosong,digunakan',
            'foto'            => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', 'Validasi gagal');
        }

        $data = $request->except('foto');

        // Auto-generate kode jika dikosongkan
        if (empty($data['kode'])) {
            $jenisModel = \App\Models\Lab\JenisLaboratorium::where('nama', $request->jenis_labor)->first();
            $data['kode'] = $this->generateKodeLab($request->jenis_labor, $id, $jenisModel?->prefix_kode);
        }

        if ($request->hasFile('foto')) {
            if ($labor->foto) Storage::disk('public')->delete($labor->foto);
            $data['foto'] = $request->file('foto')->store('labor_foto', 'public');
        }

        $labor->update($data);

        return redirect()->route('lab.admin_new.laboratorium.show', $id)
            ->with('success', 'Laboratorium berhasil diperbarui');
    }

    public function laboratoryDestroy($id)
    {
        $labor = Labor::findOrFail($id);
        if ($labor->foto) Storage::disk('public')->delete($labor->foto);
        $labor->delete();
        return redirect()->route('lab.admin_new.laboratorium.index')
            ->with('success', 'Laboratorium berhasil dihapus');
    }

    // --- Room Borrowing Management ---
    public function pinjamRuanganIndex()
    {
        $peminjaman = PinjamLabor::with(['user', 'labor', 'approver'])->latest()->get();
        return view('lab.admin-new.peminjaman.ruangan.index', compact('peminjaman'));
    }

    public function approveRuangan(Request $request, $id)
    {
        try {
            $pinjam = PinjamLabor::findOrFail($id);
            
            if ($pinjam->status !== PinjamLabor::STATUS_PENDING) {
                throw new Exception("Status peminjaman tidak valid untuk disetujui");
            }

            $pinjam->update([
                'status' => PinjamLabor::STATUS_APPROVED,
                'approved_by' => Auth::id(),
                'approved_at' => now()
            ]);

            return back()->with('success', 'Peminjaman ruangan disetujui');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function rejectRuangan(Request $request, $id)
    {
        try {
            $pinjam = PinjamLabor::findOrFail($id);
            
            if ($pinjam->status !== PinjamLabor::STATUS_PENDING) {
                throw new Exception("Status peminjaman tidak valid untuk ditolak");
            }

            $pinjam->update([
                'status' => PinjamLabor::STATUS_REJECTED,
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'alasan_penolakan' => $request->reason
            ]);

            return back()->with('success', 'Peminjaman ruangan ditolak');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // --- Manual Input ---
    public function manualInputAlatSiswa()
    {
        $siswa = Siswa::with(['user', 'kelas'])->get()->unique(function ($s) {
            return preg_replace('/[^a-z]/', '', strtolower($s->nama));
        })->sortBy('nama');
        $classes = Kelas::orderBy('nama_kelas')->get();
        $jurusanList = $classes->pluck('jurusan')->filter()->unique()->sort()->values();
        $laboratories = Labor::orderBy('nama_labor')->get();
        $inventaris = Inventaris::where('jenis', 'Alat')->where('status', 'tersedia')->orderBy('nama_inventaris')->get();
        return view('lab.admin-new.manual_input.alat_siswa', compact('siswa', 'classes', 'jurusanList', 'laboratories', 'inventaris'));
    }

    public function manualInputAlatSiswaStore(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required',
            'labor_id' => 'required|exists:labor,id',
            'inventaris_id' => 'required|exists:inventaris,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date',
            'jam_pinjam' => 'required',
            'jam_kembali' => 'required',
            'keperluan' => 'required|string'
        ]);

        try {
            // Find the user associated with the siswa
            $siswa = Siswa::findOrFail($request->siswa_id);
            $user = User::find($siswa->user_id);
            
            if (!$user) {
                throw new Exception("User tidak ditemukan untuk siswa ini");
            }

            $pinjamAlat = PinjamAlat::create([
                'user_id' => $user->id,
                'inventaris_id' => $request->inventaris_id,
                'jumlah' => $request->jumlah,
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'tanggal_kembali' => $request->tanggal_kembali ?? $request->tanggal_pinjam,
                'jam_pinjam' => $request->jam_pinjam,
                'jam_kembali' => $request->jam_kembali,
                'keperluan' => $request->keperluan,
                'status' => 'approved', // Auto-approved by admin
                'approved_by' => Auth::id(),
                'approved_at' => now()
            ]);

            return redirect()->route('lab.admin_new.peminjaman.internal.index')
                ->with('success', 'Peminjaman alat untuk siswa berhasil dicatat');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function manualInputAlatGuru()
    {
        $gurus = Guru::with('user')->get()->unique(function ($g) {
            return preg_replace('/[^a-z]/', '', strtolower($g->nama));
        })->sortBy('nama');
        $departments = Guru::select('jurusan')->whereNotNull('jurusan')->distinct()->pluck('jurusan')->sort()->values();
        $laboratories = Labor::orderBy('nama_labor')->get();
        $inventaris = Inventaris::where('jenis', 'Alat')->where('status', 'tersedia')->orderBy('nama_inventaris')->get();
        return view('lab.admin-new.manual_input.alat_guru', compact('gurus', 'departments', 'laboratories', 'inventaris'));
    }

    public function manualInputAlatGuruStore(Request $request)
    {
        $request->validate([
            'guru_id' => 'required',
            'labor_id' => 'required|exists:labor,id',
            'inventaris_id' => 'required|exists:inventaris,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date',
            'jam_pinjam' => 'required',
            'jam_kembali' => 'required',
            'keperluan' => 'required|string'
        ]);

        try {
            // Find the user associated with the guru
            $guru = Guru::findOrFail($request->guru_id);
            $user = User::find($guru->user_id);
            
            if (!$user) {
                throw new Exception("User tidak ditemukan untuk guru ini");
            }

            $pinjamAlat = PinjamAlat::create([
                'user_id' => $user->id,
                'inventaris_id' => $request->inventaris_id,
                'jumlah' => $request->jumlah,
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'tanggal_kembali' => $request->tanggal_kembali ?? $request->tanggal_pinjam,
                'jam_pinjam' => $request->jam_pinjam,
                'jam_kembali' => $request->jam_kembali,
                'keperluan' => $request->keperluan,
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now()
            ]);

            return redirect()->route('lab.admin_new.peminjaman.internal.index')
                ->with('success', 'Peminjaman alat untuk guru berhasil dicatat');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function manualInputRuanganGuru()
    {
        $gurus = Guru::with('user')->get()->unique(function ($g) {
            return preg_replace('/[^a-z]/', '', strtolower($g->nama));
        })->sortBy('nama');
        $departments = Guru::select('jurusan')->whereNotNull('jurusan')->distinct()->pluck('jurusan')->sort()->values();
        $laboratories = Labor::orderBy('nama_labor')->get();
        return view('lab.admin-new.manual_input.ruangan_guru', compact('gurus', 'departments', 'laboratories'));
    }

    public function manualInputRuanganGuruStore(Request $request)
    {
        $request->validate([
            'guru_id' => 'required',
            'labor_id' => 'required|exists:labor,id',
            'tanggal' => 'required|date',
            'tanggal_kembali' => 'required|date',
            'jam_pinjam' => 'required',
            'jam_kembali' => 'required',
            'keperluan' => 'required|string'
        ]);

        try {
            $guru = Guru::findOrFail($request->guru_id);
            $user = User::find($guru->user_id);
            
            if (!$user) {
                throw new Exception("User tidak ditemukan untuk guru ini");
            }

            $pinjamLabor = PinjamLabor::create([
                'user_id' => $user->id,
                'labor_id' => $request->labor_id,
                'nama' => $guru->nama,
                'tanggal' => $request->tanggal,
                'tanggal_kembali' => $request->tanggal_kembali,
                'waktu' => $request->jam_pinjam . ' - ' . $request->jam_kembali,
                'jam_pinjam' => $request->jam_pinjam,
                'jam_kembali' => $request->jam_kembali,
                'keperluan' => $request->keperluan,
                'kelas' => $request->kelas ?? null,
                'status' => PinjamLabor::STATUS_APPROVED,
                'approved_by' => Auth::id(),
                'approved_at' => now()
            ]);

            return redirect()->route('lab.admin_new.peminjaman.internal.index')
                ->with('success', 'Peminjaman ruangan untuk guru berhasil dicatat');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function manualInputAlatEksternal()
    {
        $laboratories = Labor::all();
        $inventaris = Inventaris::where('jenis', 'Alat')->where('status', 'tersedia')->get();
        return view('lab.admin-new.manual_input.alat_eksternal', compact('laboratories', 'inventaris'));
    }

    public function manualInputAlatEksternalStore(Request $request)
    {
        $request->validate([
            'nama_peminjam' => 'required|string',
            'instansi' => 'nullable|string',
            'kontak' => 'required|string',
            'inventaris_id' => 'required|exists:inventaris,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date',
            'jam_pinjam' => 'required',
            'jam_kembali' => 'required',
            'keperluan' => 'required|string',
            'surat_permohonan' => 'nullable|file|mimes:pdf,jpg,png|max:2048'
        ]);

        try {
            $suratPath = null;
            if ($request->hasFile('surat_permohonan')) {
                $suratPath = $request->file('surat_permohonan')->store('peminjaman_eksternal', 'public');
            }

            $peminjaman = \App\Models\Lab\PinjamEksternal::create([
                'nama_peminjam' => $request->nama_peminjam,
                'instansi' => $request->instansi,
                'kontak' => $request->kontak,
                'inventaris_id' => $request->inventaris_id,
                'jumlah' => $request->jumlah,
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'tanggal_kembali' => $request->tanggal_kembali,
                'jam_pinjam' => $request->jam_pinjam,
                'jam_kembali' => $request->jam_kembali,
                'keperluan' => $request->keperluan,
                'surat_permohonan' => $suratPath,
                'status' => 'pending', // Needs recommendation from Kalab and approval from Kepsek
                'approved_kepsek_by' => null,
                'approved_kepsek_at' => null,
                'rekomendasi_kalab_by' => null,
                'rekomendasi_kalab_at' => null
            ]);

            return redirect()->route('lab.admin_new.peminjaman.internal.index')
                ->with('success', 'Peminjaman alat eksternal berhasil dicatat');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function manualInputRuanganEksternal()
    {
        $laboratories = Labor::all();
        return view('lab.admin-new.manual_input.ruangan_eksternal', compact('laboratories'));
    }

    public function manualInputRuanganEksternalStore(Request $request)
    {
        $request->validate([
            'nama_peminjam' => 'required|string',
            'instansi' => 'nullable|string',
            'kontak' => 'required|string',
            'labor_id' => 'required|exists:labor,id',
            'tanggal' => 'required|date',
            'tanggal_kembali' => 'required|date',
            'jam_pinjam' => 'required',
            'jam_kembali' => 'required',
            'keperluan' => 'required|string'
        ]);

        try {
            $pinjamLabor = PinjamLabor::create([
                'user_id' => null, // External
                'labor_id' => $request->labor_id,
                'nama' => $request->nama_peminjam . ' (' . ($request->instansi ?? 'Individu') . ') - Telp: ' . $request->kontak,
                'tanggal' => $request->tanggal,
                'tanggal_kembali' => $request->tanggal_kembali,
                'waktu' => $request->jam_pinjam . ' - ' . $request->jam_kembali,
                'jam_pinjam' => $request->jam_pinjam,
                'jam_kembali' => $request->jam_kembali,
                'keperluan' => $request->keperluan,
                'status' => PinjamLabor::STATUS_APPROVED,
                'approved_by' => Auth::id(),
                'approved_at' => now()
            ]);

            return redirect()->route('lab.admin_new.peminjaman.internal.index')
                ->with('success', 'Peminjaman ruangan eksternal berhasil dicatat');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    // --- Damage Management ---
    public function kerusakanIndex(Request $request)
    {
        // Active: not 'selesai' OR ('selesai' but escalated and still 'menunggu')
        $laporanKerusakan = LaporanKerusakan::with(['inventaris.labor', 'user'])
            ->where(function($q) {
                $q->where('status_perbaikan', '!=', 'selesai')
                  ->orWhere(function($sq) {
                      $sq->where('status_perbaikan', 'selesai')
                         ->where('is_eskalasi', true)
                         ->where('eskalasi_status', 'menunggu');
                  });
            })
            ->latest()
            ->get();
        
        return view('lab.admin-new.kerusakan.index', compact('laporanKerusakan'));
    }

    /**
     * Specialized view for Finished Repairs
     */
    public function perbaikanSelesai(Request $request)
    {
        // Only show finished repairs. 
        // If it was escalated, it should definitely be approved to be here.
        $laporanSelesai = LaporanKerusakan::with(['inventaris.labor', 'user'])
            ->where('status_perbaikan', 'selesai')
            ->where(function($q) {
                $q->where('is_eskalasi', false)
                  ->orWhere('eskalasi_status', 'disetujui');
            })
            ->latest()
            ->get();
            
        return view('lab.admin-new.kerusakan.selesai', compact('laporanSelesai'));
    }

    public function kerusakanCreate()
    {
        $laboratories = Labor::orderBy('nama_labor')->get();
        $inventaris = Inventaris::with('labor')->orderBy('nama_inventaris')->get();
        return view('lab.admin-new.kerusakan.create', compact('laboratories', 'inventaris'));
    }

    public function kerusakanStore(Request $request)
    {
        $request->validate([
            'inventaris_id' => 'required|exists:inventaris,id',
            'deskripsi_kerusakan' => 'required|string',
            'tingkat_kerusakan' => 'required|in:Rusak Ringan,Rusak Sedang,Rusak Berat',
            'foto_bukti' => 'nullable|image|max:2048'
        ]);

        try {
            DB::beginTransaction();
            
            $fotoPath = null;
            if ($request->hasFile('foto_bukti')) {
                $fotoPath = $request->file('foto_bukti')->store('laporan_kerusakan', 'public');
            }

            $laporan = LaporanKerusakan::create([
                'user_id' => Auth::id(),
                'inventaris_id' => $request->inventaris_id,
                'deskripsi_kerusakan' => $request->deskripsi_kerusakan,
                'status_perbaikan' => 'menunggu',
                'teknisi_id' => Auth::id(),
                'foto_bukti' => $fotoPath,
                'tanggal_laporan' => now()
            ]);

            // Update inventaris condition
            $inventaris = Inventaris::findOrFail($request->inventaris_id);
            $inventaris->update(['kondisi' => $request->tingkat_kerusakan]);

            // TODO: Send notifications based on damage level
            // if rusak sedang/berat -> notify Kepala Sekolah, Waka Akademik
            // always notify            
            DB::commit();
            
            // Send notifications
            $this->sendDamageNotifications($laporan, $request->tingkat_kerusakan);
            
            return redirect()->route('lab.admin_new.kerusakan.index')
                ->with('success', 'Laporan kerusakan berhasil dibuat');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
    
    /**
     * Send damage report notifications to relevant personnel
     */
    private function sendDamageNotifications($laporan, $tingkatKerusakan)
    {
        // Always notify Kepala Lab
        $kepalaLab = User::where('role', 'kepala_lab')->get();
        if ($kepalaLab->count() > 0) {
            Notification::send($kepalaLab, new \App\Notifications\KerusakanAlatNotification($laporan));
        }
        
        // For moderate and severe damages, also notify Waka Akademik and Kepala Sekolah
        if (in_array($tingkatKerusakan, ['Rusak Sedang', 'Rusak Berat'])) {
            $wakaAkademik = User::where('role', 'waka_akademik')->get();
            $kepalaSekolah = User::where('role', 'kepala_sekolah')->get();
            
            $recipients = $wakaAkademik->merge($kepalaSekolah);
            
            if ($recipients->count() > 0) {
                Notification::send($recipients, new \App\Notifications\KerusakanAlatNotification($laporan));
            }
        }
    }

    public function kerusakanUpdate(Request $request, $id)
    {
        $request->validate([
            'kondisi_baru' => 'required|in:Sangat Baik,Baik,Rusak Ringan,Rusak Sedang,Rusak Berat',
            'tindakan_perbaikan' => 'nullable|string',
            'status_perbaikan' => 'required|in:menunggu,dalam_proses,selesai'
        ]);

        try {
            DB::beginTransaction();
            
            $laporan = LaporanKerusakan::with(['inventaris.labor', 'user'])->findOrFail($id);
            
            // Map new status to legacy status (pending, process, completed, rejected)
            $legacyStatus = 'pending';
            if ($request->status_perbaikan === 'dalam_proses') $legacyStatus = 'process';
            if ($request->status_perbaikan === 'selesai') $legacyStatus = 'completed';

            $updateData = [
                'tindakan_perbaikan' => $request->tindakan_perbaikan,
                'status_perbaikan' => $request->status_perbaikan,
                'status' => $legacyStatus
            ];

            // AUTO-APPROVE eskalasi if marked as finished by admin
            if ($request->status_perbaikan === 'selesai' && $laporan->is_eskalasi && $laporan->eskalasi_status === 'menunggu') {
                $updateData['eskalasi_status'] = 'disetujui';
            }

            $laporan->update($updateData);

            // Update inventaris condition safely
            if ($laporan->inventaris) {
                $laporan->inventaris->update(['kondisi' => $request->kondisi_baru]);
            }

            // Notify if status is finished
            if ($request->status_perbaikan === 'selesai') {
                $this->sendRepairFinishedNotifications($laporan);
            }

            DB::commit();
            return back()->with('success', 'Laporan berhasil diperbarui.');
        } catch (Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Update Kerusakan Error: ' . $e->getMessage(), [
                'id' => $id,
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    /**
     * Send notification that equipment repair is finished
     */
    private function sendRepairFinishedNotifications($laporan)
    {
        $recipients = collect();

        // 1. Notify the reporter (if possible)
        if ($laporan->user_id) {
            $recipients->push($laporan->user);
        }

        // 2. Notify Kepala Lab
        $kepalaLab = User::where('role', 'kepala_lab')->get();
        if ($kepalaLab->count() > 0) {
            $recipients = $recipients->merge($kepalaLab);
        }

        // 3. Notify Waka/Kepsek if damage was significant (Sedang or Berat)
        if (in_array($laporan->tingkat_kerusakan, ['Sedang', 'Berat'])) {
            $management = User::whereIn('role', ['waka_akademik', 'kepala_sekolah'])->get();
            $recipients = $recipients->merge($management);
        }

        // Unique recipients only
        $recipients = $recipients->unique('id');

        if ($recipients->count() > 0) {
            Notification::send($recipients, new \App\Notifications\AlatDiperbaikiNotification($laporan));
        }
    }

    public function kerusakanEskalasi(Request $request, $id)
    {
        $request->validate([
            'eskalasi_ke' => 'required|in:kepala_lab,waka_akademik,kepala_sekolah',
            'eskalasi_catatan' => 'required|string|min:5'
        ]);

        try {
            $laporan = LaporanKerusakan::findOrFail($id);
            
            $laporan->update([
                'is_eskalasi' => true,
                'eskalasi_ke' => $request->eskalasi_ke,
                'eskalasi_catatan' => $request->eskalasi_catatan,
                'eskalasi_tanggal' => now(),
                'eskalasi_status' => 'menunggu'
            ]);

            // Log Activity
            \App\Services\Lab\ActivityLogService::log(
                'escalated_damage_report',
                "Mengeksalasi laporan kerusakan '{$laporan->inventaris->nama_inventaris}' ke " . str_replace('_', ' ', $request->eskalasi_ke),
                $laporan
            );

            return back()->with('success', 'Laporan berhasil dieksalasi ke ' . str_replace('_', ' ', $request->eskalasi_ke));
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // --- Static Data Management ---
    public function masterDataIndex()
    {
        $kategoriAlat = \App\Models\Lab\KategoriAlat::orderBy('nama')->get();
        $jenisLab = \App\Models\Lab\JenisLaboratorium::orderBy('nama')->get();
        $statusKondisi = \App\Models\Lab\StatusKondisi::orderBy('nama')->get();
        $sumberAset = \App\Models\Lab\SumberAset::orderBy('nama')->get();
        
        return view('lab.admin-new.master_data.index', compact('kategoriAlat', 'jenisLab', 'statusKondisi', 'sumberAset'));
    }

    // Kategori Alat CRUD
    public function kategoriIndex()
    {
        $categories = \App\Models\Lab\KategoriAlat::orderBy('nama')->get();
        return view('lab.admin-new.inventaris.kategori', compact('categories'));
    }

    public function storeKategori(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string'
        ]);

        \App\Models\Lab\KategoriAlat::create($request->all());
        
        if ($request->has('from_kategori_page')) {
            return redirect()->route('lab.admin_new.inventaris.kategori.index')->with('success', 'Kategori berhasil ditambahkan');
        }
        return redirect()->route('lab.admin_new.master_data.index')->with('success', 'Kategori berhasil ditambahkan');
    }

    public function updateKategori(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string'
        ]);

        $kategori = \App\Models\Lab\KategoriAlat::findOrFail($id);
        $kategori->update($request->all());

        if ($request->has('from_kategori_page')) {
            return redirect()->route('lab.admin_new.inventaris.kategori.index')->with('success', 'Kategori berhasil diupdate');
        }
        return redirect()->route('lab.admin_new.master_data.index')->with('success', 'Kategori berhasil diupdate');
    }

    public function destroyKategori($id)
    {
        $kategori = \App\Models\Lab\KategoriAlat::findOrFail($id);
        $kategori->delete();
        
        if (request()->has('from_kategori_page')) {
            return redirect()->route('lab.admin_new.inventaris.kategori.index')->with('success', 'Kategori berhasil dihapus');
        }
        return redirect()->route('lab.admin_new.master_data.index')->with('success', 'Kategori berhasil dihapus');
    }

    // =========================================================
    // Jenis Lab CRUD (dedicated page)
    // =========================================================
    public function jenisLabIndex()
    {
        // Collation sudah disamakan via migrasi (utf8mb4_unicode_ci),
        // sehingga withCount via relationship bisa berjalan normal
        $jenisLab = \App\Models\Lab\JenisLaboratorium::withCount([
                'laboratorium as lab_count',
            ])
            ->orderBy('nama')
            ->get();

        $warnaOptions = [
            'primary'   => 'Biru',
            'danger'    => 'Merah',
            'warning'   => 'Oranye',
            'success'   => 'Hijau',
            'purple'    => 'Ungu',
            'info'      => 'Cyan',
            'secondary' => 'Abu-abu',
        ];

        $ikonOptions = [
            'bi-pc-display'   => 'Komputer',
            'bi-flask'        => 'Kimia/Erlenmeyer',
            'bi-lightning'    => 'Petir (Fisika)',
            'bi-translate'    => 'Bahasa',
            'bi-camera-video' => 'Kamera Video',
            'bi-building'     => 'Gedung',
            'bi-tools'        => 'Teknik',
            'bi-book'         => 'Buku',
            'bi-calculator'   => 'Kalkulator',
            'bi-cpu'          => 'CPU',
            'bi-music-note'   => 'Musik',
            'bi-easel'        => 'Seni',
            'bi-graph-up'     => 'Ekonomi',
            'bi-diagram-3'    => 'Biologi',
        ];

        $totalLabor = \App\Models\Labor::count();

        return view('lab.admin-new.jenis_lab.index', compact('jenisLab', 'warnaOptions', 'ikonOptions', 'totalLabor'));
    }

    public function storeJenisLab(Request $request)
    {
        $request->validate([
            'nama'        => 'required|string|max:100|unique:jenis_laboratoria,nama',
            'deskripsi'   => 'nullable|string',
            'ikon'        => 'nullable|string|max:50',
            'warna'       => 'required|in:primary,danger,warning,success,purple,info,secondary',
        ]);

        \App\Models\Lab\JenisLaboratorium::create([
            'nama'        => $request->nama,
            'deskripsi'   => $request->deskripsi,
            'ikon'        => $request->ikon ?? 'bi-building',
            'warna'       => $request->warna,
        ]);

        return redirect()->route('lab.admin_new.jenis_lab.index')
            ->with('success', 'Jenis laboratorium "' . $request->nama . '" berhasil ditambahkan');
    }

    public function updateJenisLab(Request $request, $id)
    {
        $request->validate([
            'nama'        => 'required|string|max:100|unique:jenis_laboratoria,nama,' . $id,
            'deskripsi'   => 'nullable|string',
            'ikon'        => 'nullable|string|max:50',
            'warna'       => 'required|in:primary,danger,warning,success,purple,info,secondary',
        ]);

        $jenis = \App\Models\Lab\JenisLaboratorium::findOrFail($id);

        $jenis->update([
            'nama'        => $request->nama,
            'deskripsi'   => $request->deskripsi,
            'ikon'        => $request->ikon ?? 'bi-building',
            'warna'       => $request->warna,
        ]);

        return redirect()->route('lab.admin_new.jenis_lab.index')
            ->with('success', 'Jenis laboratorium "' . $request->nama . '" berhasil diubah');
    }

    public function destroyJenisLab($id)
    {
        $jenis = \App\Models\Lab\JenisLaboratorium::findOrFail($id);

        // Cek apakah masih dipakai oleh laboratorium
        $labCount = \App\Models\Labor::where('jenis_labor', $jenis->nama)->count();
        if ($labCount > 0) {
            return redirect()->route('lab.admin_new.jenis_lab.index')
                ->with('error', 'Jenis "' . $jenis->nama . '" tidak bisa dihapus karena masih digunakan oleh ' . $labCount . ' laboratorium.');
        }

        $jenis->delete();
        return redirect()->route('lab.admin_new.jenis_lab.index')
            ->with('success', 'Jenis laboratorium "' . $jenis->nama . '" berhasil dihapus');
    }

    // Status Kondisi CRUD
    public function storeKondisi(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'warna' => 'required|in:success,warning,danger,info,secondary'
        ]);

        \App\Models\Lab\StatusKondisi::create($request->all());
        return redirect()->route('lab.admin_new.master_data.index')->with('success', 'Status kondisi berhasil ditambahkan');
    }

    public function updateKondisi(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'warna' => 'required|in:success,warning,danger,info,secondary'
        ]);

        $kondisi = \App\Models\Lab\StatusKondisi::findOrFail($id);
        $kondisi->update($request->all());
        return redirect()->route('lab.admin_new.master_data.index')->with('success', 'Status kondisi berhasil diupdate');
    }

    public function destroyKondisi($id)
    {
        $kondisi = \App\Models\Lab\StatusKondisi::findOrFail($id);
        $kondisi->delete();
        return redirect()->route('lab.admin_new.master_data.index')->with('success', 'Status kondisi berhasil dihapus');
    }

    // Sumber Aset CRUD
    public function storeSumber(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string'
        ]);

        \App\Models\Lab\SumberAset::create($request->all());
        return redirect()->route('lab.admin_new.master_data.index')->with('success', 'Sumber aset berhasil ditambahkan');
    }

    public function updateSumber(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string'
        ]);

        $sumber = \App\Models\Lab\SumberAset::findOrFail($id);
        $sumber->update($request->all());
        return redirect()->route('lab.admin_new.master_data.index')->with('success', 'Sumber aset berhasil diupdate');
    }

    public function destroySumber($id)
    {
        $sumber = \App\Models\Lab\SumberAset::findOrFail($id);
        $sumber->delete();
        return redirect()->route('lab.admin_new.master_data.index')->with('success', 'Sumber aset berhasil dihapus');
    }

    // --- Inventory Management ---
    public function inventarisIndex(Request $request)
    {
        $query = Inventaris::with(['labor'])->alat(); // Only equipment, not materials
        
        // Apply filters
        if ($request->filled('kategori')) {
            $query->where('kategori', 'LIKE', '%' . $request->kategori . '%');
        }
        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }
        if ($request->filled('labor_id')) {
            $laborId = (int) $request->labor_id;
            $query->where('labor_id', $laborId);
        }
        if ($request->filled('status')) {
            // Toleran terhadap variasi format: 'tersedia', 'Tersedia', 'tidak_tersedia', 'Tidak Tersedia'
            $statusMap = [
                'tersedia'       => 'Tersedia',
                'Tersedia'       => 'Tersedia',
                'tidak_tersedia' => 'Tidak Tersedia',
                'Tidak Tersedia' => 'Tidak Tersedia',
                'dipinjam'       => 'Dipinjam',
                'Dipinjam'       => 'Dipinjam',
            ];
            $statusValue = $statusMap[$request->status] ?? $request->status;
            $query->where('status', 'LIKE', '%' . $statusValue . '%');
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_inventaris', 'like', '%' . $search . '%')
                  ->orWhere('kode_inventaris', 'like', '%' . $search . '%')
                  ->orWhere('kategori', 'like', '%' . $search . '%');
            });
        }
        
        $inventaris = $query->latest()->paginate(20)->withQueryString();
        $laboratories = Labor::orderBy('nama_labor')->get();
        $categories = Inventaris::alat()->whereNotNull('kategori')->where('kategori', '!=', '')->distinct()->pluck('kategori');

        // Hitung total semua inventaris (tanpa filter) untuk ditampilkan
        $totalInventaris = Inventaris::alat()->count();
        $hasFilter = $request->hasAny(['labor_id', 'kondisi', 'status', 'search', 'kategori']);
        
        return view('lab.admin-new.inventaris.index', compact('inventaris', 'laboratories', 'totalInventaris', 'hasFilter', 'categories'));
    }

    public function inventarisShow($id)
    {
        $inventaris = Inventaris::with([
            'labor',
            'usageHistory.user',
            'damageHistory.user',
            'activityLogs.user'
        ])->findOrFail($id);
        
        $laboratories = Labor::orderBy('nama_labor')->get();
        
        return view('lab.admin-new.inventaris.show', compact('inventaris', 'laboratories'));
    }

    public function inventarisUpdateKondisi(Request $request, $id)
    {
        $request->validate([
            'kondisi' => 'required|in:Sangat Baik,Baik,Rusak Ringan,Rusak Sedang,Rusak Berat'
        ]);

        try {
            $inventaris = Inventaris::findOrFail($id);
            $oldKondisi = $inventaris->kondisi;
            
            $inventaris->update(['kondisi' => $request->kondisi]);
            
            // Log activity
            \App\Services\Lab\ActivityLogService::logInventoryConditionChange(
                $inventaris, 
                $oldKondisi,
                $request->kondisi
            );
            
            return back()->with('success', 'Kondisi alat berhasil diupdate');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function inventarisTransfer(Request $request, $id)
    {
        $request->validate([
            'labor_id' => 'required|exists:labor,id'
        ]);

        try {
            $inventaris = Inventaris::findOrFail($id);
            $oldLaborId = $inventaris->labor_id;
            
            $inventaris->update(['labor_id' => $request->labor_id]);
            
            // Log activity
            \App\Services\Lab\ActivityLogService::logInventoryTransfer(
                $inventaris,
                $oldLaborId,
                $request->labor_id
            );
            
            return back()->with('success', 'Alat berhasil dipindahkan');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function inventarisCreate()
    {
        $laboratories = Labor::orderBy('nama_labor')->get();
        $categories = \App\Models\Lab\KategoriAlat::orderBy('nama')->get();
        return view('lab.admin-new.inventaris.create', compact('laboratories', 'categories'));
    }

    public function inventarisStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_inventaris' => 'required|string|max:255',
            'kode_inventaris' => 'nullable|string|max:50|unique:inventaris,kode_inventaris',
            'kategori' => 'required|string|max:100',
            'jumlah' => 'required|integer|min:1',
            'kondisi' => 'required|in:Sangat Baik,Baik,Rusak Ringan,Rusak Sedang,Rusak Berat',
            'labor_id' => 'nullable|exists:labor,id',
            'tanggal_pengadaan' => 'required|date',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:Tersedia,Tidak Tersedia,Dipinjam',
            'jenis' => 'required|in:Alat,Bahan'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', 'Validasi gagal');
        }

        // Auto-generate kode inventaris jika kosong
        $kode = $request->kode_inventaris;
        if (empty($kode)) {
            $kode = \App\Models\Inventaris::generateKodeInventaris();
        }

        $data = $request->only([
            'nama_inventaris', 'jenis', 'kategori', 'labor_id', 'jumlah',
            'stok_minimum', 'kondisi', 'lokasi', 'tanggal_pengadaan',
            'deskripsi', 'spesifikasi', 'sumber_dana', 'tahun_perolehan', 'status'
        ]);
        $data['kode_inventaris'] = $kode;

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('inventaris', 'public');
        }

        Inventaris::create($data);

        return redirect()->route('lab.admin_new.inventaris.index')
            ->with('success', 'Inventaris berhasil ditambahkan');
    }

    public function inventarisEdit($id)
    {
        $inventaris = Inventaris::findOrFail($id);
        $laboratories = Labor::orderBy('nama_labor')->get();
        $categories = \App\Models\Lab\KategoriAlat::orderBy('nama')->get();
        return view('lab.admin-new.inventaris.edit', compact('inventaris', 'laboratories', 'categories'));
    }

    public function inventarisUpdate(Request $request, $id)
    {
        $inventaris = Inventaris::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'nama_inventaris' => 'required|string|max:255',
            'kode_inventaris' => 'nullable|string|max:50|unique:inventaris,kode_inventaris,' . $id,
            'kategori' => 'required|string|max:100',
            'jumlah' => 'required|integer|min:1',
            'kondisi' => 'required|in:Sangat Baik,Baik,Rusak Ringan,Rusak Sedang,Rusak Berat',
            'labor_id' => 'nullable|exists:labor,id',
            'tanggal_pengadaan' => 'required|date',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:Tersedia,Tidak Tersedia,Dipinjam',
            'jenis' => 'required|in:Alat,Bahan'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', 'Validasi gagal');
        }

        $data = $request->only([
            'nama_inventaris', 'kode_inventaris', 'jenis', 'kategori', 'labor_id', 'jumlah',
            'stok_minimum', 'kondisi', 'lokasi', 'tanggal_pengadaan',
            'deskripsi', 'spesifikasi', 'sumber_dana', 'tahun_perolehan', 'status'
        ]);

        if ($request->hasFile('gambar')) {
            if ($inventaris->gambar) Storage::disk('public')->delete($inventaris->gambar);
            $data['gambar'] = $request->file('gambar')->store('inventaris', 'public');
        }

        $inventaris->update($data);

        return redirect()->route('lab.admin_new.inventaris.show', $id)
            ->with('success', 'Inventaris berhasil diperbarui');
    }

    public function inventarisDestroy($id)
    {
        $inventaris = Inventaris::findOrFail($id);
        if ($inventaris->gambar) Storage::disk('public')->delete($inventaris->gambar);
        $inventaris->delete();
        return redirect()->route('lab.admin_new.inventaris.index')
            ->with('success', 'Inventaris berhasil dihapus');
    }

    // --- Materials Management ---
    public function bahanIndex(Request $request)
    {
        $query = Inventaris::with(['labor'])->bahan(); // Only materials
        
        // Apply category filter
        if ($request->filled('kategori')) {
            $query->where('kategori', 'LIKE', '%' . $request->kategori . '%');
        }

        if ($request->filled('search')) {
            $query->where('nama_inventaris', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('labor_id')) {
            $query->where('labor_id', $request->labor_id);
        }
        
        $bahan = $query->latest()->paginate(20)->withQueryString();
        $laboratories = Labor::orderBy('nama_labor')->get();
        $categories = Inventaris::bahan()->whereNotNull('kategori')->where('kategori', '!=', '')->distinct()->pluck('kategori');
        
        return view('lab.admin-new.bahan.index', compact('bahan', 'laboratories', 'categories'));
    }

    public function bahanUpdateStock(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:add,reduce',
            'jumlah' => 'required|integer|min:1'
        ]);

        try {
            $bahan = Inventaris::where('jenis', 'Bahan')->findOrFail($id);
            $oldStock = $bahan->jumlah;
            
            if ($request->action === 'add') {
                $bahan->jumlah += $request->jumlah;
            } else {
                if ($bahan->jumlah < $request->jumlah) {
                    throw new Exception('Stok tidak mencukupi');
                }
                $bahan->jumlah -= $request->jumlah;
            }
            
            $bahan->save();
            
            // Log activity
            \App\Services\Lab\ActivityLogService::log(
                $request->action === 'add' ? 'added_stock' : 'reduced_stock',
                "Mengubah stok bahan '{$bahan->nama_inventaris}' dari {$oldStock} menjadi {$bahan->jumlah}",
                $bahan,
                ['old_stock' => $oldStock, 'new_stock' => $bahan->jumlah, 'change' => $request->jumlah]
            );
            
            return back()->with('success', 'Stok berhasil diupdate');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // --- External Borrowing ---
    public function pinjamEksternalIndex()
    {
        $peminjaman = PinjamEksternal::with(['inventaris.labor', 'rekomendasiBy', 'approvedBy'])
            ->latest()
            ->get();
        
        return view('lab.admin-new.eksternal.index', compact('peminjaman'));
    }

    public function pinjamEksternalCreate()
    {
        $inventaris = Inventaris::with('labor')
            ->where('status', 'tersedia')
            ->where('jenis', 'Alat')
            ->baik()
            ->orderBy('nama_inventaris')
            ->get();
        
        return view('lab.admin-new.eksternal.create', compact('inventaris'));
    }

    public function pinjamEksternalStore(Request $request)
    {
        $request->validate([
            'nama_peminjam' => 'required|string|max:255',
            'instansi' => 'required|string|max:255',
            'kontak' => 'required|string|max:255',
            'inventaris_id' => 'required|exists:inventaris,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'keperluan' => 'required|string',
            'surat_permohonan' => 'nullable|file|mimes:pdf|max:2048'
        ]);

        try {
            $suratPath = null;
            if ($request->hasFile('surat_permohonan')) {
                $suratPath = $request->file('surat_permohonan')->store('surat_eksternal', 'public');
            }

            $peminjaman = PinjamEksternal::create([
                'nama_peminjam' => $request->nama_peminjam,
                'instansi' => $request->instansi,
                'kontak' => $request->kontak,
                'inventaris_id' => $request->inventaris_id,
                'jumlah' => $request->jumlah,
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'tanggal_kembali' => $request->tanggal_kembali,
                'keperluan' => $request->keperluan,
                'surat_permohonan' => $suratPath,
                'status' => 'menunggu_kepala_lab'
            ]);

            // Log activity
            \App\Services\Lab\ActivityLogService::log(
                'created_external_borrowing',
                "Membuat peminjaman eksternal untuk {$request->nama_peminjam} dari {$request->instansi}",
                $peminjaman
            );

            return redirect()->route('lab.admin_new.eksternal.index')
                ->with('success', 'Peminjaman eksternal berhasil dicatat');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    // --- Activity Log Viewer ---
    public function activityLogIndex(Request $request)
    {
        $query = \App\Models\Lab\ActivityLog::with(['user', 'subject']);
        
        // Apply filters
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        if ($request->filled('subject_type')) {
            $query->where('subject_type', $request->subject_type);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $logs = $query->latest()->paginate(50);
        $users = User::whereIn('role', ['admin_lab', 'kepala_lab', 'super_admin'])->get();
        
        return view('lab.admin-new.activity_log.index', compact('logs', 'users'));
    }

    // --- Lab Schedule Management ---
    public function jadwalIndex(Request $request)
    {
        $laboratoriums = Labor::orderBy('nama_labor')->get();

        // Base Query for student's class schedules
        $query = \App\Models\Lab\JadwalLaboratorium::with(['labor', 'guru', 'kelas_relation'])
            ->orderByRaw("FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')")
            ->orderBy('jam_mulai');

        if ($request->filled('labor_id')) {
            $query->where('labor_id', $request->labor_id);
        }
        if ($request->filled('hari')) {
            $query->where('hari', $request->hari);
        }
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('mata_pelajaran', 'like', '%' . $request->search . '%')
                  ->orWhere('kelas', 'like', '%' . $request->search . '%')
                  ->orWhere('keterangan', 'like', '%' . $request->search . '%');
            });
        }

        $jadwals = $query->get();

        // Group by hari for timetable view
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $jadwalByHari = [];
        foreach ($hariList as $hari) {
            $jadwalByHari[$hari] = $jadwals->where('hari', $hari)->values();
        }

        $totalJadwal = $jadwals->count();
        $guruList = User::where('role', 'guru')->orderBy('nama')->get();
        $kelasList = \App\Models\Kelas::orderBy('nama_kelas')->get();
        $mataPelajaranList = \App\Models\MataPelajaran::select('nama_mata_pelajaran')->distinct()->orderBy('nama_mata_pelajaran')->get();
        
        $mataPelajaranAll = \App\Models\MataPelajaran::with('guru.user')->get();
        $mapelGuruMap = [];
        foreach ($mataPelajaranAll as $mp) {
            $name = $mp->nama_mata_pelajaran;
            if (!isset($mapelGuruMap[$name])) {
                $mapelGuruMap[$name] = [];
            }
            if ($mp->guru && $mp->guru->user) {
                $exists = false;
                foreach ($mapelGuruMap[$name] as $g) {
                    if ($g['id'] == $mp->guru->user->id) {
                        $exists = true; break;
                    }
                }
                if (!$exists) {
                    $mapelGuruMap[$name][] = [
                        'id' => $mp->guru->user->id,
                        'nama' => $mp->guru->user->nama ?? $mp->guru->user->name
                    ];
                }
            }
        }
        $mapelGuruMapJson = json_encode($mapelGuruMap);

        return view('lab.admin-new.jadwal.index', compact(
            'laboratoriums', 'jadwals', 'jadwalByHari', 'hariList',
            'totalJadwal', 'guruList', 'kelasList', 'mataPelajaranList', 'mapelGuruMapJson'
        ));
    }

    public function jadwalStore(Request $request, $labor_id)
    {
        $request->validate([
            'mata_pelajaran' => 'required|string|max:255',
            'guru_id' => 'nullable|exists:users,id',
            'kelas_id' => 'nullable|exists:kelas,id',
            'kelas' => 'nullable|string|max:255',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'keterangan' => 'nullable|string'
        ]);

        try {
            // Check for conflicts
            $hasConflict = \App\Models\Lab\JadwalLaboratorium::checkConflict(
                $labor_id,
                $request->hari,
                $request->jam_mulai,
                $request->jam_selesai
            );

            if ($hasConflict && !$request->has('force')) {
                return back()->with('warning', 'Terdapat jadwal yang bentrok. Tambahkan parameter force=1 jika ingin tetap menambahkan.');
            }

            $jadwal = \App\Models\Lab\JadwalLaboratorium::create([
                'labor_id' => $labor_id,
                'mata_pelajaran' => $request->mata_pelajaran,
                'guru_id' => $request->guru_id,
                'kelas_id' => $request->kelas_id,
                'kelas' => $request->kelas,
                'hari' => $request->hari,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
                'keterangan' => $request->keterangan
            ]);

            return back()->with('success', 'Jadwal berhasil ditambahkan');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function jadwalUpdate(Request $request, $id)
    {
        $request->validate([
            'mata_pelajaran' => 'required|string|max:255',
            'guru_id' => 'nullable|exists:users,id',
            'kelas_id' => 'nullable|exists:kelas,id',
            'kelas' => 'nullable|string|max:255',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'keterangan' => 'nullable|string'
        ]);

        try {
            $jadwal = \App\Models\Lab\JadwalLaboratorium::findOrFail($id);
            
            // Check for conflicts (excluding current jadwal)
            $hasConflict = \App\Models\Lab\JadwalLaboratorium::checkConflict(
                $jadwal->labor_id,
                $request->hari,
                $request->jam_mulai,
                $request->jam_selesai,
                $id
            );

            if ($hasConflict && !$request->has('force')) {
                return back()->with('warning', 'Terdapat jadwal yang bentrok. Tambahkan parameter force=1 jika ingin tetap mengupdate.');
            }

            $jadwal->update($request->only([
                'mata_pelajaran', 'guru_id', 'kelas_id', 'kelas', 'hari', 
                'jam_mulai', 'jam_selesai', 'keterangan'
            ]));

            return back()->with('success', 'Jadwal berhasil diupdate');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function jadwalDestroy($id)
    {
        try {
            $jadwal = \App\Models\Lab\JadwalLaboratorium::findOrFail($id);
            $jadwal->delete();
            
            return back()->with('success', 'Jadwal berhasil dihapus');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
