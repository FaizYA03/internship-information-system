<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Penilaian;
use App\Models\MagangSiswa;
use Illuminate\Support\Facades\Auth;

class NilaiController extends Controller
{
    /**
     * Tampilkan nilai siswa
     */
    public function index()
    {
        $user = Auth::user();
        
        // Ambil data magang siswa yang status Disetujui Admin
        $magangSiswa = MagangSiswa::where('user_id', $user->id)
            ->where('status', 'Disetujui Admin')
            ->first();

        if (!$magangSiswa) {
            return redirect()->route('magang.magang.index')
                ->with('status', 'error')
                ->with('title', 'Akses Ditolak')
                ->with('message', 'Anda belum memiliki data magang yang disetujui.');
        }

        // Ambil data penilaian siswa
        $penilaian = Penilaian::where('siswa_id', $user->id)->first();

        // Persiapan data untuk view
        $nilaiData = [
            'magangSiswa' => $magangSiswa,
            'penilaian' => $penilaian,
            'nilaiPKL' => null,
            'statusNilaiPKL' => 'Menunggu penilaian mitra',
            'nilaiLaporan' => null,
            'statusNilaiLaporan' => 'Belum dinilai pembimbing',
            'nilaiAkhir' => null,
            'statusNilaiAkhir' => 'Menunggu penyelesaian penilaian',
        ];

        if ($penilaian) {
            // Check apakah sudah ada penilaian dari mitra
            if ($penilaian->hard_skill_1 || $penilaian->hard_skill_2 || $penilaian->hard_skill_3 || 
                $penilaian->kewirausahaan || $penilaian->soft_skill_1) {
                
                $nilaiData['nilaiPKL'] = $penilaian->getAverage();
                $nilaiData['statusNilaiPKL'] = 'Sudah dinilai mitra';

                // Check nilai laporan
                if ($penilaian->nilai_laporan !== null) {
                    $nilaiData['nilaiLaporan'] = $penilaian->nilai_laporan;
                    $nilaiData['statusNilaiLaporan'] = 'Sudah dinilai pembimbing';
                }

                // Check nilai akhir
                if ($penilaian->nilai_akhir !== null) {
                    $nilaiData['nilaiAkhir'] = $penilaian->nilai_akhir;
                    $nilaiData['statusNilaiAkhir'] = 'Sudah tersedia';
                }
            }
        }

        $title = 'Nilai Magang';
        $header = 'Daftar Nilai Siswa Magang';

        return view('magang.siswa.nilaimagang.index', compact(
            'title',
            'header',
            'nilaiData'
        ));
    }

    /**
     * Tampilkan breakdown detail penilaian PKL
     */
    public function breakdown()
    {
        $user = Auth::user();
        
        // Ambil data magang siswa yang status Disetujui Admin
        $magangSiswa = MagangSiswa::where('user_id', $user->id)
            ->where('status', 'Disetujui Admin')
            ->first();

        if (!$magangSiswa) {
    $nilaiData = [
        'magangSiswa' => null,
        'penilaian' => null,
        'nilaiPKL' => null,
        'statusNilaiPKL' => 'Belum memiliki magang yang disetujui',
        'nilaiLaporan' => null,
        'statusNilaiLaporan' => '-',
        'nilaiAkhir' => null,
        'statusNilaiAkhir' => '-',
    ];

    return view('magang.siswa.nilaimagang.index', [
        'title' => 'Nilai Magang',
        'header' => 'Daftar Nilai Siswa Magang',
        'nilaiData' => $nilaiData
    ]);
}

        // Ambil data penilaian siswa
        $penilaian = Penilaian::where('siswa_id', $user->id)->first();

        // Persiapan data untuk view
        $nilaiData = [
            'magangSiswa' => $magangSiswa,
            'penilaian' => $penilaian,
            'nilaiPKL' => null,
        ];

        if ($penilaian) {
            if ($penilaian->hard_skill_1 || $penilaian->hard_skill_2 || $penilaian->hard_skill_3 || 
                $penilaian->kewirausahaan || $penilaian->soft_skill_1) {
                $nilaiData['nilaiPKL'] = $penilaian->getAverage();
            }
        }

        $title = 'Breakdown Penilaian PKL';
        $header = 'Detail Komponen Penilaian';

        return view('magang.siswa.nilaimagang.breakdown', compact(
            'title',
            'header',
            'nilaiData'
        ));
    }

    /**
     * Download nilai (opsional)
     */
    public function download()
    {
        // TODO: Implementasi download PDF
        return redirect()->back()->with('status', 'info')->with('message', 'Fitur download sedang dikembangkan.');
    }
}
