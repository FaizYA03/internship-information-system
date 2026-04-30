<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas',
        'id_kelas',
        'jurusan',
        'tahun_ajaran',
        'wali_kelas_id',
        'guru_bk_id',
        'ruangan',
    ];

    public function siswa()
    {
        return $this->hasMany(Siswa::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function waliKelas()
    {
        return $this->belongsTo(User::class, 'wali_kelas_id');
    }

    public function guruBK()
    {
        return $this->belongsTo(User::class, 'guru_bk_id');
    }

    
}