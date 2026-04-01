<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Labor;
use App\Models\Laboratorium;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the student dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Dashboard Siswa';
        $header = 'Dashboard';

        // Get laboratory information for dashboard
        $laborCount = Labor::count();

        // Get today's schedule
        $today = Carbon::now();
        $jadwalToday = Laboratorium::whereDate('start', $today)
            ->orderBy('start', 'asc')
            ->take(3)
            ->get();

        // Get laboratories that are currently active
        $activeLabs = Labor::whereHas('jadwal', function ($query) {
            $query->whereDate('start', now())
                ->where('start', '<=', now())
                ->where('end', '>=', now());
        })->count();

        return view('siswa.main.index', compact('title', 'header', 'laborCount', 'jadwalToday', 'activeLabs'));
    }
}