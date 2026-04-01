<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $table = 'laporan_kerusakan';

    protected $fillable = [
        'user_id',
        'inventaris_id',
        'nama_pelapor',
        'nama_alat',
        'deskripsi_kerusakan',
        'tingkat_kerusakan',
        'foto_bukti',
        'tanggal_laporan',
        'status_perbaikan',
        'tindakan_perbaikan',
        'status',
        'tanggapan',
        'is_eskalasi',
        'eskalasi_status'
    ];
}