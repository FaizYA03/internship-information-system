<?php

namespace App\Models\Lab;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusKondisi extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'deskripsi', 'warna'];
}
