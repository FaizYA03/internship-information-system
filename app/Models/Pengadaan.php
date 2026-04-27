<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengadaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'deskripsi',
        'status',
        'total_estimasi',
        'total_aktual',
        'tanggal_usulan',
        'tanggal_approval',
        'tanggal_diterima',
        'vendor_id',
        'faktur_no',
        'faktur_tanggal',
    ];

    public function details()
    {
        return $this->hasMany(PengadaanDetail::class, 'pengadaan_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function bookCopies()
    {
        return $this->hasMany(BookCopy::class, 'pengadaan_id');
    }
}
