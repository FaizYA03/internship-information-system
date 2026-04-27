<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengadaanDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengadaan_id',
        'buku_id',
        'judul',
        'penulis',
        'penerbit',
        'isbn',
        'jumlah',
        'harga_per_unit',
        'subtotal',
    ];

    public function pengadaan()
    {
        return $this->belongsTo(Pengadaan::class, 'pengadaan_id');
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }
}
