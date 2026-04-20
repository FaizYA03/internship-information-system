<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanJudul;
use App\Models\Guru;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PengajuanJudulController extends Controller
{
   

    // ================= GURU =================
  public function index()
{
    $guru = Guru::where('user_id', Auth::id())->first();

    if (!$guru) {
        return view('magang.admin.pengajuan_judul.index', [
            'pengajuan' => collect()
        ]);
    }

    $pengajuan = PengajuanJudul::with(['user', 'wakilPerusahaan'])
        ->whereHas('user.siswa.pembimbing', function ($q) use ($guru) {
            $q->where('guru_id', $guru->id);
        })
        ->latest()
        ->get();

    return view('magang.admin.pengajuan_judul.index', compact('pengajuan'));
}
    // ================= REVIEW =================
    public function review(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,accepted,rejected',
            'catatan' => 'nullable|string'
        ]);

        $pengajuan = PengajuanJudul::findOrFail($id);

        $pengajuan->update([
            'status' => $request->status, // ✅ sesuai ENUM DB
            'catatan' => $request->catatan
        ]);

        return back()->with('success', 'Pengajuan berhasil direview.');
    }

    // ================= EXPORT PDF =================
    public function exportPdf()
    {
        $pengajuan = PengajuanJudul::with(['user', 'wakilPerusahaan'])->get();

        $pdf = Pdf::loadView('magang.admin.pengajuan_judul.pdf', compact('pengajuan'));

        return $pdf->download('daftar-pengajuan-judul.pdf');
    }

    // ================= CREATE =================
    public function create()
    {
        $user = Auth::user();

        // 🔥 ambil magang yang SUDAH DISETUJUI
        $magangSiswa = $user->magangSiswa()
            ->whereIn('status', ['Disetujui', 'Disetujui Admin'])
            ->latest()
            ->first();

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
            'jurusan' => 'required|string',
            'judul_laporan' => 'required|string|max:255',
            'link_drive' => 'required|url', // ✅ wajib
            'wakil_perusahaan_id' => 'required|exists:wakil_perusahaans,id',
        ]);

        // 🔥 CEK DUPLIKAT
        $cek = PengajuanJudul::where('user_id', Auth::id())->first();
        if ($cek) {
            return back()->with('error', 'Anda sudah pernah mengajukan judul!');
        }

        PengajuanJudul::create([
            'user_id' => Auth::id(),
            'jurusan' => $request->jurusan,
            'wakil_perusahaan_id' => $request->wakil_perusahaan_id,
            'judul_laporan' => $request->judul_laporan,
            'link_drive' => $request->link_drive,
            'status' => 'pending', // ✅ sesuai ENUM
            'catatan' => null
        ]);

        return redirect()
            ->route('magang.pengajuan_judul.indexsiswa')
            ->with('success', 'Pengajuan judul berhasil dikirim.');
    }
}