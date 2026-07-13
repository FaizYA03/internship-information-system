<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class MagangExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $applications;

    public function __construct($applications)
    {
        $this->applications = $applications;
    }

    public function collection()
    {
        return $this->applications;
    }

    public function headings(): array
    {
        return [
            'No',
            'NISN',
            'Nama Siswa',
            'Email',
            'No. HP',
            'Perusahaan / Tempat Magang',
            'Posisi',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Supervisor Mitra',
            'Guru Pembimbing',
            'Status',
            'Total Laporan',
        ];
    }

    public function map($item): array
    {
        static $rowNumber = 0;
        $rowNumber++;

        // Formatting Supervisor
        $mitraSupervisor = optional($item->mitraSupervisor)->nama_lengkap ?? (optional($item->wakilPerusahaan)->nama ? optional($item->wakilPerusahaan)->nama . ' (Default)' : 'Belum ada');
        $guruPembimbing = optional(optional($item->pembimbing)->guru)->nama ?? 'Belum ada';

        return [
            $rowNumber,
            optional(optional($item->user)->siswa)->nis ?? optional($item->user)->nis_nip ?? '-',
            $item->nama,
            $item->user->email ?? '-',
            $item->no_hp ?? '-',
            optional($item->wakilPerusahaan)->nama_perusahaan ?? 'Siswa Mengajukan Mandiri',
            optional($item->opening)->posisi ?? '-',
            $item->tanggal_mulai ? Carbon::parse($item->tanggal_mulai)->format('d M Y') : '-',
            $item->tanggal_selesai ? Carbon::parse($item->tanggal_selesai)->format('d M Y') : '-',
            $mitraSupervisor,
            $guruPembimbing,
            $item->status,
            $item->laporans_count ?? 0,
        ];
    }
}
