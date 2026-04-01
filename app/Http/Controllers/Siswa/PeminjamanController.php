<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Labor;
use App\Models\Inventaris;
use App\Models\Lab\PinjamAlat;
use App\Models\PinjamLabor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    /**
     * Display a listing of borrowing requests.
     */
    public function index()
    {
        $title = 'Riwayat Peminjaman';
        $header = 'Data Peminjaman Laboratorium';
        
        $user = Auth::user();
        
        // Peminjaman Alat
        $peminjamanAlat = PinjamAlat::where('user_id', $user->id)
            ->with(['inventaris.labor'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Peminjaman Ruangan
        $peminjamanRuangan = PinjamLabor::where('user_id', $user->id)
            ->with(['labor'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('siswa.main.peminjaman.index', compact('title', 'header', 'peminjamanAlat', 'peminjamanRuangan'));
    }

    /**
     * Show the form for creating a new borrowing request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        $title = 'Ajukan Peminjaman';
        $header = 'Form Peminjaman Alat';
        
        $laborList = Labor::orderBy('nama_labor', 'asc')->get();
        
        // If an item ID is provided (from inventory show page)
        $selectedAlat = null;
        $selectedLabor = null;
        
        if ($request->has('alat_id')) {
            $selectedAlat = Inventaris::with('labor')->find($request->alat_id);
            if ($selectedAlat) {
                $selectedLabor = $selectedAlat->labor;
            }
        }

        return view('siswa.main.peminjaman.create', compact('title', 'header', 'laborList', 'selectedAlat', 'selectedLabor'));
    }

    /**
     * Store a newly created borrowing request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'inventaris_id' => 'required|exists:inventaris,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'jam_pinjam' => 'nullable',
            'jam_kembali' => 'nullable',
            'keperluan' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('status', 'error')
                ->with('title', 'Gagal')
                ->with('message', 'Validasi gagal, mohon periksa form kembali');
        }

        // Check if item is available for the given quantity
        $item = Inventaris::findOrFail($request->inventaris_id);
        if ($item->jumlah < $request->jumlah) {
            return redirect()->back()
                ->withInput()
                ->with('status', 'error')
                ->with('title', 'Stok Kurang')
                ->with('message', 'Maaf, stok alat yang tersedia tidak mencukupi');
        }

        $peminjaman = new PinjamAlat();
        $peminjaman->user_id = Auth::id();
        $peminjaman->inventaris_id = $request->inventaris_id;
        $peminjaman->jumlah = $request->jumlah;
        $peminjaman->tanggal_pinjam = $request->tanggal_pinjam;
        $peminjaman->tanggal_kembali = $request->tanggal_kembali;
        $peminjaman->jam_pinjam = $request->jam_pinjam;
        $peminjaman->jam_kembali = $request->jam_kembali;
        $peminjaman->keperluan = $request->keperluan;
        $peminjaman->status = 'pending';
        $peminjaman->save();

        $prefix = Auth::user()->role == 'guru' ? 'guru' : 'siswa';
        
        return redirect()->route($prefix . '.labor.index')
            ->with('status', 'success')
            ->with('title', 'Berhasil')
            ->with('message', 'Permohonan peminjaman berhasil diajukan. Tunggu persetujuan dari admin lab.');
    }

    /**
     * Show the form for creating a new room borrowing request.
     */
    public function createRuangan(Request $request)
    {
        if (Auth::user()->role === 'siswa') {
            return redirect()->route('siswa.peminjaman.index')
                ->with('status', 'error')
                ->with('title', 'Akses Ditolak')
                ->with('message', 'Siswa tidak diizinkan meminjam ruangan. Hanya guru yang dapat meminjam ruangan.');
        }

        $title = 'Ajukan Peminjaman Ruangan';
        $header = 'Form Peminjaman Laboratorium';
        
        $laborList = Labor::orderBy('nama_labor', 'asc')->get();
        
        // Pre-select if lab_id is provided
        $selectedLabor = null;
        if ($request->has('lab_id')) {
            $selectedLabor = Labor::find($request->lab_id);
        }

        return view('siswa.main.peminjaman.create_ruangan', compact('title', 'header', 'laborList', 'selectedLabor'));
    }

    /**
     * Store a newly created room borrowing request.
     */
    public function storeRuangan(Request $request)
    {
        if (Auth::user()->role === 'siswa') {
            return redirect()->route('siswa.peminjaman.index')
                ->with('status', 'error')
                ->with('title', 'Akses Ditolak')
                ->with('message', 'Siswa tidak diizinkan meminjam ruangan. Hanya guru yang dapat meminjam ruangan.');
        }

        $validator = Validator::make($request->all(), [
            'labor_id' => 'required|exists:labor,id',
            'tanggal' => 'required|date|after_or_equal:today',
            'jam_pinjam' => 'required',
            'jam_kembali' => 'required|after:jam_pinjam',
            'kelas' => 'required|string|max:50',
            'mata_pelajaran' => 'required|string|max:100',
            'keperluan' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('status', 'error')
                ->with('title', 'Gagal')
                ->with('message', 'Validasi gagal, mohon periksa form kembali');
        }

        // --- CONFLICT VALIDATION ---
        $date = $request->tanggal;
        $startTime = $request->jam_pinjam;
        $endTime = $request->jam_kembali;
        $laborId = $request->labor_id;
        
        // 1. Check against Master Schedule (JadwalLaboratorium)
        // Note: Schedule uses day name (hari), not specific date.
        $dayName = \App\Http\Controllers\Siswa\JadwalController::getIndoDay(Carbon::parse($date));
        $scheduleConflict = \App\Models\Lab\JadwalLaboratorium::where('labor_id', $laborId)
            ->where('hari', $dayName)
            ->where(function($q) use ($startTime, $endTime) {
                $q->whereBetween('jam_mulai', [$startTime, $endTime])
                  ->orWhereBetween('jam_selesai', [$startTime, $endTime])
                  ->orWhere(function($subq) use ($startTime, $endTime) {
                      $subq->where('jam_mulai', '<=', $startTime)
                           ->where('jam_selesai', '>=', $endTime);
                  });
            })->exists();

        if ($scheduleConflict) {
            return redirect()->back()
                ->withInput()
                ->with('status', 'error')
                ->with('title', 'Bentrok Jadwal')
                ->with('message', 'Maaf, rangan ini sudah memiliki jadwal praktikum rutin pada jam tersebut.');
        }

        // 2. Check against other Approved/Pending Room Borrowings
        $borrowConflict = PinjamLabor::where('labor_id', $laborId)
            ->where('tanggal', $date)
            ->whereIn('status', ['pending', 'approved'])
            ->where(function($q) use ($startTime, $endTime) {
                // Using jam_pinjam / jam_kembali columns
                $q->whereBetween('jam_pinjam', [$startTime, $endTime])
                  ->orWhereBetween('jam_kembali', [$startTime, $endTime])
                  ->orWhere(function($subq) use ($startTime, $endTime) {
                      $subq->where('jam_pinjam', '<=', $startTime)
                           ->where('jam_kembali', '>=', $endTime);
                  });
            })->exists();

        if ($borrowConflict) {
            return redirect()->back()
                ->withInput()
                ->with('status', 'error')
                ->with('title', 'Bentrok Peminjaman')
                ->with('message', 'Maaf, sudah ada permohonan peminjaman lain untuk ruangan dan waktu ini.');
        }

        $peminjaman = new PinjamLabor();
        $peminjaman->user_id = Auth::id();
        $peminjaman->nama = Auth::user()->nama;
        $peminjaman->labor_id = $request->labor_id;
        $peminjaman->tanggal = $request->tanggal;
        $peminjaman->jam_pinjam = $request->jam_pinjam;
        $peminjaman->jam_kembali = $request->jam_kembali;
        $peminjaman->waktu = $request->jam_pinjam; // Legacy field
        $peminjaman->kelas = $request->kelas;
        $peminjaman->mata_pelajaran = $request->mata_pelajaran;
        $peminjaman->keperluan = $request->keperluan;
        $peminjaman->status = 'pending';
        $peminjaman->save();

        $prefix = Auth::user()->role == 'guru' ? 'guru' : 'siswa';
        
        return redirect()->route($prefix . '.labor.index')
            ->with('status', 'success')
            ->with('title', 'Berhasil')
            ->with('message', 'Permohonan peminjaman ruangan berhasil diajukan. Tunggu persetujuan dari admin lab.');
    }

    /**
     * Cancel a borrowing request.
     */
    public function cancel($id)
    {
        $peminjaman = PinjamAlat::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->findOrFail($id);
            
        $peminjaman->delete();
        
        return redirect()->back()
            ->with('status', 'success')
            ->with('title', 'Dibatalkan')
            ->with('message', 'Permohonan peminjaman telah berhasil dibatalkan.');
    }

    /**
     * Cancel a room borrowing request.
     */
    public function cancelRuangan($id)
    {
        $peminjaman = PinjamLabor::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->findOrFail($id);
            
        $peminjaman->delete();
        
        return redirect()->back()
            ->with('status', 'success')
            ->with('title', 'Dibatalkan')
            ->with('message', 'Permohonan peminjaman ruangan telah berhasil dibatalkan.');
    }
}
