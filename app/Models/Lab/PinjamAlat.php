<?php

namespace App\Models\Lab;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Inventaris;

class PinjamAlat extends Model
{
    use HasFactory;

    protected $table = 'pinjam_alat';

    protected $fillable = [
        'user_id',
        'inventaris_id',
        'jumlah',
        'tanggal_pinjam',
        'tanggal_kembali',
        'jam_pinjam',
        'jam_kembali',
        'keperluan',
        'status',
        'approved_by',
        'approved_at',
        'kondisi_awal',
        'kondisi_akhir',
        'catatan'
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_RETURNED = 'returned';
    const STATUS_LATE = 'late';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Alias for user - more semantic
    public function peminjam()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function inventaris()
    {
        return $this->belongsTo(Inventaris::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
