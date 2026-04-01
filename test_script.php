<?php

// Find User Khairul Fikri
$u = App\Models\User::where('email', 'khairulfikri730@gmail.com')->first();
if($u){
    echo 'User found: ' . $u->id . "\n";
    $s = App\Models\Siswa::where('user_id', $u->id)->first();
    echo 'Siswa found: ' . ($s ? $s->id : 'none') . "\n";
    
    // Find Class X Audio Video
    $k = App\Models\Kelas::where('nama_kelas', 'like', '%X%')
         ->where('jurusan', 'like', '%Audio%')
         ->first();
    echo 'Kelas found: ' . ($k ? $k->nama_kelas . ' - ' . $k->jurusan : 'none') . "\n";
    
    // Assign student to class
    if($s && $k){
        $s->kelas_id = $k->id;
        $s->save();
        echo 'Siswa updated successfully to ' . $k->id . ".\n";
    }
    
    // Find Teacher Fikri S.Pd M.Kom
    $guru_fikri = App\Models\User::where('nama', 'like', '%fikri%')
                  ->where('role', 'guru')->first();
    echo 'Guru found: ' . ($guru_fikri ? $guru_fikri->nama : 'none') . "\n";
    
    // Assign a schedule on Thursday for Fikri teaching this class
    if ($guru_fikri && $k) {
        $lab = App\Models\Labor::first();
        if ($lab) {
            $jadwal = App\Models\Lab\JadwalLaboratorium::where('kelas_id', $k->id)
                ->where('guru_id', $guru_fikri->id)
                ->where('hari', 'Kamis')
                ->first();
            
            if (!$jadwal) {
                 App\Models\Lab\JadwalLaboratorium::create([
                    'labor_id' => $lab->id,
                    'mata_pelajaran' => 'Teknik Audio Video',
                    'guru_id' => $guru_fikri->id,
                    'kelas_id' => $k->id,
                    'kelas' => $k->nama_kelas . ' - ' . $k->jurusan,
                    'hari' => 'Kamis',
                    'jam_mulai' => '08:00',
                    'jam_selesai' => '10:00'
                ]);
                echo "Jadwal created (Kamis, 08:00 - 10:00).\n";
            } else {
                echo "Jadwal already exists.\n";
            }
        } else {
             echo "No Laboratorium found in DB.\n";
        }
    }
} else {
    echo "User khairulfikri730@gmail.com not found.\n";
}
