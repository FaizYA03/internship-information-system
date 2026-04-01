<?php

namespace App\Http\Controllers;

use App\Models\Laboratorium;
use App\Models\PinjamLabor;
use Illuminate\Http\Request;

class LaboratoriumController extends Controller
{
    public function index()
    {
        $title = 'Profil Laboratorium';
        $header = 'Profil Laboratorium SMK';

        return view('dashboard.main.laboratorium.index', compact('title', 'header'));
    }

    public function detail_tkj()
    {
        $title = 'Detail Laboratorium TKJ';
        $header = 'Detail Laboratorium TKJ';

        return view('dashboard.main.laboratorium.detail_tkj', compact('title', 'header'));
    }

    public function detail_rpl()
    {
        $title = 'Detail Laboratorium RPL';
        $header = 'Detail Laboratorium RPL';

        return view('dashboard.main.laboratorium.detail_rpl', compact('title', 'header'));
    }

    public function detail_mm()
    {
        $title = 'Detail Laboratorium Multimedia';
        $header = 'Detail Laboratorium Multimedia';

        return view('dashboard.main.laboratorium.detail_mm', compact('title', 'header'));
    }

    public function jadwal()
    {
        $title = 'Jadwal Praktikum';
        $header = 'Jadwal Praktikum Laboratorium SMK';
    
        $events = Laboratorium::select('id', 'labor', 'start', 'end')
            ->orderBy('start', 'asc')
            ->get();

        $jam = $events->pluck('start')->map(function ($start) {
            return \Carbon\Carbon::parse($start)->format('H:i');
        })->unique()->sort()->values();

        $uniqueDates = $events->map(function ($event) {
            return [
                'start' => \Carbon\Carbon::parse($event->start)->format('Y-m-d'),
                'lab' => $event->labor, // Menambahkan informasi lab
            ];
        })->groupBy('start')->map(function ($group, $date) {
            return $group->map(function ($item) use ($date) {
                return $item['lab'] . ' (' . \Carbon\Carbon::parse($date)->format('d') . ')'; // Format lab dengan tanggal
            })->unique();
        })->flatten()->values();
        
        return view('dashboard.main.laboratorium.jadwal', compact('title', 'header', 'events', 'jam', 'uniqueDates'));
    }   
    
    public function pinjam(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'nama' => 'required',
            'lab_id' => 'required',
            'keperluan' => 'required',
            'jadwal' => 'required',
        ]);

        $jadwal = \Carbon\Carbon::parse($request->jadwal);
        
        $p = new PinjamLabor;
        $p->nama = $request->nama;
        $p->laboratorium_id = $request->lab_id;
        $p->keperluan = $request->keperluan;
        $p->tanggal = $jadwal->toDateString();
        $p->waktu = $jadwal->toTimeString();
        $p->save();

        if ($p) {
            return redirect()->route('lab.jadwal')->with('status', 'success')->with('title', 'Berhasil')->with('message', 'Berhasi mengajukan peminjaman');
        }
    }

    public function dashboard()
    {
        $title = 'Dashboard Laboratorium';
        $header = 'Sistem Informasi Laboratorium SMK';
        
        // If user is authenticated, redirect to role-based dashboard
        if (auth()->check()) {
            return redirect()->route('lab.dashboard');
        }
        
        // For guests, show the public laboratory dashboard with inventory count
        $laboratoriums = \App\Models\Labor::with(['jenisData', 'penanggungJawabUser'])
            ->withCount('inventaris')
            ->get();
        
        return view('dashboard.main.index', compact('title', 'header', 'laboratoriums'));
    }
}