<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WakilPerusahaan;

class LandingController extends Controller
{
    public function index()
    {
        // 🔥 Ambil hanya yang aktif (kalau pakai status)
        $perusahaan = WakilPerusahaan::query()
            ->whereNotNull('nama_perusahaan') // pastikan ada isi
            ->orderBy('id', 'desc')
            ->get();

        // DEBUG (hapus kalau sudah muncul)
        // dd($perusahaan);

        return view('magang.landing', compact('perusahaan'));
    }
}