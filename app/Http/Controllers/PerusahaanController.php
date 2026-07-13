<?php

namespace App\Http\Controllers;

use App\Models\WakilPerusahaan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\PerusahaanExport;

class PerusahaanController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Perusahaan';
        $header = 'Data Perusahaan Magang';
        
        $query = WakilPerusahaan::query();

        // Optional filter (misalnya nama perusahaan atau wilayah jika dibutuhkan di kemudian hari)
        if ($request->filled('search_nama')) {
            $query->where('nama_perusahaan', 'like', '%' . $request->search_nama . '%');
        }

        $wakilperusahaan = $query->latest()->get();
        return view('magang.perusahaan.index', compact('wakilperusahaan', 'title', 'header'));
    }

    public function exportExcel(Request $request)
    {
        $query = WakilPerusahaan::query();

        if ($request->filled('search_nama')) {
            $query->where('nama_perusahaan', 'like', '%' . $request->search_nama . '%');
        }

        $perusahaan = $query->latest()->get();

        return Excel::download(new PerusahaanExport($perusahaan), 'Data_Perusahaan_Mitra.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $query = WakilPerusahaan::query();

        if ($request->filled('search_nama')) {
            $query->where('nama_perusahaan', 'like', '%' . $request->search_nama . '%');
        }

        $perusahaan = $query->latest()->get();

        $pdf = Pdf::loadView('magang.perusahaan.export_pdf', compact('perusahaan'));
        $pdf->setPaper('a4', 'landscape');
        
        return $pdf->download('Data_Perusahaan_Mitra.pdf');
    }


public function landing()
{
    $perusahaan = DB::table('wakil_perusahaan')
        ->join('perusahaan', 'wakil_perusahaan.perusahaan_id', '=', 'perusahaan.id')
        ->select(
            'perusahaan.nama_perusahaan',
            'perusahaan.alamat',
            'perusahaan.no_perusahaan',
            'wakil_perusahaan.nama_pembimbing'
        )
        ->latest()
        ->get();

    return view('magang.landing', compact('perusahaan'));
}
    public function create()
    {
        $title = 'Perusahaan';
        $header = 'Tambah Data Perusahaan';
        return view('magang.perusahaan.createOrEdit', compact('title', 'header'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'nama_pembimbing' => 'required|string',
            'no_perusahaan' => 'required|string|max:15',
        ]);

        WakilPerusahaan::create($request->all());

        return redirect()->route('magang.perusahaan.index')->with('status', 'success')->with('title', 'Berhasil')->with('message', 'perusahaan berhasil ditambah');
    }

    public function edit(WakilPerusahaan $wakilperusahaan)
    {
        $title = 'Perusahaan';
        $header = 'Edit Data Perusahaan';
        return view('magang.perusahaan.createOrEdit', compact('wakilperusahaan', 'title', 'header'));
    }

    public function update(Request $request, WakilPerusahaan $wakilperusahaan)
    {
        $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'nama_pembimbing' => 'required|string',
            'no_perusahaan' => 'required|string|max:15',
        ]);

        $wakilperusahaan->update($request->all());

        return redirect()->route('magang.perusahaan.index')->with('status', 'success')->with('title', 'Berhasil')->with('message', 'perusahaan berhasil diperbarui');
    }

    public function destroy(WakilPerusahaan $wakilperusahaan)
    {
        $wakilperusahaan->delete();

        return redirect()->route('magang.perusahaan.index')->with('status', 'success')->with('title', 'Berhasil')->with('message', 'Perusahaan berhasil dihapus');
    }
}