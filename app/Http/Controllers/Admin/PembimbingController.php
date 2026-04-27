<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembimbing;
use App\Models\Guru;
use App\Models\MagangSiswa;
use App\Models\Siswa;

class PembimbingController extends Controller
{
    /**
     * ==============================
     * 📌 INDEX (TAMPIL KE ADMIN)
     * ==============================
     */
    public function index()
    {
        $title = 'Penentuan Guru Pembimbing';
        $header = 'Penentuan Guru Pembimbing';

        // 🔥 tampilkan semua yang perlu diproses + yang sudah final
        $magang = MagangSiswa::whereIn('status', [
                'Diterima Mitra',
                'Disetujui Admin'
            ])
            ->with([
                'opening',
                'user',
                'pembimbing.guru',
                'mitraSupervisor',
                'wakilPerusahaan',
            ])
            ->latest()
            ->get();

        $gurus = Guru::where('status', 'aktif')->get();

        return view('magang.admin.pembimbing.index', compact(
            'title',
            'header',
            'magang',
            'gurus'
        ));
    }

    /**
     * ==============================
     * 📌 STORE (SET GURU PERTAMA KALI)
     * ==============================
     */
    public function store(Request $request)
    {
        $request->validate([
            'magang_id' => 'required',
            'guru_id'   => 'required'
        ]);

        $magang = MagangSiswa::findOrFail($request->magang_id);

        // 🔥 ambil siswa dari user_id
        $siswa = Siswa::where('user_id', $magang->user_id)->first();

        if (!$siswa) {
            return redirect('/admin/pembimbing')
                ->with('error', 'Data siswa tidak ditemukan');
        }

        // 🔥 CEK: sudah ada pembimbing belum
        $pembimbing = Pembimbing::where('magang_id', $magang->id)->first();

        if ($pembimbing) {
            return redirect('/admin/pembimbing')
                ->with('error', 'Pembimbing sudah ada, gunakan edit.');
        }

        // 🔥 SIMPAN PEMBIMBING + LANGSUNG FINAL
        Pembimbing::create([
            'magang_id' => $magang->id,
            'siswa_id'  => $siswa->id,
            'guru_id'   => $request->guru_id,
            'status'    => 'disetujui'
        ]);

        // 🔥 STATUS LANGSUNG FINAL
        $magang->update([
            'status' => 'Disetujui Admin'
        ]);

        return redirect('/admin/pembimbing')
            ->with('success', 'Guru pembimbing berhasil ditetapkan & disetujui admin');
    }

    /**
     * ==============================
     * 📌 UPDATE (GANTI GURU)
     * ==============================
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'guru_id' => 'required'
        ]);

        $pembimbing = Pembimbing::findOrFail($id);

        // 🔥 update pembimbing
        $pembimbing->update([
            'guru_id' => $request->guru_id,
            'status'  => 'disetujui'
        ]);

        // 🔥 pastikan status magang juga final
        $magang = MagangSiswa::find($pembimbing->magang_id);

        if ($magang) {
            $magang->update([
                'status' => 'Disetujui Admin'
            ]);
        }

        return redirect('/admin/pembimbing')
            ->with('success', 'Pembimbing berhasil diperbarui');
    }

    /**
     * ==============================
     * 📌 DELETE
     * ==============================
     */
    public function destroy($id)
    {
        $pembimbing = Pembimbing::findOrFail($id);
        $pembimbing->delete();

        return redirect('/admin/pembimbing')
            ->with('success', 'Pembimbing berhasil dihapus');
    }

    /**
     * ==============================
     * 📌 FORM EDIT
     * ==============================
     */
    public function edit($id)
    {
        $magang = MagangSiswa::with([
                'pembimbing.guru',
                'opening',
                'user'
            ])
            ->findOrFail($id);

        $gurus = Guru::where('status', 'aktif')->get();

        return view('magang.admin.pembimbing.edit', compact(
            'magang',
            'gurus'
        ));
    }
}