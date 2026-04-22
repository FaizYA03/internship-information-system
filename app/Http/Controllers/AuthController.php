<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        $title = 'Login';
        return view('auth.login', compact('title'));
    }

    public function authenticate(Request $request)
    {
        // Validasi input
        $request->validate([
            'nis_nip' => 'required|string',
            'password' => 'required',
        ]);

        // Deteksi apakah input adalah email atau NIS/NIP
        $loginField = filter_var($request->nis_nip, FILTER_VALIDATE_EMAIL) ? 'email' : 'nis_nip';

        // Coba login dengan field yang sesuai
        if (Auth::attempt([$loginField => $request->nis_nip, 'password' => $request->password])) {
            $user = Auth::user();
            // Cek jika role wakil_perusahaan, pastikan status sudah Accepted
            if ($user->role === 'wakil_perusahaan') {
                $wakil = $user->wakilPerusahaan;
                if (!$wakil || $wakil->status !== 'Accepted') {
                    Auth::logout();
                    return redirect()->back()->with('loginError', 'Akun Anda belum disetujui oleh admin.');
                }
            }
            $request->session()->regenerate();
            // Redirect semua role ke default home page
            return redirect()->intended('/');
        }

        // Jika gagal login
        return redirect()->back()->with('loginError', 'Email / NIS / NIP atau password salah');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
