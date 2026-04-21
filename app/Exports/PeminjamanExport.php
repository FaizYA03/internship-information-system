<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Peminjaman;
use Carbon\Carbon;

class PeminjamanExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $dari_tanggal;
    protected $sampai_tanggal;
    private $rowNumber = 0;

    public function __construct($dari_tanggal = null, $sampai_tanggal = null)
    {
        $this->dari_tanggal = $dari_tanggal;
        $this->sampai_tanggal = $sampai_tanggal;
    }

    public function collection()
    {
        $query = Peminjaman::with('buku');
        
        if ($this->dari_tanggal) {
            $query->whereDate('tanggal_pinjam', '>=', $this->dari_tanggal);
        }
        
        if ($this->sampai_tanggal) {
            $query->whereDate('tanggal_pinjam', '<=', $this->sampai_tanggal);
        }
        
        return $query->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Peminjam',
            'Buku',
            'Tanggal Pinjam',
            'Tanggal Kembali',
            'Status'
        ];
    }

    public function map($peminjaman): array
    {
        $this->rowNumber++;
        
        return [
            $this->rowNumber,
            $peminjaman->nama,
            $peminjaman->buku->judul ?? '-',
            Carbon::parse($peminjaman->tanggal_pinjam)->format('d/m/Y'),
            $peminjaman->tanggal_kembali ? Carbon::parse($peminjaman->tanggal_kembali)->format('d/m/Y') : '-',
            $peminjaman->status
        ];
    }
}
