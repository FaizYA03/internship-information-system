<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MitraSupervisor extends Model
{
    use HasFactory;

    protected $table = 'mitra_supervisors';

    protected $fillable = [
        'wakil_perusahaan_id',
        'nama_lengkap',
        'nip',
        'jabatan',
        'departemen',
        'no_hp',
    ];

    public function wakilPerusahaan()
    {
        return $this->belongsTo(WakilPerusahaan::class);
    }
}
