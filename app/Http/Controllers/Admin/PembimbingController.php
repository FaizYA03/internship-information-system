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
     * 📌 TAMPIL DATA KE ADMIN
     */
    public function index()
    {
        // 🔥 ambil siswa yang sudah diterima mitra
        $magang = MagangSiswa::with('opening')
            ->where('status', 'Diterima Mitra')
            ->get();

        // 🔥 ambil semua guru aktif
        $gurus = Guru::where('status', 'aktif')->get();

        return view('magang.admin.pembimbing.index', compact('magang', 'gurus'));
    }

    /**
     * 📌 ADMIN PILIH GURU PEMBIMBING
     */
    public function store(Request $request)
    {
        $request->validate([
            'guru_id' => 'required|exists:guru,id',
            'magang_id' => 'required|exists:magang_siswa,id'
        ]);

        $magang = MagangSiswa::findOrFail($request->magang_id);

        // 🔥 ambil siswa
        $siswa = Siswa::where('user_id', $magang->user_id)->first();

        if (!$siswa) {
            return back()->with('error', 'Data siswa tidak ditemukan');
        }

        // ❌ cegah double assign
        $cek = Pembimbing::where('siswa_id', $siswa->id)
            ->where('magang_id', $magang->id)
            ->first();

        if ($cek) {
            return back()->with('error', 'Pembimbing sudah ditentukan');
        }

        // 🔥 simpan pembimbing
        Pembimbing::create([
            'siswa_id' => $siswa->id,
            'guru_id' => $request->guru_id,
            'magang_id' => $magang->id,
            'status' => 'disetujui'
        ]);

        // 🔥 update status magang final
        $magang->update([
            'status' => 'Disetujui Admin'
        ]);

        return back()->with('success', 'Guru pembimbing berhasil ditentukan');
    }

    /**
     * 📌 APPROVE (optional kalau masih dipakai)
     */
    public function approve($id)
    {
        $pembimbing = Pembimbing::findOrFail($id);

        $pembimbing->update([
            'status' => 'disetujui'
        ]);

        $magang = MagangSiswa::find($pembimbing->magang_id);

        if ($magang) {
            $magang->update([
                'status' => 'Disetujui Admin'
            ]);
        }

        return back()->with('success', 'Pembimbing disetujui');
    }

    /**
     * 📌 GANTI GURU
     */
    public function updateGuru(Request $request, $id)
    {
        $request->validate([
            'guru_id' => 'required|exists:guru,id'
        ]);

        $pembimbing = Pembimbing::findOrFail($id);

        $pembimbing->update([
            'guru_id' => $request->guru_id
        ]);

        return back()->with('success', 'Guru pembimbing berhasil diganti');
    }
}