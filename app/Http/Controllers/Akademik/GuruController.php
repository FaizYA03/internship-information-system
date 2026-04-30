<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\User;
use App\Models\Jurusan;
use App\Imports\GuruImport;
use App\Exports\GuruExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        $query = Guru::with(['user', 'jurusan']);

        // Filter by keyword
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nip', 'like', "%$search%")
                  ->orWhereHas('user', fn($q2) => $q2->where('nama', 'like', "%$search%"));
            });
        }

        // Filter by jurusan
        if ($request->filled('jurusan_id')) {
            $query->where('jurusan_id', $request->jurusan_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by jenis kelamin
        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        $gurus = $query->join('users', 'users.id', '=', 'guru.user_id')
                       ->orderBy('users.nama')
                       ->select('guru.*')
                       ->get();

        $jurusanList = Jurusan::orderBy('nama_jurusan')->get();

        return view('sistem_akademik.guru.index', compact('gurus', 'jurusanList'));
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
            'tanggal_lahir' => 'nullable|date',
            'alamat'        => 'nullable',
            'no_hp'         => 'nullable',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'agama'         => 'nullable|string|max:50',
            'foto'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = User::create([
            'nis_nip'  => $request->nip,
            'nama'     => $request->nama,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'guru',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('foto_guru', 'public');
        }

        Guru::create([
            'user_id'       => $user->id,
            'nip'           => $request->nip,
            'jurusan_id'    => $request->jurusan_id,
            'status'        => $request->status,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat'        => $request->alamat,
            'no_hp'         => $request->no_hp,
            'jenis_kelamin' => $request->jenis_kelamin,
            'agama'         => $request->agama,
            'foto'          => $fotoPath,
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
            'tanggal_lahir' => 'nullable|date',
            'alamat'        => 'nullable',
            'no_hp'         => 'nullable',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'agama'         => 'nullable|string|max:50',
            'foto'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $userData = [
            'nama'    => $request->nama,
            'email'   => $request->email,
            'nis_nip' => $request->nip,
        ];
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }
        $guru->user->update($userData);

        // Handle foto
        $fotoPath = $guru->foto;
        if ($request->hasFile('foto')) {
            if ($fotoPath && Storage::disk('public')->exists($fotoPath)) {
                Storage::disk('public')->delete($fotoPath);
            }
            $fotoPath = $request->file('foto')->store('foto_guru', 'public');
        }

        $guru->update([
            'nip'           => $request->nip,
            'jurusan_id'    => $request->jurusan_id,
            'status'        => $request->status,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat'        => $request->alamat,
            'no_hp'         => $request->no_hp,
            'jenis_kelamin' => $request->jenis_kelamin,
            'agama'         => $request->agama,
            'foto'          => $fotoPath,
        ]);

        return redirect()->route('sistem_akademik.guru.index')
            ->with('status', 'success')
            ->with('message', 'Data guru berhasil diubah');
    }

    public function destroy(Guru $guru)
    {
        if ($guru->foto && Storage::disk('public')->exists($guru->foto)) {
            Storage::disk('public')->delete($guru->foto);
        }
        $guru->user()->delete();
        return redirect()->route('sistem_akademik.guru.index')
            ->with('status', 'success')
            ->with('message', 'Data guru berhasil dihapus');
    }

    public function profile()
    {
        $title = 'Profile Guru';
        $guru = auth()->user()->guru;
        return view('sistem_akademik.guru.profile', compact('guru', 'title'));
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return redirect()->route('sistem_akademik.guru.index')
                ->with('status', 'error')
                ->with('message', 'Tidak ada guru yang dipilih.');
        }

        $gurus = Guru::whereIn('id', $ids)->get();
        foreach ($gurus as $guru) {
            if ($guru->foto && Storage::disk('public')->exists($guru->foto)) {
                Storage::disk('public')->delete($guru->foto);
            }
            $guru->user()->delete();
        }

        $count = count($ids);
        return redirect()->route('sistem_akademik.guru.index')
            ->with('status', 'success')
            ->with('title', 'Berhasil')
            ->with('message', "$count data guru berhasil dihapus.");
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

    public function importGuru(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ], [
            'file.required' => 'File Excel wajib dipilih.',
            'file.mimes'    => 'File harus berformat XLSX, XLS, atau CSV.',
            'file.max'      => 'Ukuran file maksimal 5MB.',
        ]);

        $import = new GuruImport();
        Excel::import($import, $request->file('file'));

        $count   = count($import->importedCount);
        $skipped = count($import->skippedRows);

        $msg = "Berhasil mengimpor $count guru.";
        if ($skipped > 0) {
            $msg .= " $skipped baris dilewati (NIP duplikat / data tidak lengkap).";
        }

        return redirect()->route('sistem_akademik.guru.index')
            ->with('status', 'success')
            ->with('title', 'Import Berhasil')
            ->with('message', $msg);
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_import_guru.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, ['nama_guru', 'nip', 'lp', 'agama', 'jurusan', 'no_hp', 'status']);
            fputcsv($file, ['Ahmad Fauzi, S.Pd', '199001012020011001', 'L', 'Islam', 'Teknik Komputer Jaringan', '081234567890', 'Aktif']);
            fputcsv($file, ['Siti Rahayu, S.Pd', '198505102019012002', 'P', 'Islam', 'Teknik Instalasi Tenaga Listrik', '089876543210', 'Aktif']);
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function exportGuru(Request $request)
    {
        $filters = $request->only(['search', 'jurusan_id', 'status', 'jenis_kelamin']);
        $filename = 'data_guru_' . date('Ymd_His') . '.xlsx';
        return Excel::download(new GuruExport($filters), $filename);
    }
}
