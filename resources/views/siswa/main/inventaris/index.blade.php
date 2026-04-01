@extends('siswa.layouts.main')

@php
    $role_prefix = Auth::check() && Auth::user()->role == 'guru' ? 'guru' : 'siswa';
@endphp

@section('css')
<style>
    .inventaris-container {
        --primary: #2563eb;
        --primary-dark: #1e40af;
        --secondary: #64748b;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --info: #06b6d4;
        --light: #f8fafc;
        --dark: #1e293b;
        --border: #e2e8f0;
    }

    /* Modern Card Design */
    .alat-card {
        border-radius: 16px;
        background: white;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        height: 100%;
        border: 1px solid var(--border);
    }
    
    .alat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px -8px rgba(0, 0, 0, 0.15);
    }
    
    .alat-image {
        height: 220px;
        object-fit: cover;
        width: 100%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .alat-image-placeholder {
        height: 220px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .alat-content {
        padding: 1.5rem;
    }
    
    /* Badge Styling */
    .kategori-badge {
        display: inline-block;
        font-size: 0.75rem;
        font-weight: 600;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        margin-bottom: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .alat-title {
        font-size: 1.125rem;
        margin-bottom: 0.75rem;
        font-weight: 700;
        color: var(--dark);
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    /* Status Badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.5rem 1rem;
        border-radius: 999px;
        font-size: 0.875rem;
        font-weight: 600;
        letter-spacing: 0.025em;
    }
    
    .status-tersedia {
        background-color: #d1fae5;
        color: #065f46;
    }
    
    .status-tersedia::before {
        content: '';
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #10b981;
    }
    
    .status-dipinjam {
        background-color: #fef3c7;
        color: #92400e;
    }
    
    .status-dipinjam::before {
        content: '';
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #f59e0b;
    }
    
    .status-tidak-tersedia {
        background-color: #fee2e2;
        color: #991b1b;
    }
    
    .status-tidak-tersedia::before {
        content: '';
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #ef4444;
    }
    
    /* Detail Icons */
    .detail-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid var(--border);
    }
    
    .detail-item {
        display: flex;
        align-items: flex-start;
        gap: 0.625rem;
    }
    
    .detail-icon {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        flex-shrink: 0;
        font-size: 0.875rem;
    }
    
    .detail-text {
        flex: 1;
    }
    
    .detail-label {
        font-size: 0.75rem;
        color: var(--secondary);
        margin-bottom: 0.125rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }
    
    .detail-value {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--dark);
        margin: 0;
    }
    
    /* Filter Section */
    .filter-section {
        background: white;
        border-radius: 16px;
        padding: 1.75rem;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        border: 1px solid var(--border);
    }
    
    .filter-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--border);
    }
    
    .filter-header h5 {
        margin: 0;
        font-weight: 700;
        color: var(--dark);
    }
    
    .filter-header i {
        font-size: 1.5rem;
        color: var(--primary);
    }
    
    /* Search Box */
    .search-box {
        position: relative;
    }
    
    .search-box input {
        padding-left: 2.75rem;
        border-radius: 12px;
        border: 2px solid var(--border);
        transition: all 0.3s;
        font-size: 0.9375rem;
    }
    
    .search-box input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
    }
    
    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--secondary);
        font-size: 1.125rem;
    }
    
    /* Form Controls */
    .form-label {
        font-weight: 600;
        color: var(--dark);
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .form-select, .form-control {
        border-radius: 12px;
        border: 2px solid var(--border);
        padding: 0.625rem 1rem;
        transition: all 0.3s;
        font-size: 0.9375rem;
    }
    
    .form-select:focus, .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
    }
    
    /* Buttons */
    .btn-modern {
        border-radius: 12px;
        padding: 0.625rem 1.5rem;
        font-weight: 600;
        font-size: 0.9375rem;
        transition: all 0.3s;
        border: none;
    }
    
    .btn-primary-modern {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .btn-primary-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px -4px rgba(102, 126, 234, 0.5);
    }
    
    .btn-outline-modern {
        border: 2px solid var(--border);
        background: white;
        color: var(--dark);
    }
    
    .btn-outline-modern:hover {
        border-color: var(--primary);
        color: var(--primary);
        background: rgba(37, 99, 235, 0.05);
    }
    
    .btn-detail {
        width: 100%;
        margin-top: 1rem;
        padding: 0.75rem;
        border-radius: 12px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-weight: 600;
        font-size: 0.9375rem;
        transition: all 0.3s;
        border: none;
    }
    
    .btn-detail:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px -4px rgba(102, 126, 234, 0.5);
        color: white;
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }
    
    .empty-icon {
        font-size: 5rem;
        color: var(--border);
        margin-bottom: 1.5rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .empty-state h5 {
        color: var(--dark);
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .empty-state p {
        color: var(--secondary);
        font-size: 0.9375rem;
    }
    
    /* Pagination */
    .pagination {
        gap: 0.5rem;
        align-items: center;
    }
    
    .page-link {
        border-radius: 10px;
        border: 2px solid var(--border);
        color: var(--dark);
        font-weight: 600;
        padding: 0.5rem 1rem;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 40px;
        min-width: 40px;
    }
    
    /* Fix large arrows in Laravel default pagination */
    .page-link svg {
        width: 16px !important;
        height: 16px !important;
        max-width: 16px !important;
        max-height: 16px !important;
    }
    
    .page-link:hover {
        border-color: var(--primary);
        background: rgba(37, 99, 235, 0.05);
        color: var(--primary);
    }
    
    .page-item.active .page-link {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: transparent;
        color: white;
    }
    
    /* Sort Dropdown */
    .sort-select {
        min-width: 200px;
    }
    
    /* Kondisi Badge */
    .kondisi-badge {
        display: inline-block;
        padding: 0.25rem 0.625rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .kondisi-sangat-baik {
        background-color: #d1fae5;
        color: #065f46;
    }
    
    .kondisi-baik {
        background-color: #dbeafe;
        color: #1e40af;
    }
    
    .kondisi-rusak-ringan {
        background-color: #fef3c7;
        color: #92400e;
    }
    
    .kondisi-rusak-sedang {
        background-color: #fed7aa;
        color: #9a3412;
    }
    
    .kondisi-rusak-berat {
        background-color: #fee2e2;
        color: #991b1b;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .filter-section {
            padding: 1.25rem;
        }
        
        .alat-card {
            margin-bottom: 1rem;
        }
        
        .detail-grid {
            grid-template-columns: 1fr;
        }
    }
    
    /* Page Header */
    .page-header {
        margin-bottom: 2rem;
    }
    
    .page-title {
        font-size: 2rem;
        font-weight: 800;
        color: var(--dark);
        margin-bottom: 0.5rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .page-subtitle {
        color: var(--secondary);
        font-size: 1rem;
        margin: 0;
    }
</style>
@endsection

@section('content')
<div class="container-fluid inventaris-container">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">📦 Inventaris Laboratorium</h1>
        <p class="page-subtitle">Daftar peralatan dan perlengkapan yang tersedia di laboratorium</p>
    </div>
    
    <!-- Filter Section -->
    <div class="filter-section">
        <div class="filter-header">
            <i class="bi bi-funnel"></i>
            <h5>Filter & Pencarian</h5>
        </div>
        
        <form action="{{ route($role_prefix . '.inventaris.index') }}" method="GET" id="filterForm">
            <div class="row g-3">
                <!-- Search -->
                <div class="col-md-4">
                    <label for="search" class="form-label">Pencarian</label>
                    <div class="search-box">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text" class="form-control" id="search" name="search" 
                               placeholder="Cari nama alat..." value="{{ request('search') }}">
                    </div>
                </div>
                
                <!-- Category Filter -->
                <div class="col-md-2">
                    <label for="kategori" class="form-label">Kategori</label>
                    <select class="form-select" id="kategori" name="kategori">
                        <option value="">Semua</option>
                        @foreach($kategoriList as $kategori)
                            <option value="{{ $kategori }}" {{ request('kategori') == $kategori ? 'selected' : '' }}>
                                {{ $kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Laboratory Filter -->
                <div class="col-md-2">
                    <label for="labor" class="form-label">Laboratorium</label>
                    <select class="form-select" id="labor" name="labor">
                        <option value="">Semua</option>
                        @foreach($laborList as $labor)
                            <option value="{{ $labor->id }}" {{ request('labor') == $labor->id ? 'selected' : '' }}>
                                {{ $labor->nama_labor }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Status Filter -->
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Semua</option>
                        @foreach($statusList as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Condition Filter -->
                <div class="col-md-2">
                    <label for="kondisi" class="form-label">Kondisi</label>
                    <select class="form-select" id="kondisi" name="kondisi">
                        <option value="">Semua</option>
                        @foreach($kondisiList as $kondisi)
                            <option value="{{ $kondisi }}" {{ request('kondisi') == $kondisi ? 'selected' : '' }}>
                                {{ $kondisi }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="row mt-3">
                <div class="col-md-8">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary-modern btn-modern">
                            <i class="bi bi-search me-2"></i>Terapkan Filter
                        </button>
                        <a href="{{ route($role_prefix . '.inventaris.index') }}" class="btn btn-outline-modern btn-modern">
                            <i class="bi bi-arrow-clockwise me-2"></i>Reset
                        </a>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="sort" class="form-label">Urutkan</label>
                    <select class="form-select sort-select" id="sort" name="sort" onchange="this.form.submit()">
                        <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                        <option value="nama" {{ request('sort') == 'nama' ? 'selected' : '' }}>Nama (A-Z)</option>
                        <option value="stok_terbanyak" {{ request('sort') == 'stok_terbanyak' ? 'selected' : '' }}>Stok Terbanyak</option>
                        <option value="kondisi_terbaik" {{ request('sort') == 'kondisi_terbaik' ? 'selected' : '' }}>Kondisi Terbaik</option>
                    </select>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Results Count -->
    @if($alat->total() > 0)
    <div class="mb-3">
        <p class="text-muted">
            <i class="bi bi-info-circle me-2"></i>
            Menampilkan {{ $alat->firstItem() }} - {{ $alat->lastItem() }} dari {{ $alat->total() }} alat
        </p>
    </div>
    @endif
    
    <!-- Equipment Grid -->
    <div class="row g-4">
        @forelse($alat as $item)
        <div class="col-lg-4 col-md-6">
            <div class="alat-card">
                @if($item->gambar)
                    <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama_inventaris }}" class="alat-image">
                @else
                    <div class="alat-image-placeholder">
                        <i class="bi bi-box-seam text-white" style="font-size: 4rem; opacity: 0.5;"></i>
                    </div>
                @endif
                
                <div class="alat-content">
                    <span class="kategori-badge">{{ $item->kategori }}</span>
                    <h3 class="alat-title" title="{{ $item->nama_inventaris }}">{{ $item->nama_inventaris }}</h3>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $item->status)) }}">
                            {{ $item->status }}
                        </span>
                        <span class="text-dark fw-bold">
                            <i class="bi bi-box me-1"></i>{{ $item->jumlah }} Unit
                        </span>
                    </div>
                    
                    <div class="detail-grid">
                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="bi bi-building"></i>
                            </div>
                            <div class="detail-text">
                                <p class="detail-label">Lokasi</p>
                                <p class="detail-value">{{ $item->labor ? $item->labor->nama_labor : $item->lokasi }}</p>
                            </div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <div class="detail-text">
                                <p class="detail-label">Kondisi</p>
                                <p class="detail-value">
                                    @php
                                        $kondisiClass = '';
                                        switch($item->kondisi) {
                                            case 'Sangat Baik': $kondisiClass = 'kondisi-sangat-baik'; break;
                                            case 'Baik': $kondisiClass = 'kondisi-baik'; break;
                                            case 'Rusak Ringan': $kondisiClass = 'kondisi-rusak-ringan'; break;
                                            case 'Rusak Sedang': $kondisiClass = 'kondisi-rusak-sedang'; break;
                                            case 'Rusak Berat': $kondisiClass = 'kondisi-rusak-berat'; break;
                                            default: $kondisiClass = 'kondisi-baik';
                                        }
                                    @endphp
                                    <span class="kondisi-badge {{ $kondisiClass }}" 
                                          @if(in_array($item->kondisi, ['Rusak Ringan', 'Rusak Sedang']))
                                          data-bs-toggle="tooltip" 
                                          title="Alat masih bisa digunakan dengan catatan tertentu"
                                          @endif>
                                        {{ $item->kondisi ?? 'Baik' }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <a href="{{ route($role_prefix . '.inventaris.show', $item->id) }}" class="btn-detail">
                        <i class="bi bi-eye"></i>
                        Lihat Detail
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="empty-state">
                <i class="bi bi-inbox empty-icon"></i>
                <h5>Tidak Ada Data Alat</h5>
                <p>Tidak ada alat yang sesuai dengan filter yang Anda pilih. Coba ubah filter atau reset pencarian.</p>
                <a href="{{ route($role_prefix . '.inventaris.index') }}" class="btn btn-primary-modern btn-modern mt-3">
                    <i class="bi bi-arrow-clockwise me-2"></i>Reset Filter
                </a>
            </div>
        </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    @if($alat->hasPages())
    <div class="d-flex justify-content-center mt-5">
        {{ $alat->appends(request()->except('page'))->links() }}
    </div>
    @endif
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        // Auto-submit on sort change (already in HTML)
        
        // Optional: Add loading state when submitting form
        $('#filterForm').on('submit', function() {
            $(this).find('button[type="submit"]').html('<span class="spinner-border spinner-border-sm me-2"></span>Memuat...');
        });
    });
</script>
@endsection