<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Guru;
use App\Models\Jurusan;
use Faker\Factory as Faker;

class FixGuruDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        $jurusans = Jurusan::all()->pluck('id')->toArray();

        if (empty($jurusans)) {
            $this->command->error("No jurusans found!");
            return;
        }

        $gurus = Guru::all();

        $count = 0;
        foreach ($gurus as $guru) {
            if (stripos($guru->nama, 'fikri') !== false) {
                continue; // Skip Fikri
            }

            // check if nip already set correctly or just overwrite all of these
            
            $guru->nip = $faker->unique()->numerify('19##########20##1###');
            $guru->tanggal_lahir = $faker->dateTimeBetween('-50 years', '-25 years')->format('Y-m-d');
            $guru->alamat = $faker->address;
            $guru->no_hp = $faker->phoneNumber;
            $guru->jurusan_id = $faker->randomElement($jurusans);
            $guru->save();
            
            $count++;
        }

        $this->command->info("Updated $count gurus.");
    }
}
