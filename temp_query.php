<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $constraints = DB::select("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_NAME = 'pinjam_labor' AND COLUMN_NAME = 'laboratorium_id'");
    var_dump($constraints);
} catch (\Exception $e) {
    echo $e->getMessage();
}
