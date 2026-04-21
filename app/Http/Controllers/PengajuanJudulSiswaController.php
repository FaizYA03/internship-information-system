<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanJudul;
use Illuminate\Support\Facades\Auth;

class PengajuanJudulSiswaController extends Controller
{
    // ================= INDEX =================
    public function index()
    {
        if (Auth::user()->role === 'siswa') {
            $pengajuanJuduls = PengajuanJudul::with('wakilPerusahaan')
                ->where('user_id', Auth::id())
                ->get();
        } else {
            $pengajuanJuduls = PengajuanJudul::with('user', 'wakilPerusahaan')
                ->latest()
                ->get();
        }

        return view('magang.pengajuan_judul.indexsiswa', compact('pengajuanJuduls'));
    }

    // ================= CREATE =================
    public function create()
    {
        $user = Auth::user();

        // 🔥 ambil data magang siswa yang SUDAH DISETUJUI
        $magangSiswa = $user->magangSiswa()
            ->whereIn('status', ['Disetujui', 'Disetujui Admin'])
            ->latest()
            ->first();

        // 🔥 ambil perusahaan dari relasi
        $namaPerusahaan = $magangSiswa?->wakilPerusahaan?->nama_perusahaan;
        $wakilPerusahaanId = $magangSiswa?->wakilPerusahaan?->id;

        return view('magang.pengajuan_judul.create', compact(
            'namaPerusahaan',
            'wakilPerusahaanId'
        ));
    }

    // ================= STORE =================
    public function store(Request $request)
    {
        $request->validate([
            'jurusan' => 'required',
            'judul_laporan' => 'required',
            'link_drive' => 'required|url',
            'wakil_perusahaan_id' => 'required',
        ]);

        // 🔥 CEGAH DUPLIKAT (opsional tapi penting)
        $cek = PengajuanJudul::where('user_id', Auth::id())->first();
        if ($cek) {
            return back()->with('error', 'Anda sudah pernah mengajukan judul!');
        }

        PengajuanJudul::create([
            'user_id' => Auth::id(),
            'jurusan' => $request->jurusan,
            'judul_laporan' => $request->judul_laporan,
            'link_drive' => $request->link_drive,
            'wakil_perusahaan_id' => $request->wakil_perusahaan_id,
            'status' => 'pending', // ✅ sesuai DB
        ]);

        return redirect()
            ->route('magang.pengajuan_judul.indexsiswa')
            ->with('success', 'Pengajuan berhasil dikirim!');
    }

    // ================= EDIT =================
    public function edit($id)
    {
        $pengajuan = PengajuanJudul::findOrFail($id);

        // 🔥 CEGAH AKSES - hanya pemilik pengajuan yang bisa edit
        if ($pengajuan->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit pengajuan ini.');
        }

        // 🔥 CEGAH EDIT - jika status bukan pending
        if ($pengajuan->status !== 'pending') {
            return redirect()
                ->route('magang.pengajuan_judul.indexsiswa')
                ->with('error', 'Pengajuan tidak dapat diedit setelah direview oleh pembimbing.');
        }

        $user = Auth::user();
        $magangSiswa = $user->magangSiswa()
            ->whereIn('status', ['Disetujui', 'Disetujui Admin'])
            ->latest()
            ->first();

        $namaPerusahaan = $magangSiswa?->wakilPerusahaan?->nama_perusahaan;
        $wakilPerusahaanId = $magangSiswa?->wakilPerusahaan?->id;

        return view('magang.pengajuan_judul.edit', compact(
            'pengajuan',
            'namaPerusahaan',
            'wakilPerusahaanId'
        ));
    }

    // ================= UPDATE =================
    public function update(Request $request, $id)
    {
        $pengajuan = PengajuanJudul::findOrFail($id);

        // 🔥 CEGAH AKSES - hanya pemilik pengajuan yang bisa update
        if ($pengajuan->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengupdate pengajuan ini.');
        }

        // 🔥 CEGAH UPDATE - jika status bukan pending
        if ($pengajuan->status !== 'pending') {
            return redirect()
                ->route('magang.pengajuan_judul.indexsiswa')
                ->with('error', 'Pengajuan tidak dapat diedit setelah direview oleh pembimbing.');
        }

        $request->validate([
            'jurusan' => 'required',
            'judul_laporan' => 'required',
            'link_drive' => 'required|url',
            'wakil_perusahaan_id' => 'required',
        ]);

        $pengajuan->update([
            'jurusan' => $request->jurusan,
            'judul_laporan' => $request->judul_laporan,
            'link_drive' => $request->link_drive,
            'wakil_perusahaan_id' => $request->wakil_perusahaan_id,
        ]);

        return redirect()
            ->route('magang.pengajuan_judul.indexsiswa')
            ->with('success', 'Pengajuan berhasil diperbarui!');
    }
}