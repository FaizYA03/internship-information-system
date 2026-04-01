<?php

namespace App\Services\Lab;

use App\Models\Lab\PinjamAlat;
use App\Models\Lab\PinjamEksternal;
use App\Models\Lab\Pengadaan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class BorrowingService
{
    /**
     * Setujui peminjaman alat internal (Siswa/Guru)
     */
    public function approveInternal(PinjamAlat $pinjamAlat, $notes = null)
    {
        if ($pinjamAlat->status !== 'pending') {
            throw new Exception("Status peminjaman tidak valid untuk disetujui (current: {$pinjamAlat->status})");
        }

        // Cek stok
        if ($pinjamAlat->inventaris->jumlah < $pinjamAlat->jumlah) {
            throw new Exception("Stok barang tidak mencukupi");
        }

        DB::transaction(function () use ($pinjamAlat, $notes) {
            $pinjamAlat->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'catatan' => $notes
            ]);
            
            // Observer handles stock decrement
        });

        return $pinjamAlat;
    }

    /**
     * Tolak peminjaman alat internal
     */
    public function rejectInternal(PinjamAlat $pinjamAlat, $reason)
    {
        if ($pinjamAlat->status !== 'pending') {
            throw new Exception("Status peminjaman tidak valid untuk ditolak");
        }

        $pinjamAlat->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(), // Rejected by who
            'approved_at' => now(),
            'catatan' => $reason
        ]);

        return $pinjamAlat;
    }

    /**
     * Kembalikan alat internal
     */
    public function returnInternal(PinjamAlat $pinjamAlat, $kondisiAkhir, $notes = null)
    {
        if ($pinjamAlat->status !== 'approved') {
            throw new Exception("Hanya peminjaman yang disetujui yang bisa dikembalikan");
        }

        DB::transaction(function () use ($pinjamAlat, $kondisiAkhir, $notes) {
            $pinjamAlat->update([
                'status' => 'returned',
                'kondisi_akhir' => $kondisiAkhir,
                'jam_kembali' => now()->format('H:i:s'),
                'tanggal_kembali' => now()->toDateString(), // Actual return date
                'catatan' => $notes
            ]);
            
            // Observer handles stock increment
        });

        return $pinjamAlat;
    }
}
