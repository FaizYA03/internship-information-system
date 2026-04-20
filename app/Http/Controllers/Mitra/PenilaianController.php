<?php

namespace App\Http\Controllers\Mitra;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Penilaian;
use Illuminate\Support\Facades\Auth;
use App\Models\WakilPerusahaan;
use App\Models\MagangSiswa;

class PenilaianController extends Controller
{
    // INDEX - Tampilkan daftar penilaian
    public function index()
    {
        $user = auth()->user();

        // Ambil wakil perusahaan berdasarkan email
        $wakil = WakilPerusahaan::where('email', $user->email)->first();
        if (!$wakil) abort(403, 'Wakil perusahaan tidak ditemukan.');

        // Ambil semua penilaian yang dilakukan oleh mitra ini
        $penilaians = Penilaian::whereIn('siswa_id', function ($query) use ($wakil) {
            $query->select('user_id')
                ->from('magang_siswa')
                ->where('perusahaan_id', $wakil->id);
        })->with(['siswa']) ->latest()->get();

        return view('magang.wakil_perusahaan.penilaian.index', compact('penilaians'));
    }

    // CREATE - Form input penilaian
    
    public function create()
    {
        $user = auth()->user();

        $wakil = WakilPerusahaan::where('email', $user->email)->first();
        if (!$wakil) abort(403, 'Wakil perusahaan tidak ditemukan.');

        // Ambil siswa magang aktif dari perusahaan terkait
         // Ambil siswa magang aktif dari perusahaan terkait dan filter null user
        $siswas = MagangSiswa::with('user')
            ->where('perusahaan_id', $wakil->id)
            ->where('status', 'Disetujui Admin')
            ->get()
            ->pluck('user')
            ->filter()  // buang yang null
            ->values(); // reset index // Ambil data user-nya

        return view('magang.wakil_perusahaan.penilaian.create', compact('siswas', 'wakil'));
    }

    // STORE - Simpan data penilaian
    public function store(Request $request)
    {
         $user = auth()->user();
        $wakil = WakilPerusahaan::where('email', $user->email)->first();
        $data = $request->validate([
            'siswa_id' => 'required|exists:users,id',
            'hard_skill_1' => 'required|numeric|min:0|max:100',
            'hard_skill_2' => 'required|numeric|min:0|max:100',
            'hard_skill_3' => 'required|numeric|min:0|max:100',
            'kewirausahaan' => 'required|numeric|min:0|max:100',
            'soft_skill_1' => 'required|numeric|min:0|max:100',
            'soft_skill_2' => 'required|numeric|min:0|max:100',
            'soft_skill_3' => 'required|numeric|min:0|max:100',
            'soft_skill_4' => 'required|numeric|min:0|max:100',
            'soft_skill_5' => 'required|numeric|min:0|max:100',
            'soft_skill_6' => 'required|numeric|min:0|max:100',
        ]);

         $isMagang = MagangSiswa::where('user_id', $data['siswa_id'])
                ->where('perusahaan_id', $wakil->id)
                ->where('status', 'Disetujui Admin')
                ->exists();

    if (!$isMagang) {
        return back()->withErrors(['siswa_id' => 'Siswa tidak magang di perusahaan Anda.'])->withInput();
    }

         // Perhitungan nilai akhir
        $jumlahNilai = $data['hard_skill_1'] + $data['hard_skill_2'] + $data['hard_skill_3']
                    + $data['kewirausahaan']
                    + $data['soft_skill_1'] + $data['soft_skill_2'] + $data['soft_skill_3']
                    + $data['soft_skill_4'] + $data['soft_skill_5'] + $data['soft_skill_6'];

        $totalIndikator = 10;
        $rataRata = $jumlahNilai / $totalIndikator;
        $data['nilai_akhir'] = round($rataRata * 0.7, 2);

        $exists = Penilaian::where('siswa_id', $data['siswa_id'])
            ->where('wakil_perusahaan_id', $wakil->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['siswa_id' => 'Siswa ini sudah dinilai.'])->withInput();
        }

         // Tambahkan wakil_perusahaan_id ke dalam data
       $data['wakil_perusahaan_id'] = $wakil->user_id;

        Penilaian::create($data);

        return redirect()->route('magang.wakil_perusahaan.penilaian.index')->with('success', 'Nilai berhasil disimpan.');
    }

    // SHOW - Detail penilaian
    public function show($id)
    {
        $penilaian = Penilaian::with('siswa')->findOrFail($id);
        return view('magang.wakil_perusahaan.penilaian.show', compact('penilaian'));
    }

    // EDIT - Form edit nilai
    public function edit($id)
    {
        $penilaian = Penilaian::with('siswa')->findOrFail($id);
        return view('magang.wakil_perusahaan.penilaian.edit', compact('penilaian'));
    }

    // UPDATE - Update data penilaian
    public function update(Request $request, $id)
    {
        $penilaian = Penilaian::findOrFail($id);

        $data = $request->validate([
            'siswa_id' => 'required|exists:users,id',
            'hard_skill_1' => 'required|numeric|min:0|max:100',
            'hard_skill_2' => 'required|numeric|min:0|max:100',
            'hard_skill_3' => 'required|numeric|min:0|max:100',
            'kewirausahaan' => 'required|numeric|min:0|max:100',
            'soft_skill_1' => 'required|numeric|min:0|max:100',
            'soft_skill_2' => 'required|numeric|min:0|max:100',
            'soft_skill_3' => 'required|numeric|min:0|max:100',
            'soft_skill_4' => 'required|numeric|min:0|max:100',
            'soft_skill_5' => 'required|numeric|min:0|max:100',
            'soft_skill_6' => 'required|numeric|min:0|max:100',
        ]);

         // Perhitungan nilai akhir
        $jumlahNilai = $data['hard_skill_1'] + $data['hard_skill_2'] + $data['hard_skill_3']
                    + $data['kewirausahaan']
                    + $data['soft_skill_1'] + $data['soft_skill_2'] + $data['soft_skill_3']
                    + $data['soft_skill_4'] + $data['soft_skill_5'] + $data['soft_skill_6'];

        $totalIndikator = 10;
        $rataRata = $jumlahNilai / $totalIndikator;
        $data['nilai_akhir'] = round($rataRata * 0.7, 2);

            $penilaian->update($data);

        return redirect()->route('magang.wakil_perusahaan.penilaian.index')->with('success', 'Nilai berhasil diperbarui.');
    }
}
