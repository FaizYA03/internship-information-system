<?php

namespace App\Http\Controllers\Akademik;

use App\Models\Kelas;
use App\Models\User;
use App\Models\Siswa;
use App\Models\AdminProfile;
use App\Imports\SiswaImport;
use App\Exports\SiswaExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;

class SiswaController extends Controller
{

    public function index(Request $request)
    {
        $title  = 'Siswa';
        $header = 'Kelola Data Siswa';

        $query = Siswa::with(['user', 'dataKelas']);

        // Filter by keyword (nama or NIS)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nis', 'like', "%$search%")
                  ->orWhereHas('user', function($q2) use ($search) {
                      $q2->where('nama', 'like', "%$search%");
                  });
            });
        }

        // Filter by kelas
        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }

        // Filter by jurusan
        if ($request->filled('jurusan')) {
            $query->where('jurusan', $request->jurusan);
        }

        $students = $query->join('users', 'users.id', '=', 'siswa.user_id')
                          ->orderBy('users.nama')
                          ->select('siswa.*')
                          ->get();

        // For dropdown options
        $kelasList   = Siswa::distinct()->whereNotNull('kelas')->orderBy('kelas')->pluck('kelas');
        $jurusanList = Siswa::distinct()->whereNotNull('jurusan')->orderBy('jurusan')->pluck('jurusan');

        return view('sistem_akademik.index', compact('students', 'title', 'header', 'kelasList', 'jurusanList'));
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
            'nama'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users',
            'password'      => 'required',
            'nis'           => 'required|string|unique:siswa',
            'kelas_id'      => 'required',
            'tanggal_lahir' => 'nullable|date',
            'alamat'        => 'nullable',
            'no_hp'         => 'nullable',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'agama'         => 'nullable|string|max:50',
            'foto'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $kelas = Kelas::find($request->kelas_id);
        if (!$kelas) {
            return redirect()->back()->with('status', 'error')->with('title', 'Gagal')->with('message', 'Data kelas tidak ditemukan !');
        }

        $user = User::create([
            'nis_nip'  => $request->nis,
            'nama'     => $request->nama,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'siswa',
        ]);

        // Handle foto upload
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('foto_siswa', 'public');
        }

        Siswa::create([
            'user_id'       => $user->id,
            'nis'           => $request->nis,
            'kelas_id'      => $kelas->id,
            'kelas'         => $kelas->nama_kelas,
            'jurusan'       => $kelas->jurusan,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat'        => $request->alamat,
            'no_hp'         => $request->no_hp,
            'jenis_kelamin' => $request->jenis_kelamin,
            'agama'         => $request->agama,
            'foto'          => $fotoPath,
        ]);

        return redirect()->route('sistem_akademik.siswa.index')->with('status', 'success')->with('title', 'Berhasil')->with('message', 'Data berhasil ditambah');
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
            'nama'          => 'required|string|max:255',
            'nis'           => 'required|string|unique:siswa,nis,' . $siswa->id,
            'kelas_id'      => 'required|exists:kelas,id',
            'tanggal_lahir' => 'nullable|date',
            'alamat'        => 'nullable',
            'no_hp'         => 'nullable',
            'password'      => 'nullable',
            'email'         => 'required|email|unique:users,email,' . $siswa->user_id,
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'agama'         => 'nullable|string|max:50',
            'foto'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
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

        // Handle foto upload
        $fotoPath = $siswa->foto;
        if ($request->hasFile('foto')) {
            if ($fotoPath && Storage::disk('public')->exists($fotoPath)) {
                Storage::disk('public')->delete($fotoPath);
            }
            $fotoPath = $request->file('foto')->store('foto_siswa', 'public');
        }

        $siswa->update([
            'nis'           => $request->nis,
            'kelas_id'      => $kelas->id,
            'kelas'         => $kelas->nama_kelas,
            'jurusan'       => $kelas->jurusan,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat'        => $request->alamat,
            'no_hp'         => $request->no_hp,
            'jenis_kelamin' => $request->jenis_kelamin,
            'agama'         => $request->agama,
            'foto'          => $fotoPath,
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

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return redirect()->route('sistem_akademik.siswa.index')
                ->with('status', 'error')
                ->with('message', 'Tidak ada siswa yang dipilih.');
        }

        $siswas = Siswa::whereIn('id', $ids)->get();
        foreach ($siswas as $siswa) {
            if ($siswa->foto && Storage::disk('public')->exists($siswa->foto)) {
                Storage::disk('public')->delete($siswa->foto);
            }
            $siswa->user()->delete();
        }

        return redirect()->route('sistem_akademik.siswa.index')
            ->with('status', 'success')
            ->with('title', 'Berhasil')
            ->with('message', count($ids) . ' data siswa berhasil dihapus.');
    }

    public function importSiswa(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ], [
            'file.required' => 'File Excel wajib dipilih.',
            'file.mimes'    => 'File harus berformat XLSX, XLS, atau CSV.',
            'file.max'      => 'Ukuran file maksimal 5MB.',
        ]);

        $import = new SiswaImport();
        Excel::import($import, $request->file('file'));

        $count   = count($import->importedCount);
        $skipped = count($import->skippedRows);

        $msg = "Berhasil mengimpor $count siswa.";
        if ($skipped > 0) {
            $msg .= " $skipped baris dilewati (NIS duplikat / data tidak lengkap).";
        }

        return redirect()->route('sistem_akademik.siswa.index')
            ->with('status', 'success')
            ->with('title', 'Import Berhasil')
            ->with('message', $msg);
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_import_siswa.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, ['nama_siswa', 'nis', 'id_kelas', 'lp', 'agama', 'kelas', 'jurusan', 'tanggal_lahir', 'alamat', 'no_hp']);
            fputcsv($file, ['Budi Santoso', '12345678', '01', 'L', 'Islam', 'X TKJ 1', 'Teknik Komputer Jaringan', '2008-05-12', 'Jl. Merdeka No. 1', '081234567890']);
            fputcsv($file, ['Sari Dewi', '87654321', '02', 'P', 'Islam', 'X TKJ 2', 'Teknik Komputer Jaringan', '2008-09-20', 'Jl. Sudirman No. 5', '089876543210']);
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function exportSiswa(Request $request)
    {
        $filters = $request->only(['search', 'kelas', 'jurusan']);
        $filename = 'data_siswa_' . date('Ymd_His') . '.xlsx';
        return Excel::download(new SiswaExport($filters), $filename);
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
