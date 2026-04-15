<?php

namespace App\Http\Controllers;

use App\Models\MagangSiswa;
use App\Models\WakilPerusahaan;
use App\Models\Pembimbing;
use App\Models\Siswa;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WakilPerusahaanInternsController extends Controller
{
    public function index()
    {
        $title = 'Siswa Magang';
        $header = 'Daftar Siswa Magang';
        
        $user = Auth::user();
        $wakilPerusahaan = WakilPerusahaan::where('email', $user->email)->first();
        
        if (!$wakilPerusahaan) {
            return redirect()->route('magang.wakil_perusahaan.dashboard')
                ->with('status', 'error')
                ->with('message', 'Data perusahaan tidak ditemukan.');
        }
        
        // 🔥 MENUNGGU
        $pendingInterns = MagangSiswa::where('perusahaan_id', $wakilPerusahaan->id)
            ->where('status', 'Menunggu')
            ->with('opening')
            ->get();
        
        // 🔥 SUDAH DITERIMA MITRA (MENUNGGU ADMIN)
        $acceptedInterns = MagangSiswa::where('perusahaan_id', $wakilPerusahaan->id)
            ->where('status', 'Diterima Mitra')
            ->with('opening')
            ->get();
        
        // 🔥 SUDAH FINAL DARI ADMIN
        $finalInterns = MagangSiswa::where('perusahaan_id', $wakilPerusahaan->id)
            ->where('status', 'Disetujui Admin')
            ->with('opening')
            ->get();
        
        // 🔥 DITOLAK
        $rejectedInterns = MagangSiswa::where('perusahaan_id', $wakilPerusahaan->id)
            ->where('status', 'Ditolak')
            ->with('opening')
            ->get();
        
        return view('magang.wakil_perusahaan.interns.index', compact(
            'title',
            'header',
            'pendingInterns',
            'acceptedInterns',
            'finalInterns',
            'rejectedInterns'
        ));
    }
    
    public function show($id)
    {
        $title = 'Detail Siswa Magang';
        $header = 'Detail Siswa Magang';
        
        $user = Auth::user();
        $wakilPerusahaan = WakilPerusahaan::where('email', $user->email)->first();
        
        if (!$wakilPerusahaan) {
            return redirect()->route('magang.wakil_perusahaan.dashboard')
                ->with('status', 'error')
                ->with('message', 'Data perusahaan tidak ditemukan.');
        }
        
        $intern = MagangSiswa::where('id', $id)
            ->where('perusahaan_id', $wakilPerusahaan->id)
            ->with('opening')
            ->firstOrFail();
        
        return view('magang.wakil_perusahaan.interns.show', compact('title', 'header', 'intern'));
    }
    
    // 🔥 TERIMA (SEKARANG JADI "DITERIMA MITRA")
  

public function approve(Request $request, $id)
{
    $user = Auth::user();
    $wakilPerusahaan = WakilPerusahaan::where('email', $user->email)->first();

    $intern = MagangSiswa::where('id', $id)
                ->where('perusahaan_id', $wakilPerusahaan->id)
                ->where('status', 'Menunggu')
                ->firstOrFail();

    // ✅ update status
    $intern->status = 'Diterima Mitra';
    $intern->save();

    // 🔥 AMBIL DATA SISWA
    $siswa = Siswa::where('user_id', $intern->user_id)->first();

    if ($siswa) {

        // 🔥 REKOMENDASI GURU
        $guru = Guru::where('jurusan_id', $siswa->jurusan_id)
            ->where('status', 'aktif')
            ->withCount('pembimbing')
            ->orderBy('pembimbing_count', 'asc')
            ->first();

        // 🔥 SIMPAN KE PEMBIMBING
        Pembimbing::create([
            'siswa_id' => $siswa->id,
            'guru_id' => $guru?->id,
            'magang_id' => $intern->id,
            'status' => 'rekomendasi'
        ]);
    }

    return redirect()->back()->with('success', 'Siswa diterima & masuk ke admin');
}
    
    // 🔥 TOLAK
    public function reject(Request $request, $id)
    {
        $user = Auth::user();
        $wakilPerusahaan = WakilPerusahaan::where('email', $user->email)->first();
        
        if (!$wakilPerusahaan) {
            return redirect()->back()->with('error', 'Data perusahaan tidak ditemukan.');
        }
        
        $request->validate([
            'alasan' => 'required',
        ]);
        
        $intern = MagangSiswa::where('id', $id)
            ->where('perusahaan_id', $wakilPerusahaan->id)
            ->where('status', 'Menunggu')
            ->firstOrFail();
        
        $intern->status = 'Ditolak';
        $intern->catatan = $request->alasan;
        $intern->save();
        
        return redirect()->route('magang.wakil_perusahaan.interns')
            ->with('success', 'Pendaftaran siswa ditolak.');
    }
}