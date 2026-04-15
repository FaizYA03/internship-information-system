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

    protected $table = 'pembimbing';

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function magang()
    {
        return $this->belongsTo(MagangOpening::class, 'magang_id');
    }
}