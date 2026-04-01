@extends('siswa.layouts.main')

@php
    $role_prefix = Auth::check() && Auth::user()->role == 'guru' ? 'guru' : 'siswa';
@endphp

@section('css')
<style>
    /* ======================================
       LABORATORIUM PAGE - SISWA
       Modern Dashboard-like UI
    ====================================== */
    
    :root {
        --color-primary: #4361ee;
        --color-primary-light: #7186f5;
        --color-secondary: #3f37c9;
        --color-success: #10b981;
        --color-warning: #f59e0b;
        --color-danger: #ef4444;
        --color-info: #3b82f6;
        
        /* Soft Pastel Backgrounds */
        --bg-soft-blue: #e0e7ff;
        --bg-soft-purple: #ede9fe;
        --bg-soft-yellow: #fef3c7;
        --bg-soft-green: #d1fae5;
        
        /* Typography */
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --text-light: #94a3b8;
        
        /* Spacing & Borders */
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 16px;
        --shadow-sm: 0 1px 3px rgba(0,0,0,0.08);
        --shadow-md: 0 4px 6px rgba(0,0,0,0.08);
        --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1);
    }
    
    body {
        background-color: #f8fafc;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }
    
    /* ======================================
       PAGE HEADER
    ====================================== */
    .page-header {
        margin-bottom: 2rem;
    }
    
    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
    }
    
    .page-subtitle {
        color: var(--text-muted);
        font-size: 0.95rem;
    }
    
    /* ======================================
       SUMMARY CARD STATISTICS
    ====================================== */
    .summary-card {
        background: white;
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        box-shadow: var(--shadow-sm);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(0,0,0,0.04);
        height: 100%;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .summary-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-md);
    }
    
    .summary-icon {
        width: 56px;
        height: 56px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }
    
    .icon-blue { background: var(--bg-soft-blue); color: var(--color-info); }
    .icon-purple { background: var(--bg-soft-purple); color: #8b5cf6; }
    .icon-yellow { background: var(--bg-soft-yellow); color: #f59e0b; }
    .icon-green { background: var(--bg-soft-green); color: var(--color-success); }
    
    .summary-content {
        flex: 1;
    }
    
    .summary-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-dark);
        line-height: 1;
        margin-bottom: 0.25rem;
    }
    
    .summary-label {
        font-size: 0.85rem;
        color: var(--text-muted);
        font-weight: 500;
    }
    
    /* ======================================
       QUICK ACTION CARDS
    ====================================== */
    .quick-action-card {
        background: white;
        border-radius: var(--radius-lg);
        padding: 2rem;
        box-shadow: var(--shadow-sm);
        border: 1px solid rgba(0,0,0,0.04);
        height: 100%;
    }
    
    .quick-action-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f1f5f9;
    }
    
    .quick-action-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-dark);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .quick-action-title i {
        color: var(--color-primary);
    }
    
    .action-count-badge {
        background: var(--color-primary);
        color: white;
        padding: 0.25rem 0.65rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    /* Quick Action List Items */
    .quick-item {
        background: #f8fafc;
        border-radius: var(--radius-sm);
        padding: 1rem;
        margin-bottom: 0.75rem;
        transition: all 0.2s;
        border-left: 3px solid transparent;
    }
    
    .quick-item:hover {
        background: #f1f5f9;
        border-left-color: var(--color-primary);
    }
    
    .quick-item-title {
        font-weight: 600;
        color: var(--text-dark);
        font-size: 0.95rem;
        margin-bottom: 0.25rem;
    }
    
    .quick-item-meta {
        font-size: 0.8rem;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .quick-item-meta i {
        margin-right: 0.25rem;
    }
    
    .status-badge {
        padding: 0.25rem 0.65rem;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }
    
    .badge-pending { background: #fef3c7; color: #92400e; }
    .badge-approved { background: #d1fae5; color: #065f46; }
    .badge-process { background: #dbeafe; color: #1e40af; }
    
    /* Empty State */
    .empty-state-small {
        text-align: center;
        padding: 2rem 1rem;
        color: var(--text-light);
    }
    
    .empty-state-small i {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
        opacity: 0.3;
    }
    
    .empty-state-small p {
        font-size: 0.85rem;
        margin: 0;
    }
    
    /* Quick Action Buttons */
    .btn-action-group {
        display: flex;
        gap: 0.75rem;
        margin-top: 1rem;
    }
    
    .btn-outline-custom {
        flex: 1;
        padding: 0.6rem 1rem;
        border: 1.5px solid #e2e8f0;
        border-radius: var(--radius-sm);
        background: white;
        color: var(--text-dark);
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.2s;
        text-align: center;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .btn-outline-custom:hover {
        border-color: var(--color-primary);
        color: var(--color-primary);
        background: rgba(67, 97, 238, 0.05);
    }
    
    .btn-primary-custom {
        flex: 1;
        padding: 0.6rem 1rem;
        border: none;
        border-radius: var(--radius-sm);
        background: linear-gradient(135deg, var(--color-primary), var(--color-primary-light));
        color: white;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.2s;
        text-align: center;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        box-shadow: 0 2px 4px rgba(67, 97, 238, 0.2);
    }
    
    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(67, 97, 238, 0.3);
        color: white;
    }
    
    /* ======================================
       LABORATORIUM GRID SECTION
    ====================================== */
    .section-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .section-title i {
        color: var(--color-primary);
    }
    
    /* Labor Card */
    .labor-card {
        background: white;
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(0,0,0,0.04);
        height: 100%;
        position: relative;
    }
    
    .labor-card:hover {
        transform: translateY(-6px);
        box-shadow: var(--shadow-lg);
    }
    
    /* Used by Student's Class Highlight */
    .labor-card.my-class {
        border: 2px solid var(--color-primary);
    }
    
    .labor-card.my-class::before {
        content: "Digunakan oleh kelas Anda";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        background: linear-gradient(135deg, var(--color-primary), var(--color-primary-light));
        color: white;
        padding: 0.35rem;
        text-align: center;
        font-size: 0.75rem;
        font-weight: 600;
        z-index: 2;
        letter-spacing: 0.025em;
    }
    
    .labor-image-wrapper {
        position: relative;
        overflow: hidden;
        background: #f1f5f9; /* Fallback background color */
        height: 180px;
    }
    
    /* Dark overlay gradient for better text/badge readability */
    .labor-image-wrapper::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(180deg, rgba(0,0,0,0.2) 0%, rgba(0,0,0,0.05) 100%);
        pointer-events: none;
        z-index: 1;
    }
    
    .labor-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s;
        display: block;
    }
    
    /* Styled placeholder for missing image */
    .labor-image-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        color: rgba(255,255,255,0.8);
        text-align: center;
        padding: 1rem;
    }
    
    .labor-image-placeholder i {
        font-size: 3rem;
        margin-bottom: 0.5rem;
        opacity: 0.5;
    }
    
    .labor-card:hover .labor-image {
        transform: scale(1.05);
    }
    
    .labor-status-badge {
        position: absolute;
        top: 0.75rem;
        right: 0.75rem;
        padding: 0.4rem 0.9rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        box-shadow: var(--shadow-md);
        letter-spacing: 0.025em;
        max-width: calc(100% - 1.5rem);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        z-index: 2;  /* Ensure badge is above gradient overlay */
    }
    
    .badge-tersedia { background: var(--color-success); color: white; }
    .badge-digunakan { background: var(--color-warning); color: white; }
    .badge-no-equipment { background: #94a3b8; color: white; }
    
    .labor-card-body {
        padding: 1.5rem;
    }
    
    .labor-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
        gap: 0.5rem;
    }
    
    .labor-name {
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .labor-description {
        font-size: 0.85rem;
        color: var(--text-muted);
        margin-bottom: 1rem;
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .labor-info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
        margin-bottom: 1rem;
        padding: 1rem;
        background: #f8fafc;
        border-radius: var(--radius-sm);
    }
    
    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .info-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        flex-shrink: 0;
    }
    
    .icon-teacher { background: rgba(67, 97, 238, 0.1); color: var(--color-primary); }
    .icon-technician { background: rgba(59, 130, 246, 0.1); color: var(--color-info); }
    
    .info-content {
        flex: 1;
        min-width: 0;
    }
    
    .info-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        color: var(--text-light);
        font-weight: 600;
        letter-spacing: 0.025em;
        margin-bottom: 0.15rem;
    }
    
    .info-value {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-dark);
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .equipment-count {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-radius: var(--radius-sm);
        margin-bottom: 1rem;
        border: 1px solid #e2e8f0;
    }
    
    .equipment-count.no-equipment {
        background: linear-gradient(135deg, #fee, #fdd);
        border-color: #fcc;
    }
    
    .equipment-count i {
        font-size: 1.5rem;
        color: var(--color-primary);
    }
    
    .equipment-count.no-equipment i {
        color: #94a3b8;
    }
    
    .equipment-count-text {
        flex: 1;
    }
    
    .equipment-number {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-dark);
    }
    
    .equipment-label {
        font-size: 0.75rem;
        color: var(--text-muted);
    }
    
    .labor-actions {
        display: flex;
        gap: 0.5rem;
    }
    
    .btn-labor {
        flex: 1;
        padding: 0.65rem;
        border-radius: var(--radius-sm);
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.2s;
        text-align: center;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .btn-labor-outline {
        border: 1.5px solid #e2e8f0;
        background: white;
        color: var(--text-dark);
    }
    
    .btn-labor-outline:hover {
        border-color: var(--color-primary);
        color: var(--color-primary);
        background: rgba(67, 97, 238, 0.05);
    }
    
    .btn-labor-primary {
        border: none;
        background: var(--color-primary);
        color: white;
        box-shadow: 0 2px 4px rgba(67, 97, 238, 0.2);
    }
    
    .btn-labor-primary:hover:not(.disabled) {
        background: var(--color-primary-light);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(67, 97, 238, 0.3);
        color: white;
    }
    
    .btn-labor-primary.disabled {
        background: #cbd5e1;
        cursor: not-allowed;
        opacity: 0.6;
        pointer-events: none;
    }
    
    /* ======================================
       FILTER SECTION
    ====================================== */
    .filter-card {
        background: white;
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        box-shadow: var(--shadow-sm);
        margin-bottom: 2rem;
        border: 1px solid rgba(0,0,0,0.04);
    }
    
    .form-label-custom {
        font-size: 0.75rem;
        text-transform: uppercase;
        font-weight: 700;
        color: var(--text-muted);
        letter-spacing: 0.025em;
        margin-bottom: 0.5rem;
    }
    
    .form-control-custom, .form-select-custom {
        border: 1.5px solid #e2e8f0;
        border-radius: var(--radius-sm);
        padding: 0.65rem 1rem;
        font-size: 0.9rem;
        transition: all 0.2s;
    }
    
    .form-control-custom:focus, .form-select-custom:focus {
        border-color: var(--color-primary);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }
    
    /* ======================================
       SKELETON LOADING
    ====================================== */
    @keyframes shimmer {
        0% { background-position: -468px 0; }
        100% { background-position: 468px 0; }
    }
    
    .skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 468px 100%;
        animation: shimmer 1.2s infinite linear;
        border-radius: var(--radius-sm);
    }
    
    .skeleton-card {
        height: 400px;
        border-radius: var(--radius-lg);
    }
    
    /* ======================================
       RESPONSIVE
    ====================================== */
    @media (min-width: 992px) {
        .labor-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
        }
    }

    @media (max-width: 991px) and (min-width: 768px) {
        .labor-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }
    }

    @media (max-width: 767px) {
        .labor-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
    }

    @media (max-width: 768px) {
        .page-title { font-size: 1.5rem; }
        .summary-number { font-size: 1.75rem; }
        .labor-info-grid { grid-template-columns: 1fr; }
        .btn-action-group { flex-direction: column; }
    }
    
    /* ======================================
       ANIMATIONS
    ====================================== */
    .fade-in {
        animation: fadeIn 0.5s ease-in-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* Stagger animation delays */
    .delay-1 { animation-delay: 0.1s; }
    .delay-2 { animation-delay: 0.2s; }
    .delay-3 { animation-delay: 0.3s; }
</style>
@endsection

@section('content')
<div class="container-fluid px-4 py-4">
    
    {{-- ======================================
         PAGE HEADER
    ====================================== --}}
    <div class="page-header fade-in">
        <h1 class="page-title">Laboratorium</h1>
        <p class="page-subtitle mb-0">Kelola kegiatan praktikum dan peminjaman alat laboratorium</p>
    </div>
    
    {{-- ======================================
         SUMMARY CARD STATISTICS
    ====================================== --}}
    @if($role_prefix == 'guru')
    <div class="row g-3 mb-4 fade-in delay-1">
        {{-- Jadwal Mengajar Hari Ini --}}
        <div class="col-6 col-md-3">
            <div class="summary-card">
                <div class="summary-icon icon-blue">
                    <i class="bi bi-calendar-day"></i>
                </div>
                <div class="summary-content">
                    <div class="summary-number">{{ $stats['jadwal_hari_ini'] }}</div>
                    <div class="summary-label">Jadwal Mengajar Hari Ini</div>
                </div>
            </div>
        </div>
        
        {{-- Total Jadwal Minggu Ini --}}
        <div class="col-6 col-md-3">
            <div class="summary-card">
                <div class="summary-icon icon-purple">
                    <i class="bi bi-calendar-week"></i>
                </div>
                <div class="summary-content">
                    <div class="summary-number">{{ $stats['jadwal_minggu_ini'] }}</div>
                    <div class="summary-label">Total Jadwal Minggu Ini</div>
                </div>
            </div>
        </div>
        
        {{-- Peminjaman Aktif --}}
        <div class="col-6 col-md-3">
            <div class="summary-card">
                <div class="summary-icon icon-yellow">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div class="summary-content">
                    <div class="summary-number">{{ $stats['peminjaman_aktif'] }}</div>
                    <div class="summary-label">Peminjaman Aktif</div>
                </div>
            </div>
        </div>
        
        {{-- Laporan Kerusakan dari Kelas --}}
        <div class="col-6 col-md-3">
            <div class="summary-card">
                <div class="summary-icon icon-red">
                    <i class="bi bi-tools"></i>
                </div>
                <div class="summary-content">
                    <div class="summary-number">{{ $stats['laporan_kelas'] }}</div>
                    <div class="summary-label">Laporan Dari Kelas</div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row g-3 mb-4 fade-in delay-1">
        {{-- Total Laboratorium --}}
        <div class="col-6 col-md-3">
            <div class="summary-card">
                <div class="summary-icon icon-blue">
                    <i class="bi bi-building"></i>
                </div>
                <div class="summary-content">
                    <div class="summary-number">{{ $stats['total_laboratorium'] }}</div>
                    <div class="summary-label">Total Laboratorium</div>
                </div>
            </div>
        </div>
        
        {{-- Peminjaman Aktif --}}
        <div class="col-6 col-md-3">
            <div class="summary-card">
                <div class="summary-icon icon-purple">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div class="summary-content">
                    <div class="summary-number">{{ $stats['peminjaman_aktif'] }}</div>
                    <div class="summary-label">Peminjaman Aktif</div>
                </div>
            </div>
        </div>
        
        {{-- Laporan Kerusakan Saya --}}
        <div class="col-6 col-md-3">
            <div class="summary-card">
                <div class="summary-icon icon-yellow">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <div class="summary-content">
                    <div class="summary-number">{{ $stats['laporan_saya'] }}</div>
                    <div class="summary-label">Laporan Saya</div>
                </div>
            </div>
        </div>
        
        {{-- Jadwal Hari Ini --}}
        <div class="col-6 col-md-3">
            <div class="summary-card">
                <div class="summary-icon icon-green">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <div class="summary-content">
                    <div class="summary-number">{{ $stats['jadwal_hari_ini'] }}</div>
                    <div class="summary-label">Jadwal Hari Ini</div>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    {{-- ======================================
         QUICK ACTION SECTION
    ====================================== --}}
    <div class="row g-4 mb-4 fade-in delay-2">
        {{-- Jadwal Mengajar Hari Ini (Mini Card) --}}
        <div class="col-lg-6">
            <div class="quick-action-card">
                <div class="quick-action-header">
                    <div class="quick-action-title">
                        <i class="bi bi-calendar-event"></i>
                        Jadwal Hari Ini
                    </div>
                </div>
                
                @if($todaySchedules->count() > 0)
                    @foreach($todaySchedules as $js)
                        <div class="quick-item">
                            <div class="quick-item-title">{{ $js->labor->nama_labor ?? 'Laboratorium' }}</div>
                            <div class="quick-item-meta">
                                <span><i class="bi bi-clock"></i> {{ $js->jam_mulai }} - {{ $js->jam_selesai }}</span>
                                <span><i class="bi bi-people"></i> {{ $js->kelas ?? '-' }}</span>
                                <span class="status-badge badge-approved">Aktif</span>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="empty-state-small">
                        <i class="bi bi-calendar-x"></i>
                        <p>Tidak ada jadwal mengajar hari ini</p>
                    </div>
                @endif
                
                <div class="btn-action-group">
                    <a href="{{ route($role_prefix . '.peminjaman.ruangan.create') }}" class="btn-outline-custom">
                        <i class="bi bi-door-open"></i> Ajukan Peminjaman Ruangan
                    </a>
                    <a href="{{ route($role_prefix . '.peminjaman.create') }}" class="btn-primary-custom">
                        <i class="bi bi-plus-lg"></i> Ajukan Peminjaman Alat
                    </a>
                </div>
            </div>
        </div>
        
        {{-- Laporan Kerusakan Terbaru --}}
        <div class="col-lg-6">
            <div class="quick-action-card">
                <div class="quick-action-header">
                    <div class="quick-action-title">
                        <i class="bi bi-exclamation-triangle"></i>
                        Laporan Kerusakan Terbaru
                    </div>
                    @if($recentReports->count() > 0)
                        <span class="action-count-badge">{{ $recentReports->count() }}</span>
                    @endif
                </div>
                
                @if($recentReports->count() > 0)
                    @foreach($recentReports as $report)
                        <div class="quick-item">
                            <div class="quick-item-title">{{ $report->nama_alat }}</div>
                            <div class="quick-item-meta">
                                <span><i class="bi bi-geo-alt"></i>{{ $report->lokasi ?? 'Lokasi tidak tercatat' }}</span>
                                <span><i class="bi bi-calendar3"></i>{{ \Carbon\Carbon::parse($report->tanggal_laporan)->format('d M Y') }}</span>
                                <span class="status-badge badge-{{ $report->status }}">{{ ucfirst($report->status) }}</span>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="empty-state-small">
                        <i class="bi bi-check-circle"></i>
                        <p>Belum ada laporan kerusakan</p>
                    </div>
                @endif
                
                <div class="btn-action-group">
                    <a href="{{ route($role_prefix . '.laporan.index') }}" class="btn-outline-custom">
                        <i class="bi bi-list-ul"></i> Semua Laporan
                    </a>
                    <a href="{{ route($role_prefix . '.laporan.create') }}" class="btn-primary-custom">
                        <i class="bi bi-plus-lg"></i> Buat Laporan
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    {{-- ======================================
         FILTER & SEARCH
    ====================================== --}}
    <div class="filter-card fade-in delay-3">
        <form action="{{ route($role_prefix . '.labor.index') }}" method="GET">
            <div class="row g-3 align-items-end">
                {{-- Search --}}
                <div class="col-md-4">
                    <label for="search" class="form-label form-label-custom">Cari Laboratorium</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" class="form-control form-control-custom border-start-0 ps-0" id="search" name="search" 
                            value="{{ request('search') }}" placeholder="Nama laboratorium...">
                    </div>
                </div>
                
                {{-- Filter Jenis --}}
                <div class="col-md-3">
                    <label for="jenis" class="form-label form-label-custom">Jenis Laboratorium</label>
                    <select class="form-select form-select-custom" id="jenis" name="jenis">
                        <option value="">Semua Jenis</option>
                        @foreach($jenisLaborList as $jenis)
                            <option value="{{ $jenis }}" {{ request('jenis') == $jenis ? 'selected' : '' }}>
                                {{ $jenis }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                {{-- Sort --}}
                <div class="col-md-3">
                    <label for="sort" class="form-label form-label-custom">Urutkan</label>
                    <select class="form-select form-select-custom" id="sort" name="sort">
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama (A-Z)</option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama (Z-A)</option>
                        <option value="tools_desc" {{ request('sort') == 'tools_desc' ? 'selected' : '' }}>Alat Terbanyak</option>
                        <option value="tools_asc" {{ request('sort') == 'tools_asc' ? 'selected' : '' }}>Alat Terdikit</option>
                    </select>
                </div>
                
                {{-- Button --}}
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100 fw-bold" style="padding: 0.65rem;">
                        Terapkan
                    </button>
                </div>
            </div>
        </form>
    </div>
    
    {{-- ======================================
         DAFTAR LABORATORIUM
    ====================================== --}}
    <div class="mb-4">
        <h2 class="section-title">
            <i class="bi bi-grid-3x3-gap"></i>
            Daftar Laboratorium
        </h2>
    </div>
    
    <div class="labor-grid mb-5">
        @forelse($labor as $lab)
            @php
                $alatCount = $lab->alat_tersedia_count ?? 0;
                $hasFoto   = !empty($lab->foto) && file_exists(public_path('storage/labor_foto/' . $lab->foto));
                $jenisData = $lab->jenisData;
                
                // Dynamic colors from DB mapping
                $colorMap = [
                    'primary'   => ['grad' => 'linear-gradient(135deg, #2563eb 0%, #3b82f6 100%)', 'icon' => 'bi-laptop'],
                    'danger'    => ['grad' => 'linear-gradient(135deg, #dc2626 0%, #ef4444 100%)', 'icon' => 'bi-flask'],
                    'warning'   => ['grad' => 'linear-gradient(135deg, #d97706 0%, #f59e0b 100%)', 'icon' => 'bi-lightning'],
                    'success'   => ['grad' => 'linear-gradient(135deg, #059669 0%, #10b981 100%)', 'icon' => 'bi-translate'],
                    'purple'    => ['grad' => 'linear-gradient(135deg, #7c3aed 0%, #8b5cf6 100%)', 'icon' => 'bi-camera-video'],
                    'info'      => ['grad' => 'linear-gradient(135deg, #0891b2 0%, #06b6d4 100%)', 'icon' => 'bi-info-circle'],
                    'secondary' => ['grad' => 'linear-gradient(135deg, #475569 0%, #64748b 100%)', 'icon' => 'bi-building'],
                ];

                $warnaKey = $jenisData->warna ?? 'primary';
                $config   = $colorMap[$warnaKey] ?? $colorMap['primary'];
                
                $bgGradient = $config['grad'];
                $icon       = $jenisData->ikon ?? $config['icon'];
            @endphp
            
            <div class="labor-card {{ $lab->status_usage == 'digunakan' ? 'my-class' : '' }}">
                {{-- Image --}}
                <div class="labor-image-wrapper">
                    @if($lab->foto)
                        <img src="{{ asset('storage/labor_foto/' . $lab->foto) }}" 
                             class="labor-image" 
                             alt="{{ $lab->nama_labor }}" 
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                             loading="lazy">
                    @endif
                    
                    {{-- CSS Fallback (shown if no foto OR if image fails to load via JS onerror) --}}
                    <div class="labor-image-placeholder" style="background: {{ $bgGradient }}; {{ $lab->foto ? 'display:none;' : '' }}">
                        <i class="bi {{ $icon }}"></i>
                        <small class="text-uppercase fw-bold" style="font-size: 0.6rem; opacity: 0.7; letter-spacing: 1px;">
                            {{ $lab->jenis_labor ?? 'LABORATORIUM' }}
                        </small>
                    </div>
                </div>
                
                {{-- Body --}}
                <div class="labor-card-body">
                    <div class="labor-card-header">
                        <h3 class="labor-name" title="{{ $lab->nama_labor }}">
                            <a href="{{ route($role_prefix . '.labor.show', $lab->id) }}" class="text-decoration-none text-dark hover-primary">{{ $lab->nama_labor }}</a>
                        </h3>
                        
                        @if($alatCount > 0)
                            <span class="badge badge-tersedia rounded-pill px-3 py-1">TERSEDIA</span>
                        @else
                            <span class="badge badge-no-equipment rounded-pill px-2 py-1" style="background-color: #fee2e2; color: #ef4444; border: 1px solid #fecaca; font-size: 0.65rem;">BELUM ADA INVENTARIS</span>
                        @endif
                    </div>
                    
                    <p class="labor-description">{{ $lab->deskripsi ?? 'Laboratorium modern dengan fasilitas lengkap untuk mendukung kegiatan praktikum.' }}</p>
                    
                    {{-- Info Grid --}}
                    <div class="labor-info-grid">
                        <div class="info-item">
                            <div class="info-icon icon-teacher">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Guru Penanggung Jawab</div>
                                <div class="info-value">{{ $lab->penanggungJawabUser->name ?? '-' }}</div>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon icon-technician">
                                <i class="bi bi-tools"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Teknisi Lab</div>
                                <div class="info-value">{{ $lab->teknisiUser->name ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Equipment Count --}}
                    <div class="equipment-count {{ $alatCount == 0 ? 'no-equipment' : '' }}">
                        <i class="bi bi-{{ $alatCount > 0 ? 'box-seam' : 'x-circle' }}"></i>
                        <div class="equipment-count-text">
                            <div class="equipment-number">{{ $alatCount }}</div>
                            <div class="equipment-label">{{ $alatCount > 0 ? 'Alat Tersedia' : 'Belum Ada Inventaris' }}</div>
                        </div>
                    </div>
                    
                    {{-- Actions --}}
                    <div class="labor-actions">
                        <a href="{{ route($role_prefix . '.labor.show', $lab->id) }}" class="btn-labor btn-labor-outline" title="Lihat Detail">
                            <i class="bi bi-info-circle"></i> Detail
                        </a>
                        <a href="{{ route($role_prefix . '.jadwal.index', ['labor' => $lab->id]) }}" class="btn-labor btn-labor-outline" title="Lihat Jadwal">
                            <i class="bi bi-calendar-week"></i> Jadwal
                        </a>
                        <a href="{{ $alatCount > 0 ? route($role_prefix . '.inventaris.index', ['labor' => $lab->id]) : '#' }}" 
                           class="btn-labor btn-labor-primary {{ $alatCount == 0 ? 'disabled' : '' }}"
                           {{ $alatCount == 0 ? 'onclick="return false;" title="Tidak ada alat tersedia"' : '' }}>
                            <i class="bi bi-search"></i> Alat
                        </a>
                    </div>
                </div>
            </div>
        @empty
            {{-- Empty State --}}
            <div style="grid-column: 1 / -1;">
                <div class="empty-state-small" style="padding: 5rem 2rem; background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-sm);">
                    <i class="bi bi-building-slash" style="font-size: 4rem; opacity: 0.2; color: var(--text-muted);"></i>
                    <h4 class="fw-bold mt-3" style="color: var(--text-dark);">Tidak ada laboratorium ditemukan</h4>
                    <p class="text-muted">Coba ubah filter atau kata kunci pencarian Anda.</p>
                    <a href="{{ route($role_prefix . '.labor.index') }}" class="btn btn-outline-secondary mt-3">Reset Filter</a>
                </div>
            </div>
        @endforelse
    </div>
    
    {{-- ======================================
         PAGINATION
    ====================================== --}}
    <div class="d-flex justify-content-center">
        {{ $labor->links() }}
    </div>
    
</div>
@endsection

@section('script')
<script>
    // Smooth scroll animations
    document.addEventListener('DOMContentLoaded', function() {
        // Add fade-in animation to elements as they load
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in');
                }
            });
        });
        
        document.querySelectorAll('.labor-card').forEach(card => {
            observer.observe(card);
        });
    });
</script>
@endsection
