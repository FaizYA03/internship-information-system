@extends('perpustakaan.layouts.main')

@section('content')
<style>
    /* Fixed badge positioning */
    .pdf-badge {
        position: absolute;
        top: 1rem;
        left: 1rem;
        background-color: var(--primary);
        color: white;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        box-shadow: var(--shadow-sm);
        z-index: 2;
    }

    .pdf-icon {
        color: var(--primary);
        margin-left: 0.5rem;
        font-size: 0.9rem;
    }

    .book-card-header {
        padding-top: 3.5rem !important; /* Increased padding to accommodate badges */
        position: relative;
    }

    .stock-badge {
        top: 1rem;
        right: 1rem;
        position: absolute;
        z-index: 2;
    }

    .badge-container {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3rem;
        display: flex;
        justify-content: space-between;
        padding: 1rem;
        pointer-events: none;
    }

    .badge-container > * {
        pointer-events: auto;
    }

    .book-title {
        margin-top: 0.5rem;
        width: 100%;
        max-width: 100%;
    }
    .cover-img {
        display: block;
        margin: 0 auto 0.5rem auto;
        max-height: 120px;
        border-radius: 8px;
        object-fit: cover;
        background: #f5f5f5;
        box-shadow: var(--shadow-xs);
    }
    .table-cover-img {
        max-height: 60px;
        border-radius: 6px;
        object-fit: cover;
        background: #f5f5f5;
    }

    /* Style button for pinjam */
    .btn-pinjam {
        background-color: var(--primary);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: var(--radius-sm);
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: var(--transition);
        text-decoration: none;
        border: none;
    }
    .btn-pinjam:hover {
        background-color: var(--primary-dark);
        color: white;
    }
    .btn-pinjam.disabled {
        background-color: #6c757d;
        cursor: not-allowed;
        opacity: 0.65;
    }
</style>

<section class="book-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <a href="{{ route('perpustakaan.kategori.index') }}" class="btn-secondary-app mb-3" style="display:inline-block;">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <h1 class="page-title">Detail Kategori: {{ $kategori->nama_kategori }}</h1>
                    <p class="text-muted">Daftar buku yang termasuk dalam kategori ini</p>
                </div>

                <div class="actions-row">
                    <div class="toggle-view">
                        <button type="button" class="view-btn active" id="gridViewBtn">
                            <i class="bi bi-grid-3x3-gap-fill"></i> Grid
                        </button>
                        <button type="button" class="view-btn" id="tableViewBtn">
                            <i class="bi bi-table"></i> Tabel
                        </button>
                    </div>
                </div>

                @if($kategori->books->count() > 0)
                    <!-- Grid View -->
                    <div class="book-grid" id="gridView">
                        @foreach($kategori->books as $b)
                        <div class="book-card">
                            <div class="book-card-header">
                                <div class="badge-container">
                                    @if($b->pdf_path)
                                    <span class="pdf-badge" title="PDF tersedia">
                                        <i class="bi bi-file-earmark-pdf"></i>
                                    </span>
                                    @endif

                                    <span class="stock-badge {{ $b->stok > 0 ? 'in-stock' : 'out-of-stock' }}">
                                        <i class="bi {{ $b->stok > 0 ? 'bi-check-circle' : 'bi-x-circle' }}"></i>
                                        {{ $b->stok > 0 ? 'Tersedia' : 'Stok Habis' }}
                                    </span>
                                </div>
                                {{-- Tampilkan cover buku --}}
                                @if($b->cover_path)
                                    <img src="{{ asset('storage/' . $b->cover_path) }}" alt="Cover Buku" class="cover-img">
                                @else
                                    <img src="{{ asset('images/default-cover.png') }}" alt="Default Cover" class="cover-img">
                                @endif
                                <h3 class="book-title">{{ $b->judul }}</h3>
                                <p class="book-author">{{ $b->pengarang }}</p>
                            </div>
                            <div class="book-card-body">
                                <div class="book-details">
                                    <span class="book-detail-item">
                                        <i class="bi bi-building"></i> {{ $b->penerbit }}
                                    </span>
                                    <span class="book-detail-item">
                                        <i class="bi bi-calendar3"></i> {{ $b->tahun_terbit }}
                                    </span>
                                    <span class="book-detail-item">
                                        <i class="bi bi-journal-bookmark"></i> Stok: {{ $b->stok }}
                                    </span>
                                </div>
                                <div class="book-actions mt-3">
                                    <a href="{{ route('perpustakaan.buku.show', $b->id) }}" class="btn-secondary-app w-100 justify-content-center">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Table View -->
                    <div class="table-container d-none" id="tableView">
                        <div class="table-responsive table-modern">
                            <table class="table" id="data-table">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Cover</th>
                                    <th>Judul</th>
                                    <th>Pengarang</th>
                                    <th>Penerbit</th>
                                    <th>Tahun</th>
                                    <th>Stok</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kategori->books as $index => $b)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if($b->cover_path)
                                            <img src="{{ asset('storage/' . $b->cover_path) }}" alt="Cover Buku" class="table-cover-img">
                                        @else
                                            <img src="{{ asset('images/default-cover.png') }}" alt="Default Cover" class="table-cover-img">
                                        @endif
                                    </td>
                                    <td>
                                        {{ $b->judul }}
                                        @if($b->pdf_path)
                                        <i class="bi bi-file-earmark-pdf pdf-icon" title="PDF tersedia"></i>
                                        @endif
                                    </td>
                                    <td>{{ $b->pengarang }}</td>
                                    <td>{{ $b->penerbit }}</td>
                                    <td>{{ $b->tahun_terbit }}</td>
                                    <td>
                                        @if($b->stok > 0)
                                            <span class="badge bg-success">{{ $b->stok }}</span>
                                        @else
                                            <span class="badge bg-danger">Habis</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('perpustakaan.buku.show', $b->id) }}" class="btn-secondary-app btn-sm" title="Detail">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <!-- Enhanced empty state section -->
                    <div class="empty-state text-center py-5">
                        <i class="bi bi-journal-x" style="font-size: 4rem; color: #ccc;"></i>
                        <p class="mt-3">Belum ada buku yang tersedia di kategori ini</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
