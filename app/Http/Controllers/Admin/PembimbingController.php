<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembimbing;
use App\Models\Guru;

class PembimbingController extends Controller
{
    public function index()
    {
        $data = Pembimbing::with('siswa', 'guru', 'magang')->latest()->get();
        return view('admin.pembimbing.index', compact('data'));
    }

    public function approve($id)
    {
        $pembimbing = Pembimbing::findOrFail($id);
        $pembimbing->update(['status' => 'disetujui']);

        return back()->with('success', 'Pembimbing disetujui');
    }

    public function updateGuru(Request $request, $id)
    {
        $request->validate([
            'guru_id' => 'required|exists:gurus,id'
        ]);

        $pembimbing = Pembimbing::findOrFail($id);

        $pembimbing->update([
            'guru_id' => $request->guru_id,
            'status' => 'disetujui'
        ]);

        return back()->with('success', 'Guru pembimbing berhasil diganti');
    }
}