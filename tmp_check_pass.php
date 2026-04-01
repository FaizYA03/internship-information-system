<?php
use Illuminate\Support\Facades\Hash;
use App\Models\User;

$emails = [
    'kepsek@gmail.com',
    'waka@gmail.com',
    'fikrisantri07@gmail.com',
    'kalab@gmail.com',
    'adminLab@gmail.com'
];

$common_passwords = ['password', 'admin', '12345678', '123456', '12345', '123456789', 'admin123', 'adminlab', 'kepsek', 'waka', 'guru', '123'];

$results = [];

foreach($emails as $email) {
    $user = User::where('email', $email)->first();
    if($user) {
        $found = false;
        foreach($common_passwords as $pwd) {
            if(Hash::check($pwd, $user->password)) {
                $results[$email] = $pwd;
                $found = true;
                break;
            }
        }
        if(!$found) {
            $results[$email] = 'UNKNOWN (Hash: '. substr($user->password, 0, 10) . '...)';
        }
    } else {
        $results[$email] = 'USER_NOT_FOUND';
    }
}

file_put_contents('tmp_check_pass.json', json_encode($results, JSON_PRETTY_PRINT));
