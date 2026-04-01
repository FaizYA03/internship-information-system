<?php

namespace App\Models\Lab;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SumberAset extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'deskripsi'];
}
