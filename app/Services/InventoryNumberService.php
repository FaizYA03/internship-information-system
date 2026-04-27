<?php

namespace App\Services;

use App\Models\BookCopy;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InventoryNumberService
{
    /**
     * Generate unique inventory number.
     * Format: INV-YYYYMMDD-XXXX
     */
    public static function generate(): string
    {
        return DB::transaction(function () {
            $prefix = 'INV-' . Carbon::now()->format('Ymd') . '-';
            
            // Get the last number used today
            $lastCopy = BookCopy::where('inventaris_no', 'like', $prefix . '%')
                        ->orderBy('inventaris_no', 'desc')
                        ->lockForUpdate()
                        ->first();
                        
            if ($lastCopy) {
                // Extract last 4 numbers
                $lastNumber = (int) substr($lastCopy->inventaris_no, -4);
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }
            
            return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        });
    }
}
