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
            ->with(['wakilPerusahaan'])
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
            // Tandai penilaian sebagai telah dibaca jika nilai akhir sudah tersedia
            if ($penilaian->nilai_akhir !== null && $penilaian->is_read_by_siswa == false) {
                $penilaian->update(['is_read_by_siswa' => true]);
            }
            
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
            ->with(['wakilPerusahaan'])
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

    public function download()
    {
        $user = Auth::user();
        
        $magangSiswa = MagangSiswa::where('user_id', $user->id)
            ->where('status', 'Disetujui Admin')
            ->with(['perusahaan', 'wakilPerusahaan', 'pembimbing.guru.user'])
            ->first();

        if (!$magangSiswa) {
            return redirect()->route('magang.magang.index')
                ->with('status', 'error')
                ->with('message', 'Anda belum memiliki data magang yang disetujui.');
        }

        $penilaian = Penilaian::where('siswa_id', $user->id)->first();

        if (!$penilaian || $penilaian->nilai_akhir === null) {
            return redirect()->back()
                ->with('status', 'error')
                ->with('message', 'Nilai akhir Anda belum tersedia untuk diunduh.');
        }

        $nilaiPKL = $penilaian->getAverage();
        $nilaiLaporan = $penilaian->nilai_laporan;
        $nilaiAkhir = $penilaian->nilai_akhir;

        $keterangan = match (true) {
            $nilaiAkhir >= 91 => 'Sangat Baik',
            $nilaiAkhir >= 81 => 'Baik',
            $nilaiAkhir >= 71 => 'Cukup',
            default => 'Kurang'
        };

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('magang.siswa.nilaimagang.pdf', compact(
            'magangSiswa',
            'nilaiPKL',
            'nilaiLaporan',
            'nilaiAkhir',
            'keterangan'
        ));

        return $pdf->download('Nilai_Akhir_Magang_' . str_replace(' ', '_', $user->name) . '.pdf');
    }
}
