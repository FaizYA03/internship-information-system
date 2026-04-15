<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MagangSiswa extends Model
{
    use HasFactory;

    protected $table = 'magang_siswa';

    protected $fillable = [
        'nama',
        'email',
        'no_hp',
        'perusahaan_id',
        'opening_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'user_id',
        'catatan',
    ];

    // Relasi ke user (siswa)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke perusahaan (wakil_perusahaan)
    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class);
    }

    // Relasi ke opening lowongan magang
    public function opening()
    {
        return $this->belongsTo(MagangOpening::class, 'opening_id');
    }

    // Laporan magang
    public function laporans()
    {
        return $this->hasMany(MagangLaporan::class, 'magang_siswa_id');
    }

    public function wakilPerusahaan()
{
    return $this->belongsTo(\App\Models\WakilPerusahaan::class, 'perusahaan_id');
}

public function openingmagang()
{
    return $this->belongsTo(MagangOpening::class, 'magang_opening_id');
}

public function pembimbing()
{
    return $this->hasOne(\App\Models\Pembimbing::class, 'magang_id');
}

}
