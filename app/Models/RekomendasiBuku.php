<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekomendasiBuku extends Model
{
    protected $table = 'rekomendasi_buku';

    protected $fillable = [
        'judul_buku',
        'pengarang',
        'penerbit',
        'mapel_id',
        'jurusan_id',
        'prioritas',
        'alasan',
        'status',
        'waka_id',
        'pengadaan_id'
    ];

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }

    public function waka()
    {
        return $this->belongsTo(User::class, 'waka_id');
    }

    public function pengadaan()
    {
        return $this->belongsTo(Pengadaan::class);
    }
}
