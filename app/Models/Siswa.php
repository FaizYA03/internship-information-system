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
    ];

    protected $appends = ['nama'];

    public function getNamaAttribute()
    {
        return $this->user ? $this->user->nama : '-';
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function courses()
    {
        return $this->belongsToMany(\App\Models\Course::class, 'course_siswa', 'siswa_id', 'course_id')->withTimestamps();
    }
}
