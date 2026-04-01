<?php

// Fix User Khairul Fikri to become TAV student
$u = App\Models\User::where('email', 'khairulfikri730@gmail.com')->first();
if($u){
    $s = App\Models\Siswa::where('user_id', $u->id)->first();
    if($s){
        // Find Class (TAV)
        $k = App\Models\Kelas::where('nama_kelas', 'like', '%X%')
             ->where('jurusan', 'like', '%Audio%')
             ->first();
        
        if($k){
            $s->kelas_id = $k->id;
            $s->kelas = 'X'; 
            $s->jurusan = $k->jurusan;
            $s->save();
            echo "SUCCESS: Student Khairul Fikri updated to {$k->nama_kelas} - {$k->jurusan}.\n";
        } else {
             // If ID not found, update strings anyway so UI looks correct
             $s->kelas = 'X';
             $s->jurusan = 'Teknik Audio Video';
             $s->save();
             echo "PARTIAL SUCCESS: Updated string fields, but TAV Kelas model not found in DB.\n";
        }
    }
} else {
    echo "ERROR: User khairulfikri730@gmail.com not found.\n";
}
