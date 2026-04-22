<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\MagangLaporan;
use App\Models\MagangSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MagangLaporanController extends Controller
{
    public function index()
    {
        $title = 'Laporan Magang';
        $header = 'Daftar Laporan Kegiatan Magang';
        
        $user = Auth::user();
        $magangSiswa = MagangSiswa::where('user_id', $user->id)
                            ->where('status', 'Disetujui Admin')
                            ->first();
        
        if (!$magangSiswa) {
            return redirect()->route('magang.magang.index')
                ->with('status', 'error')
                ->with('title', 'Akses Ditolak')
                ->with('message', 'Anda tidak memiliki akses ke menu laporan magang.');
        }
        
        $laporans = MagangLaporan::where('magang_siswa_id', $magangSiswa->id)
                        ->orderBy('minggu_ke', 'desc')
                        ->get();
        
        return view('magang.siswa.laporan.index', compact(
            'title',
            'header',
            'laporans',
            'magangSiswa'
        ));
    }

    public function create()
    {
        $title = 'Buat Laporan Magang';
        $header = 'Buat Laporan Kegiatan Magang';
        
        $user = Auth::user();
        $magangSiswa = MagangSiswa::where('user_id', $user->id)
                            ->where('status', 'Disetujui Admin')
                            ->first();
        
        if (!$magangSiswa) {
            return redirect()->route('magang.magang.index')
                ->with('status', 'error')
                ->with('title', 'Akses Ditolak')
                ->with('message', 'Anda tidak memiliki akses ke menu laporan magang.');
        }
        
        // Determine next week number
        $latestReport = MagangLaporan::where('magang_siswa_id', $magangSiswa->id)
                            ->orderBy('minggu_ke', 'desc')
                            ->first();
        
        $nextWeek = $latestReport ? $latestReport->minggu_ke + 1 : 1;
        
        return view('magang.siswa.laporan.create', compact(
            'title',
            'header',
            'magangSiswa',
            'nextWeek'
        ));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $magangSiswa = MagangSiswa::where('user_id', $user->id)
                            ->where('status', 'Disetujui Admin')
                            ->first();
        
        if (!$magangSiswa) {
            return redirect()->route('magang.magang.index')
                ->with('status', 'error')
                ->with('title', 'Akses Ditolak')
                ->with('message', 'Anda tidak memiliki akses ke menu laporan magang.');
        }
        
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'minggu_ke' => 'required|integer|min:1',
            'tanggal_mulai' => 'required|date',
            'status' => 'required|in:draft,submitted',
        ]);
        
        MagangLaporan::create([
            'magang_siswa_id' => $magangSiswa->id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'minggu_ke' => $request->minggu_ke,
            'tanggal_mulai' => $request->tanggal_mulai,
            'status' => $request->status,
        ]);
        
        return redirect()->route('magang.siswa.laporan.index')
            ->with('status', 'success')
            ->with('title', 'Berhasil')
            ->with('message', 'Laporan kegiatan magang berhasil dibuat.');
    }

    public function show($id)
    {
        $title = 'Detail Laporan';
        $header = 'Detail Laporan Kegiatan';
        
        $user = Auth::user();
        $magangSiswa = MagangSiswa::where('user_id', $user->id)->first();
        
        if (!$magangSiswa) {
            return redirect()->route('magang.magang.index')
                ->with('status', 'error')
                ->with('title', 'Akses Ditolak')
                ->with('message', 'Anda tidak memiliki akses ke menu laporan magang.');
        }
        
        $laporan = MagangLaporan::where('id', $id)
                      ->where('magang_siswa_id', $magangSiswa->id)
                      ->firstOrFail();
        
        return view('magang.siswa.laporan.show', compact(
            'title',
            'header',
            'laporan'
        ));
    }

    public function edit($id)
    {
        $title = 'Edit Laporan';
        $header = 'Edit Laporan Kegiatan';
        
        $user = Auth::user();
        $magangSiswa = MagangSiswa::where('user_id', $user->id)->first();
        
        if (!$magangSiswa) {
            return redirect()->route('magang.magang.index')
                ->with('status', 'error')
                ->with('title', 'Akses Ditolak')
                ->with('message', 'Anda tidak memiliki akses ke menu laporan magang.');
        }
        
        $laporan = MagangLaporan::where('id', $id)
                      ->where('magang_siswa_id', $magangSiswa->id)
                      ->where('status', '!=', 'approved')
                      ->firstOrFail();
        
        return view('magang.siswa.laporan.edit', compact(
            'title',
            'header',
            'laporan'
        ));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $magangSiswa = MagangSiswa::where('user_id', $user->id)->first();
        
        if (!$magangSiswa) {
            return redirect()->route('magang.magang.index')
                ->with('status', 'error')
                ->with('title', 'Akses Ditolak')
                ->with('message', 'Anda tidak memiliki akses ke menu laporan magang.');
        }
        
        $laporan = MagangLaporan::where('id', $id)
                      ->where('magang_siswa_id', $magangSiswa->id)
                      ->where('status', '!=', 'approved')
                      ->firstOrFail();
        
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'minggu_ke' => 'required|integer|min:1',
            'tanggal_mulai' => 'required|date',
            'status' => 'required|in:draft,submitted',
        ]);
        
        $laporan->update([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'minggu_ke' => $request->minggu_ke,
            'tanggal_mulai' => $request->tanggal_mulai,
            'status' => $request->status,
        ]);
        
        return redirect()->route('magang.siswa.laporan.index')
            ->with('status', 'success')
            ->with('title', 'Berhasil')
            ->with('message', 'Laporan kegiatan magang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $magangSiswa = MagangSiswa::where('user_id', $user->id)->first();
        
        if (!$magangSiswa) {
            return redirect()->route('magang.magang.index')
                ->with('status', 'error')
                ->with('title', 'Akses Ditolak')
                ->with('message', 'Anda tidak memiliki akses ke menu laporan magang.');
        }
        
        $laporan = MagangLaporan::where('id', $id)
                      ->where('magang_siswa_id', $magangSiswa->id)
                      ->where('status', '!=', 'approved')
                      ->firstOrFail();
        
        $laporan->delete();
        
        return redirect()->route('magang.siswa.laporan.index')
            ->with('status', 'success')
            ->with('title', 'Berhasil')
            ->with('message', 'Laporan kegiatan magang berhasil dihapus.');
    }
}