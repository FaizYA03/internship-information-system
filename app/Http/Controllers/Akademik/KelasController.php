<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\User;
use App\Models\Jurusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class KelasController extends Controller
{
    public function index()
    {
        $title = 'Kelola Kelas';
        $header = 'Data Kelas';
        $kelas = Kelas::with(['waliKelas', 'guruBK'])->get();

        return view('sistem_akademik.kelas.index', compact('kelas', 'title', 'header'));
    }

    public function create()
    {
        $title = 'Kelola Kelas';
        $header = 'Tambah Data Kelas';

        // WALI: semua guru yang belum menjadi wali kelas
        $assignedWaliIds = Kelas::whereNotNull('wali_kelas_id')->pluck('wali_kelas_id')->filter()->toArray();
        $availableWali = User::where('role', 'guru')
            ->whereNotIn('id', $assignedWaliIds)
            ->orderBy('nama')
            ->get();

        // GURU BK: ambil guru dengan jumlah penugasan < 2
        $availableGuruBk = User::select('users.id', 'users.nama', DB::raw('COUNT(kelas.id) as kelas_count'))
            ->leftJoin('kelas', 'kelas.guru_bk_id', '=', 'users.id')
            ->where('users.role', 'guru')
            ->groupBy('users.id', 'users.nama')
            ->havingRaw('COUNT(kelas.id) < 2')
            ->orderBy('users.nama')
            ->get();

        // saat create, tidak ada $kelas
        $kelas = null;
        $jurusans = Jurusan::all();

        return view('sistem_akademik.kelas.createOrEdit', compact('kelas', 'title', 'header', 'availableWali', 'availableGuruBk', 'jurusans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'jurusan' => 'required|string|max:255',
            'tahun_ajaran' => 'required|string|max:255',
            'wali_kelas_id' => 'nullable|exists:users,id',
            'guru_bk_id' => 'nullable|exists:users,id',
            'ruangan' => ['nullable', 'string', 'max:255', 'unique:kelas,ruangan'],
        ]);

        // tambahan validasi business rules
        // 1) wali_kelas tidak boleh sudah terpilih pada kelas lain
        if ($request->filled('wali_kelas_id')) {
            $exists = Kelas::where('wali_kelas_id', $request->wali_kelas_id)->exists();
            if ($exists) {
                return back()->withInput()->withErrors(['wali_kelas_id' => 'Guru ini sudah ditunjuk sebagai wali kelas di kelas lain.']);
            }
        }

        // 2) guru_bk boleh max 2 kelas
        if ($request->filled('guru_bk_id')) {
            $count = Kelas::where('guru_bk_id', $request->guru_bk_id)->count();
            if ($count >= 2) {
                return back()->withInput()->withErrors(['guru_bk_id' => 'Guru BK ini sudah ditugaskan ke 2 kelas (maksimal 2).']);
            }
        }

        // 3) wali_kelas dan guru_bk tidak boleh sama
        if ($request->filled('wali_kelas_id') && $request->filled('guru_bk_id') && $request->wali_kelas_id == $request->guru_bk_id) {
            return back()->withInput()->withErrors(['guru_bk_id' => 'Wali kelas dan Guru BK tidak boleh sama orang.']);
        }

        Kelas::create([
            'nama_kelas' => $request->nama_kelas,
            'jurusan' => $request->jurusan,
            'tahun_ajaran' => $request->tahun_ajaran,
            'wali_kelas_id' => $request->wali_kelas_id ?: null,
            'guru_bk_id' => $request->guru_bk_id ?: null,
            'ruangan' => $request->ruangan ?: null,
        ]);

        return redirect()->route('sistem_akademik.kelas.index')
            ->with('status', 'success')
            ->with('title', 'Berhasil')
            ->with('message', 'Data kelas berhasil ditambahkan');
    }

    public function edit(Kelas $kela)
    {
        $title = 'Kelola Kelas';
        $header = 'Edit Data Kelas';
        $kelas = $kela;

        // Available wali: semua guru yang belum menjadi wali OR guru yg merupakan wali pada kelas ini
        $assignedWaliIds = Kelas::whereNotNull('wali_kelas_id')->where('id', '!=', $kela->id)->pluck('wali_kelas_id')->filter()->toArray();
        $availableWali = User::where('role', 'guru')
            ->whereNotIn('id', $assignedWaliIds)
            ->orderBy('nama')
            ->get();

        // Available guru_bk: guru dengan <2 kelas OR guru yang saat ini guru_bk untuk kelas ini
        // gunakan left join untuk hitung
        $guruCounts = User::select('users.id', 'users.nama', DB::raw('COUNT(kelas.id) as kelas_count'))
            ->leftJoin('kelas', 'kelas.guru_bk_id', '=', 'users.id')
            ->where('users.role', 'guru')
            ->groupBy('users.id', 'users.nama');

        // collect results
        $guruList = $guruCounts->get();

        $availableGuruBk = $guruList->filter(function ($g) use ($kela) {
            // jika guru ini adalah guru_bk dari kelas yang sedang diedit -> always include
            if ($kela->guru_bk_id && $g->id == $kela->guru_bk_id) return true;
            // else only include if kelas_count < 2
            return (int)$g->kelas_count < 2;
        })->values();

        $jurusans = Jurusan::all();

        return view('sistem_akademik.kelas.createOrEdit', compact('kelas', 'title', 'header', 'availableWali', 'availableGuruBk', 'jurusans'));
    }

    public function update(Request $request, Kelas $kela)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'jurusan' => 'required|string|max:255',
            'tahun_ajaran' => 'required|string|max:255',
            'wali_kelas_id' => 'nullable|exists:users,id',
            'guru_bk_id' => 'nullable|exists:users,id',
            'ruangan' => ['nullable', 'string', 'max:255', Rule::unique('kelas', 'ruangan')->ignore($kela->id)],
        ]);

        // 1) wali_kelas tidak boleh sudah terpilih pada kelas lain
        if ($request->filled('wali_kelas_id')) {
            $exists = Kelas::where('wali_kelas_id', $request->wali_kelas_id)
                ->where('id', '!=', $kela->id)
                ->exists();
            if ($exists) {
                return back()->withInput()->withErrors(['wali_kelas_id' => 'Guru ini sudah ditunjuk sebagai wali kelas di kelas lain.']);
            }
        }

        // 2) guru_bk boleh max 2 kelas (exc current)
        if ($request->filled('guru_bk_id')) {
            $count = Kelas::where('guru_bk_id', $request->guru_bk_id)
                ->where('id', '!=', $kela->id)
                ->count();
            if ($count >= 2) {
                return back()->withInput()->withErrors(['guru_bk_id' => 'Guru BK ini sudah ditugaskan ke 2 kelas (maksimal 2).']);
            }
        }

        // 3) wali_kelas dan guru_bk tidak boleh sama
        if ($request->filled('wali_kelas_id') && $request->filled('guru_bk_id') && $request->wali_kelas_id == $request->guru_bk_id) {
            return back()->withInput()->withErrors(['guru_bk_id' => 'Wali kelas dan Guru BK tidak boleh sama orang.']);
        }

        // 4) ruangan duplicate already handled by unique rule above - but double-check (defensive)
        if ($request->filled('ruangan')) {
            $existsRoom = Kelas::where('ruangan', $request->ruangan)
                ->where('id', '!=', $kela->id)
                ->exists();
            if ($existsRoom) {
                return back()->withInput()->withErrors(['ruangan' => 'Nama ruangan sudah digunakan oleh kelas lain.']);
            }
        }

        $kela->update([
            'nama_kelas' => $request->nama_kelas,
            'jurusan' => $request->jurusan,
            'tahun_ajaran' => $request->tahun_ajaran,
            'wali_kelas_id' => $request->wali_kelas_id ?: null,
            'guru_bk_id' => $request->guru_bk_id ?: null,
            'ruangan' => $request->ruangan ?: null,
        ]);

        return redirect()->route('sistem_akademik.kelas.index')
            ->with('status', 'success')
            ->with('title', 'Berhasil')
            ->with('message', 'Data kelas berhasil diperbarui');
    }

    public function destroy(Kelas $kela)
    {
        $kela->delete();

        return redirect()->route('sistem_akademik.kelas.index')
            ->with('status', 'success')
            ->with('title', 'Berhasil')
            ->with('message', 'Data kelas berhasil dihapus');
    }
}