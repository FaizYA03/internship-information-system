<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Lab\LaporanKerusakan;

class KerusakanAlatNotification extends Notification
{
    use Queueable;

    protected $laporanKerusakan;
    protected $tingkatKerusakan;

    /**
     * Create a new notification instance.
     */
    public function __construct(LaporanKerusakan $laporanKerusakan)
    {
        $this->laporanKerusakan = $laporanKerusakan;
        $this->tingkatKerusakan = $laporanKerusakan->inventaris->kondisi ?? 'Rusak';
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        // Use database for in-app notifications
        // Can add 'mail' if email notifications are needed
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $severity = $this->getSeverityText();
        
        return (new MailMessage)
            ->subject('Laporan Kerusakan Alat - ' . $severity)
            ->greeting('Halo, ' . $notifiable->nama)
            ->line('Terdapat laporan kerusakan alat di laboratorium.')
            ->line('**Alat:** ' . ($this->laporanKerusakan->inventaris->nama_inventaris ?? 'N/A'))
            ->line('**Laboratorium:** ' . ($this->laporanKerusakan->inventaris->labor->nama_labor ?? 'N/A'))
            ->line('**Tingkat Kerusakan:** ' . $this->tingkatKerusakan)
            ->line('**Deskripsi:** ' . $this->laporanKerusakan->deskripsi_kerusakan)
            ->line('**Dilaporkan oleh:** ' . ($this->laporanKerusakan->user->nama ?? $this->laporanKerusakan->nama_pelapor ?? 'Admin'))
            ->action('Lihat Detail', url('/lab/admin-new/kerusakan'))
            ->line('Terima kasih atas perhatian Anda.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'type' => 'kerusakan_alat',
            'laporan_id' => $this->laporanKerusakan->id,
            'inventaris_id' => $this->laporanKerusakan->inventaris_id,
            'nama_alat' => $this->laporanKerusakan->inventaris->nama_inventaris ?? 'N/A',
            'labor' => $this->laporanKerusakan->inventaris->labor->nama_labor ?? 'N/A',
            'tingkat_kerusakan' => $this->tingkatKerusakan,
            'deskripsi' => $this->laporanKerusakan->deskripsi_kerusakan,
            'pelapor' => $this->laporanKerusakan->user->nama ?? $this->laporanKerusakan->nama_pelapor ?? 'Admin',
            'tanggal_laporan' => $this->laporanKerusakan->tanggal_laporan,
            'url' => url('/lab/admin-new/kerusakan'),
        ];
    }

    /**
     * Get severity text based on damage level
     */
    private function getSeverityText(): string
    {
        $kondisi = strtolower($this->tingkatKerusakan);
        
        if (strpos($kondisi, 'berat') !== false) {
            return 'TINGGI';
        } elseif (strpos($kondisi, 'sedang') !== false) {
            return 'MENENGAH';
        } else {
            return 'RENDAH';
        }
    }
}
