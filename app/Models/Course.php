<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'kelas_id',
        'mata_pelajaran_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'ruangan',
        'labor_id'
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }
    
    public function siswa()
    {
        return $this->belongsToMany(Siswa::class, 'course_siswa', 'course_id', 'siswa_id')
                    ->withTimestamps();
    }
}