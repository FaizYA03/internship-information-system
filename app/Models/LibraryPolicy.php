<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibraryPolicy extends Model
{
    protected $fillable = [
        'key',
        'value',
        'description',
    ];

    public static function getVal($key, $default = null)
    {
        $policy = self::where('key', $key)->first();
        return $policy ? $policy->value : $default;
    }

    public static function setVal($key, $value, $description = null)
    {
        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'description' => $description]
        );
    }
}
