<?php

namespace App\Http\Controllers;

use App\Models\Pengadaan;
use App\Models\PengadaanDetail;
use App\Models\Vendor;
use App\Models\Buku;
use App\Models\BookCopy;
use App\Services\InventoryNumberService;
use App\Services\BarcodeService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengadaanController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->status;
        $query = Pengadaan::with('vendor')->orderBy('created_at', 'desc');
        
        if ($status) {
            $query->where('status', $status);
        }
        
        $pengadaans = $query->get();
        return view('perpustakaan.pengadaan.index', compact('pengadaans', 'status'));
    }

    public function create()
    {
        $vendors = Vendor::all();
        $buku = Buku::all(); // For checking duplicates/existing books
        $rekomendasiWaka = \App\Models\RekomendasiBuku::with(['waka', 'mapel', 'jurusan'])->where('status', 'Draft')->get();
        return view('perpustakaan.pengadaan.create', compact('vendors', 'buku', 'rekomendasiWaka'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'vendor_id' => 'nullable|exists:vendors,id',
            'details' => 'required|array',
            'details.*.judul' => 'required|string|max:255',
            'details.*.jumlah' => 'required|integer|min:1',
            'details.*.harga_per_unit' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $totalEstimasi = 0;
            foreach ($request->details as $detail) {
                $totalEstimasi += ($detail['jumlah'] * $detail['harga_per_unit']);
            }

            $pengadaan = Pengadaan::create([
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'status' => 'Draft',
                'total_estimasi' => $totalEstimasi,
                'total_aktual' => 0,
                'tanggal_usulan' => Carbon::now(),
                'vendor_id' => $request->vendor_id,
            ]);

            // Update status rekomendasi jika di-import
            if ($request->has('rekomendasi_ids')) {
                foreach ($request->rekomendasi_ids as $rekId) {
                    \App\Models\RekomendasiBuku::where('id', $rekId)->update([
                        'status' => 'Diproses',
                        'pengadaan_id' => $pengadaan->id
                    ]);
                }
            }

            foreach ($request->details as $detail) {
                // Find if book already exists (duplicate check logic)
                $bukuId = null;
                $existingBuku = Buku::where('judul', 'like', '%' . $detail['judul'] . '%')->first();
                if ($existingBuku) {
                    $bukuId = $existingBuku->id;
                }

                PengadaanDetail::create([
                    'pengadaan_id' => $pengadaan->id,
                    'buku_id' => $bukuId,
                    'judul' => $detail['judul'],
                    'penulis' => $detail['penulis'] ?? null,
                    'penerbit' => $detail['penerbit'] ?? null,
                    'isbn' => $detail['isbn'] ?? null,
                    'jumlah' => $detail['jumlah'],
                    'harga_per_unit' => $detail['harga_per_unit'],
                    'subtotal' => $detail['jumlah'] * $detail['harga_per_unit'],
                ]);
            }
        });

        return redirect()->route('perpustakaan.pengadaan.index')->with('success', 'Draft pengadaan berhasil dibuat.');
    }

    public function show(Pengadaan $pengadaan)
    {
        $pengadaan->load(['details.buku', 'vendor', 'bookCopies']);
        $budgetTahunIni = \App\Models\LibraryBudget::where('tahun', Carbon::now()->year)->first();
        return view('perpustakaan.pengadaan.show', compact('pengadaan', 'budgetTahunIni'));
    }

    public function edit(Pengadaan $pengadaan)
    {
        if ($pengadaan->status !== 'Draft' && $pengadaan->status !== 'Ditolak') {
            return redirect()->route('perpustakaan.pengadaan.show', $pengadaan)->with('error', 'Hanya pengadaan berstatus Draft atau Ditolak yang bisa diedit.');
        }

        $pengadaan->load('details');
        $vendors = Vendor::all();
        return view('perpustakaan.pengadaan.edit', compact('pengadaan', 'vendors'));
    }

    public function update(Request $request, Pengadaan $pengadaan)
    {
        // For sending to approval
        if ($request->has('ajukan_persetujuan')) {
            $pengadaan->update(['status' => 'Menunggu Persetujuan']);
            return redirect()->route('perpustakaan.pengadaan.show', $pengadaan)->with('success', 'Pengajuan berhasil dikirimkan ke Kepala Sekolah.');
        }
        
        // Full update logic (simplified for the prompt, would normally handle details array update)
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'vendor_id' => 'nullable|exists:vendors,id',
            'faktur_no' => 'nullable|string',
            'faktur_tanggal' => 'nullable|date',
            'total_aktual' => 'nullable|numeric|min:0',
        ]);
        
        $pengadaan->update($request->only(['judul', 'deskripsi', 'vendor_id', 'faktur_no', 'faktur_tanggal', 'total_aktual']));

        return redirect()->route('perpustakaan.pengadaan.show', $pengadaan)->with('success', 'Data pengadaan berhasil diperbarui.');
    }

    // Role: Waka Kurikulum or Kepala Sekolah
    public function approve(Request $request, Pengadaan $pengadaan)
    {
        if (!auth()->user()->isKepalaSekolah() && !auth()->user()->isWakaAkademik()) {
            return back()->with('error', 'Tidak memiliki otorisasi.');
        }

        // Revisi Plafon jika ada
        $plafon = $request->input('override_estimasi') ?: $pengadaan->total_estimasi;

        $budget = \App\Models\LibraryBudget::where('tahun', Carbon::now()->year)->first();
        if ($budget) {
            if ($plafon > $budget->sisa_anggaran) {
                return back()->with('error', 'Plafon Disetujui (Rp ' . number_format($plafon,0,',','.') . ') melebihi sisa anggaran (Rp ' . number_format($budget->sisa_anggaran,0,',','.') . ')!');
            }
            // Kurangi sisa anggaran, tambah terpakai
            $budget->update([
                'terpakai' => $budget->terpakai + $plafon,
                'sisa_anggaran' => $budget->sisa_anggaran - $plafon,
            ]);
        }

        $pengadaan->update([
            'status' => 'Disetujui',
            'total_estimasi' => $plafon, // The real constraint now
            'tanggal_approval' => Carbon::now(),
        ]);

        \App\Models\RekomendasiBuku::where('pengadaan_id', $pengadaan->id)->update([
            'status' => 'Disetujui Kepala Sekolah'
        ]);

        return back()->with('success', 'Pengajuan pengadaan disetujui dengan pagu Rp ' . number_format($plafon, 0, ',', '.') . '.');
    }

    // Role: Waka Kurikulum or Kepala Sekolah
    public function reject(Request $request, Pengadaan $pengadaan)
    {
         if (!auth()->user()->isKepalaSekolah() && !auth()->user()->isWakaAkademik()) {
            return back()->with('error', 'Tidak memiliki otorisasi.');
        }

        $pengadaan->update([
            'status' => 'Ditolak',
        ]);

        \App\Models\RekomendasiBuku::where('pengadaan_id', $pengadaan->id)->update([
            'status' => 'Ditolak'
        ]);

        return back()->with('success', 'Pengajuan pengadaan ditolak.');
    }

    public function receive(Request $request, Pengadaan $pengadaan)
    {
        if ($pengadaan->status !== 'Disetujui') {
            return back()->with('error', 'Hanya pengajuan yang telah disetujui yang dapat diterima.');
        }

        $pengadaan->load('details');
        
        DB::transaction(function () use ($pengadaan, $request) {
            foreach ($pengadaan->details as $detail) {
                // Determine missing book data
                // If it wasn't linked to existing book, create the book in catalog
                if (!$detail->buku_id) {
                    $buku = Buku::create([
                        'judul' => $detail->judul,
                        'pengarang' => $detail->penulis ?? 'Tidak Diketahui',
                        'penerbit' => $detail->penerbit ?? 'Tidak Diketahui',
                        'tahun_terbit' => Carbon::now()->year,
                        'stok' => 0,
                    ]);
                    $detail->update(['buku_id' => $buku->id]);
                } else {
                    $buku = Buku::find($detail->buku_id);
                }

                // Auto generate copies, barcodes, inventaris
                for ($i = 0; $i < $detail->jumlah; $i++) {
                    $inventarisNo = InventoryNumberService::generate();
                    $barcode = BarcodeService::generate($inventarisNo);

                    BookCopy::create([
                        'buku_id' => $buku->id,
                        'pengadaan_id' => $pengadaan->id,
                        'inventaris_no' => $inventarisNo,
                        'barcode' => $barcode,
                        'kondisi' => 'Baik',
                        'status' => 'Tersedia',
                    ]);
                }

                // Update catalog stok
                $buku->increment('stok', $detail->jumlah);
            }

            $pengadaan->update([
                'status' => 'Diterima',
                'tanggal_diterima' => Carbon::now(),
                'total_aktual' => $request->total_aktual ?? $pengadaan->total_estimasi,
                'faktur_no' => $request->faktur_no ?? $pengadaan->faktur_no,
                'faktur_tanggal' => $request->faktur_tanggal ?? Carbon::now(),
            ]);
        });

        return back()->with('success', 'Buku telah diterima dan dikatalogisasi otomatis!');
    }
}
