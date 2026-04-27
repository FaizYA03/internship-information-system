<?php

namespace App\Exports;

use App\Models\Pengadaan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class PengadaanExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $status;
    private $rowNumber = 0;

    public function __construct($status = null)
    {
        $this->status = $status;
    }

    public function collection()
    {
        $query = Pengadaan::with(['vendor', 'details']);
        
        if ($this->status) {
            $query->where('status', $this->status);
        }
        
        return $query->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Judul Pengadaan',
            'Status',
            'Vendor',
            'Tanggal Diterima',
            'Faktur No',
            'Total Estimasi',
            'Total Aktual'
        ];
    }

    public function map($pengadaan): array
    {
        $this->rowNumber++;
        
        return [
            $this->rowNumber,
            $pengadaan->judul,
            $pengadaan->status,
            $pengadaan->vendor ? $pengadaan->vendor->nama : '-',
            $pengadaan->tanggal_diterima ? Carbon::parse($pengadaan->tanggal_diterima)->format('d/m/Y') : '-',
            $pengadaan->faktur_no ?? '-',
            $pengadaan->total_estimasi,
            $pengadaan->total_aktual
        ];
    }
}
