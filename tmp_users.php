<?php
$users = App\Models\User::all();
$output = '';
foreach($users as $u) {
    // Assuming the table might have 'role' column or something similar. Let's dump the attributes to be safe.
    $output .= json_encode($u->getAttributes()) . "\n";
}
file_put_contents('tmp_users_data.txt', $output);
