@extends('siswa.layouts.main')

@php
    $role_prefix = Auth::check() && Auth::user()->role == 'guru' ? 'guru' : 'siswa';
@endphp

@section('css')
<style>
    /* Global Styles for Laporan Page */
    :root {
        --primary-color: #4361ee;
        --secondary-color: #3f37c9;
        --success-color: #4cc9f0;
        --danger-color: #f72585;
        --warning-color: #f8961e;
        --info-color: #4895ef;
        --light-bg: #f8f9fa;
        --card-bg: #ffffff;
        --text-color: #2b2d42;
        --text-muted: #8d99ae;
        --border-radius: 12px;
        --shadow-sm: 0 2px 4px rgba(0,0,0,0.05);
        --shadow-md: 0 4px 6px rgba(0,0,0,0.1);
        --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1);
    }

    body {
        background-color: #f0f2f5;
        font-family: 'Inter', sans-serif; /* Assumption: Inter is available via layout or Google Fonts */
    }

    .page-header {
        margin-bottom: 2rem;
    }

    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text-color);
        margin-bottom: 0.5rem;
    }

    .page-subtitle {
        color: var(--text-muted);
        font-size: 1rem;
    }

    /* Summary Cards */
    .summary-card {
        background: var(--card-bg);
        border-radius: var(--border-radius);
        padding: 1.5rem;
        box-shadow: var(--shadow-sm);
        display: flex;
        align-items: center;
        transition: transform 0.2s, box-shadow 0.2s;
        border: 1px solid rgba(0,0,0,0.05);
        height: 100%;
    }

    .summary-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
    }

    .summary-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-right: 1rem;
    }
    
    .bg-soft-primary { background-color: rgba(67, 97, 238, 0.1); color: var(--primary-color); }
    .bg-soft-warning { background-color: rgba(248, 150, 30, 0.1); color: var(--warning-color); }
    .bg-soft-info { background-color: rgba(72, 149, 239, 0.1); color: var(--info-color); }
    .bg-soft-success { background-color: rgba(76, 201, 240, 0.1); color: #00b4d8; }

    .summary-content h3 {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
        line-height: 1.2;
    }

    .summary-content p {
        margin: 0;
        color: var(--text-muted);
        font-size: 0.85rem;
        font-weight: 500;
    }

    /* Filter Section */
    .filter-card {
        background: var(--card-bg);
        border-radius: var(--border-radius);
        padding: 1.25rem;
        box-shadow: var(--shadow-sm);
        margin-bottom: 2rem;
        border: 1px solid rgba(0,0,0,0.05);
    }

    .form-control-custom {
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 0.6rem 1rem;
        font-size: 0.9rem;
    }

    .form-control-custom:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
    }

    .btn-create {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        border: none;
        border-radius: 8px;
        padding: 0.6rem 1.2rem;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(67, 97, 238, 0.25);
    }

    .btn-create:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(67, 97, 238, 0.3);
        color: white;
    }

    /* Report Lists using Cards */
    .report-card {
        background: var(--card-bg);
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-sm);
        margin-bottom: 1.5rem;
        overflow: hidden;
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.05);
        position: relative;
    }

    .report-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
    }

    .report-card-body {
        padding: 1.5rem;
    }

    .report-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .report-title-section {
        flex: 1;
    }

    .report-tool-name {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
        color: var(--text-color);
    }

    .report-lab-name {
        font-size: 0.85rem;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .status-badge {
        padding: 0.35rem 0.75rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.025em;
        text-transform: uppercase;
    }
    
    .badge-pending { background-color: #e2e8f0; color: #475569; }
    .badge-process { background-color: #dbeafe; color: #1e40af; } /* Blue for Diproses/Processing */
    .badge-repairing { background-color: #dbeafe; color: #1e40af; }
    .badge-completed { background-color: #dcfce7; color: #166534; border: 1px solid #bbf7d0; } /* Green for Selesai/Completed */
    .badge-rejected { background-color: #fee2e2; color: #991b1b; } /* Red for Ditolak/Rejected */

    .damage-level-indicator {
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
    }
    
    .level-ringan { background-color: #fcd34d; } /* Yellow */
    .level-sedang { background-color: #f97316; } /* Orange */
    .level-berat { background-color: #ef4444; }   /* Red */

    .report-meta {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 1rem;
        font-size: 0.85rem;
        color: #64748b;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .report-desc {
        background-color: #f8fafc;
        padding: 0.75rem;
        border-radius: 8px;
        font-size: 0.9rem;
        color: #475569;
        margin-bottom: 1rem;
        line-height: 1.5;
        border-left: 3px solid #e2e8f0;
    }

    .report-actions {
        display: flex;
        justify-content: flex-end;
    }

    .btn-detail {
        background-color: white;
        color: var(--primary-color);
        border: 1px solid #e2e8f0;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-detail:hover {
        border-color: var(--primary-color);
        background-color: rgba(67, 97, 238, 0.05);
    }

    /* Filters Responsiveness */
    @media (max-width: 768px) {
        .report-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
        
        .status-badge {
            align-self: flex-start;
        }
        
        .report-meta {
            flex-direction: column;
            gap: 0.5rem;
        }
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 12px;
        box-shadow: var(--shadow-sm);
    }
    
    .empty-icon {
        font-size: 3rem;
        color: #cbd5e1;
        margin-bottom: 1rem;
    }
    
    /* Skeleton Loading Animation */
    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }
    
    .skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
        border-radius: 4px;
    }

    /* Transition */
    .fade-in {
        animation: fadeIn 0.5s ease-in-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 fade-in">
        <div>
            <h1 class="page-title">Laporan Aktif</h1>
            <p class="page-subtitle mb-0">Monitor status laporan kerusakan yang sedang diproses.</p>
        </div>
        <!-- Mobile Create Button (Visible on XS only) -->
        <a href="{{ route($role_prefix . '.laporan.create') }}" class="btn btn-create d-block d-md-none">
            <i class="bi bi-plus-lg"></i>
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="row g-3 mb-4 fade-in" style="animation-delay: 0.1s;">
        <div class="col-6 col-md-4">
            <div class="summary-card">
                <div class="summary-icon bg-soft-primary">
                    <i class="bi bi-clipboard-data"></i>
                </div>
                <div class="summary-content">
                    <h3>{{ $stats['total'] ?? 0 }}</h3>
                    <p>Total Laporan</p>
                </div>
            </div>
        </div>
        <!-- Aktif -->
        <div class="col-6 col-md-4">
            <div class="summary-card">
                <div class="summary-icon bg-soft-warning">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div class="summary-content">
                    <h3>{{ $stats['aktif'] ?? 0 }}</h3>
                    <p>Laporan Aktif</p>
                </div>
            </div>
        </div>
        <!-- Selesai -->
        <div class="col-6 col-md-4">
            <div class="summary-card">
                <div class="summary-icon bg-soft-success">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div class="summary-content">
                    <h3>{{ $stats['selesai'] ?? 0 }}</h3>
                    <p>Telah Diperbaiki</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-card fade-in" style="animation-delay: 0.2s;">
        <form action="{{ route($role_prefix . '.laporan.index') }}" method="GET">
            <div class="row g-3 align-items-end">
                <div class="col-md-3 col-sm-6">
                    <label for="search" class="form-label text-muted small fw-bold">Cari Alat</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" class="form-control form-control-custom border-start-0 ps-0" id="search" name="search" placeholder="Nama alat..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2 col-sm-6">
                    <label for="status" class="form-label text-muted small fw-bold">Status</label>
                    <select class="form-select form-control-custom" id="status" name="status">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                        <option value="process" {{ request('status') == 'process' ? 'selected' : '' }}>Diproses</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-2 col-sm-6">
                    <label for="tingkat_kerusakan" class="form-label text-muted small fw-bold">Kerusakan</label>
                    <select class="form-select form-control-custom" id="tingkat_kerusakan" name="tingkat_kerusakan">
                        <option value="">Semua Tingkat</option>
                        <option value="Ringan" {{ request('tingkat_kerusakan') == 'Ringan' ? 'selected' : '' }}>Ringan</option>
                        <option value="Sedang" {{ request('tingkat_kerusakan') == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                        <option value="Berat" {{ request('tingkat_kerusakan') == 'Berat' ? 'selected' : '' }}>Berat</option>
                    </select>
                </div>
                <div class="col-md-3 col-sm-6">
                    <label class="form-label text-muted small fw-bold">Filter Tanggal</label>
                    <div class="input-group">
                        <input type="date" class="form-control form-control-custom" name="tanggal_awal" value="{{ request('tanggal_awal') }}">
                        <span class="input-group-text bg-light border-start-0 border-end-0">-</span>
                        <input type="date" class="form-control form-control-custom" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}">
                    </div>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-outline-secondary w-100 fw-bold" style="padding: 0.6rem;">Filter</button>
                    <!-- Desktop Create Button -->
                    <a href="{{ route($role_prefix . '.laporan.create') }}" class="btn btn-create d-none d-md-block w-100 text-center">
                        <i class="bi bi-plus-lg me-1"></i> Buat
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Laporan List -->
    <div class="laporan-list fade-in" style="animation-delay: 0.3s;">
        @if($laporan->count() > 0)
            <div class="row">
                @foreach($laporan as $item)
                    <div class="col-md-6 col-lg-6">
                        <div class="report-card">
                            <!-- Damage Level Indicator -->
                            @php
                                $levelClass = 'level-ringan'; // default
                                if($item->tingkat_kerusakan == 'Sedang') $levelClass = 'level-sedang';
                                if($item->tingkat_kerusakan == 'Berat') $levelClass = 'level-berat';
                                
                                $statusClass = 'badge-pending';
                                $statusText = 'Menunggu';
                                if($item->status_perbaikan == 'dalam_proses') {
                                    $statusClass = 'badge-process';
                                    $statusText = 'Diproses';
                                } elseif($item->status_perbaikan == 'selesai') {
                                    $statusClass = 'badge-completed';
                                    $statusText = 'Selesai';
                                }
                            @endphp
                            <div class="damage-level-indicator {{ $levelClass }}"></div>
                            
                            <div class="report-card-body">
                                <div class="report-header">
                                    <div class="report-title-section">
                                        <h4 class="report-tool-name">{{ $item->nama_alat }}</h4>
                                        <div class="report-lab-name">
                                            <i class="bi bi-geo-alt"></i> {{ $item->lokasi ?? 'Laboratorium Umum' }}
                                        </div>
                                    </div>
                                    <span class="status-badge {{ $statusClass }}">
                                        @if($item->status == 'completed') <i class="bi bi-check-all me-1"></i> @endif
                                        {{ $statusText }}
                                    </span>
                                </div>

                                <div class="report-meta">
                                    <div class="meta-item" title="Pelapor">
                                        <i class="bi bi-person text-muted"></i>
                                        <span>{{ $item->nama_pelapor }}</span>
                                    </div>
                                    <div class="meta-item" title="Tanggal Laporan">
                                        <i class="bi bi-calendar3 text-muted"></i>
                                        <span>{{ \Carbon\Carbon::parse($item->tanggal_laporan)->format('d M Y') }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="bi bi-exclamation-triangle text-muted"></i>
                                        <span>{{ $item->tingkat_kerusakan ?? 'Tidak dikategorikan' }}</span>
                                    </div>
                                </div>

                                <div class="report-desc">
                                    {{ \Illuminate\Support\Str::limit($item->deskripsi_kerusakan, 100) }}
                                </div>

                                <div class="report-actions">
                                    <a href="{{ route($role_prefix . '.laporan.show', $item->id) }}" class="btn btn-detail">
                                        Lihat Detail <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-check2-circle empty-icon"></i>
                <h4 class="fw-bold">Belum ada laporan kerusakan</h4>
                <p class="text-muted mb-4">Semua alat laboratorium dalam kondisi baik, atau belum ada yang melaporkannya.</p>
                <a href="{{ route($role_prefix . '.laporan.create') }}" class="btn btn-create">
                    <i class="bi bi-plus-lg me-1"></i> Buat Laporan Pertama
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@section('script')
<script>
    // Simple script to handle any dynamic interactions if needed
    // Currently, filtering is done via GET requests, so no complex JS needed here
    // Transitions are handled by CSS animations
</script>
@endsection
