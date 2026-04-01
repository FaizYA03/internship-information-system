<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PinjamLabor extends Model
{
    use HasFactory;

    protected $table = 'pinjam_labor';
    
    protected $fillable = [
        'nama',
        'user_id',
        'laboratorium_id',
        'labor_id',
        'keperluan',
        'tanggal',
        'tanggal_kembali',
        'waktu',
        'jam_pinjam',
        'jam_kembali',
        'kelas',
        'status',
        'approved_by',
        'approved_at',
        'alasan_penolakan'
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_COMPLETED = 'completed';

    protected $dates = [
        'tanggal',
        'approved_at',
        'created_at',
        'updated_at'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function labor()
    {
        return $this->belongsTo(Labor::class, 'labor_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
