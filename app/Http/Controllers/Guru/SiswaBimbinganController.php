<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Guru;
use App\Models\Pembimbing;
use Illuminate\Support\Facades\Auth;

class SiswaBimbinganController extends Controller
{
    public function index()
{
    $guru = Guru::where('user_id', Auth::id())->first();

    if (!$guru) {
        return view('magang.guru.siswa.index', [
            'data' => collect()
        ]);
    }

    $data = Pembimbing::with([
            'siswa.user',
            'siswa.magangSiswa.wakilPerusahaan',
            'siswa.magangSiswa.opening'
        ])
        ->where('guru_id', $guru->id)
        ->get();

    return view('magang.guru.siswa.index', compact('data'));
}
}