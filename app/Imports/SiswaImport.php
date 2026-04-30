<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\User;
use App\Models\Kelas;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;

class SiswaImport implements ToCollection, WithHeadingRow, SkipsOnError
{
    use SkipsErrors;

    public array $importedCount = [];
    public array $skippedRows   = [];

    public function collection(Collection $rows)
    {
        $kelasList = Kelas::all();

        // Build lookup maps
        $byKode  = $kelasList->keyBy('id_kelas');  // "01" => Kelas
        $byNama  = $kelasList->keyBy('nama_kelas');  // "X TKJ 1" => Kelas
        $byId    = $kelasList->keyBy('id');           // 40 => Kelas

        foreach ($rows as $rowIndex => $row) {
            $nama = trim($row['nama_siswa'] ?? $row['nama'] ?? '');
            $nis  = trim((string)($row['nis'] ?? ''));

            if (empty($nama) || empty($nis)) {
                $this->skippedRows[] = "Baris " . ($rowIndex + 2) . ": Nama atau NIS kosong, dilewati.";
                continue;
            }

            // Resolve kelas: first try kode_kelas, then nama_kelas, then id
            $kelasObj = null;
            $kodeKelas = trim((string)($row['id_kelas'] ?? $row['kode_kelas'] ?? ''));
            $kelasNama = trim($row['kelas'] ?? '');

            if ($kodeKelas !== '') {
                $kode = str_pad($kodeKelas, 2, '0', STR_PAD_LEFT);
                $kelasObj = $byKode->get($kode);
            }
            if (!$kelasObj && $kelasNama !== '') {
                $kelasObj = $byNama->get($kelasNama);
                if (!$kelasObj) {
                    // Partial match
                    foreach ($kelasList as $k) {
                        if (stripos($k->nama_kelas, $kelasNama) !== false) {
                            $kelasObj = $k; break;
                        }
                    }
                }
            }

            $jurusanRow = trim($row['jurusan'] ?? '');

            // Map jenis kelamin
            $lpRaw = strtoupper(trim($row['lp'] ?? $row['jenis_kelamin'] ?? ''));
            $jenisKelamin = null;
            if (in_array($lpRaw, ['L', 'LAKI', 'LAKI-LAKI']))    $jenisKelamin = 'Laki-laki';
            elseif (in_array($lpRaw, ['P', 'PEREMPUAN']))         $jenisKelamin = 'Perempuan';

            $agama        = trim($row['agama'] ?? '');
            $tanggalLahir = trim($row['tanggal_lahir'] ?? '');
            $alamat       = trim($row['alamat'] ?? '');
            $noHp         = trim($row['no_hp'] ?? $row['nomor_hp'] ?? '');

            // Generate email: namasiswa@gmail.com
            $baseName  = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $nama));
            $baseEmail = $baseName . '@gmail.com';
            $email     = $baseEmail;
            $counter   = 1;

            // Check if user already exists with this NIS
            $existingUser = User::where('nis_nip', $nis)->where('role', 'siswa')->first();
            if (!$existingUser) {
                while (User::where('email', $email)->exists()) {
                    $email = $baseName . str_repeat('_', $counter) . '@gmail.com';
                    $counter++;
                }
            }

            if ($existingUser) {
                $existingUser->update(['nama' => $nama]);
                $user = $existingUser;
            } else {
                if (Siswa::where('nis', $nis)->exists()) {
                    $this->skippedRows[] = "Baris " . ($rowIndex + 2) . ": NIS '$nis' sudah ada, dilewati.";
                    continue;
                }
                $user = User::create([
                    'nama'     => $nama,
                    'nis_nip'  => $nis,
                    'email'    => $email,
                    'password' => Hash::make('siswa'),
                    'role'     => 'siswa',
                ]);
            }

            Siswa::updateOrCreate(
                ['nis' => $nis],
                [
                    'user_id'       => $user->id,
                    'kelas_id'      => $kelasObj?->id,
                    'kelas'         => $kelasObj?->nama_kelas ?? $kelasNama,
                    'jurusan'       => $kelasObj?->jurusan    ?? $jurusanRow,
                    'jenis_kelamin' => $jenisKelamin,
                    'agama'         => $agama ?: null,
                    'tanggal_lahir' => $tanggalLahir ?: null,
                    'alamat'        => $alamat ?: null,
                    'no_hp'         => $noHp ?: null,
                ]
            );

            $this->importedCount[] = $nis;
        }
    }
}
