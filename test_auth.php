<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

$creds = [
    ['email' => 'kalab@gmail.com', 'password' => 'password'],
    ['email' => 'kepsek@gmail.com', 'password' => 'password'],
    ['email' => 'waka@gmail.com', 'password' => 'password'],
];

foreach ($creds as $cred) {
    $user = User::where('email', $cred['email'])->first();
    if (!$user) {
        echo "User {$cred['email']} NOT FOUND\n";
        continue;
    }
    
    $check = Hash::check($cred['password'], $user->password);
    echo "User: {$user->email}\n";
    echo "  Role: {$user->role}\n";
    echo "  NIP: {$user->nis_nip}\n";
    echo "  Password Check ('{$cred['password']}'): " . ($check ? "SUCCESS" : "FAIL") . "\n";
    
    // Test Auth::attempt logic
    $loginField = 'email';
    $attempt = Auth::attempt([$loginField => $cred['email'], 'password' => $cred['password']]);
    echo "  Auth::attempt: " . ($attempt ? "SUCCESS" : "FAIL") . "\n";
}
