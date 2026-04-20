<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku';

    protected $fillable = [
        'judul',
        'pengarang',
        'penerbit',
        'tahun_terbit',
        'stok',
        'pdf_path',
        'cover_path', // Tambahkan ini
        'kategori_id',
    ];

    public function category()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }
}