<?php
namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class BukuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
        
        // Only restrict CRUD operations, not viewing
        $this->middleware('role:super_admin,admin_perpus')->only([
            'create', 'store', 'edit', 'update', 'destroy'
        ]);
    }

    public function index()
    {
        $title = 'Buku';
        $header = 'Data Buku Perpustakaan';
        $buku = Buku::all();

        if (!Auth::check()) {
            return view('perpustakaan.buku.landing', compact('buku', 'title', 'header'));
        }

        return view('perpustakaan.buku.index', compact('buku', 'title', 'header'));
    }

    public function create()
    {
        if (!Gate::allows('manage-perpustakaan')) {
            abort(403, 'Unauthorized action.');
        }

        $title = 'Buku';
        $header = 'Tambah Buku Perpustakaan';
        $categories = \App\Models\Kategori::all();
        return view('perpustakaan.buku.createOrEdit', compact('title', 'header', 'categories'));
    }

    public function store(Request $request)
    {
        if (!Gate::allows('manage-perpustakaan')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'judul' => 'required',
            'pengarang' => 'required',
            'penerbit' => 'required',
            'tahun_terbit' => 'required|digits:4',
            'stok' => 'required|integer',
            'kategori_id' => 'required',
            'pdf_file' => 'nullable|mimes:pdf|max:10240', // Max 10MB
            'cover' => 'nullable|image|mimes:jpg,jpeg,png,jfif|max:2048',
        ]);

        $data = $request->except('pdf_file', 'cover');
        
        // Handle PDF upload if present
        if ($request->hasFile('pdf_file')) {
            $pdfFile = $request->file('pdf_file');
            $filename = time() . '_' . preg_replace('/[^A-Za-z0-9]/', '', $request->judul) . '.pdf';
            $pdfPath = $pdfFile->storeAs('books', $filename, 'public');
            $data['pdf_path'] = $pdfPath;
        }

        // Handle cover image upload if present
        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('covers', 'public');
            $data['cover_path'] = $coverPath;
        }

        Buku::create($data);

        return redirect()->route('perpustakaan.buku.index')
            ->with('status', 'success')
            ->with('title', 'Berhasil')
            ->with('message', 'Data buku berhasil ditambah');
    }

    public function edit(Buku $buku)
    {
        if (!Gate::allows('manage-perpustakaan')) {
            abort(403, 'Unauthorized action.');
        }

        $title = 'Buku';
        $header = 'Edit Buku Perpustakaan';
        $categories = \App\Models\Kategori::all();
        return view('perpustakaan.buku.createOrEdit', compact('buku', 'title', 'header', 'categories'));
    }

    public function update(Request $request, Buku $buku)
    {
        if (!Gate::allows('manage-perpustakaan')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'judul' => 'required',
            'pengarang' => 'required',
            'penerbit' => 'required',
            'tahun_terbit' => 'required|digits:4',
            'stok' => 'required|integer',
            'kategori_id' => 'required',
            'pdf_file' => 'nullable|mimes:pdf|max:10240', // Max 10MB
            'cover' => 'nullable|image|mimes:jpg,jpeg,png,jfif|max:2048',
        ]);

        $data = $request->except(['pdf_file', 'cover', '_token', '_method']);
        
        // Handle PDF upload if present
        if ($request->hasFile('pdf_file')) {
            // Delete old file if exists
            if ($buku->pdf_path && Storage::disk('public')->exists($buku->pdf_path)) {
                Storage::disk('public')->delete($buku->pdf_path);
            }
            
            $pdfFile = $request->file('pdf_file');
            $filename = time() . '_' . preg_replace('/[^A-Za-z0-9]/', '', $request->judul) . '.pdf';
            $pdfPath = $pdfFile->storeAs('books', $filename, 'public');
            $data['pdf_path'] = $pdfPath;
        }

        // Handle cover image upload if present
        if ($request->hasFile('cover')) {
            // Delete old cover if exists
            if ($buku->cover_path && Storage::disk('public')->exists($buku->cover_path)) {
                Storage::disk('public')->delete($buku->cover_path);
            }
            
            $coverPath = $request->file('cover')->store('covers', 'public');
            $data['cover_path'] = $coverPath;
        }

        $buku->update($data);

        return redirect()->route('perpustakaan.buku.index')
            ->with('status', 'success')
            ->with('title', 'Berhasil')
            ->with('message', 'Data buku berhasil diubah');
    }

    public function destroy(Buku $buku)
    {
        if (!Gate::allows('manage-perpustakaan')) {
            abort(403, 'Unauthorized action.');
        }

        $buku->delete();
        return redirect()->route('perpustakaan.buku.index')->with('status', 'success')->with('title', 'Berhasil')->with('message', 'Data buku berhasil dihapus');
    }

    public function show(Buku $buku)
    {
        $title = 'Detail Buku';
        $header = 'Detail Buku Perpustakaan';
        return view('perpustakaan.buku.show', compact('buku', 'title', 'header'));
    }

    public function showPdf(Buku $buku)
    {
        if (!$buku->pdf_path || !Storage::disk('public')->exists($buku->pdf_path)) {
            abort(404, 'File PDF tidak ditemukan');
        }
        
        return response()->file(storage_path('app/public/' . $buku->pdf_path));
    }
}