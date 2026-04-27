<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BukuKurikulum extends Model
{
    protected $table = 'buku_kurikulum';
    
    protected $fillable = [
        'buku_id',
        'mapel_id',
        'jurusan_id',
        'kompetensi_dasar'
    ];

    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }
}
