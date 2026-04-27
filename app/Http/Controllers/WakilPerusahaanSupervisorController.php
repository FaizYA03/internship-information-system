<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MitraSupervisor;
use App\Models\WakilPerusahaan;
use Illuminate\Support\Facades\Auth;

class WakilPerusahaanSupervisorController extends Controller
{
    private function getWakilPerusahaan()
    {
        return WakilPerusahaan::where('user_id', Auth::id())->first();
    }

    public function index()
    {
        $wakil = $this->getWakilPerusahaan();
        if (!$wakil) {
            return redirect()->back()->with('error', 'Profil Perusahaan tidak ditemukan.');
        }

        $supervisors = $wakil->supervisors()->latest()->get();

        return view('magang.wakil_perusahaan.supervisors.index', compact('supervisors', 'wakil'));
    }

    public function store(Request $request)
    {
        $wakil = $this->getWakilPerusahaan();
        if (!$wakil) {
            return redirect()->back()->with('error', 'Profil Perusahaan tidak ditemukan.');
        }

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nip' => 'nullable|string|max:100',
            'jabatan' => 'nullable|string|max:255',
            'departemen' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:15',
        ]);

        MitraSupervisor::create([
            'wakil_perusahaan_id' => $wakil->id,
            'nama_lengkap' => $request->nama_lengkap,
            'nip' => $request->nip,
            'jabatan' => $request->jabatan,
            'departemen' => $request->departemen,
            'no_hp' => $request->no_hp,
        ]);

        return redirect()->route('magang.wakil_perusahaan.supervisors.index')->with('success', 'Supervisor lapangan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $wakil = $this->getWakilPerusahaan();
        $supervisor = MitraSupervisor::where('id', $id)->where('wakil_perusahaan_id', $wakil->id)->firstOrFail();

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nip' => 'nullable|string|max:100',
            'jabatan' => 'nullable|string|max:255',
            'departemen' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:15',
        ]);

        $supervisor->update([
            'nama_lengkap' => $request->nama_lengkap,
            'nip' => $request->nip,
            'jabatan' => $request->jabatan,
            'departemen' => $request->departemen,
            'no_hp' => $request->no_hp,
        ]);

        return redirect()->route('magang.wakil_perusahaan.supervisors.index')->with('success', 'Data Supervisor berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $wakil = $this->getWakilPerusahaan();
        $supervisor = MitraSupervisor::where('id', $id)->where('wakil_perusahaan_id', $wakil->id)->firstOrFail();
        
        $supervisor->delete();

        return redirect()->route('magang.wakil_perusahaan.supervisors.index')->with('success', 'Supervisor berhasil dihapus.');
    }
}
