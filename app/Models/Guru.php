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
        'kelas',
        'jurusan',
        'tanggal_lahir',
        'alamat',
        'no_hp',
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
}
