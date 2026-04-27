<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstruksiKepalaSekolah extends Model
{
    use HasFactory;

    protected $table = 'instruksi_kepala_sekolah';
    protected $primaryKey = 'id_instruksi';
    public $timestamps = false;
    
    protected $fillable = [
        'id_buku',
        'jenis_tindakan',
        'catatan',
        'tujuan',
        'status',
        'hasil_evaluasi',
        'created_at'
    ];

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'id_buku', 'id');
    }
}
