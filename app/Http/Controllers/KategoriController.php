<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::withCount('books')->get();
        return view('perpustakaan.kategori.index', [
            'kategoris' => $kategoris,
            'title' => 'Kategori Buku'
        ]);
    }

    public function show(Kategori $kategori)
    {
        $kategori->load('books');
        return view('perpustakaan.kategori.show', [
            'kategori' => $kategori,
            'title' => 'Detail Kategori: ' . $kategori->nama_kategori
        ]);
    }

    public function create()
    {
        return view('perpustakaan.kategori.create', [
            'title' => 'Tambah Kategori'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'kode_buku' => 'required|string|max:50',
        ]);

        // Mengisi semua kolom pada tabel
        Kategori::create([
            'nama_kategori' => $request->nama_kategori,
            'kode_buku' => $request->kode_buku,
            'jumlah' => 0, // default atau diabaikan
        ]);

        return redirect()->route('perpustakaan.kategori.index')->with('success', 'Kategori berhasil ditambahkan');
    }

    public function edit(Kategori $kategori)
    {
        return view('perpustakaan.kategori.edit', [
            'kategori' => $kategori,
            'title' => 'Edit Kategori'
        ]);
    }

    public function update(Request $request, Kategori $kategori)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'kode_buku' => 'required|string|max:50',
        ]);

        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
            'kode_buku' => $request->kode_buku,
        ]);

        return redirect()->route('perpustakaan.kategori.index')->with('success', 'Kategori berhasil diupdate');
    }

    public function destroy(Kategori $kategori)
    {
        $kategori->delete();
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus');
    }
}
