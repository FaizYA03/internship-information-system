<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WakilPerusahaan extends Model
{
    use HasFactory;
    
    protected $table = 'wakil_perusahaan';
    
    protected $fillable = [
        'user_id',
        'nama',
        'email',
        'no_perusahaan',
        'alamat',
        'nama_perusahaan',
        'bukti_lampiran',
        'password',
        'status'
    ];
    
    // Hash password when setting
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function siswaMagang()
{
    return $this->hasMany(\App\Models\MagangSiswa::class, 'perusahaan_id');
}

// WakilPerusahaan.php

public function user()
{
    return $this->belongsTo(User::class);
}

public function supervisors()
{
    return $this->hasMany(MitraSupervisor::class, 'wakil_perusahaan_id');
}




}