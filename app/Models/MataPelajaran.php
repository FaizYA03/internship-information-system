<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    use HasFactory;

    protected $table = 'mata_pelajaran';

    protected $fillable = [
        'guru_id',
        'nama_mata_pelajaran',
    ];

    // Relations
    public function guru()
    {
        return $this->belongsTo(\App\Models\Guru::class, 'guru_id');
    }

    public function courses()
    {
        return $this->hasMany(\App\Models\Course::class, 'mata_pelajaran_id');
    }
}
