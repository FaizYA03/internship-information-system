<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanJudul extends Model
{
    use HasFactory;

    protected $fillable = [
    'user_id',
    'wakil_perusahaan_id',
    'jurusan',
    'judul_laporan',
    'link_drive',
    'catatan',
    'alasan', // ✅ WAJIB ADA
    'status',
];


   

    public function wakilPerusahaan()
    {
        return $this->belongsTo(WakilPerusahaan::class);
    }

    public function user()
{
    return $this->belongsTo(\App\Models\User::class, 'user_id');
}


}
