<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    use HasFactory;

    protected $fillable = [
        'siswa_id', 'wakil_perusahaan_id',
        'hard_skill_1', 'hard_skill_2', 'hard_skill_3',
        'kewirausahaan',
        'soft_skill_1', 'soft_skill_2', 'soft_skill_3',
        'soft_skill_4', 'soft_skill_5', 'soft_skill_6', 'nilai_laporan', 'nilai_akhir'
    ];

    public function siswa()
{
    return $this->belongsTo(User::class, 'siswa_id');
}

public function wakilPerusahaan()
{
    return $this->belongsTo(WakilPerusahaan::class, 'wakil_perusahaan_id' ,'user_id');
}

// Penilaian.php
public function getAverage()
{
    $fields = [
        $this->hard_skill_1, $this->hard_skill_2, $this->hard_skill_3,
        $this->kewirausahaan,
        $this->soft_skill_1, $this->soft_skill_2, $this->soft_skill_3,
        $this->soft_skill_4, $this->soft_skill_5, $this->soft_skill_6
    ];

    $values = array_filter($fields, fn($val) => $val !== null);

    return count($values) > 0 ? round(array_sum($values) / count($values), 2) : 0;
}

// User.php atau Siswa.php (tergantung)
public function pendaftaranMagang()
{
    return $this->hasOne(MagangSiswa::class);
}


}
