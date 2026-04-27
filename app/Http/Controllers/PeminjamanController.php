<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Perpustakaan';
        $header = 'Peminjam Buku';
        
        $query = Peminjaman::with('buku');
        
        if ($request->has('dari_tanggal') && $request->dari_tanggal) {
            $query->whereDate('tanggal_pinjam', '>=', $request->dari_tanggal);
        }
        
        if ($request->has('sampai_tanggal') && $request->sampai_tanggal) {
            $query->whereDate('tanggal_pinjam', '<=', $request->sampai_tanggal);
        }
        
        $peminjaman = $query->get();
        return view('perpustakaan.peminjaman.index', compact('peminjaman', 'title', 'header'));
    }

    public function exportPDF(Request $request)
    {
        $query = Peminjaman::with('buku');
        
        if ($request->has('dari_tanggal') && $request->dari_tanggal) {
            $query->whereDate('tanggal_pinjam', '>=', $request->dari_tanggal);
        }
        
        if ($request->has('sampai_tanggal') && $request->sampai_tanggal) {
            $query->whereDate('tanggal_pinjam', '<=', $request->sampai_tanggal);
        }
        
        $peminjaman = $query->get();
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('perpustakaan.peminjaman.pdf', compact('peminjaman'));
        return $pdf->download('laporan-peminjaman-' . date('Y-m-d') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $dari_tanggal = $request->dari_tanggal ?? '';
        $sampai_tanggal = $request->sampai_tanggal ?? '';
        
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\PeminjamanExport($dari_tanggal, $sampai_tanggal), 
            'laporan-peminjaman-' . date('Y-m-d') . '.xlsx'
        );
    }

    public function create(Request $request)
    {
        $title = 'Peminjaman Buku';
        $header = 'Form Peminjaman Buku';
        
        $selectedBuku = null;
        if ($request->has('buku_id')) {
            $selectedBuku = Buku::find($request->buku_id);
        }

        $buku = Buku::where('stok', '>', 0)->get();
        
        // Get authenticated user's name if logged in
        $nama = auth()->check() ? auth()->user()->name : '';
        $isStudent = auth()->check() && in_array(auth()->user()->role, ['siswa', 'guru']);
        
        $hasUnpaidFine = false;
        if (auth()->check()) {
            $hasUnpaidFine = Peminjaman::where('nama', auth()->user()->name)
                                ->where('denda', '>', 0)
                                ->where('denda_dibayar', false)
                                ->exists();
        }
        
        return view('perpustakaan.peminjaman.create', compact('selectedBuku', 'buku', 'title', 'header', 'nama', 'isStudent', 'hasUnpaidFine'));
    }

    public function store(Request $request)
    {
        // Validate the input
        $request->validate([
            'nama' => 'required',
            'buku_id' => 'required|array|min:1|max:2',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
        ], [
            'buku_id.max' => 'Maksimal peminjaman adalah 2 buku dalam satu waktu.',
            'tanggal_kembali.after_or_equal' => 'Tanggal kembali tidak boleh sebelum tanggal pinjam.'
        ]);
        
        // If user is a student or guru, use their actual name
        if (auth()->check() && in_array(auth()->user()->role, ['siswa', 'guru'])) {
            $request->merge(['nama' => auth()->user()->name]);
        }

        // Check active loans limit (max 2)
        $userNama = auth()->user()->name;
        $activeLoansCount = Peminjaman::where('nama', $userNama)
                            ->whereIn('status', ['Menunggu', 'Disetujui'])
                            ->count();
        
        if ($activeLoansCount + count($request->buku_id) > 2) {
            $message = $activeLoansCount > 0 
                ? "Batas peminjaman adalah 2 buku. Anda saat ini memiliki $activeLoansCount pinjaman aktif."
                : "Batas peminjaman adalah 2 buku.";
            return redirect()->back()->withInput()->with('error', $message);
        }
        
        // Pengecekan Penangguhan Denda (Suspend)
        $hasUnpaidFine = Peminjaman::where('nama', $userNama)
                            ->where('denda', '>', 0)
                            ->where('denda_dibayar', false)
                            ->exists();
                            
        if ($hasUnpaidFine) {
            return redirect()->back()->withInput()->with('error', 'Penangguhan Akun: Anda memiliki denda keterlambatan buku sebelumnya yang belum dilunasi. Harap lunasi terlebih dahulu untuk meminjam buku.');
        }

        // Validate max 7 days programmatically
        $tglPinjam = \Carbon\Carbon::parse($request->tanggal_pinjam);
        $tglKembali = \Carbon\Carbon::parse($request->tanggal_kembali);
        if ($tglKembali->diffInDays($tglPinjam) > 7) {
            return redirect()->back()->withInput()->with('error', 'Maksimal waktu peminjaman adalah 7 hari.');
        }

        // Process each book
        foreach ($request->buku_id as $buku_id) {
            $buku = Buku::find($buku_id);
            if (!$buku || $buku->stok <= 0) {
                continue; // Skip if book not found or out of stock (should be rare due to view filter)
            }

            $buku->decrement('stok');
            Peminjaman::create([
                'nama' => $request->nama,
                'buku_id' => $buku_id,
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'tanggal_kembali' => $request->tanggal_kembali,
                'status' => 'Menunggu',
            ]);
        }

        return redirect()->route('perpustakaan.peminjaman.history')
            ->with('status', 'success')
            ->with('title', 'Berhasil')
            ->with('message', 'Peminjaman buku berhasil diajukan');
    }

    public function edit(Peminjaman $peminjaman)
    {
        $title = 'Peminjaman Buku';
        $header = 'Form Peminjaman Buku';
        $buku = Buku::all();
        return view('perpustakaan.peminjaman.edit', compact('peminjaman', 'buku', 'title', 'header'));
    }

    public function update(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'status' => 'required|in:Menunggu,Disetujui,Ditolak,Dikembalikan,Terlambat',
            'tanggal_dikembalikan' => 'nullable|date',
            'denda' => 'nullable|numeric|min:0',
            'denda_dibayar' => 'nullable|boolean'
        ]);
        
        $oldStatus = $peminjaman->status;

        $peminjaman->update([
            'status' => $request->status,
            'tanggal_dikembalikan' => $request->tanggal_dikembalikan,
            'denda' => $request->denda ?? 0,
            'denda_dibayar' => $request->has('denda_dibayar') ? $request->denda_dibayar : false
        ]);
    
        // Jika dari belum kembali menjadi kembali
        if (($request->status == 'Dikembalikan' || $request->status == 'Terlambat') && 
             $oldStatus != 'Dikembalikan' && $oldStatus != 'Terlambat') {
            $buku = Buku::find($peminjaman->buku_id);
            if ($buku) {
                $buku->increment('stok');
            }
        }
        
        // Jika dari kembali menjadi belum kembali (pembatalan pengembalian)
        if (($oldStatus == 'Dikembalikan' || $oldStatus == 'Terlambat') && 
            ($request->status != 'Dikembalikan' && $request->status != 'Terlambat')) {
            $buku = Buku::find($peminjaman->buku_id);
            if ($buku) {
                $buku->decrement('stok');
            }
        }
    
        return redirect()
            ->route('perpustakaan.peminjaman.index')
            ->with('status', 'success')
            ->with('title', 'Berhasil')
            ->with('message', 'Status peminjaman berhasil diperbarui.');
    }

    public function destroy(Peminjaman $peminjaman)
    {
        $peminjaman->delete();
        return redirect()->route('perpustakaan.peminjaman.index')->with('status', 'success')->with('title', 'Berhasil')->with('message', 'Peminjamanan berhasil dihapus');
    }

    public function history()
    {
        $title = 'Riwayat Peminjaman';
        $header = 'Riwayat Peminjaman Buku';
        
        // Get current authenticated user's name
        $userNama = auth()->user()->name;
        
        // Get peminjaman records for this user
        $peminjaman = Peminjaman::with('buku')
                        ->where('nama', $userNama)
                        ->orderBy('created_at', 'desc')
                        ->get();
                        
        $hasUnpaidFine = Peminjaman::where('nama', $userNama)
                            ->where('denda', '>', 0)
                            ->where('denda_dibayar', false)
                            ->exists();
        
        return view('perpustakaan.peminjaman.history', compact('peminjaman', 'title', 'header', 'hasUnpaidFine'));
    }
}