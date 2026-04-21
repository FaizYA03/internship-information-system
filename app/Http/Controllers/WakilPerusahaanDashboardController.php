<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MagangSiswa;
use App\Models\WakilPerusahaan;
use App\Models\MagangOpening;

class WakilPerusahaanDashboardController extends Controller
{
    public function index()
    {
        $title = 'Dashboard Mitra Magang';
        $header = 'Dashboard Mitra Magang';
        
        // Get the authenticated user
        $user = Auth::user();
        
        // Get their company data from wakil_perusahaan table
        $wakilPerusahaan = WakilPerusahaan::where('email', $user->email)->first();
        $jumlahPerusahaan = WakilPerusahaan::where('status', 'Accepted')->count();
        if (!$wakilPerusahaan) {
            // Fallback if data not found
            return redirect()->route('login')
                ->with('status', 'error')
                ->with('title', 'Error')
                ->with('message', 'Data perusahaan tidak ditemukan.');
        }
        
        // Get stats for the dashboard
        $totalInterns = MagangSiswa::where('perusahaan_id', $wakilPerusahaan->id)->count();
        $activeInterns = MagangSiswa::where('perusahaan_id', $wakilPerusahaan->id)
                            ->where('status', 'Disetujui')->count();
        $totalPrograms = MagangOpening::where('wakil_perusahaan_id', $wakilPerusahaan->id)->count();
        
        // Get recent interns for display
        $recentInterns = MagangSiswa::where('perusahaan_id', $wakilPerusahaan->id)
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();
        
        return view('magang.wakil_perusahaan.dashboard', compact(
            'title', 
            'header', 
            'wakilPerusahaan', 
            'totalInterns', 
            'activeInterns',
            'totalPrograms',
            'recentInterns'
        ));
    }
}