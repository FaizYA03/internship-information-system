<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\User;
use App\Models\Jurusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class GuruController extends Controller
{
    public function index()
    {
        $gurus = Guru::with('user')->get();
        return view('sistem_akademik.guru.index', compact('gurus'));
    }

    public function create()
    {
        $title = 'Guru';
        $header = 'Tambah Data Guru';
        $jurusans = Jurusan::all();
        return view('sistem_akademik.guru.createOrEdit', compact('title', 'header', 'jurusans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users',
            'password'      => 'required|min:6',
            'nip'           => 'required|string|unique:guru|unique:users,nis_nip',
            'jurusan_id'    => 'required|exists:jurusans,id',
            'status'        => 'required|in:Aktif,Nonaktif',
            'tanggal_lahir' => 'required|date',
            'alamat'        => 'required',
            'no_hp'         => 'required',
        ]);

        // Create user with role 'guru'
        $user = User::create([
            'nis_nip'  => $request->nip,
            'nama'     => $request->nama,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'guru',
        ]);

        // Create guru record
        Guru::create([
            'user_id'       => $user->id,
            'nip'           => $request->nip,
            'jurusan_id'    => $request->jurusan_id,
            'status'        => $request->status,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat'        => $request->alamat,
            'no_hp'         => $request->no_hp,
        ]);

        return redirect()->route('sistem_akademik.guru.index')
            ->with('status', 'success')
            ->with('message', 'Guru berhasil ditambahkan');
    }

    public function edit(Guru $guru)
    {
        $guru->load('user');
        $jurusans = Jurusan::all();
        return view('sistem_akademik.guru.createOrEdit', compact('guru', 'jurusans'));
    }

    public function update(Request $request, Guru $guru)
    {
        $request->validate([
            'nama'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $guru->user_id,
            'nip'           => 'required|string|unique:guru,nip,' . $guru->id . '|unique:users,nis_nip,' . $guru->user_id,
            'jurusan_id'    => 'required|exists:jurusans,id',
            'status'        => 'required|in:Aktif,Nonaktif',
            'tanggal_lahir' => 'required|date',
            'alamat'        => 'required',
            'no_hp'         => 'required',
        ]);

        // Update user
        $userData = [
            'nama'  => $request->nama,
            'email' => $request->email,
            'nis_nip' => $request->nip,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $guru->user->update($userData);

        // Update guru
        $guru->update([
            'nip'           => $request->nip,
            'jurusan_id'    => $request->jurusan_id,
            'status'        => $request->status,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat'        => $request->alamat,
            'no_hp'         => $request->no_hp,
        ]);

        return redirect()->route('sistem_akademik.guru.index')
            ->with('status', 'success')
            ->with('message', 'Data guru berhasil diubah');
    }

    public function destroy(Guru $guru)
    {
        $guru->user()->delete();
        return redirect()->route('sistem_akademik.guru.index')
            ->with('status', 'success')
            ->with('message', 'Data guru berhasil dihapus');
    }

    public function profile()
    {
        $title = 'Profile Guru';
        $guru = auth()->user()->guru; // Assuming authentication is implemented
        return view('sistem_akademik.guru.profile', compact('guru', 'title'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $guru = $user->guru;

        $request->validate([
            'tanggal_lahir' => 'required|date',
            'alamat'        => 'required|string',
            'no_hp'         => 'required|string',
        ]);

        $guru->update([
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat'        => $request->alamat,
            'no_hp'         => $request->no_hp,
        ]);

        return redirect()->route('sistem_akademik.profile')
            ->with('status', 'success')
            ->with('message', 'Profile berhasil diperbarui');
    }
}
