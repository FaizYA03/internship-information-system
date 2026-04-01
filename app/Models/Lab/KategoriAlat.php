<?php

namespace App\Models\Lab;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriAlat extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'deskripsi'];
}
