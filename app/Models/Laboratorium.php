<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Laboratorium extends Model
{
    use HasFactory;

    protected $table = 'laboratorium';

    protected $fillable = [
        'labor',
        'status',
        'start',
        'end',
        'penanggung_jawab',
        'teknisi',
        'keterangan',
        'foto'
    ];

    protected $dates = [
        'start',
        'end'
    ];

    public function inventaris()
    {
        return $this->hasMany(\App\Models\Inventaris::class, 'labor_id');
    }

}