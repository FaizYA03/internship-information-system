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
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10 col-12">
            <h1 class="page-title">Edit Kategori Buku</h1>
            <p class="text-muted mb-4">Perbarui informasi kategori buku di perpustakaan</p>
            
            <div class="form-container" data-aos="fade-up">
                <form action="{{ route('perpustakaan.kategori.update', $kategori->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Nama Kategori -->
                    <div class="mb-4">
                        <label for="nama_kategori" class="form-label">Nama Kategori</label>
                        <input 
                            type="text" 
                            name="nama_kategori" 
                            id="nama_kategori" 
                            class="form-control @error('nama_kategori') is-invalid @enderror"
                            value="{{ old('nama_kategori', $kategori->nama_kategori) }}" 
                            placeholder="Masukkan nama kategori buku"
                            required>
                        @error('nama_kategori')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Kode Buku -->
                    <div class="mb-4">
                        <label for="kode_buku" class="form-label">Kode Buku</label>
                        <input 
                            type="text" 
                            name="kode_buku" 
                            id="kode_buku" 
                            class="form-control @error('kode_buku') is-invalid @enderror"
                            value="{{ old('kode_buku', $kategori->kode_buku) }}" 
                            placeholder="Masukkan kode buku"
                            required>
                        @error('kode_buku')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    

                
                    <div class="d-flex mt-5">
                        <a href="{{ route('perpustakaan.kategori.index') }}" class="btn-back">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn-submit">
                            <i class="bi bi-pencil-square"></i> Update Kategori
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
