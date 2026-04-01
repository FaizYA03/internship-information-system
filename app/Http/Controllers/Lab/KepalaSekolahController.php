<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lab\PinjamAsat;
use App\Models\Lab\PinjamEksternal;
use App\Models\Lab\Pengadaan;
use Illuminate\Support\Facades\Auth;

class KepalaSekolahController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:kepala_sekolah,super_admin']);
    }

    public function index()
    {
        $stats = [
            'approval_eksternal' => PinjamEksternal::where('status', 'recommended')->count(),
            'approval_pengadaan' => Pengadaan::where('status', 'pending')->count(),
        ];
        return view('lab.kepala_sekolah.dashboard', compact('stats'));
    }

    // --- Approval Peminjaman Eksternal ---
    public function approvalEksternalIndex()
    {
        // Hanya yang sudah direkomendasi Kepala Lab
        $requests = PinjamEksternal::where('status', 'recommended')->get();
        return view('lab.kepala_sekolah.approval.eksternal', compact('requests'));
    }

    public function approveEksternal($id)
    {
        $pinjam = PinjamEksternal::findOrFail($id);
        
        // Cek stok sebelum approve final
        $inventaris = $pinjam->inventaris;
        if ($inventaris->jumlah < $pinjam->jumlah) {
            return back()->with('error', 'Stok barang tidak mencukupi untuk disetujui');
        }
        
        $pinjam->update([
            'status' => 'approved',
            'approved_kepsek_by' => Auth::id(),
            'approved_kepsek_at' => now(),
        ]);
        
        // Kurangi stok di sini atau lewat observer
        $inventaris->decrement('jumlah', $pinjam->jumlah);
        
        return back()->with('success', 'Peminjaman eksternal disetujui');
    }

    public function rejectEksternal(Request $request, $id)
    {
        $pinjam = PinjamEksternal::findOrFail($id);
        $pinjam->update([
            'status' => 'rejected',
            'approved_kepsek_by' => Auth::id(),
            'approved_kepsek_at' => now(),
            // simpan alasan penolakan jika perlu
        ]);
        
        return back()->with('success', 'Peminjaman eksternal ditolak');
    }

    // --- Approval Pengadaan ---
    public function approvalPengadaanIndex()
    {
        $requests = Pengadaan::where('status', 'pending')->with('user')->get();
        return view('lab.kepala_sekolah.approval.pengadaan', compact('requests'));
    }

    public function approvePengadaan(Request $request, $id)
    {
        $pengadaan = Pengadaan::findOrFail($id);
        $pengadaan->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'catatan_approval' => $request->catatan
        ]);

        return back()->with('success', 'Pengadaan disetujui');
    }

    public function rejectPengadaan(Request $request, $id)
    {
        $pengadaan = Pengadaan::findOrFail($id);
        $pengadaan->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'catatan_approval' => $request->catatan
        ]);

        return back()->with('success', 'Pengadaan ditolak');
    }
}
