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
        $title = 'Penentuan Guru Pembimbing';
        $header = 'Penentuan Guru Pembimbing';

        // 🔥 ambil siswa yang sudah disetujui admin
        $magang = MagangSiswa::where('status', 'Disetujui Admin')
            ->with(['opening', 'pembimbing.guru'])
            ->get();

        // 🔥 guru aktif
        $gurus = Guru::where('status', 'aktif')->get();

        return view('magang.admin.pembimbing.index', compact(
            'title',
            'header',
            'magang',
            'gurus'
        ));
    }

    /**
     * 📌 STORE (JIKA BELUM ADA PEMBIMBING)
     */
    public function store(Request $request)
    {
        $request->validate([
            'magang_id' => 'required',
            'guru_id' => 'required'
        ]);

        $magang = MagangSiswa::findOrFail($request->magang_id);

        $siswa = Siswa::where('user_id', $magang->user_id)->first();

        if (!$siswa) {
            return back()->with('error', 'Data siswa tidak ditemukan');
        }

        // 🔥 create baru
        Pembimbing::create([
            'magang_id' => $magang->id,
            'siswa_id' => $siswa->id,
            'guru_id' => $request->guru_id,
            'status' => 'ditetapkan'
        ]);

        return back()->with('success', 'Guru pembimbing berhasil ditetapkan');
    }

    /**
     * 📌 UPDATE (DARI MODAL EDIT)
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'guru_id' => 'required'
        ]);

        $pembimbing = Pembimbing::findOrFail($id);

        $pembimbing->update([
            'guru_id' => $request->guru_id,
            'status' => 'ditetapkan'
        ]);

        return back()->with('success', 'Pembimbing berhasil diupdate');
    }

    /**
     * 📌 DELETE
     */
    public function destroy($id)
    {
        $pembimbing = Pembimbing::findOrFail($id);
        $pembimbing->delete();

        return back()->with('success', 'Pembimbing berhasil dihapus');
    }

    /**
     * 📌 APPROVE (OPTIONAL)
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

    public function edit($id)
{
    $magang = MagangSiswa::with('pembimbing.guru', 'opening')->findOrFail($id);
    $gurus = Guru::all();

    return view('magang.admin.pembimbing.edit', compact('magang', 'gurus'));
}
}