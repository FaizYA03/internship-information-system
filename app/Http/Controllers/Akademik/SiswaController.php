<?php

namespace App\Http\Controllers\Akademik;

use App\Models\Kelas;
use App\Models\User;
use App\Models\Siswa;
use App\Models\AdminProfile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class SiswaController extends Controller
{

    public function index()
    {
        $title  = 'Siswa';
        $header = 'Kelola Data Siswa';

        // Ambil semua siswa, beserta relasi 'user' dan 'dataKelas'
        $students = Siswa::with(['user', 'dataKelas'])->get();

        return view('sistem_akademik.index', compact('students', 'title', 'header'));
    }

    public function create()
    {
        $title = 'Siswa';
        $header = 'Tambah Data Siswa';
        $kelas = Kelas::get();
        return view('sistem_akademik.createOrEdit', compact('title', 'header', 'kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'nis' => 'required|string|unique:siswa',
            'kelas_id' => 'required',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required',
            'no_hp' => 'required',
        ]);

        // Get data kelas
        $kelas = Kelas::find($request->kelas_id);

        // Jiika user tidak ditemukan
        if (!$kelas) {
            return redirect()->back()->with('status', 'error')->with('title', 'Gagal')->with('message', 'Data user tidak ditemukan !');
        }

        // Create user in 'users' table
        $user = User::create([
            'nis_nip' => $request->nis,
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'siswa',
        ]);

        // Create student record in 'siswa' table
        Siswa::create([
            'user_id' => $user->id,
            'nis' => $request->nis,
            'kelas_id' => $kelas->id,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
        ]);

        return redirect()->route('sistem_akademik.siswa.index')->with('status', 'success  ')->with('title', 'Berhasil')->with('message', 'Data berhasil ditambah');
    }

    public function edit(Siswa $siswa)
    {
        $title = 'Siswa';
        $header = 'Edit Data Siswa';
        $kelas = Kelas::get();
        return view('sistem_akademik.createOrEdit', compact('siswa', 'title', 'header', 'kelas'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nis' => 'required|string|unique:siswa,nis,' . $siswa->id,
            'kelas_id' => 'required|exists:kelas,id',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required',
            'no_hp' => 'required',
            'password' => 'nullable',
            'email' => 'required|email|unique:users,email,' . $siswa->user_id,
        ]);

        $kelas = Kelas::find($request->kelas_id);

        if (!$kelas) {
            return redirect()->back()->with('status', 'error')->with('title', 'Gagal')->with('message', 'Data kelas tidak ditemukan !');
        }

        $siswa->user->update([
            'nama'     => $request->nama,
            'email'    => $request->email,
            'password' => $request->filled('password') ? Hash::make($request->password) : $siswa->user->password,
        ]);

        $siswa->update([
            'nis' => $request->nis,
            'kelas_id' => $kelas->id,
            'kelas' => $kelas->nama_kelas,
            'jurusan' => $kelas->jurusan,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
        ]);

        return redirect()->route('sistem_akademik.siswa.index')->with('status', 'success')->with('title', 'Berhasil')->with('message', 'Data berhasil diupdate');
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->user()->delete();
        return redirect()->route('sistem_akademik.siswa.index')->with('status', 'success')->with('title', 'Berhasil')->with('message', 'Data berhasil dihapus');
    }

    public function profile()
    {
        $title = 'Profile';
        /** @var User $user */
        $user = auth()->user();
        $user->load('siswa', 'guru');
        return view('sistem_akademik.profile', compact('user', 'title'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $role = $user->role;

        // Aturan validasi
        $rules = [
            'tanggal_lahir' => 'required|date',
            'alamat'        => 'required|string',
            'no_hp'         => 'required|string',
        ];
        if (in_array($role, ['super_admin', 'admin_sa'])) {
            $rules['jurusan'] = 'required|string|max:255';
        }
        $data = $request->validate($rules);

        // Simpan berdasarkan role
        if ($role === 'siswa') {
            $user->siswa->update($data);
        } elseif ($role === 'guru') {
            $user->guru->update($data);
        } elseif (in_array($role, ['super_admin', 'admin_sa'])) {
            AdminProfile::updateOrCreate(
                ['user_id' => $user->id],
                $data
            );
        }else {
            return redirect()->route('sistem_akademik.dashboard')
                ->with('status', 'warning')
                ->with('message', 'Tidak dapat mengubah profil ini.');
        }

        return redirect()->route('sistem_akademik.profile')->with('status', 'success')->with('title', 'Berhasil')->with('message', 'Data berhasil diupdate');
    }
}
