<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Lab\LaporanKerusakan;

class AlatDiperbaikiNotification extends Notification
{
    use Queueable;

    protected $laporanKerusakan;

    /**
     * Create a new notification instance.
     */
    public function __construct(LaporanKerusakan $laporanKerusakan)
    {
        $this->laporanKerusakan = $laporanKerusakan;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'type' => 'alat_diperbaiki',
            'laporan_id' => $this->laporanKerusakan->id,
            'inventaris_id' => $this->laporanKerusakan->inventaris_id,
            'nama_alat' => $this->laporanKerusakan->inventaris?->nama_inventaris ?? $this->laporanKerusakan->nama_alat ?? 'N/A',
            'labor' => $this->laporanKerusakan->inventaris?->labor?->nama_labor ?? 'N/A',
            'status' => 'Selesai Diperbaiki',
            'tindakan' => $this->laporanKerusakan->tindakan_perbaikan,
            'url' => $this->laporanKerusakan->user_id && $this->laporanKerusakan->user?->role == 'siswa' 
                    ? url('/siswa/perbaikan-selesai') 
                    : url('/lab/admin-new/perbaikan-selesai'),
        ];
    }
}
