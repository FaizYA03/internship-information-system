<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Labor extends Model
{
    use HasFactory;
    
    protected $table = 'labor';
    
    protected $fillable = [
        'nama_labor',
        'kode',
        'jenis_labor',
        'kapasitas',
        'penanggung_jawab',
        'teknisi',
        'deskripsi',
        'fasilitas',
        'foto',
        'status_penggunaan',
        'lokasi'
    ];
    
    // Relationships
    public function jenisData()
    {
        return $this->belongsTo(\App\Models\Lab\JenisLaboratorium::class, 'jenis_labor', 'nama');
    }

    public function jadwal()
    {
        return $this->hasMany(Laboratorium::class, 'labor', 'kode');
    }

    public function penanggungJawabUser()
    {
        return $this->belongsTo(User::class, 'penanggung_jawab');
    }

    public function teknisiUser()
    {
        return $this->belongsTo(User::class, 'teknisi');
    }

    public function inventaris()
    {
        return $this->hasMany(Inventaris::class, 'labor_id');
    }

    public function jadwalPenggunaan()
    {
        return $this->hasMany(Laboratorium::class, 'labor', 'kode')
            ->whereDate('start', '>=', Carbon::today());
    }

    public function peminjamanRuangan()
    {
        return $this->hasMany(PinjamLabor::class, 'labor_id');
    }

    public function jadwalTetap()
    {
        return $this->hasMany(\App\Models\Lab\JadwalLaboratorium::class, 'labor_id');
    }

    // Helper Methods
    public function getCurrentStatus()
    {
        // Check if there's an ongoing schedule
        $now = Carbon::now();
        $ongoingSchedule = $this->jadwal()
            ->where('start', '<=', $now)
            ->where('end', '>=', $now)
            ->exists();

        if ($ongoingSchedule) {
            return 'digunakan';
        }

        return $this->status_penggunaan ?? 'kosong';
    }

    public function getJumlahAlatAttribute()
    {
        return $this->inventaris()->count();
    }

    public function getJumlahAlatTersediaAttribute()
    {
        return $this->inventaris()
            ->where('status', 'tersedia')
            ->where(function($query) {
                $query->whereIn('kondisi', ['Sangat Baik', 'Baik'])
                    ->orWhere('jenis', 'Bahan');
            })
            ->count();
    }
}
