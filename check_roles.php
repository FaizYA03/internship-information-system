<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$roles = ['kepala_lab', 'kepala_sekolah', 'waka_akademik', 'waka_kurikulum', 'admin_lab', 'super_admin'];
foreach ($roles as $role) {
    $users = User::where('role', $role)->get(['nama', 'email']);
    echo "Role: $role\n";
    if ($users->isEmpty()) {
        echo "  (None found)\n";
    } else {
        foreach ($users as $user) {
            echo "  - {$user->nama} ({$user->email})\n";
        }
    }
}
