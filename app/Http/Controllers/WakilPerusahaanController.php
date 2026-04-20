<?php

namespace App\Http\Controllers;

use App\Models\WakilPerusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class WakilPerusahaanController extends Controller
{
    // WakilPerusahaanController.php
    public function profile()
    {
        $wakil = auth()->user()->wakilPerusahaan;

        return view('magang.wakil_perusahaan.profile', compact('wakil'));
    }

    // WakilPerusahaanController.php

public function editProfile()
{
    $wakil = auth()->user()->wakilPerusahaan;

    return view('magang.wakil_perusahaan.edit', compact('wakil'));
}

public function updateProfile(Request $request)
{
    $request->validate([
        'nama' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'no_perusahaan' => 'nullable|string',
        'alamat' => 'nullable|string',
        'nama_perusahaan' => 'required|string|max:255',
    ]);

    $wakil = auth()->user()->wakilPerusahaan;

    $wakil->update($request->only([
        'nama',
        'email',
        'no_perusahaan',
        'alamat',
        'nama_perusahaan',
    ]));

    return redirect()->route('magang.wakil_perusahaan.profile')->with('success', 'Profil berhasil diperbarui!');
}


    public function showRegistrationForm()
    {
        return view('magang.wakil_perusahaan.register');
    }
    
   public function register(Request $request)
{
    $request->validate([
        'nama' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:wakil_perusahaan|unique:users,email',
        'nama_perusahaan' => 'required|string|max:255',
        'alamat' => 'required|string',
        'no_perusahaan' => 'required|string',
        'bukti_lampiran' => 'required|file|mimes:pdf|max:2048',
        'password' => 'required|string|min:8|confirmed',
    ]);

    // Store file
    $file = $request->file('bukti_lampiran');
    $filename = time() . '_' . $file->getClientOriginalName();
    $filePath = $file->storeAs('bukti_lampiran', $filename, 'public');

    // Simpan ke tabel users dulu
    $user = User::create([
        'nama' => $request->nama,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'role' => 'wakil_perusahaan',
    ]);

    // Simpan ke wakil_perusahaan
    WakilPerusahaan::create([
        'user_id' => $user->id,
        'nama' => $request->nama,
        'email' => $request->email,
        'nama_perusahaan' => $request->nama_perusahaan,
        'alamat' => $request->alamat,
        'no_perusahaan' => $request->no_perusahaan,
        'bukti_lampiran' => $filePath,
        'password' => bcrypt($request->password), // Penting: simpan password terenkripsi juga di sini
        'status' => 'Pending',
    ]);

    return redirect()->route('magang.wakil_perusahaan.success');
}

    
    public function showSuccessPage()
    {
        return view('magang.wakil_perusahaan.success');
    }

    public function approve($id)
{
    $wakil = WakilPerusahaan::findOrFail($id);

    if (!$wakil) {
        return redirect()->back()->with('error', 'Data perusahaan tidak ditemukan.');
    }

    $wakil->status = 'Accepted';
    $wakil->save();

    // Cek jika belum ada di tabel users
    $existingUser = User::where('email', $wakil->email)->first();

    if (!$existingUser) {
        User::create([
            'nama' => $wakil->nama,
            'email' => $wakil->email,
            'password' => $wakil->password, // Sudah di-bcrypt dari model
            'role' => 'wakil_perusahaan',
        ]);
    }

    return redirect()->back()->with('success', 'Wakil perusahaan berhasil disetujui dan ditambahkan sebagai user.');
}
}