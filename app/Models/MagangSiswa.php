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

    /*
    |--------------------------------------------------------------------------
    | 🔥 STATUS CONSTANT (ANTI TYPO)
    |--------------------------------------------------------------------------
    */
    const STATUS_MENUNGGU        = 'Menunggu';
    const STATUS_MITRA           = 'Disetujui Mitra';
    const STATUS_ADMIN           = 'Disetujui Admin';
    const STATUS_DITOLAK         = 'Ditolak';

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIP
    |--------------------------------------------------------------------------
    */

    // User (Siswa)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Perusahaan
    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'perusahaan_id');
    }

    // Wakil perusahaan
    public function wakilPerusahaan()
    {
        return $this->belongsTo(\App\Models\WakilPerusahaan::class, 'perusahaan_id');
    }

    // Opening magang
    public function opening()
    {
        return $this->belongsTo(MagangOpening::class, 'opening_id');
    }

    // Pembimbing
    public function pembimbing()
    {
        return $this->hasOne(\App\Models\Pembimbing::class, 'magang_id');
    }

    // Laporan harian
    public function laporans()
    {
        return $this->hasMany(MagangLaporan::class, 'magang_siswa_id');
    }

    /*
    |--------------------------------------------------------------------------
    | 🔥 HELPER STATUS (SUPER PENTING)
    |--------------------------------------------------------------------------
    */

    public function isMenunggu()
    {
        return $this->status === self::STATUS_MENUNGGU;
    }

    public function isDisetujuiMitra()
    {
        return $this->status === self::STATUS_MITRA;
    }

    public function isDisetujuiAdmin()
    {
        return $this->status === self::STATUS_ADMIN;
    }

    public function isDitolak()
    {
        return $this->status === self::STATUS_DITOLAK;
    }
}