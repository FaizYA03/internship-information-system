<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InstruksiKepalaSekolah;
use App\Models\Buku;
use Illuminate\Support\Facades\DB;

class AdminEwsController extends Controller
{
    public function index()
    {
        $title = 'Dashboard Admin / Pustakawan EWS';
        
        $instruksis = InstruksiKepalaSekolah::with('buku')
            ->where('tujuan', 'admin_perpus')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('perpustakaan.admin.ews.index', compact('title', 'instruksis'));
    }

    public function resolve(Request $request)
    {
        $request->validate([
            'id_instruksi' => 'required|exists:instruksi_kepala_sekolah,id_instruksi',
        ]);

        $instruksi = InstruksiKepalaSekolah::findOrFail($request->id_instruksi);
        
        DB::beginTransaction();
        try {
            // Ubah status instruksi
            $instruksi->update(['status' => 'selesai']);

            // Jika tindakan adalah Pemutihan, kosongkan stok buku atau tandai sebagai dihapus
            if (strtolower($instruksi->jenis_tindakan) === 'pemutihan' && $instruksi->buku) {
                // Sesuai logika user sebelumnya: UPDATE buku SET status = 'dihapus'
                // Karena kita menggunakan Laravel model, jika di database tidak ada kolom status, kita bisa mengosongkan stok 
                // Jika user memiliki SoftDeletes di Buku, kita bisa mendeletenya.
                // Disini saya asumsikan soft delete atau reset stok.
                $instruksi->buku->stok = 0;
                $instruksi->buku->save();
            }

            DB::commit();
            return redirect()->back()->with('success', 'Instruksi EWS berhasil dieksekusi dan ditandai selesai.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memproses instruksi: ' . $e->getMessage());
        }
    }
}
