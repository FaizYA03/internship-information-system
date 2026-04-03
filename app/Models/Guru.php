<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'guru';

    protected $fillable = [
        'user_id',
        'nip',
        'tanggal_lahir',
        'alamat',
        'no_hp',
        'jurusan_id',
        'status',
    ];

    protected $appends = ['nama'];

    public function getNamaAttribute()
    {
        return $this->user ? $this->user->nama : '-';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id');
    }

    public function kelasWali()
    {
        return $this->hasOne(Kelas::class, 'wali_kelas_id', 'user_id');
    }

    public function mapels()
    {
        return $this->hasMany(MataPelajaran::class, 'guru_id');
    }
}
