<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\Labor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $title = 'Laporan Kerusakan';
        $header = 'Daftar Laporan Kerusakan (Aktif)';
        
        $query = Laporan::where('user_id', Auth::id())
            ->where('status_perbaikan', '!=', 'selesai');

        if ($request->has('tingkat_kerusakan') && $request->tingkat_kerusakan != '') {
            $query->where('tingkat_kerusakan', $request->tingkat_kerusakan);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where('nama_alat', 'like', '%' . $request->search . '%');
        }

        $stats = [
            'total' => Laporan::where('user_id', Auth::id())->count(),
            'aktif' => Laporan::where('user_id', Auth::id())->where('status_perbaikan', '!=', 'selesai')->count(),
            'selesai' => Laporan::where('user_id', Auth::id())->where('status_perbaikan', 'selesai')->count(),
        ];
        
        $laporan = $query->latest()->paginate(10);
        
        return view('siswa.main.laporan.index', compact('laporan', 'title', 'header', 'stats'));
    }

    /**
     * History of Finished Repairs for Students
     */
    public function perbaikanSelesai(Request $request)
    {
        $title = 'Riwayat Perbaikan';
        $header = 'Daftar Perbaikan Selesai';
        
        $laporan = Laporan::where('user_id', Auth::id())
            ->where('status_perbaikan', 'selesai')
            ->latest()
            ->paginate(10);
            
        return view('siswa.main.laporan.selesai', compact('laporan', 'title', 'header'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Buat Laporan';
        $header = 'Buat Laporan Kerusakan';
        
        $laborList = Labor::orderBy('nama_labor', 'asc')->get();
        
        return view('siswa.main.laporan.create', compact('title', 'header', 'laborList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_alat' => 'required|string|max:255',
            'inventaris_id' => 'nullable|exists:inventaris,id',
            'lokasi' => 'required|string',
            'deskripsi_kerusakan' => 'required|string',
            'tingkat_kerusakan' => 'required|in:Ringan,Sedang,Berat',
            'foto_bukti' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'nama_pelapor' => 'required|string',
            'tanggal_laporan' => 'required|date',
            'kelas' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('status', 'error')
                ->with('title', 'Gagal')
                ->with('message', 'Validasi gagal, mohon periksa form kembali');
        }
        
        $namaPelapor = $request->nama_pelapor;
        if (Auth::user()->role === 'guru' && $request->filled('kelas')) {
            $namaPelapor .= ' (' . $request->kelas . ')';
        }

        $laporan = new Laporan();
        $laporan->user_id = Auth::id(); // Added user_id relation
        $laporan->nama_alat = $request->nama_alat;
        $laporan->inventaris_id = $request->inventaris_id; // Store inventaris_id
        // $laporan->lokasi = $request->lokasi; 
        $laporan->nama_pelapor = $namaPelapor;
        $laporan->deskripsi_kerusakan = $request->deskripsi_kerusakan;
        $laporan->tingkat_kerusakan = $request->tingkat_kerusakan;
        $laporan->tanggal_laporan = $request->tanggal_laporan;
        $laporan->status = 'pending';

        if ($request->hasFile('foto_bukti')) {
            $file = $request->file('foto_bukti');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/laporan_kerusakan', $filename);
            $laporan->foto_bukti = $filename;
        }

        $laporan->save();
        
        $prefix = Auth::user()->role == 'guru' ? 'guru' : 'siswa';
        return redirect()->route($prefix . '.laporan.index')
            ->with('status', 'success')
            ->with('title', 'Berhasil')
            ->with('message', 'Laporan kerusakan berhasil dikirim');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $title = 'Detail Laporan';
        $header = 'Detail Laporan Kerusakan';
        
        $laporan = Laporan::findOrFail($id);
        
        // Verify ownership
        if ($laporan->user_id != Auth::id()) {
            $prefix = Auth::user()->role == 'guru' ? 'guru' : 'siswa';
            return redirect()->route($prefix . '.laporan.index')
                ->with('status', 'error')
                ->with('title', 'Akses Ditolak')
                ->with('message', 'Anda tidak memiliki akses ke laporan tersebut');
        }
        
        return view('siswa.main.laporan.show', compact('title', 'header', 'laporan'));
    }

    /**
     * Get inventory items by laboratory ID or name.
     * Used for AJAX in forms.
     */
    public function getInventarisByLab(Request $request)
    {
        $labor_id = $request->labor_id;
        $nama_labor = $request->nama_labor;
        
        if (!$labor_id && !$nama_labor) {
            return response()->json([]);
        }

        $query = \App\Models\Inventaris::query();

        if ($labor_id) {
            $query->where('labor_id', $labor_id);
        } else {
            $labor = Labor::where('nama_labor', $nama_labor)->first();
            if (!$labor) {
                return response()->json([]);
            }
            $query->where('labor_id', $labor->id);
        }

        // Get available inventory for this lab
        $inventaris = $query->where('jenis', 'Alat')
            ->orderBy('nama_inventaris', 'asc')
            ->get(['id', 'nama_inventaris', 'kode_inventaris', 'jumlah']);

        return response()->json($inventaris);
    }
}
