<?php

namespace App\Http\Controllers;

use App\Models\WakilPerusahaan;
use Illuminate\Http\Request;
use App\Models\User;

class WakilController extends Controller
{
    public function index()
    {
        $wakils = WakilPerusahaan::all();
        return view('magang.perusahaan.index', compact('wakils'));
    }

    public function create()
    {
        $title = 'Tambah Perusahaan';
        $header = 'Form Tambah Wakil Perusahaan';
        return view('magang.perusahaan.createoredit', compact('title', 'header'));
    }

   public function store(Request $request)
{
    $request->validate([
        'nama' => 'required|string|max:255',
        'email' => 'required|email|unique:wakil_perusahaan,email|unique:users,email',
        'nama_perusahaan' => 'required|string|max:255',
        'alamat' => 'required|string',
        'no_perusahaan' => 'required|string',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $hashedPassword = bcrypt($request->password);

    // 1. Simpan dulu ke users
    $user = User::create([
        'nama' => $request->nama,
        'email' => $request->email,
        'password' => $hashedPassword,
        'role' => 'wakil_perusahaan',
    ]);

    // 2. Baru simpan ke wakil_perusahaan dan kaitkan dengan user_id
    WakilPerusahaan::create([
        'user_id' => $user->id, // 👈 inilah kunci yang sebelumnya hilang
        'nama' => $request->nama,
        'email' => $request->email,
        'nama_perusahaan' => $request->nama_perusahaan,
        'alamat' => $request->alamat,
        'no_perusahaan' => $request->no_perusahaan,
        'password' => $hashedPassword,
        'status' => 'Accepted',
        'bukti_lampiran' => '-',
    ]);

    return redirect()->route('magang.perusahaan.index')
        ->with('success', 'Wakil perusahaan berhasil ditambahkan.');
}


    public function edit($id)
    {
        $wakil = WakilPerusahaan::findOrFail($id);
        $title = 'Edit Perusahaan';
        $header = 'Edit Data Wakil Perusahaan';
        return view('magang.perusahaan.createoredit', compact('wakil', 'title', 'header'));
    }

    public function update(Request $request, $id)
    {
        $wakil = WakilPerusahaan::findOrFail($id);

        $rules = [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:wakil_perusahaan,email,' . $wakil->id . '|unique:users,email,' . $wakil->user_id,
            'nama_perusahaan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_perusahaan' => 'required|string',
            'password' => 'nullable|string|min:8',
            'password_confirmation' => 'nullable',
        ];

        // Hanya validasi password_confirmation jika password diisi
        if ($request->filled('password')) {
            $rules['password_confirmation'] = 'required|same:password';
        }

        $request->validate($rules);

        // Update User record
        $user = User::findOrFail($wakil->user_id);
        $user->update([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => $request->filled('password') ? bcrypt($request->password) : $user->password,
        ]);

        $wakil->update([
            'nama' => $request->nama,
            'email' => $request->email,
            'nama_perusahaan' => $request->nama_perusahaan,
            'alamat' => $request->alamat,
            'no_perusahaan' => $request->no_perusahaan,
            'status' => $wakil->status ?? 'Accepted',
            'bukti_lampiran' => $wakil->bukti_lampiran ?? '-',
            'password' => $request->filled('password') ? bcrypt($request->password) : $wakil->password,
        ]);

        return redirect()->route('magang.perusahaan.index')->with('success', 'Data wakil perusahaan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $wakil = WakilPerusahaan::findOrFail($id);

        // Hapus user jika ada
        User::where('email', $wakil->email)->delete();

        $wakil->delete();

        return redirect()->route('magang.perusahaan.index')->with('success', 'Data berhasil dihapus.');
    }
}
