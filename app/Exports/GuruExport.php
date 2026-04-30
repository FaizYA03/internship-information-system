<?php

namespace App\Exports;

use App\Models\Guru;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class GuruExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function title(): string
    {
        return 'Data Guru';
    }

    public function collection()
    {
        $query = Guru::with(['user', 'jurusan']);

        if (!empty($this->filters['search'])) {
            $s = $this->filters['search'];
            $query->where(function ($q) use ($s) {
                $q->where('nip', 'like', "%$s%")
                  ->orWhereHas('user', fn($q2) => $q2->where('nama', 'like', "%$s%"));
            });
        }
        if (!empty($this->filters['jurusan_id'])) {
            $query->where('jurusan_id', $this->filters['jurusan_id']);
        }
        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }
        if (!empty($this->filters['jenis_kelamin'])) {
            $query->where('jenis_kelamin', $this->filters['jenis_kelamin']);
        }

        return $query->join('users', 'users.id', '=', 'guru.user_id')
                     ->orderBy('users.nama')
                     ->select('guru.*')
                     ->get();
    }

    public function headings(): array
    {
        return [
            'No', 'Nama Guru', 'Email', 'NIP',
            'Jurusan', 'Jenis Kelamin', 'Agama',
            'No HP', 'Status',
        ];
    }

    public function map($guru): array
    {
        static $no = 0;
        $no++;
        return [
            $no,
            $guru->user->nama ?? '-',
            $guru->user->email ?? '-',
            $guru->nip ?? '-',
            $guru->jurusan->nama_jurusan ?? '-',
            $guru->jenis_kelamin ?? '-',
            $guru->agama ?? '-',
            $guru->no_hp ?? '-',
            $guru->status ?? '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF15803D']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }
}
