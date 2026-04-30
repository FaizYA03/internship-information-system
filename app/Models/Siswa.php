<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';

    protected $fillable = [
        'user_id',
        'nis',
        'kelas',
        'jurusan',
        'tanggal_lahir',
        'alamat',
        'no_hp',
        'kelas_id',
        'status_siswa',
        'tahun_masuk',
        'foto',
        'jenis_kelamin',
        'agama',
    ];

    protected $appends = ['nama'];

    public function getNamaAttribute()
    {
        return $this->user ? $this->user->nama : '-';
    }

    public function dataKelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }


    public function courses()
    {
        return $this->belongsToMany(\App\Models\Course::class, 'course_siswa', 'siswa_id', 'course_id')->withTimestamps();
    }

  

    public function magangSiswa()
    {
        return $this->hasOne(MagangSiswa::class, 'user_id', 'user_id');
    }


    public function user()
{
    return $this->belongsTo(\App\Models\User::class, 'user_id');
}
    public function pembimbing()
{
    return $this->hasOne(\App\Models\Pembimbing::class, 'siswa_id');
}
   


}
