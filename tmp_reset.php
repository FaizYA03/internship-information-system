<?php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

$user = User::where('email', 'fikrisantri07@gmail.com')->first();
if($user) {
    $user->password = Hash::make('password');
    $user->save();
    echo "Fikri password reset to 'password'";
}
