<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Inventaris;
use App\Models\Labor;
use App\Models\Lab\PinjamAlat;
use Illuminate\Http\Request;

class InventarisController extends Controller
{
    /**
     * Display a listing of the resource (Inventory List for Students)
     * 
     * Features:
     * - Dynamic filtering by category, laboratory, status, condition
     * - Search by name
     * - Sorting options
     * - Pagination (12 items per page)
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = 'Inventaris Laboratorium';
        $header = 'Daftar Inventaris';
        
        // Build query with filters
        $alat = Inventaris::query();
        
        // Filter by category (using kategori string for now - dapat diubah ke kategori_id nanti)
        if ($request->filled('kategori')) {
            $alat->where('kategori', $request->kategori);
        }
        
        // Filter by laboratory
        if ($request->filled('labor')) {
            $alat->where('labor_id', $request->labor);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $alat->where('status', $request->status);
        }
        
        // Filter by condition
        if ($request->filled('kondisi')) {
            $alat->where('kondisi', $request->kondisi);
        }
        
        // Search by name
        if ($request->filled('search')) {
            $alat->where('nama_inventaris', 'like', '%' . $request->search . '%');
        }
        
        // Sorting
        $sortBy = $request->get('sort', 'terbaru');
        switch ($sortBy) {
            case 'terbaru':
                $alat->orderBy('created_at', 'desc');
                break;
            case 'stok_terbanyak':
                $alat->orderBy('jumlah', 'desc');
                break;
            case 'kondisi_terbaik':
                // Order by kondisi: Sangat Baik > Baik > Rusak Ringan > Rusak Sedang > Rusak Berat
                $alat->orderByRaw("FIELD(kondisi, 'Sangat Baik', 'Baik', 'Rusak Ringan', 'Rusak Sedang', 'Rusak Berat')");
                break;
            case 'nama':
                $alat->orderBy('nama_inventaris', 'asc');
                break;
            default:
                $alat->orderBy('created_at', 'desc');
        }
        
        // Exclude deleted items and only show equipment (Alat)
        $alat = $alat->where('jenis', 'Alat')
                     ->where('status', '!=', 'dihapus')
                     ->with('labor') // Eager load laboratory relationship
                     ->paginate(12);
        
        // Get all laboratories for filter dropdown
        $laborList = Labor::orderBy('nama_labor', 'asc')->get();
        
        // Get all unique categories for filter dropdown
        $kategoriList = Inventaris::select('kategori')
                                   ->where('jenis', 'Alat')
                                   ->where('status', '!=', 'dihapus')
                                   ->distinct()
                                   ->pluck('kategori');
        
        // Get all unique conditions for filter dropdown
        $kondisiList = ['Sangat Baik', 'Baik', 'Rusak Ringan', 'Rusak Sedang', 'Rusak Berat'];
        
        // Status list
        $statusList = ['Tersedia', 'Tidak Tersedia', 'Dipinjam'];
        
        return view('siswa.main.inventaris.index', compact(
            'title', 
            'header', 
            'alat', 
            'laborList', 
            'kategoriList',
            'kondisiList',
            'statusList'
        ));
    }

    /**
     * Display the specified resource (Equipment Detail for Students)
     * 
     * Features:
     * - Complete equipment information
     * - Asset tracking details
     * - Borrowing history (read-only)
     * - Link to borrowing form
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $title = 'Detail Inventaris';
        $header = 'Detail Inventaris';
        
        // Get equipment with relationships
        $item = Inventaris::with(['labor', 'peminjaman' => function($query) {
                    $query->orderBy('created_at', 'desc')->limit(10);
                }])
                ->findOrFail($id);
        
        // Check if equipment is borrowable
        $canBorrow = $this->canBorrowEquipment($item);
        
        // Get borrowing history for this equipment
        $riwayatPeminjaman = PinjamAlat::where('inventaris_id', $id)
                                        ->with('peminjam')
                                        ->orderBy('created_at', 'desc')
                                        ->limit(10)
                                        ->get();
        
        return view('siswa.main.inventaris.show', compact('title', 'header', 'item', 'canBorrow', 'riwayatPeminjaman'));
    }
    
    /**
     * Check if equipment can be borrowed
     * 
     * Business Logic:
     * 1. jumlah_tersedia must be > 0
     * 2. kondisi cannot be 'Rusak Berat'
     * 3. status cannot be 'Dipinjam' or 'Tidak Tersedia'
     *
     * @param  Inventaris  $item
     * @return bool
     */
    private function canBorrowEquipment($item)
    {
        // Check if available quantity > 0
        $hasStock = $item->jumlah > 0;
        
        // Check if not heavily damaged
        $notBroken = $item->kondisi !== 'Rusak Berat';
        
        // Check if status is available
        $isAvailable = in_array($item->status, ['Tersedia', 'tersedia']);
        
        return $hasStock && $notBroken && $isAvailable;
    }
}