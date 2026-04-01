<?php

namespace App\Models\Lab;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisLaboratorium extends Model
{
    use HasFactory;

    protected $table = 'jenis_laboratoria';

    protected $fillable = ['nama', 'deskripsi', 'ikon', 'warna'];

    /**
     * Relasi ke laboratorium yang menggunakan jenis ini
     */
    public function laboratorium()
    {
        return $this->hasMany(\App\Models\Labor::class, 'jenis_labor', 'nama');
    }

    /**
     * Warna Bootstrap badge
     */
    public function getWarnaBootstrapAttribute(): string
    {
        $map = [
            'primary'   => 'bg-primary',
            'danger'    => 'bg-danger',
            'warning'   => 'bg-warning text-dark',
            'success'   => 'bg-success',
            'purple'    => 'bg-purple',
            'secondary' => 'bg-secondary',
            'info'      => 'bg-info text-dark',
        ];
        return $map[$this->warna] ?? 'bg-secondary';
    }

    /**
     * Warna hex untuk background soft
     */
    public function getBgSoftAttribute(): string
    {
        $map = [
            'primary'   => '#e8efff',
            'danger'    => '#ffeef0',
            'warning'   => '#fff4e8',
            'success'   => '#e8f8f6',
            'purple'    => '#f5e8ff',
            'secondary' => '#f0f0f0',
            'info'      => '#e8f7ff',
        ];
        return $map[$this->warna] ?? '#f0f0f0';
    }

    /**
     * Warna teks sesuai jenis
     */
    public function getColorHexAttribute(): string
    {
        $map = [
            'primary'   => '#4361ee',
            'danger'    => '#e63946',
            'warning'   => '#f4a261',
            'success'   => '#2a9d8f',
            'purple'    => '#7b2d8b',
            'secondary' => '#6c757d',
            'info'      => '#0dcaf0',
        ];
        return $map[$this->warna] ?? '#6c757d';
    }

}
