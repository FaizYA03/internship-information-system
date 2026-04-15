<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MagangOpening;
use App\Models\WakilPerusahaan;

class WakilPerusahaanOpeningsController extends Controller
{
    // Display a listing of openings
    public function index()
    {
        $title = 'Program Magang';
        $header = 'Kelola Program Magang';
        
        $user = Auth::user();
        $wakilPerusahaan = WakilPerusahaan::where('email', $user->email)->first();
        
        if (!$wakilPerusahaan) {
            return redirect()->route('magang.wakil_perusahaan.dashboard')
                ->with('status', 'error')
                ->with('title', 'Error')
                ->with('message', 'Data perusahaan tidak ditemukan.');
        }
        
        $openings = MagangOpening::where('wakil_perusahaan_id', $wakilPerusahaan->id)
                        ->orderBy('created_at', 'desc')
                        ->get();
        
        return view('magang.wakil_perusahaan.openings.index', compact('title', 'header', 'openings', 'wakilPerusahaan'));
    }
    
    // Show the form for creating a new opening
    public function create()
    {
        $title = 'Tambah Program Magang';
        $header = 'Tambah Program Magang Baru';
        
        $user = Auth::user();
        $wakilPerusahaan = WakilPerusahaan::where('email', $user->email)->first();
        
        if (!$wakilPerusahaan) {
            return redirect()->route('magang.wakil_perusahaan.dashboard')
                ->with('status', 'error')
                ->with('title', 'Error')
                ->with('message', 'Data perusahaan tidak ditemukan.');
        }
        
        return view('magang.wakil_perusahaan.openings.create', compact('title', 'header', 'wakilPerusahaan'));
    }
    
    // Store a newly created opening
    public function store(Request $request)
    {
        $user = Auth::user();
        $wakilPerusahaan = WakilPerusahaan::where('email', $user->email)->first();
        
        if (!$wakilPerusahaan) {
            return redirect()->route('magang.wakil_perusahaan.dashboard')
                ->with('status', 'error')
                ->with('title', 'Error')
                ->with('message', 'Data perusahaan tidak ditemukan.');
        }
        
        $request->validate([
            'posisi' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'keahlian' => 'nullable|string',
            'jumlah_posisi' => 'required|integer|min:1',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'status' => 'required|in:Aktif,Tidak Aktif',
        ]);
        
        MagangOpening::create([
            'wakil_perusahaan_id' => $wakilPerusahaan->id,
            'posisi' => $request->posisi,
            'deskripsi' => $request->deskripsi,
            'keahlian' => $request->keahlian,
            'jumlah_posisi' => $request->jumlah_posisi,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => $request->status,
        ]);
        
        return redirect()->route('magang.wakil_perusahaan.openings.index')
            ->with('status', 'success')
            ->with('title', 'Berhasil')
            ->with('message', 'Program magang berhasil ditambahkan.');
    }
    
    // Show the form for editing the specified opening
    public function edit($id)
    {
        $title = 'Edit Program Magang';
        $header = 'Edit Program Magang';
        
        $user = Auth::user();
        $wakilPerusahaan = WakilPerusahaan::where('email', $user->email)->first();
        
        if (!$wakilPerusahaan) {
            return redirect()->route('magang.wakil_perusahaan.dashboard')
                ->with('status', 'error')
                ->with('title', 'Error')
                ->with('message', 'Data perusahaan tidak ditemukan.');
        }
        
        $opening = MagangOpening::where('id', $id)
                     ->where('wakil_perusahaan_id', $wakilPerusahaan->id)
                     ->firstOrFail();
        
        // Change this line to use the create view instead of edit
        return view('magang.wakil_perusahaan.openings.create', compact('title', 'header', 'opening', 'wakilPerusahaan'));
    }
    
    // Update the specified opening
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $wakilPerusahaan = WakilPerusahaan::where('email', $user->email)->first();
        
        if (!$wakilPerusahaan) {
            return redirect()->route('magang.wakil_perusahaan.dashboard')
                ->with('status', 'error')
                ->with('title', 'Error')
                ->with('message', 'Data perusahaan tidak ditemukan.');
        }
        
        $opening = MagangOpening::where('id', $id)
                     ->where('wakil_perusahaan_id', $wakilPerusahaan->id)
                     ->firstOrFail();
        
        $request->validate([
            'posisi' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'keahlian' => 'nullable|string',
            'jumlah_posisi' => 'required|integer|min:1',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'status' => 'required|in:Aktif,Tidak Aktif',
        ]);
        
        $opening->update([
            'posisi' => $request->posisi,
            'deskripsi' => $request->deskripsi,
            'keahlian' => $request->keahlian,
            'jumlah_posisi' => $request->jumlah_posisi,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => $request->status,
        ]);
        
        return redirect()->route('magang.wakil_perusahaan.openings.index')
            ->with('status', 'success')
            ->with('title', 'Berhasil')
            ->with('message', 'Program magang berhasil diperbarui.');
    }
    
    // Delete the specified opening
    public function destroy($id)
    {
        $user = Auth::user();
        $wakilPerusahaan = WakilPerusahaan::where('email', $user->email)->first();
        
        if (!$wakilPerusahaan) {
            return redirect()->route('magang.wakil_perusahaan.dashboard')
                ->with('status', 'error')
                ->with('title', 'Error')
                ->with('message', 'Data perusahaan tidak ditemukan.');
        }
        
        $opening = MagangOpening::where('id', $id)
                     ->where('wakil_perusahaan_id', $wakilPerusahaan->id)
                     ->firstOrFail();
        
        $opening->delete();
        
        return redirect()->route('magang.wakil_perusahaan.openings.index')
            ->with('status', 'success')
            ->with('title', 'Berhasil')
            ->with('message', 'Program magang berhasil dihapus.');
    }
    
    // Show applicants for a specific opening
    public function showApplicants($id)
    {
        $title = 'Pelamar Program Magang';
        $header = 'Daftar Pelamar Program Magang';
        
        $user = Auth::user();
        $wakilPerusahaan = WakilPerusahaan::where('email', $user->email)->first();
        
        if (!$wakilPerusahaan) {
            return redirect()->route('magang.wakil_perusahaan.dashboard')
                ->with('status', 'error')
                ->with('title', 'Error')
                ->with('message', 'Data perusahaan tidak ditemukan.');
        }
        
        $opening = MagangOpening::where('id', $id)
                     ->where('wakil_perusahaan_id', $wakilPerusahaan->id)
                     ->firstOrFail();
        
        $applicants = $opening->applicants;
        
        return view('magang.wakil_perusahaan.openings.applicants', compact('title', 'header', 'opening', 'applicants', 'wakilPerusahaan'));
    }
}