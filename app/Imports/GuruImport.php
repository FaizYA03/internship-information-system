<?php

namespace App\Imports;

use App\Models\Guru;
use App\Models\User;
use App\Models\Jurusan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;

class GuruImport implements ToCollection, WithHeadingRow, SkipsOnError
{
    use SkipsErrors;

    public array $importedCount = [];
    public array $skippedRows   = [];

    public function collection(Collection $rows)
    {
        // Build jurusan lookup map (nama_jurusan => id)
        $jurusanMap = Jurusan::pluck('id', 'nama_jurusan')->toArray();

        foreach ($rows as $rowIndex => $row) {
            $nama = trim($row['nama_guru'] ?? '');
            $nip  = trim((string) ($row['nip'] ?? ''));

            if (empty($nama) || empty($nip)) {
                $this->skippedRows[] = "Baris " . ($rowIndex + 2) . ": Nama atau NIP kosong, dilewati.";
                continue;
            }

            // Map Jurusan
            $jurusanNama = trim($row['jurusan'] ?? '');
            $jurusanId   = $jurusanMap[$jurusanNama] ?? null;
            if (!$jurusanId) {
                // Try partial/case-insensitive match
                foreach ($jurusanMap as $jNama => $jId) {
                    if (stripos($jNama, $jurusanNama) !== false || stripos($jurusanNama, $jNama) !== false) {
                        $jurusanId = $jId;
                        break;
                    }
                }
            }

            // Map jenis kelamin
            $lpRaw = strtoupper(trim($row['lp'] ?? $row['jenis_kelamin'] ?? ''));
            $jenisKelamin = null;
            if (in_array($lpRaw, ['L', 'LAKI', 'LAKI-LAKI'])) {
                $jenisKelamin = 'Laki-laki';
            } elseif (in_array($lpRaw, ['P', 'PEREMPUAN'])) {
                $jenisKelamin = 'Perempuan';
            }

            $status = ucfirst(strtolower(trim($row['status'] ?? 'Aktif')));
            if (!in_array($status, ['Aktif', 'Nonaktif'])) $status = 'Aktif';

            $agama = trim($row['agama'] ?? '');

            // Generate email: baseName@gmail.com
            $baseEmail = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $nama)) . '@gmail.com';
            $email = $baseEmail;
            $counter = 1;
            while (User::where('email', $email)->exists()) {
                $email = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $nama)) . str_repeat('_', $counter) . '@gmail.com';
                $counter++;
            }

            // Create or update user by NIP
            $user = User::where('nis_nip', $nip)->where('role', 'guru')->first();
            if ($user) {
                $user->update([
                    'nama'  => $nama,
                    'role'  => 'guru',
                ]);
            } else {
                // Check if NIP already exists in guru table
                if (Guru::where('nip', $nip)->exists()) {
                    $this->skippedRows[] = "Baris " . ($rowIndex + 2) . ": NIP '$nip' sudah ada, dilewati.";
                    continue;
                }
                $user = User::create([
                    'nama'     => $nama,
                    'nis_nip'  => $nip,
                    'email'    => $email,
                    'password' => Hash::make('guru'),
                    'role'     => 'guru',
                ]);
            }

            // Create or update guru record
            Guru::updateOrCreate(
                ['nip' => $nip],
                [
                    'user_id'       => $user->id,
                    'jurusan_id'    => $jurusanId,
                    'jenis_kelamin' => $jenisKelamin,
                    'agama'         => $agama ?: null,
                    'no_hp'         => trim($row['no_hp'] ?? '') ?: null,
                    'status'        => $status,
                ]
            );

            $this->importedCount[] = $nip;
        }
    }
}
