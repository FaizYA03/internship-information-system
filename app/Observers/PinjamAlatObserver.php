<?php

namespace App\Observers;

use App\Models\Lab\PinjamAlat;
use App\Models\Inventaris;
use App\Models\Lab\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class PinjamAlatObserver
{
    /**
     * Handle the PinjamAlat "created" event.
     */
    public function created(PinjamAlat $pinjamAlat)
    {
        $this->logActivity($pinjamAlat, 'created', 'Mengajukan peminjaman alat');
    }

    /**
     * Handle the PinjamAlat "updated" event.
     */
    public function updated(PinjamAlat $pinjamAlat)
    {
        // Status change logic
        if ($pinjamAlat->isDirty('status')) {
            $newStatus = $pinjamAlat->status;
            $oldStatus = $pinjamAlat->getOriginal('status');
            
            $inventaris = $pinjamAlat->inventaris;
            
            // Logika Stok
            if ($inventaris) {
                // Saat disetujui, kurangi stok
                if ($newStatus === 'approved' && $oldStatus === 'pending') {
                    $inventaris->decrement('jumlah', $pinjamAlat->jumlah);
                    $this->logActivity($pinjamAlat, 'approved', 'Peminjaman disetujui, stok dikurangi');
                } 
                // Saat dikembalikan, kembalikan stok
                elseif ($newStatus === 'returned' && $oldStatus !== 'returned') {
                    $inventaris->increment('jumlah', $pinjamAlat->jumlah);
                    $this->logActivity($pinjamAlat, 'returned', 'Alat dikembalikan, stok ditambah');
                }
                // Jika dibatalkan/ditolak dari status approved (misal salah approve), kembalikan stok
                elseif (($newStatus === 'rejected' || $newStatus === 'pending') && $oldStatus === 'approved') {
                    $inventaris->increment('jumlah', $pinjamAlat->jumlah);
                }
            }
            
            // Log status change
            if ($newStatus !== $oldStatus) {
                $this->logActivity($pinjamAlat, 'status_changed', "Status berubah dari $oldStatus ke $newStatus");
            }
        }
    }

    protected function logActivity($model, $action, $description)
    {
        if (!Auth::check()) return;

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'description' => $description,
            'subject_type' => get_class($model),
            'subject_id' => $model->id,
            'properties' => $action === 'status_changed' ? [
                'old' => $model->getOriginal('status'),
                'new' => $model->status
            ] : null,
            'ip_address' => Request::ip()
        ]);
    }
}
