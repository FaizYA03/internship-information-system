<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MagangSiswa;
use App\Models\Perusahaan;
use App\Models\MagangOpening;
use App\Models\Siswa;
use App\Models\Pembimbing;
use App\Models\WakilPerusahaan;
use App\Services\RekomendasiGuruService;
use Illuminate\Support\Facades\Auth;

class MagangController extends Controller
{
    public function pelamarAktif()
    {
        return $this->hasMany(\App\Models\MagangSiswa::class, 'opening_id')
            ->where('status', '!=', 'Ditolak');
    }

  public function dashboard()
{
    $title = 'Magang';
    $header = 'Data Magang';

    $perusahaan = Perusahaan::all();

    if (!Auth::check()) {
        return view('magang.landing', compact('perusahaan', 'title'));
    }

    $user = Auth::user();
    $userId = $user->id;

    // 🔥 DATA MAGANG SISWA (hanya untuk siswa)
    $magangSiswa = null;

    if ($user->role === 'siswa') {
        $magangSiswa = MagangSiswa::with([
                'opening',
                'pembimbing.guru',
                'wakilPerusahaan',
                'mitraSupervisor'
            ])
            ->where('user_id', $userId)
            ->whereIn('status', ['Disetujui', 'Disetujui Admin'])
            ->latest()
            ->first();
    }

    // 🔥 PERUSAHAAN MITRA (GLOBAL)
    $perusahaanMitra = WakilPerusahaan::count();

    // 🔥 LOGIKA BERDASARKAN ROLE
    if ($user->role === 'siswa') {

        // DATA KHUSUS SISWA
        $totalSiswaMagang = MagangSiswa::where('user_id', $userId)->count();

        $sudahDisetujui = MagangSiswa::where('user_id', $userId)
            ->whereIn('status', ['Disetujui', 'Disetujui Admin'])
            ->count();

        $totalPendaftar = MagangSiswa::where('user_id', $userId)->count();

    } else {

        // 🔥 ADMIN / SUPER ADMIN → GLOBAL DATA
        $totalSiswaMagang = MagangSiswa::count();

        $sudahDisetujui = MagangSiswa::whereIn('status', ['Disetujui', 'Disetujui Admin'])
            ->count();

        $totalPendaftar = MagangSiswa::count();
    }

    // 🔥 PERSENTASE
    $tingkatKeberhasilan = $totalPendaftar > 0
        ? round(($sudahDisetujui / $totalPendaftar) * 100)
        : 0;

    return view('magang.dashboard', compact(
        'perusahaan',
        'title',
        'header',
        'magangSiswa',
        'perusahaanMitra',
        'totalSiswaMagang',
        'sudahDisetujui',
        'tingkatKeberhasilan'
    ));
}
    public function index()
    {
        $user = Auth::user();
        
        // Cek jika user adalah admin
        if (in_array($user->role, ['super_admin', 'admin_magang'])) {
            $title = 'Kelola Magang';
            $header = 'Kelola Magang Siswa';
            
            // Ambil semua data dengan relasi lengkap
            $applications = MagangSiswa::with([
                'user',
                'perusahaan',
                'opening',
                'pembimbing.guru',
                'wakilPerusahaan',
                'mitraSupervisor'
            ])
            ->latest()
            ->get();
            
            return view('magang.admin.kelola_magang.index', compact('title', 'header', 'applications'));
        }

        // Tampilan untuk Siswa
        $title = 'Data Magang';
        $header = 'Data Magang';
        
        // Get user's internship applications
        $applications = MagangSiswa::where('user_id', $user->id)
                            ->with(['opening', 'wakilPerusahaan', 'mitraSupervisor'])
                            ->latest()
                            ->get();
        
        return view('magang.magang.index', compact('title', 'header', 'applications'));
    }

    public function create()
    {
        $title = 'Daftar Magang';
        $header = 'Pendaftaran Magang';
        
        // Tampilkan SEMUA program magang
        $openings = \App\Models\MagangOpening::with('wakilPerusahaan')
                ->orderBy('created_at', 'desc')
                ->get();

        // Get list of companies
        $perusahaan = Perusahaan::orderBy('nama_perusahaan')->get();

        return view('magang.magang.create', compact('title', 'header', 'perusahaan', 'openings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'perusahaan_id' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        // Validasi siswa hanya bisa daftar jika belum pernah daftar ke program manapun
        $sudahDiterima = MagangSiswa::where('user_id', Auth::id())
    ->whereIn('status', ['Disetujui', 'Disetujui Admin'])
    ->exists();

if ($sudahDiterima) {
    return redirect()->back()
        ->with('status', 'error')
        ->with('title', 'Gagal')
        ->with('message', 'Anda sudah diterima magang dan tidak bisa mendaftar lagi.');
}
        

        // Validasi program magang aktif dan periode masih dibuka
        $opening = null;
        if ($request->opening_id) {
            $opening = MagangOpening::find($request->opening_id);
            if (!$opening || $opening->status !== 'Aktif' || now()->lt($opening->tanggal_mulai) || now()->gt($opening->tanggal_penutupan)) {
                return redirect()->back()->with('status', 'error')->with('title', 'Gagal')->with('message', 'Program magang tidak aktif atau periode pendaftaran sudah ditutup.');
            }
        }

        $magang = new MagangSiswa();
        $magang->nama = $request->nama;
        $magang->perusahaan_id = $request->perusahaan_id;
        $magang->tanggal_mulai = $request->tanggal_mulai;
        $magang->tanggal_selesai = $request->tanggal_selesai;
        $magang->status = 'Menunggu';
        $magang->user_id = Auth::id();
        if ($opening) {
            $magang->opening_id = $opening->id;
            // Reduce quota for the opening
            $opening->jumlah_posisi = $opening->jumlah_posisi - 1;
            $opening->save();
        }
        $magang->save();

        return redirect()->route('magang.magang.index')->with('status', 'success')->with('title', 'Berhasil')->with('message', 'Data magang berhasil ditambah');
    }

    public function show($id)
    {

    }

    public function edit($id)
    {
        $title = 'Magang';
        $header = 'Data Magang';
        $magang = MagangSiswa::findOrFail($id);
        $perusahaan = Perusahaan::all();
        $status = ['Menunggu', 'Disetujui', 'Ditolak'];
        return view('magang.createOrEdit', compact('magang', 'perusahaan', 'title', 'magang', 'status'));
    }

    public function update(Request $request, $id, RekomendasiGuruService $service)
{
    $request->validate([
        'nama' => 'required',
        'perusahaan_id' => 'required',
        'tanggal_mulai' => 'required|date',
        'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        'status' => 'required',
    ]);

    $magang = MagangSiswa::findOrFail($id);
    $magang->update($request->all());

    // 🚀 TRIGGER SAAT DISETUJUI
    if ($request->status == 'Disetujui') {

        // Ambil siswa berdasarkan user_id
        $siswa = Siswa::where('user_id', $magang->user_id)->first();

        if ($siswa) {

            // Cek apakah sudah ada pembimbing
            $existing = Pembimbing::where('siswa_id', $siswa->id)->first();

            if (!$existing) {

                // Ambil rekomendasi guru
                $guru = $service->getRekomendasi($siswa);

                if ($guru) {
                    Pembimbing::create([
                        'siswa_id' => $siswa->id,
                        'guru_id' => $guru->id,
                        'magang_id' => $magang->opening_id,
                        'status' => 'rekomendasi'
                    ]);
                }
            }
        }
    }

    return redirect()->route('magang.magang.index')
        ->with('status', 'success')
        ->with('title', 'Berhasil')
        ->with('message', 'Data magang berhasil diubah');
}

    public function destroy($id)
    {
        $magang = MagangSiswa::findOrFail($id);
        $magang->delete();

        return redirect()->route('magang.magang.index')->with('status', 'success')->with('title', 'Berhasil')->with('message', 'Data magang berhasil dihapus');
    }

    public function apply(Request $request)
    {
        try {
            $user = Auth::user();
            $opening = MagangOpening::findOrFail($request->opening_id);
            
            // Check if user has already applied for this opening
            $existingApplication = MagangSiswa::where('user_id', $user->id)
                                      ->where('opening_id', $opening->id)
                                      ->first();
            
            if ($existingApplication) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah mendaftar pada program magang ini.'
                ]);
            }

            // ❗ CEK SUDAH DITERIMA
        $sudahDiterima = MagangSiswa::where('user_id', $user->id)
            ->whereIn('status', ['Disetujui', 'Disetujui Admin'])
            ->exists();

        if ($sudahDiterima) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah diterima magang dan tidak bisa mendaftar lagi.'
            ]);
        }
            
            // Create new application
            $application = new MagangSiswa();
            $application->nama = $user->nama;
            $application->email = $user->email;
            $application->no_hp = $user->no_hp ?? null;
            $application->perusahaan_id = $opening->wakil_perusahaan_id;
            $application->opening_id = $opening->id;
            $application->tanggal_mulai = $opening->tanggal_mulai;
            $application->tanggal_selesai = $opening->tanggal_selesai;
            $application->status = 'Menunggu';
            $application->user_id = $user->id;
            $application->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran berhasil. Menunggu konfirmasi dari perusahaan.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
}