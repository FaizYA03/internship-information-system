<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use Illuminate\Http\Request;

class JurusanController extends Controller
{
    public function index()
    {
        $title = 'Kelola Jurusan';
        $header = 'Data Jurusan';
        $jurusans = Jurusan::all();
        return view('sistem_akademik.jurusan.index', compact('jurusans', 'title', 'header'));
    }

    public function create()
    {
        $title = 'Kelola Jurusan';
        $header = 'Tambah Data Jurusan';
        return view('sistem_akademik.jurusan.createOrEdit', compact('title', 'header'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jurusan' => 'required|string|max:255|unique:jurusans',
        ]);

        Jurusan::create($request->all());

        return redirect()->route('sistem_akademik.jurusan.index')
            ->with('status', 'success')
            ->with('message', 'Jurusan berhasil ditambahkan');
    }

    public function edit(Jurusan $jurusan)
    {
        $title = 'Kelola Jurusan';
        $header = 'Edit Data Jurusan';
        return view('sistem_akademik.jurusan.createOrEdit', compact('jurusan', 'title', 'header'));
    }

    public function update(Request $request, Jurusan $jurusan)
    {
        $request->validate([
            'nama_jurusan' => 'required|string|max:255|unique:jurusans,nama_jurusan,' . $jurusan->id,
        ]);

        $jurusan->update($request->all());

        return redirect()->route('sistem_akademik.jurusan.index')
            ->with('status', 'success')
            ->with('message', 'Jurusan berhasil diperbarui');
    }

    public function destroy(Jurusan $jurusan)
    {
        $jurusan->delete();
        return redirect()->route('sistem_akademik.jurusan.index')
            ->with('status', 'success')
            ->with('message', 'Jurusan berhasil dihapus');
    }
}
