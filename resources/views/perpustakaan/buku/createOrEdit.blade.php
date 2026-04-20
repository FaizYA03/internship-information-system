@extends('perpustakaan.layouts.main')

@section('css')
<style>
    .form-container {
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(26, 42, 58, 0.1);
        padding: 2rem;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }
    
    .page-title {
        font-size: 1.875rem;
        font-weight: 700;
        color: #1a2a3a;
        margin-bottom: 1.5rem;
        position: relative;
        display: inline-block;
    }
    
    .page-title::after {
        content: '';
        display: block;
        width: 70px;
        height: 3px;
        background: linear-gradient(to right, #3bafa6, #4ecdc4);
        margin-top: 0.5rem;
        border-radius: 2px;
    }
    
    .form-label {
        font-weight: 600;
        color: #1a2a3a;
        margin-bottom: 0.5rem;
        display: block;
    }
    
    .form-control {
        border-radius: 8px;
        border: 1px solid rgba(26, 42, 58, 0.15);
        padding: 0.75rem 1rem;
        font-size: 1rem;
        transition: all 0.3s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }
    
    .form-control:focus {
        border-color: #4ecdc4;
        box-shadow: 0 0 0 0.2rem rgba(78, 205, 196, 0.25);
    }
    
    .btn-submit {
        background-color: #4ecdc4;
        color: #fff;
        padding: 0.75rem 2rem;
        font-weight: 600;
        border-radius: 8px;
        border: none;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 180px;
    }
    
    .btn-submit:hover {
        background-color: #3bafa6;
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(26, 42, 58, 0.15);
        color: #fff;
    }
    
    .btn-submit i {
        margin-right: 0.5rem;
        font-size: 1.1rem;
    }
    
    .btn-back {
        background-color: #f8f9fa;
        color: #1a2a3a;
        padding: 0.75rem 2rem;
        font-weight: 600;
        border-radius: 8px;
        border: 1px solid rgba(26, 42, 58, 0.15);
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        min-width: 120px;
    }
    
    .btn-back:hover {
        background-color: #e9ecef;
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(26, 42, 58, 0.08);
        color: #1a2a3a;
    }
    
    .btn-back i {
        margin-right: 0.5rem;
        font-size: 1.1rem;
    }
    
    .text-danger {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    
    .form-text {
        font-size: 0.875rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }
    
    /* Animation */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .form-container {
        animation: fadeIn 0.5s ease;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .form-container {
            padding: 1.5rem;
        }
        
        .d-flex.mt-5 {
            flex-direction: column;
            gap: 1rem;
        }
        
        .btn-back, .btn-submit {
            width: 100%;
            margin-right: 0;
        }
    }
    
    /* Add PDF upload styling */
    .pdf-upload-container {
        position: relative;
    }
    
    .current-pdf {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .pdf-preview {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        color: var(--secondary);
        font-size: 0.9rem;
        text-decoration: none;
    }
    
    .pdf-preview:hover {
        text-decoration: underline;
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10 col-12">
            <h1 class="page-title">{{ isset($buku) ? 'Edit Buku' : 'Tambah Buku Baru' }}</h1>
            <p class="text-muted mb-4">{{ isset($buku) ? 'Perbarui informasi buku di perpustakaan' : 'Tambahkan buku baru ke koleksi perpustakaan SMK Negeri 5 Padang' }}</p>
            
            <div class="form-container" data-aos="fade-up">
                <form action="{{ isset($buku) ? route('perpustakaan.buku.update', $buku->id) : route('perpustakaan.buku.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(isset($buku))
                        @method('PUT') 
                    @endif
                    
                    <div class="mb-4">
                        <label for="kategori_id" class="form-label">Kategori</label>
                        <select name="kategori_id" id="kategori_id" class="form-control @error('kategori_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ (old('kategori_id', $buku->kategori_id ?? '') == $category->id) ? 'selected' : '' }}>
                                    {{ $category->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="judul" class="form-label">Judul Buku</label>
                        <input 
                            type="text" 
                            name="judul" 
                            id="judul" 
                            class="form-control @error('judul') is-invalid @enderror"
                            value="{{ old('judul', $buku->judul ?? '') }}" 
                            placeholder="Masukkan judul buku"
                            required>
                        @error('judul')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="pengarang" class="form-label">Pengarang</label>
                        <input 
                            type="text" 
                            name="pengarang" 
                            id="pengarang" 
                            class="form-control @error('pengarang') is-invalid @enderror"
                            value="{{ old('pengarang', $buku->pengarang ?? '') }}" 
                            placeholder="Masukkan nama pengarang"
                            required>
                        @error('pengarang')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="penerbit" class="form-label">Penerbit</label>
                        <input 
                            type="text" 
                            name="penerbit" 
                            id="penerbit" 
                            class="form-control @error('penerbit') is-invalid @enderror"
                            value="{{ old('penerbit', $buku->penerbit ?? '') }}" 
                            placeholder="Masukkan nama penerbit"
                            required>
                        @error('penerbit')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
                            <input 
                                type="number" 
                                name="tahun_terbit" 
                                id="tahun_terbit" 
                                class="form-control @error('tahun_terbit') is-invalid @enderror"
                                value="{{ old('tahun_terbit', $buku->tahun_terbit ?? '') }}" 
                                placeholder="contoh: 2023"
                                min="1900" 
                                max="{{ date('Y') }}"
                                required>
                            @error('tahun_terbit')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <label for="stok" class="form-label">Stok</label>
                            <input 
                                type="number" 
                                name="stok" 
                                id="stok" 
                                class="form-control @error('stok') is-invalid @enderror"
                                value="{{ old('stok', $buku->stok ?? '') }}" 
                                placeholder="Jumlah buku tersedia"
                                min="0"
                                required>
                            @error('stok')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="pdf_file" class="form-label">File PDF (Opsional)</label>
                        <div class="pdf-upload-container">
                            <input 
                                type="file" 
                                name="pdf_file" 
                                id="pdf_file" 
                                class="form-control @error('pdf_file') is-invalid @enderror"
                                accept=".pdf"
                            >
                            <div class="form-text">
                                <i class="bi bi-info-circle"></i> Upload file PDF buku (maksimal 10MB)
                            </div>
                            @if(isset($buku) && $buku->pdf_path)
                                <div class="current-pdf mt-2">
                                    <span class="badge bg-success">
                                        <i class="bi bi-file-earmark-pdf"></i> File PDF sudah terupload
                                    </span>
                                    <a href="{{ route('perpustakaan.buku.pdf', $buku->id) }}" class="pdf-preview" target="_blank">
                                        <i class="bi bi-eye"></i> Lihat PDF
                                    </a>
                                </div>
                            @endif
                        </div>
                        @error('pdf_file')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    {{-- Input Cover Buku --}}
                    <div class="mb-4">
                        <label for="cover" class="form-label">Cover Buku (Opsional)</label>
                        <input 
                            type="file" 
                            name="cover" 
                            id="cover" 
                            class="form-control @error('cover') is-invalid @enderror"
                            accept="image/png, image/jpeg, image/jpg, image/jfif"
                        >
                        <div class="form-text">
                            Format yang diizinkan: JPG, JPEG, PNG, JFIF. Maksimal 2MB.
                        </div>
                        @if(isset($buku) && $buku->cover_path)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $buku->cover_path) }}" alt="Cover Buku" style="max-height: 120px; border-radius:8px;">
                            </div>
                        @endif
                        @error('cover')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                
                    <div class="d-flex mt-5">
                        <a href="{{ route('perpustakaan.buku.index') }}" class="btn-back">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn-submit">
                            <i class="bi bi-{{ isset($buku) ? 'pencil-square' : 'plus-circle' }}"></i> 
                            {{ isset($buku) ? 'Update Buku' : 'Simpan Buku' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set default year if adding a new book
        if (!document.getElementById('tahun_terbit').value) {
            document.getElementById('tahun_terbit').value = new Date().getFullYear();
        }
        
        // Validate year input
        document.getElementById('tahun_terbit').addEventListener('input', function() {
            const year = parseInt(this.value);
            const currentYear = new Date().getFullYear();
            
            if (year > currentYear) {
                this.value = currentYear;
                Swal.fire({
                    title: 'Peringatan',
                    text: 'Tahun terbit tidak boleh melebihi tahun saat ini',
                    icon: 'warning',
                    confirmButtonColor: '#4ecdc4'
                });
            }
        });
    });
</script>
@endsection