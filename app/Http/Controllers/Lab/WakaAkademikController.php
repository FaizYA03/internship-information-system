<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lab\ActivityLog;
use App\Models\Lab\PinjamAsat;
use App\Models\Lab\Pengadaan;

class WakaAkademikController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:waka_akademik,super_admin']);
    }

    public function index()
    {
        $logs = ActivityLog::with('user')->latest()->take(10)->get();
        return view('lab.waka_akademik.dashboard', compact('logs'));
    }
    
    public function monitoring()
    {
        // Simple monitoring view
        $activities = ActivityLog::with(['user', 'subject'])->latest()->paginate(20);
        return view('lab.waka_akademik.monitoring', compact('activities'));
    }
}
