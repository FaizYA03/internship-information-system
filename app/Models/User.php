<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\AdminProfile;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'menu',
        'nis_nip',
    ];

    public function getNameAttribute()
    {
        return $this->attributes['nama'];
    }

    public function setNameAttribute($value)
    {
        $this->attributes['nama'] = $value;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function laboratorium()
    {
        return $this->hasMany(Laboratorium::class);
    }

    public function mata_pelajaran()
    {
        return $this->hasMany(MataPelajaran::class);
    }

    public function guru()
    {
        return $this->hasOne(Guru::class);
    }


    public function adminProfile()
    {
        return $this->hasOne(AdminProfile::class, 'user_id');
    }

    public function magangSiswa()
    {
        return $this->hasMany(\App\Models\MagangSiswa::class, 'user_id');
    }
    public function magang()
    {
        return $this->hasMany(\App\Models\MagangOpening::class, 'siswa_id');
    }

    public function wakilPerusahaan()
    {
        return $this->hasOne(WakilPerusahaan::class);
    }

    public function magangssiswa()
    {
        return $this->hasOne(\App\Models\MagangSiswa::class, 'user_id');
    }

    public function magangreports()
    {
        return $this->hasOne(MagangSiswa::class, 'user_id');
    }

    public function peminatan()
    {
        return $this->hasOne(Peminatan::class, 'user_id');
    }

    // --- Lab System Roles & Helpers ---
    public function isSuperAdmin() { return $this->role === 'super_admin'; }
    public function isAdminLab() { return $this->role === 'admin_lab' || $this->role === 'super_admin'; }
    public function isKepalaLab() { return $this->role === 'kepala_lab'; }
    public function isKepalaSekolah() { return $this->role === 'kepala_sekolah'; }
    public function isWakaAkademik() { return $this->role === 'waka_akademik'; }
    public function isGuru() { return $this->role === 'guru'; }
    public function isSiswa() { return $this->role === 'siswa'; }

    // --- Lab System Relationships ---
    public function pinjamAlat()
    {
        return $this->hasMany(\App\Models\Lab\PinjamAlat::class);
    }

    public function pengadaan()
    {
        return $this->hasMany(\App\Models\Lab\Pengadaan::class);
    }

    public function pembimbing()
    {
        return $this->hasOne(\App\Models\Pembimbing::class, 'siswa_id');
    }


public function siswa()
{
    return $this->hasOne(Siswa::class, 'user_id');
}

}
