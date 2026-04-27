<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibraryBudget extends Model
{
    protected $fillable = [
        'tahun',
        'total_anggaran',
        'terpakai',
        'sisa_anggaran',
        'catatan',
    ];
}
