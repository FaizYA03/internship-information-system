@extends('perpustakaan.layouts.main')

@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
@endphp

@section('css')
<style>
    .book-detail-section {
        background-color: #f8fafc;
    }

    .page-title {
        font-size: 1.875rem;
        font-weight: 700;
        color: #1a2a3a;
        margin-bottom: 0.75rem;
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

    .detail-container {
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(26, 42, 58, 0.1);
        overflow: hidden;
        margin-top: 1.5rem;
        transition: all 0.3s ease;
    }

    .detail-container:hover {
        box-shadow: 0 8px 25px rgba(26, 42, 58, 0.15);
    }

    .detail-header {
        background: linear-gradient(135deg, #1a2a3a, #2c3e50);
        padding: 2rem;
        color: white;
        position: relative;
        border-bottom: 5px solid #4ecdc4;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1.5rem;
    }

    .header-content {
        flex: 1;
        min-width: 250px;
    }

    .detail-title {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        word-break: break-word;
        line-height: 1.3;
    }

    .detail-author {
        font-size: 1.1rem;
        font-weight: 400;
        font-style: italic;
        opacity: 0.9;
    }

    .detail-body {
        padding: 2rem;
    }

    .detail-row {
        margin-bottom: 1.5rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        padding-bottom: 1.5rem;
        display: flex;
        align-items: flex-start;
    }

    .detail-row:last-child {
        margin-bottom: 0;
        border-bottom: none;
        padding-bottom: 0;
    }

    .detail-label {
        color: #6c757d;
        font-weight: 600;
        width: 150px;
        min-width: 150px;
        font-size: 0.95rem;
    }

    .detail-value {
        color: #1a2a3a;
        font-weight: 500;
        flex: 1;
    }

    .stock-badge {
        position: static;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        align-self: flex-start;
        margin-top: 0.25rem;
        white-space: nowrap;
    }

    .stock-badge.available {
        background-color: rgba(25, 135, 84, 0.9);
        color: white;
    }

    .stock-badge.unavailable {
        background-color: rgba(220, 53, 69, 0.9);
        color: white;
    }

    .stock-badge i {
        font-size: 1rem;
    }

    .action-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-top: 2rem;
    }

    .btn-secondary-app {
        background-color: #f8f9fa;
        color: #1a2a3a;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        text-decoration: none;
        border: 1px solid rgba(0, 0, 0, 0.1);
    }

    .btn-secondary-app:hover {
        background-color: #e9ecef;
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        color: #1a2a3a;
    }

    .btn-secondary-app {
        background-color: #4ecdc4;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        text-decoration: none;
        box-shadow: 0 3px 5px rgba(0, 0, 0, 0.05);
    }

    .btn-secondary-app:hover {
        background-color: #3bafa6;
        transform: translateY(-3px);
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        color: white;
    }

    .btn-secondary-app.disabled {
        background-color: #6c757d;
        cursor: not-allowed;
        opacity: 0.7;
        pointer-events: none;
    }

    /* Animations */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .detail-container {
        animation: fadeIn 0.5s ease;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .detail-header {
            padding: 1.5rem;
            flex-direction: column;
            gap: 1rem;
        }

        .header-content {
            width: 100%;
            text-align: center;
        }

        .detail-title {
            font-size: 1.5rem;
            width: 100%;
        }

        .stock-badge {
            margin: 0 auto;
            justify-content: center;
            align-self: center;
        }

        .detail-body {
            padding: 1.5rem;
        }

        .detail-row {
            flex-direction: column;
        }

        .detail-label {
            width: 100%;
            margin-bottom: 0.5rem;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn-secondary-app, .btn-secondary-app {
            width: 100%;
            justify-content: center;
        }
    }

    /* Add new styles for PDF section */
    .pdf-section {
        background-color: var(--secondary-light);
        padding: 1.5rem;
        border-radius: var(--radius);
        margin-top: 2rem;
        display: flex;
        flex-wrap: wrap;
        gap: 2rem;
        align-items: center;
        justify-content: space-between;
    }

    .pdf-info {
        flex: 1;
        min-width: 250px;
    }

    .pdf-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 0.5rem;
    }

    .pdf-description {
        color: var(--text-muted);
        margin-bottom: 1rem;
    }

    .qr-container {
        padding: 1rem;
        background-color: white;
        border-radius: var(--radius-sm);
        box-shadow: var(--shadow-sm);
        text-align: center;
    }

    .qr-label {
        display: block;
        font-size: 0.8rem;
        color: var(--text-muted);
        margin-top: 0.5rem;
    }

    @media (max-width: 768px) {
        .pdf-section {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .pdf-info {
            width: 100%;
        }
    }
</style>
@endsection

@section('content')
<section class="book-detail-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10 col-12">
                <h1 class="page-title">Detail Buku</h1>
                <p class="text-muted">Informasi lengkap tentang buku perpustakaan SMK Negeri 5 Padang</p>

                <div class="detail-container">
                    <div class="detail-header">
                        <div class="header-content">
                            <h2 class="detail-title">{{ $buku->judul }}</h2>
                            <p class="detail-author">Penulis: {{ $buku->pengarang }}</p>
                        </div>

                        @if($buku->stok > 0)
                        <div class="stock-badge available">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Tersedia ({{ $buku->stok }} buku)</span>
                        </div>
                        @else
                        <div class="stock-badge unavailable">
                            <i class="bi bi-x-circle-fill"></i>
                            <span>Stok Habis</span>
                        </div>
                        @endif
                    </div>

                    <div class="detail-body" style="display: flex; gap: 2rem; align-items: flex-start;">
                        <div style="flex:2;">
                            <div class="detail-row">
                                <div class="detail-label">Penerbit</div>
                                <div class="detail-value">{{ $buku->penerbit }}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Tahun Terbit</div>
                                <div class="detail-value">{{ $buku->tahun_terbit }}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Ketersediaan</div>
                                <div class="detail-value">
                                    @if($buku->stok > 0)
                                        <span class="text-success fw-bold">{{ $buku->stok }} buku tersedia untuk dipinjam</span>
                                    @else
                                        <span class="text-danger fw-bold">Saat ini buku sedang tidak tersedia</span>
                                    @endif
                                </div>
                            </div>
                            <div class="action-buttons mt-4">
                                <a href="{{ route('perpustakaan.buku.index') }}" class="btn-secondary-app">
                                    <i class="bi bi-arrow-left"></i> Kembali
                                </a>
                                @can('manage-perpustakaan')
                                <a href="{{ route('perpustakaan.buku.edit', $buku->id) }}" class="btn-secondary-app">
                                    <i class="bi bi-pencil-square"></i> Edit Buku
                                </a>
                                @endcan
                                @if($buku->stok > 0)
                                    @if(auth()->check() && in_array(auth()->user()->role, ['siswa', 'guru']))
                                    <a href="{{ route('perpustakaan.peminjaman.create', ['buku_id' => $buku->id]) }}" class="btn-secondary-app">
                                        <i class="bi bi-journal-arrow-up"></i> Pinjam Buku
                                    </a>
                                    @elseif(!auth()->check())
                                    <a href="{{ route('login', ['from' => 'perpustakaan']) }}" class="btn-secondary-app">
                                        <i class="bi bi-journal-arrow-up"></i> Pinjam Buku
                                    </a>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div style="min-width:120px;max-width:180px;flex:1;display:flex;align-items:flex-start;justify-content:center;">
                            @if($buku->cover_path)
                                <img src="{{ asset('storage/' . $buku->cover_path) }}" alt="Cover Buku" style="width:100%;max-width:150px;max-height:220px;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,0.08);background:#f5f5f5;">
                            @else
                                <img src="{{ asset('images/default-cover.png') }}" alt="Default Cover" style="width:100%;max-width:150px;max-height:220px;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,0.08);background:#f5f5f5;">
                            @endif
                        </div>
                    </div>

                    @if($buku->pdf_path)
                    <div class="pdf-section">
                        <div class="pdf-info">
                            <h3 class="pdf-title">E-Book Tersedia</h3>
                            <p class="pdf-description">Buku ini tersedia dalam format digital (PDF). Anda dapat mengunduh atau memindai kode QR untuk akses.</p>
                            <a href="{{ route('perpustakaan.buku.pdf', $buku->id) }}" class="btn-secondary-app" target="_blank">
                                <i class="bi bi-file-earmark-pdf"></i> Buka E-Book
                            </a>
                        </div>

                        <div class="qr-container">
                            {!! QrCode::size(150)->generate(route('perpustakaan.buku.pdf', $buku->id)) !!}
                            <span class="qr-label">Pindai untuk mengakses PDF</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
