<?php

namespace App\Services;

class BarcodeService
{
    /**
     * Generate barcode value.
     * Since we are using CODE-128, the barcode value can be alphanumeric.
     * For simplicity and consistency, we can use the Inventory Number as the barcode value.
     * This service is a placeholder in case we need to generate complex barcodes or images later.
     */
    public static function generate(string $inventarisNo): string
    {
        // For barcode value, we just use the inventaris number
        return $inventarisNo;
    }
}
