<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventaris extends Model
{
    use HasFactory;

    protected $table = 'inventaris';

    protected $fillable = [
        'kode_inventaris',
        'nama_inventaris',
        'jenis', // Alat, Bahan
        'kategori', // Kategori string lama (mungkin bisa dipetakan ke kategori_id nanti)
        'labor_id',
        'jumlah',
        'stok_minimum',
        'kondisi',
        'lokasi',
        'tanggal_pengadaan',
        'deskripsi',
        'spesifikasi',
        'sumber_dana',
        'tahun_perolehan',
        'status',
        'gambar',
    ];

    // Scopes
    public function scopeAlat($query)
    {
        return $query->where('jenis', 'Alat');
    }

    public function scopeBahan($query)
    {
        return $query->where('jenis', 'Bahan');
    }

    // Relationships
    public function labor()
    {
        return $this->belongsTo(\App\Models\Labor::class, 'labor_id');
    }

    public function peminjaman()
    {
        return $this->hasMany(\App\Models\Lab\PinjamAlat::class);
    }

    public function usageHistory()
    {
        return $this->hasMany(\App\Models\Lab\PinjamAlat::class, 'inventaris_id');
    }

    public function damageHistory()
    {
        return $this->hasMany(\App\Models\Lab\LaporanKerusakan::class, 'inventaris_id');
    }

    public function activityLogs()
    {
        return $this->morphMany(\App\Models\Lab\ActivityLog::class, 'subject');
    }

    // Define custom date fields if needed
    protected $dates = [
        'tanggal_pengadaan',
        'created_at',
        'updated_at'
    ];

    // Helper Methods
    public function isRusak()
    {
        return in_array($this->kondisi, ['Rusak Ringan', 'Rusak Sedang', 'Rusak Berat']);
    }

    public function getTingkatKerusakanColor()
    {
        $colorMap = [
            'Sangat Baik' => 'success',
            'Baik' => 'info',
            'Rusak Ringan' => 'warning',
            'Rusak Sedang' => 'orange',
            'Rusak Berat' => 'danger',
        ];

        return $colorMap[$this->kondisi] ?? 'secondary';
    }

    public function getTingkatKerusakanBadgeClass()
    {
        $classMap = [
            'Sangat Baik' => 'bg-success',
            'Baik' => 'bg-info',
            'Rusak Ringan' => 'bg-warning',
            'Rusak Sedang' => 'bg-orange',
            'Rusak Berat' => 'bg-danger',
        ];

        return $classMap[$this->kondisi] ?? 'bg-secondary';
    }

    public function scopeRusak($query)
    {
        return $query->whereIn('kondisi', ['Rusak Ringan', 'Rusak Sedang', 'Rusak Berat']);
    }

    public function scopeBaik($query)
    {
        return $query->whereIn('kondisi', ['Sangat Baik', 'Baik']);
    }

    /**
     * Generate unique inventory code
     */
    public static function generateKodeInventaris()
    {
        $prefix = 'INV';
        $year = date('Y');
        $lastInventory = self::where('kode_inventaris', 'like', $prefix . $year . '%')
            ->orderBy('kode_inventaris', 'desc')
            ->first();
        
        if ($lastInventory) {
            $lastNumber = (int) substr($lastInventory->kode_inventaris, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . $year . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get full history (usage + damage + condition changes)
     */
    public function getFullHistoryAttribute()
    {
        return [
            'usage' => $this->usageHistory,
            'damage' => $this->damageHistory,
            'activities' => $this->activityLogs
        ];
    }
}