<?php

namespace App\Exports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class SiswaExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function title(): string
    {
        return 'Data Siswa';
    }

    public function collection()
    {
        $query = Siswa::with(['user', 'dataKelas']);

        if (!empty($this->filters['search'])) {
            $s = $this->filters['search'];
            $query->where(function ($q) use ($s) {
                $q->where('nis', 'like', "%$s%")
                  ->orWhereHas('user', fn($q2) => $q2->where('nama', 'like', "%$s%"));
            });
        }
        if (!empty($this->filters['kelas'])) {
            $query->where('kelas', $this->filters['kelas']);
        }
        if (!empty($this->filters['jurusan'])) {
            $query->where('jurusan', $this->filters['jurusan']);
        }

        return $query->join('users', 'users.id', '=', 'siswa.user_id')
                     ->orderBy('users.nama')
                     ->select('siswa.*')
                     ->get();
    }

    public function headings(): array
    {
        return [
            'No', 'Nama Siswa', 'Email', 'NIS',
            'Kode Kelas', 'Kelas', 'Jurusan',
            'Jenis Kelamin', 'Agama',
            'Tanggal Lahir', 'Alamat', 'No HP',
        ];
    }

    public function map($siswa): array
    {
        static $no = 0;
        $no++;
        return [
            $no,
            $siswa->user->nama ?? '-',
            $siswa->user->email ?? '-',
            $siswa->nis ?? '-',
            $siswa->dataKelas->id_kelas ?? '-',
            $siswa->kelas ?? '-',
            $siswa->jurusan ?? '-',
            $siswa->jenis_kelamin ?? '-',
            $siswa->agama ?? '-',
            $siswa->tanggal_lahir ?? '-',
            $siswa->alamat ?? '-',
            $siswa->no_hp ?? '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1D4ED8']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }
}
