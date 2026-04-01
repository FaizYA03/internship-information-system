<?php

namespace App\Models\Lab;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Inventaris;

class LaporanKerusakan extends Model
{
    use HasFactory;

    protected $table = 'laporan_kerusakan';

    protected $fillable = [
        'nama_pelapor',
        'user_id',
        'nama_alat',
        'inventaris_id',
        'deskripsi_kerusakan',
        'tingkat_kerusakan',
        'status_perbaikan',
        'tindakan_perbaikan',
        'teknisi_id',
        'foto_bukti',
        'tanggal_laporan',
        'is_eskalasi',
        'eskalasi_ke',
        'eskalasi_catatan',
        'eskalasi_tanggal',
        'eskalasi_status',
        'status', // Legacy compatibility
        'tanggapan' // Legacy compatibility
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reportedBy()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getReporterInfoAttribute()
    {
        if ($this->user) {
            $name = $this->user->nama;
            if ($this->user->role === 'siswa' && $this->user->siswa) {
                $siswa = $this->user->siswa;
                // Since 'kelas' is both a column and a relationship, Laravel usually returns the column value first if it exists as an attribute.
                $kelasVal = $siswa->getAttribute('kelas');
                if (empty($kelasVal) && $siswa->kelas_id) {
                    $kelasVal = $siswa->kelas->nama_kelas ?? '';
                }
                return $name . ($kelasVal ? " ({$kelasVal})" : "");
            }
            return $name;
        }
        return $this->nama_pelapor ?? '—';
    }

    public function inventaris()
    {
        return $this->belongsTo(Inventaris::class);
    }

    public function teknisi()
    {
        return $this->belongsTo(User::class, 'teknisi_id');
    }

    /**
     * Helper to determine damage severity based on condition
     */
    public static function determineTingkatKerusakan($kondisi)
    {
        if ($kondisi === 'Rusak Ringan') {
            return 'Ringan';
        } elseif ($kondisi === 'Rusak Sedang') {
            return 'Sedang';
        } elseif ($kondisi === 'Rusak Berat') {
            return 'Berat';
        }
        return 'Ringan'; // default
    }

    /**
     * Get badge color for tingkat kerusakan
     */
    public function getBadgeColorAttribute()
    {
        $colors = [
            'Ringan' => 'warning',
            'Sedang' => 'orange',
            'Berat' => 'danger'
        ];
        return $colors[$this->tingkat_kerusakan] ?? 'secondary';
    }
}
