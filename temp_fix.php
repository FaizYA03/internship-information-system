<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    DB::statement("ALTER TABLE pinjam_labor DROP FOREIGN KEY pinjam_labor_laboratorium_id_foreign");
    echo "FK Dropped. ";
} catch (\Exception $e) {
    echo "FK Drop Error: " . $e->getMessage() . " ";
}

try {
    DB::statement("ALTER TABLE pinjam_labor MODIFY laboratorium_id bigint unsigned null");
    echo "Column Modified.";
} catch (\Exception $e) {
    echo "Column Modify Error: " . $e->getMessage();
}
