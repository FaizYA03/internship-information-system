<?php

namespace App\Http\Controllers;

use App\Models\MagangLaporan;
use App\Models\MagangSiswa;
use App\Models\WakilPerusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WakilPerusahaanReportsController extends Controller
{
    public function index()
{
    $title = 'Laporan Siswa Magang';
    $header = 'Daftar Laporan Kegiatan Siswa Magang';
    $user = Auth::user();

    if ($user->role === 'wakil_perusahaan') {
        $wakilPerusahaan = WakilPerusahaan::where('email', $user->email)->first();

        if (!$wakilPerusahaan) {
            return redirect()->route('magang.wakil_perusahaan.dashboard')
                ->with('status', 'error')
                ->with('title', 'Error')
                ->with('message', 'Data perusahaan tidak ditemukan.');
        }

        $magangSiswaIds = MagangSiswa::where('perusahaan_id', $wakilPerusahaan->id)
            ->where('status', 'Disetujui Admin')
            ->pluck('id');
    } elseif ($user->role === 'admin_magang') {
        // Admin bisa akses semua siswa magang yang disetujui
        $magangSiswaIds = MagangSiswa::where('status', 'Disetujui Admin')->pluck('id');
    } else {
        abort(403);
    }

    $laporans = MagangLaporan::whereIn('magang_siswa_id', $magangSiswaIds)
        ->with('magangSiswa')
        ->orderBy('created_at', 'desc')
        ->get();

    $pendingLaporans = $laporans->where('status', 'submitted');
    $reviewedLaporans = $laporans->whereIn('status', ['approved', 'rejected']);

    return view('magang.wakil_perusahaan.reports.index', compact(
        'title',
        'header',
        'pendingLaporans',
        'reviewedLaporans'
    ));
}

    
    public function show($id)
    {
        $title = 'Detail Laporan';
        $header = 'Detail Laporan Kegiatan Siswa';
        
        $user = Auth::user();
        $wakilPerusahaan = WakilPerusahaan::where('email', $user->email)->first();
        
        if (!$wakilPerusahaan) {
            return redirect()->route('magang.wakil_perusahaan.dashboard')
                ->with('status', 'error')
                ->with('title', 'Error')
                ->with('message', 'Data perusahaan tidak ditemukan.');
        }
        
        $magangSiswaIds = MagangSiswa::where('perusahaan_id', $wakilPerusahaan->id)
                            ->pluck('id');
        
        $laporan = MagangLaporan::whereIn('magang_siswa_id', $magangSiswaIds)
                      ->with('magangSiswa')
                      ->findOrFail($id);
        
        return view('magang.wakil_perusahaan.reports.show', compact(
            'title',
            'header',
            'laporan'
        ));
    }
    
    public function review(Request $request, $id)
    {
        $user = Auth::user();
        $wakilPerusahaan = WakilPerusahaan::where('email', $user->email)->first();
        
        if (!$wakilPerusahaan) {
            return redirect()->back()
                ->with('status', 'error')
                ->with('title', 'Error')
                ->with('message', 'Data perusahaan tidak ditemukan.');
        }
        
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'komentar' => 'required_if:status,rejected|string|nullable',
        ]);
        
        $magangSiswaIds = MagangSiswa::where('perusahaan_id', $wakilPerusahaan->id)
                            ->pluck('id');
        
        $laporan = MagangLaporan::whereIn('magang_siswa_id', $magangSiswaIds)
                      ->where('status', 'submitted')
                      ->findOrFail($id);
        
        $laporan->status = $request->status;
        $laporan->komentar = $request->komentar;
        $laporan->save();
        
        $statusText = $request->status == 'approved' ? 'disetujui' : 'ditolak';
        
        return redirect()->route('magang.wakil_perusahaan.reports')
            ->with('status', 'success')
            ->with('title', 'Berhasil')
            ->with('message', "Laporan kegiatan siswa berhasil $statusText.");
    }
}