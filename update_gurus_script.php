<?php
use App\Models\Guru;
use App\Models\Jurusan;
use Carbon\Carbon;
use Faker\Factory as Faker;

$faker = Faker::create('id_ID');
$jurusans = Jurusan::all()->pluck('id')->toArray();

if (empty($jurusans)) {
    echo "No jurusans found!";
    exit;
}

$gurus = Guru::where('nama', '!=', 'fikri,S.Pd, M.Kom')
             ->where('nama', '!=', 'Fikri, S.Pd., M.Kom.')
             ->get();

$count = 0;
foreach ($gurus as $guru) {
    if (stripos($guru->nama, 'fikri') !== false) {
        continue; // Skip Fikri
    }
    
    // update data
    $guru->nip = $faker->unique()->numerify('19##########20##1###');
    $guru->tanggal_lahir = $faker->dateTimeBetween('-50 years', '-25 years')->format('Y-m-d');
    $guru->alamat = $faker->address;
    $guru->no_hp = $faker->phoneNumber;
    $guru->jurusan_id = $faker->randomElement($jurusans);
    $guru->save();
    
    $count++;
}

echo "Updated $count gurus.\n";
