<?php

namespace App\Models\Lab;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Inventaris;
use App\Models\User;

class PinjamEksternal extends Model
{
    use HasFactory;

    protected $table = 'pinjam_eksternal';

    protected $fillable = [
        'nama_peminjam',
        'instansi',
        'kontak',
        'inventaris_id',
        'jumlah',
        'tanggal_pinjam',
        'tanggal_kembali',
        'keperluan',
        'status',
        'rekomendasi_kalab_by',
        'rekomendasi_kalab_at',
        'approved_kepsek_by',
        'approved_kepsek_at',
        'surat_permohonan'
    ];

    public function inventaris()
    {
        return $this->belongsTo(Inventaris::class);
    }

    public function rekomendasiBy()
    {
        return $this->belongsTo(User::class, 'rekomendasi_kalab_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_kepsek_by');
    }
}
