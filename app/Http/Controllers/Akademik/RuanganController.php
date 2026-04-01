<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use App\Models\Ruangan;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    public function index()
    {
        $ruangans = Ruangan::orderBy('jenis_ruangan')->orderBy('nama_ruangan')->get();
        return view('sistem_akademik.ruangan.index', compact('ruangans'));
    }

    public function create()
    {
        return view('sistem_akademik.ruangan.createOrEdit', ['ruangan' => null]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_ruangan' => 'required|array',
            'nama_ruangan.*' => 'required|string|max:255',
            'jenis_ruangan' => 'required|array',
            'jenis_ruangan.*' => 'required|string|max:50',
        ]);

        foreach ($request->nama_ruangan as $index => $nama) {
            if (!empty(trim($nama))) {
                Ruangan::create([
                    'nama_ruangan' => trim($nama),
                    'jenis_ruangan' => $request->jenis_ruangan[$index] ?? 'Lainnya',
                ]);
            }
        }

        return redirect()->route('sistem_akademik.ruangans.index')
            ->with('status', 'success')
            ->with('message', 'Data Ruangan berhasil ditambahkan.');
    }

    public function edit(Ruangan $ruangan)
    {
        return view('sistem_akademik.ruangan.createOrEdit', compact('ruangan'));
    }

    public function update(Request $request, Ruangan $ruangan)
    {
        $request->validate([
            'nama_ruangan' => 'required|string|max:255',
            'jenis_ruangan' => 'required|string|max:50',
        ]);

        $ruangan->update([
            'nama_ruangan' => $request->nama_ruangan,
            'jenis_ruangan' => $request->jenis_ruangan,
        ]);

        return redirect()->route('sistem_akademik.ruangans.index')
            ->with('status', 'success')
            ->with('message', 'Data Ruangan berhasil diperbarui.');
    }

    public function destroy(Ruangan $ruangan)
    {
        $ruangan->delete();

        return redirect()->route('sistem_akademik.ruangans.index')
            ->with('status', 'success')
            ->with('message', 'Data Ruangan berhasil dihapus.');
    }
}
