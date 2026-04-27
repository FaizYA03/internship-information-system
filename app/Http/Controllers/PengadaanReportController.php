<?php

namespace App\Http\Controllers;

use App\Models\Pengadaan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PengadaanExport;

class PengadaanReportController extends Controller
{
    public function exportPdf(Request $request)
    {
        $status = $request->status;
        $query = Pengadaan::with(['vendor', 'details.buku', 'bookCopies'])->orderBy('created_at', 'desc');
        
        if ($status) {
            $query->where('status', $status);
        }
        
        $pengadaans = $query->get();
        
        $pdf = Pdf::loadView('perpustakaan.pengadaan.pdf', compact('pengadaans', 'status'))
            ->setPaper('a4', 'landscape');
            
        return $pdf->download('laporan-pengadaan-' . date('Y-m-d') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $status = $request->status ?? '';
        return Excel::download(new PengadaanExport($status), 'laporan-pengadaan-' . date('Y-m-d') . '.xlsx');
    }
}
