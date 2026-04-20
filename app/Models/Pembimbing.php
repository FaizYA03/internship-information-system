<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembimbing extends Model
{
    protected $fillable = [
        'siswa_id',
        'guru_id',
        'magang_id',
        'status'
    ];

    protected $table = 'pembimbings';
    

   

    public function magang()
    {
        return $this->belongsTo(MagangOpening::class, 'magang_id');
    }


public function siswa()
{
    return $this->belongsTo(\App\Models\Siswa::class, 'siswa_id');
}

public function guru()
{
    return $this->belongsTo(\App\Models\Guru::class, 'guru_id');
}


    
}