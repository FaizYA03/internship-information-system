<?php

namespace App\Exports;

use App\Models\WakilPerusahaan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PerusahaanExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Perusahaan',
            'Alamat',
            'Kontak',
            'Nama Pembimbing',
        ];
    }

    public function map($item): array
    {
        static $rowNumber = 0;
        $rowNumber++;

        return [
            $rowNumber,
            $item->nama_perusahaan,
            $item->alamat,
            $item->no_perusahaan,
            $item->nama,
        ];
    }
}
