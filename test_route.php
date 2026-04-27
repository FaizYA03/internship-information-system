<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$u = App\Models\User::where('role', 'siswa')->first();
Auth::login($u);

$req = Illuminate\Http\Request::create('/magang/magang/create', 'GET');
$res = app()->handle($req);

echo "Status Code: " . $res->getStatusCode() . "\n";
echo "Location: " . $res->headers->get('Location') . "\n";
