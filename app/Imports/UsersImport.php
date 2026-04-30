<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Siswa;
use App\Models\Guru;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;

class UsersImport implements ToCollection, WithHeadingRow
{
    use Importable;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $role     = strtolower(trim($row['role']));
            $emailRaw = trim((string) $row['email']);

            if (filter_var($emailRaw, FILTER_VALIDATE_EMAIL)) {
                // Import berdasarkan email: update jika sudah ada
                $user = User::updateOrCreate(
                    ['email' => $emailRaw],
                    [
                        'nama'     => $row['nama'],
                        'nis_nip'  => $row['nis_nip'],
                        'password' => Hash::make($row['password']),
                        'role'     => $role,
                    ]
                );
            } else {
                // Email null/invalid → coba cari dari nis_nip
                if (!empty($row['nis_nip'])) {
                    $user = User::where('nis_nip', $row['nis_nip'])->first();
                    if ($user) {
                        $user->update([
                            'nama'     => $row['nama'],
                            'password' => Hash::make($row['password']),
                            'role'     => $role,
                        ]);
                    } else {
                        $user = User::create([
                            'nama'     => $row['nama'],
                            'nis_nip'  => $row['nis_nip'],
                            'email'    => null,
                            'password' => Hash::make($row['password']),
                            'role'     => $role,
                        ]);
                    }
                } else {
                    $user = User::create([
                        'nama'     => $row['nama'],
                        'nis_nip'  => null,
                        'email'    => null,
                        'password' => Hash::make($row['password']),
                        'role'     => $role,
                    ]);
                }
            }

            $role = strtolower(trim($row['role']));

            if ($role === 'siswa' && !empty($row['nis_nip'])) {
                Siswa::updateOrCreate(
                    ['nis' => $row['nis_nip']],
                    [
                        'user_id'       => $user->id,
                        'kelas_id'      => $row['kelas_id'] ?? null,
                        'kelas'         => $row['kelas'] ?? null,
                        'jurusan'       => $row['jurusan'] ?? null,
                        'tanggal_lahir' => $row['tanggal_lahir'] ?? null,
                        'alamat'        => $row['alamat'] ?? null,
                        'no_hp'         => $row['no_hp'] ?? null,
                    ]
                );
            } elseif ($role === 'guru' && !empty($row['nis_nip'])) {
                Guru::updateOrCreate(
                    ['nip' => $row['nis_nip']],
                    [
                        'user_id'       => $user->id,
                        'kelas'         => $row['kelas'] ?? null,
                        'jurusan'       => $row['jurusan'] ?? null,
                        'tanggal_lahir' => $row['tanggal_lahir'] ?? null,
                        'alamat'        => $row['alamat'] ?? null,
                        'no_hp'         => $row['no_hp'] ?? null,
                    ]
                );
            }
        }
    }
}
