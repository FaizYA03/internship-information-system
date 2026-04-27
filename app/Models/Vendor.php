<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'alamat',
        'kontak',
        'email',
        'telepon',
    ];

    public function pengadaans()
    {
        return $this->hasMany(Pengadaan::class, 'vendor_id');
    }
}
