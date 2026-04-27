<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LibraryPolicy;
use App\Models\LibraryBudget;
use Carbon\Carbon;

class LibraryPolicyController extends Controller
{
    public function index()
    {
        $title = 'Policy & Budget Center';
        
        $policies = LibraryPolicy::pluck('value', 'key')->toArray();
        $budgetTahunIni = LibraryBudget::where('tahun', Carbon::now()->year)->first();
        
        if (!$budgetTahunIni) {
            $budgetTahunIni = LibraryBudget::create([
                'tahun' => Carbon::now()->year,
                'total_anggaran' => 0,
                'terpakai' => 0,
                'sisa_anggaran' => 0
            ]);
        }

        return view('perpustakaan.kepsek.policy.index', compact('title', 'policies', 'budgetTahunIni'));
    }

    public function updatePolicy(Request $request)
    {
        $request->validate([
            'max_borrow_days' => 'required|numeric',
            'fine_per_week' => 'required|numeric',
            'procurement_urgency' => 'required|string'
        ]);

        LibraryPolicy::setVal('max_borrow_days', $request->max_borrow_days, 'Batas Maksimal Hari Peminjaman');
        LibraryPolicy::setVal('fine_per_week', $request->fine_per_week, 'Nominal Denda Per Minggu Keterlambatan (Rp)');
        LibraryPolicy::setVal('procurement_urgency', $request->procurement_urgency, 'Tipe Urgensi Pengadaan Buku');

        return back()->with('success', 'Kebijakan perpustakaan berhasil diperbarui.');
    }

    public function updateBudget(Request $request)
    {
        $request->validate([
            'total_anggaran' => 'required|numeric|min:0'
        ]);

        $budget = LibraryBudget::where('tahun', Carbon::now()->year)->firstOrFail();
        
        $terpakai = $budget->terpakai;
        $sisa = $request->total_anggaran - $terpakai;
        
        $budget->update([
            'total_anggaran' => $request->total_anggaran,
            'sisa_anggaran' => $sisa
        ]);

        // Catat ke Audit Log
        \App\Models\AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'UPDATE_BUDGET',
            'model_type' => 'LibraryBudget',
            'model_id' => $budget->id,
            'description' => "Kepala Sekolah menyesuaikan pagu anggaran perpus tahun " . Carbon::now()->year,
            'changes' => json_encode(['new_total' => $request->total_anggaran]),
            'ip_address' => request()->ip()
        ]);

        return back()->with('success', 'Anggaran Perpustakaan tahun ini telah disesuaikan.');
    }
}
