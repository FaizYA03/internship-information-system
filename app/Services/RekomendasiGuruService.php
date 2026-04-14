<?php

namespace App\Services;

use App\Models\Guru;

class RekomendasiGuruService
{
    public function getRekomendasi($siswa)
    {
        return Guru::where('jurusan_id', $siswa->jurusan_id)
            ->where('status', 'aktif')
            ->withCount('pembimbing')
            ->orderBy('pembimbing_count', 'asc')
            ->first();
    }
}