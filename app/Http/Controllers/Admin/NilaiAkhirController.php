<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penilaian;
use App\Models\Guru;
use App\Models\Pembimbing;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class NilaiAkhirController extends Controller
{
    // ================= EXPORT PDF =================
   public function exportPdf()
{
    $penilaians = Penilaian::with(['siswa', 'wakilPerusahaan'])
        ->whereNotNull('nilai_akhir')
        ->get();

    $pdf = Pdf::loadView('magang.wakil_perusahaan.nilaiakhir.pdf', compact('penilaians'));

    return $pdf->stream('rekap-nilai-akhir.pdf');
}

    // ================= INDEX =================
    public function index()
{
    $user = Auth::user();

    if ($user->role == 'guru') {

        $guru = Guru::where('user_id', $user->id)->first();

        if (!$guru) {
            $penilaians = collect();
        } else {

            // 1️⃣ ambil siswa dari pembimbing (siswas.id)
            $siswaIds = Pembimbing::where('guru_id', $guru->id)
                ->pluck('siswa_id');

            // 2️⃣ mapping ke user_id
            $userIds = Siswa::whereIn('id', $siswaIds)
                ->pluck('user_id');

            // 3️⃣ ambil penilaian
            $penilaians = Penilaian::with(['siswa', 'wakilPerusahaan'])
                ->whereIn('siswa_id', $userIds)
                ->whereNotNull('nilai_akhir') // hanya yang sudah dihitung
                ->get();
        }

    } else {

        $penilaians = Penilaian::with(['siswa', 'wakilPerusahaan'])
            ->whereNotNull('nilai_akhir')
            ->get();
    }

    return view('magang.wakil_perusahaan.nilaiakhir.index', compact('penilaians'));
}
    // ================= CREATE =================
   public function create()
{
    $user = Auth::user();

    if ($user->role == 'guru') {

        $guru = Guru::where('user_id', $user->id)->first();

        if (!$guru) {
            $penilaians = collect();
        } else {

            // 1️⃣ ambil siswa (siswas.id)
            $siswaIds = Pembimbing::where('guru_id', $guru->id)
                ->pluck('siswa_id');

            // 2️⃣ mapping ke user_id
            $userIds = Siswa::whereIn('id', $siswaIds)
                ->pluck('user_id');

            // 3️⃣ ambil penilaian berdasarkan user_id
            $penilaians = Penilaian::with(['siswa', 'wakilPerusahaan'])
                ->whereIn('siswa_id', $userIds)
                ->whereNotNull('hard_skill_1') // sudah dinilai mitra
                ->get();
        }

    } else {

        $penilaians = Penilaian::with(['siswa', 'wakilPerusahaan'])
            ->whereNotNull('hard_skill_1')
            ->get();
    }

    return view('magang.wakil_perusahaan.nilaiakhir.create', compact('penilaians'));
}
    // ================= STORE =================
    public function store(Request $request)
    {
        $request->validate([
           'siswa_id' => 'required|exists:users,id', // ✅ FIX: pakai siswa_id
            'nilai_laporan' => 'required|numeric|min:0|max:100',
        ]);

        $penilaian = Penilaian::where('siswa_id', $request->siswa_id)->first();

        if (!$penilaian) {
            return back()->with('error', 'Penilaian siswa belum ditemukan.');
        }

        // 🔥 HITUNG NILAI
        $avgHardSkill = (
            $penilaian->hard_skill_1 +
            $penilaian->hard_skill_2 +
            $penilaian->hard_skill_3
        ) / 3;

        $kewirausahaan = $penilaian->kewirausahaan;

        $avgSoftSkill = (
            $penilaian->soft_skill_1 +
            $penilaian->soft_skill_2 +
            $penilaian->soft_skill_3 +
            $penilaian->soft_skill_4 +
            $penilaian->soft_skill_5 +
            $penilaian->soft_skill_6
        ) / 6;

        $nilaiPKL = round(($avgHardSkill + $kewirausahaan + $avgSoftSkill) / 3, 2);
        $nilaiLaporan = $request->nilai_laporan;
        $nilaiAkhir = round(($nilaiPKL * 0.7) + ($nilaiLaporan * 0.3), 2);

        // 🔥 SIMPAN
        $penilaian->update([
            'nilai_laporan' => $nilaiLaporan,
            'nilai_akhir' => $nilaiAkhir
        ]);

        return redirect()
            ->route('magang.wakil_perusahaan.nilaiakhir.index')
            ->with('success', 'Nilai akhir berhasil disimpan!');
    }

    // ================= SHOW =================
    public function show($id)
    {
        $penilaian = Penilaian::with(['siswa.user', 'wakilPerusahaan'])->findOrFail($id);

        if ($penilaian->nilai_laporan === null) {
            return back()->with('error', 'Nilai laporan belum tersedia.');
        }

        $avgHardSkill = (
            $penilaian->hard_skill_1 +
            $penilaian->hard_skill_2 +
            $penilaian->hard_skill_3
        ) / 3;

        $kewirausahaan = $penilaian->kewirausahaan;

        $avgSoftSkill = (
            $penilaian->soft_skill_1 +
            $penilaian->soft_skill_2 +
            $penilaian->soft_skill_3 +
            $penilaian->soft_skill_4 +
            $penilaian->soft_skill_5 +
            $penilaian->soft_skill_6
        ) / 6;

        $nilaiPKL = round(($avgHardSkill + $kewirausahaan + $avgSoftSkill) / 3, 2);
        $nilaiLaporan = $penilaian->nilai_laporan;
        $nilaiAkhir = round(($nilaiPKL * 0.7) + ($nilaiLaporan * 0.3), 2);

        $keterangan = match (true) {
            $nilaiAkhir >= 91 => 'Sangat Baik',
            $nilaiAkhir >= 81 => 'Baik',
            $nilaiAkhir >= 71 => 'Cukup',
            default => 'Kurang'
        };

        return view('magang.wakil_perusahaan.nilaiakhir.show', compact(
            'penilaian',
            'nilaiAkhir',
            'nilaiPKL',
            'nilaiLaporan',
            'keterangan'
        ));
    }
}