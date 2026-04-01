<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class JurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jurusan = [
            'Teknik Otomotif Sepeda Motor',
            'Teknik Otomotif Kendaraan Ringan',
            'Teknik Pemesinan',
            'Teknik Audio Video',
            'Teknik Gambar Bangunan',
            'Teknik Konstruksi Batu dan Beton',
            'Teknik Komputer Jaringan',
            'Teknik Instalasi Tenaga Listrik'
        ];

        foreach ($jurusan as $j) {
            \App\Models\Jurusan::updateOrCreate(['nama_jurusan' => $j]);
        }
    }
}
