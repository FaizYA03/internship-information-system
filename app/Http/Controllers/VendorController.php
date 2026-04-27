<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index()
    {
        $vendors = Vendor::all();
        return view('perpustakaan.vendor.index', compact('vendors'));
    }

    public function create()
    {
        return view('perpustakaan.vendor.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:vendors',
            'alamat' => 'nullable|string',
            'kontak' => 'nullable|string',
            'email' => 'nullable|email',
            'telepon' => 'nullable|string',
        ]);

        Vendor::create($request->all());

        return redirect()->route('perpustakaan.vendor.index')->with('success', 'Data Vendor berhasil ditambahkan.');
    }

    public function edit(Vendor $vendor)
    {
        return view('perpustakaan.vendor.edit', compact('vendor'));
    }

    public function update(Request $request, Vendor $vendor)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:vendors,nama,' . $vendor->id,
            'alamat' => 'nullable|string',
            'kontak' => 'nullable|string',
            'email' => 'nullable|email',
            'telepon' => 'nullable|string',
        ]);

        $vendor->update($request->all());

        return redirect()->route('perpustakaan.vendor.index')->with('success', 'Data Vendor berhasil diperbarui.');
    }

    public function destroy(Vendor $vendor)
    {
        $vendor->delete();
        return redirect()->route('perpustakaan.vendor.index')->with('success', 'Data Vendor berhasil dihapus.');
    }
}
