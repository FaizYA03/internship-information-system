<?php

namespace App\Models\Lab;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Pengadaan extends Model
{
    use HasFactory;

    protected $table = 'pengadaan';

    protected $fillable = [
        'user_id',
        'nama_barang',
        'spesifikasi',
        'jumlah',
        'estimasi_harga',
        'link_referensi',
        'urgensi',
        'alasan',
        'status',
        'approved_by',
        'approved_at',
        'catatan_approval'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
