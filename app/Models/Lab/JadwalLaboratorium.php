<?php

namespace App\Models\Lab;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Labor;
use App\Models\User;

class JadwalLaboratorium extends Model
{
    use HasFactory;

    protected $table = 'jadwal_laboratorium';

    protected $fillable = [
        'course_id',
        'labor_id',
        'mata_pelajaran',
        'guru_id',
        'kelas_id',
        'kelas',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'keterangan'
    ];

    // Relationships
    public function labor()
    {
        return $this->belongsTo(Labor::class, 'labor_id');
    }

    public function laboratorium()
    {
        return $this->labor();
    }

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function kelas_relation()
    {
        return $this->belongsTo(\App\Models\Kelas::class, 'kelas_id');
    }

    // Helper Methods
    /**
     * Check if there's a schedule conflict
     */
    public static function checkConflict($labor_id, $hari, $jam_mulai, $jam_selesai, $exclude_id = null)
    {
        $query = self::where('labor_id', $labor_id)
            ->where('hari', $hari)
            ->where(function($q) use ($jam_mulai, $jam_selesai) {
                // Check if new schedule overlaps with existing schedules
                $q->where(function($query) use ($jam_mulai, $jam_selesai) {
                    $query->whereBetween('jam_mulai', [$jam_mulai, $jam_selesai])
                        ->orWhereBetween('jam_selesai', [$jam_mulai, $jam_selesai])
                        ->orWhere(function($q) use ($jam_mulai, $jam_selesai) {
                            $q->where('jam_mulai', '<=', $jam_mulai)
                              ->where('jam_selesai', '>=', $jam_selesai);
                        });
                });
            });

        if ($exclude_id) {
            $query->where('id', '!=', $exclude_id);
        }

        return $query->exists();
    }

    /**
     * Get formatted time range
     */
    public function getTimeRangeAttribute()
    {
        return $this->jam_mulai . ' - ' . $this->jam_selesai;
    }
}
