<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class JabatanStrukturalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Kepala Lab
        User::updateOrCreate(
            ['email' => 'kalab@gmail.com'],
            [
                'nama' => 'Kepala Laboratorium',
                'password' => Hash::make('password'),
                'role' => 'kepala_lab',
                'menu' => 'labor',
                'nis_nip' => '197001012023011001'
            ]
        );

        // 2. Kepala Sekolah
        User::updateOrCreate(
            ['email' => 'kepsek@gmail.com'],
            [
                'nama' => 'Kepala Sekolah SMKN 5',
                'password' => Hash::make('password'),
                'role' => 'kepala_sekolah',
                'menu' => 'labor', // Dashboard Kepsek ada di modul labor juga
                'nis_nip' => '196501011990011001'
            ]
        );

        // 3. Waka Kurikulum (Waka Akademik)
        User::updateOrCreate(
            ['email' => 'waka@gmail.com'],
            [
                'nama' => 'Waka Kurikulum',
                'password' => Hash::make('password'),
                'role' => 'waka_akademik',
                'menu' => 'labor',
                'nis_nip' => '198001012010011001'
            ]
        );
    }
}
