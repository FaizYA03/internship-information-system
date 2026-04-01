<?php
$users = App\Models\User::where('nama', 'like', '%teknisi%')->orWhere('role', 'like', '%teknisi%')->get();
$res = [];
foreach($users as $u) {
    if($u) {
        $res[] = ['nama' => $u->nama, 'email' => $u->email, 'role' => $u->role];
    }
}
file_put_contents('tmp_teknisi.json', json_encode($res, JSON_PRETTY_PRINT));
