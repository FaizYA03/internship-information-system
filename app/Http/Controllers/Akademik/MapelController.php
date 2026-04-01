<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use App\Models\Mapel;
use Illuminate\Http\Request;

class MapelController extends Controller
{
    public function index()
    {
        $mapels = Mapel::orderBy('nama_mapel')->get();
        return view('sistem_akademik.mapel.index', compact('mapels'));
    }

    public function create()
    {
        return view('sistem_akademik.mapel.createOrEdit', ['mapel' => null]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_mapel' => 'required|array',
            'nama_mapel.*' => 'required|string|max:255|unique:mapels,nama_mapel',
        ]);

        foreach ($request->nama_mapel as $nama) {
            if (!empty(trim($nama))) {
                Mapel::firstOrCreate(['nama_mapel' => trim($nama)]);
            }
        }

        return redirect()->route('sistem_akademik.mapels.index')
            ->with('status', 'success')
            ->with('message', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function edit(Mapel $mapel)
    {
        return view('sistem_akademik.mapel.createOrEdit', compact('mapel'));
    }

    public function update(Request $request, Mapel $mapel)
    {
        $request->validate([
            'nama_mapel' => 'required|string|max:255|unique:mapels,nama_mapel,' . $mapel->id,
        ]);

        $mapel->update([
            'nama_mapel' => $request->nama_mapel,
        ]);

        return redirect()->route('sistem_akademik.mapels.index')
            ->with('status', 'success')
            ->with('message', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy(Mapel $mapel)
    {
        $mapel->delete();

        return redirect()->route('sistem_akademik.mapels.index')
            ->with('status', 'success')
            ->with('message', 'Mata pelajaran berhasil dihapus.');
    }
}
