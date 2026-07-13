<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MagangLaporan extends Model
{
    use HasFactory;

    protected $fillable = [
        'magang_siswa_id',
        'judul',
        'deskripsi',
        'minggu_ke',
        'tanggal_mulai',
        'status',
        'komentar',
        'is_read_by_siswa'
    ];

    public function magangSiswa()
    {
        return $this->belongsTo(MagangSiswa::class, 'magang_siswa_id');
    }
}