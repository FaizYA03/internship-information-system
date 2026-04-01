@extends('siswa.layouts.main')

@php
    $role_prefix = Auth::check() && Auth::user()->role == 'guru' ? 'guru' : 'siswa';
@endphp

@section('css')
<style>
    .detail-container {
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

    /* Main Container */
    .detail-container {
        max-width: 1400px;
        margin: 0 auto;
    }

    /* Page Header */
    .page-header {
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .page-title {
        font-size: 2rem;
        font-weight: 800;
        color: var(--dark);
        margin: 0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .btn-back {
        border-radius: 12px;
        padding: 0.625rem 1.5rem;
        font-weight: 600;
        background: white;
        border: 2px solid var(--border);
        color: var(--dark);
        text-decoration: none;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-back:hover {
        border-color: var(--primary);
        color: var(--primary);
        transform: translateX(-4px);
    }

    /* Detail Card */
    .detail-card {
        background: white;
        border-radius: 16px;
        padding: 2.5rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        border: 1px solid var(--border);
        margin-bottom: 2rem;
    }
    
    .detail-header {
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 2px solid var(--border);
    }
    
    .detail-title {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--dark);
        margin-bottom: 0.75rem;
    }
    
    .detail-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        font-size: 0.9375rem;
        color: var(--secondary);
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .meta-item i {
        color: var(--primary);
    }

    /* Image Section */
    .detail-image {
        width: 100%;
        max-height: 500px;
        object-fit: cover;
        border-radius: 16px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .no-image-placeholder {
        height: 400px;
        border-radius: 16px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        color: white;
        margin-bottom: 2rem;
    }
    
    .no-image-placeholder i {
        font-size: 5rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    .no-image-placeholder p {
        font-size: 1.125rem;
        font-weight: 600;
    }

    /* Info Grid */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .info-card {
        background: var(--light);
        border-radius: 12px;
        padding: 1.5rem;
        border-left: 4px solid var(--primary);
    }
    
    .info-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }
    
    .info-value {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--dark);
        margin: 0;
    }

    /* Description Section */
    .description-section {
        background: var(--light);
        border-radius: 12px;
        padding: 1.75rem;
        margin-bottom: 2rem;
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .section-title i {
        color: var(--primary);
    }
    
    .description-text {
        color: var(--secondary);
        line-height: 1.8;
        white-space: pre-line;
    }

    /* Sidebar Cards */
    .sidebar-card {
        background: white;
        border-radius: 16px;
        padding: 1.75rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        border: 1px solid var(--border);
        margin-bottom: 1.5rem;
    }
    
    .custom-sidebar-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--border);
    }
    
    .custom-sidebar-header i {
        font-size: 1.5rem;
        color: var(--primary);
    }
    
    .custom-sidebar-header h5 {
        margin: 0;
        font-weight: 700;
        color: var(--dark);
    }

    /* Status Badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.25rem;
        border-radius: 999px;
        font-size: 1rem;
        font-weight: 700;
        letter-spacing: 0.025em;
    }
    
    .status-Tersedia, .status-tersedia {
        background-color: #d1fae5;
        color: #065f46;
    }
    
    .status-Tersedia::before, .status-tersedia::before {
        content: '';
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: #10b981;
    }
    
    .status-Dipinjam {
        background-color: #fef3c7;
        color: #92400e;
    }
    
    .status-Dipinjam::before {
        content: '';
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: #f59e0b;
    }
    
    .status-Tidak, .status-tidak {
        background-color: #fee2e2;
        color: #991b1b;
    }
    
    .status-Tidak::before, .status-tidak::before {
        content: '';
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: #ef4444;
    }

    /* Condition Badges */
    .kondisi-badge {
        display: inline-block;
        padding: 0.625rem 1.125rem;
        border-radius: 999px;
        font-size: 0.9375rem;
        font-weight: 700;
    }
    
    .kondisi-Sangat-Baik {
        background-color: #d1fae5;
        color: #065f46;
    }
    
    .kondisi-Baik {
        background-color: #dbeafe;
        color: #1e40af;
    }
    
    .kondisi-Rusak-Ringan {
        background-color: #fef3c7;
        color: #92400e;
    }
    
    .kondisi-Rusak-Sedang {
        background-color: #fed7aa;
        color: #9a3412;
    }
    
    .kondisi-Rusak-Berat {
        background-color: #fee2e2;
        color: #991b1b;
    }

    /* Info List */
    .info-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .info-item {
        display: flex;
        padding: 1rem 0;
        border-bottom: 1px solid var(--border);
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    .info-item-label {
        min-width: 160px;
        font-weight: 700;
        color: var(--dark);
        font-size: 0.9375rem;
    }
    
    .info-item-value {
        flex: 1;
        color: var(--secondary);
        font-size: 0.9375rem;
    }

    /* Borrow Button */
    .btn-borrow {
        width: 100%;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        font-weight: 700;
        font-size: 1.0625rem;
        transition: all 0.3s;
        border: none;
        margin-top: 1.5rem;
    }
    
    .btn-borrow:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px -8px rgba(102, 126, 234, 0.5);
        color: white;
    }
    
    .btn-borrow:disabled {
        background: linear-gradient(135deg, #94a3b8 0%, #64748b 100%);
        cursor: not-allowed;
        opacity: 0.6;
    }

    /* Alert Box */
    .alert-custom {
        border-radius: 12px;
        padding: 1.25rem;
        border-left: 4px solid;
        margin-top: 1.5rem;
    }
    
    .alert-info-custom {
        background-color: #dbeafe;
        border-color: #3b82f6;
        color: #1e40af;
    }
    
    .alert-warning-custom {
        background-color: #fef3c7;
        border-color: #f59e0b;
        color: #92400e;
    }
    
    .alert-danger-custom {
        background-color: #fee2e2;
        border-color: #ef4444;
        color: #991b1b;
    }

    /* History Table */
    .history-table {
        margin-top: 1.5rem;
    }
    
    .table-modern {
        border-radius: 12px;
        overflow: hidden;
    }
    
    .table-modern thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .table-modern thead th {
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        padding: 1rem;
        border: none;
    }
    
    .table-modern tbody tr {
        border-bottom: 1px solid var(--border);
    }
    
    .table-modern tbody tr:last-child {
        border-bottom: none;
    }
    
    .table-modern tbody td {
        padding: 1rem;
        vertical-align: middle;
    }
    
    .history-badge {
        display: inline-block;
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.8125rem;
        font-weight: 600;
    }
    
    .history-status-pending {
        background-color: #fef3c7;
        color: #92400e;
    }
    
    .history-status-approved {
        background-color: #dbeafe;
        color: #1e40af;
    }
    
    .history-status-returned {
        background-color: #d1fae5;
        color: #065f46;
    }
    
    .history-status-rejected {
        background-color: #fee2e2;
        color: #991b1b;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .detail-card {
            padding: 1.5rem;
        }
        
        .info-grid {
            grid-template-columns: 1fr;
        }
        
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .info-item {
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .info-item-label {
            min-width: auto;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid detail-container">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">📋 Detail Inventaris</h1>
        </div>
        <a href="{{ route($role_prefix . '.inventaris.index') }}" class="btn-back">
            <i class="bi bi-arrow-left"></i>
            Kembali ke Daftar
        </a>
    </div>
    
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <div class="detail-card">
                <div class="detail-header">
                    <h2 class="detail-title">{{ $item->nama_inventaris }}</h2>
                    <div class="detail-meta">
                        <div class="meta-item">
                            <i class="bi bi-tag-fill"></i>
                            <span>{{ $item->kategori }}</span>
                        </div>
                        @if($item->kode_inventaris)
                        <div class="meta-item">
                            <i class="bi bi-upc-scan"></i>
                            <span>{{ $item->kode_inventaris }}</span>
                        </div>
                        @endif
                        <div class="meta-item">
                            <i class="bi bi-building"></i>
                            <span>{{ $item->labor ? $item->labor->nama_labor : $item->lokasi }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Image -->
                @if($item->gambar)
                    <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama_inventaris }}" class="detail-image">
                @else
                    <div class="no-image-placeholder">
                        <i class="bi bi-camera"></i>
                        <p>Tidak ada foto alat</p>
                    </div>
                @endif
                
                <!-- Info Grid -->
                <div class="info-grid">
                    <div class="info-card">
                        <div class="info-label">Status Ketersediaan</div>
                        <p class="info-value">
                            <span class="status-badge status-{{ str_replace(' ', '-', $item->status) }}">
                                {{ $item->status }}
                            </span>
                        </p>
                    </div>
                    
                    <div class="info-card">
                        <div class="info-label">Kondisi Alat</div>
                        <p class="info-value">
                            <span class="kondisi-badge kondisi-{{ str_replace(' ', '-', $item->kondisi ?? 'Baik') }}">
                                {{ $item->kondisi ?? 'Baik' }}
                            </span>
                        </p>
                    </div>
                    
                    <div class="info-card">
                        <div class="info-label">Jumlah Total</div>
                        <p class="info-value">
                            <i class="bi bi-box me-2 text-primary"></i>{{ $item->jumlah }} Unit
                        </p>
                    </div>
                    
                    <div class="info-card">
                        <div class="info-label">Unit Tersedia</div>
                        <p class="info-value">
                            <i class="bi bi-check-circle me-2 text-success"></i>{{ $item->jumlah }} Unit
                        </p>
                    </div>
                </div>
                
                <!-- Description -->
                @if($item->deskripsi)
                <div class="description-section">
                    <div class="section-title">
                        <i class="bi bi-file-text"></i>
                        <span>Deskripsi Alat</span>
                    </div>
                    <p class="description-text">{{ $item->deskripsi }}</p>
                </div>
                @endif
                
                <!-- Asset Information -->
                <div class="description-section">
                    <div class="section-title">
                        <i class="bi bi-bank"></i>
                        <span>Informasi Aset</span>
                    </div>
                    <ul class="info-list">
                        @if($item->sumber_dana)
                        <li class="info-item">
                            <div class="info-item-label">Sumber Dana</div>
                            <div class="info-item-value">{{ $item->sumber_dana }}</div>
                        </li>
                        @endif
                        @if($item->tahun_perolehan)
                        <li class="info-item">
                            <div class="info-item-label">Tahun Perolehan</div>
                            <div class="info-item-value">{{ $item->tahun_perolehan }}</div>
                        </li>
                        @endif
                        @if($item->tanggal_pengadaan)
                        <li class="info-item">
                            <div class="info-item-label">Tanggal Pengadaan</div>
                            <div class="info-item-value">{{ \Carbon\Carbon::parse($item->tanggal_pengadaan)->isoFormat('D MMMM Y') }}</div>
                        </li>
                        @endif
                        @if($item->spesifikasi)
                        <li class="info-item">
                            <div class="info-item-label">Spesifikasi</div>
                            <div class="info-item-value">{{ $item->spesifikasi }}</div>
                        </li>
                        @endif
                    </ul>
                </div>
                
                <!-- Borrowing History -->
                @if(isset($riwayatPeminjaman) && $riwayatPeminjaman->count() > 0)
                <div class="description-section">
                    <div class="section-title">
                        <i class="bi bi-clock-history"></i>
                        <span>Riwayat Peminjaman (10 Terakhir)</span>
                    </div>
                    <div class="history-table">
                        <div class="table-responsive">
                            <table class="table table-modern">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Peminjam</th>
                                        <th>Jumlah</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($riwayatPeminjaman as $riwayat)
                                    <tr>
                                        <td>{{ $riwayat->created_at->isoFormat('D MMM Y') }}</td>
                                        <td>
                                            @if($riwayat->peminjam)
                                                {{ $riwayat->peminjam->name }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $riwayat->jumlah ?? 1 }} unit</td>
                                        <td>
                                            @php
                                                $statusClass = 'history-status-pending';
                                                $statusText = $riwayat->status ?? 'pending';
                                                
                                                if($statusText == 'approved') $statusClass = 'history-status-approved';
                                                elseif($statusText == 'returned') $statusClass = 'history-status-returned';
                                                elseif($statusText == 'rejected') $statusClass = 'history-status-rejected';
                                            @endphp
                                            <span class="history-badge {{ $statusClass }}">
                                                {{ ucfirst($statusText) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Summary Card -->
            <div class="sidebar-card">
                <div class="custom-sidebar-header">
                    <i class="bi bi-info-circle-fill"></i>
                    <h5>Ringkasan</h5>
                </div>
                <ul class="info-list">
                    <li class="info-item">
                        <div class="info-item-label">Kode Inventaris</div>
                        <div class="info-item-value">{{ $item->kode_inventaris ?? '-' }}</div>
                    </li>
                    <li class="info-item">
                        <div class="info-item-label">Kategori</div>
                        <div class="info-item-value">{{ $item->kategori }}</div>
                    </li>
                    <li class="info-item">
                        <div class="info-item-label">Lokasi</div>
                        <div class="info-item-value">{{ $item->labor ? $item->labor->nama_labor : $item->lokasi }}</div>
                    </li>
                    <li class="info-item">
                        <div class="info-item-label">Jumlah Total</div>
                        <div class="info-item-value">{{ $item->jumlah }} unit</div>
                    </li>
                    <li class="info-item">
                        <div class="info-item-label">Ditambahkan</div>
                        <div class="info-item-value">{{ \Carbon\Carbon::parse($item->created_at)->isoFormat('D MMMM Y') }}</div>
                    </li>
                    <li class="info-item">
                        <div class="info-item-label">Terakhir Diubah</div>
                        <div class="info-item-value">{{ \Carbon\Carbon::parse($item->updated_at)->diffForHumans() }}</div>
                    </li>
                </ul>
            </div>
            
            <!-- Borrow Action Card -->
            <div class="sidebar-card">
                <div class="custom-sidebar-header">
                    <i class="bi bi-hand-index-fill"></i>
                    <h5>Ajukan Peminjaman</h5>
                </div>
                
                @if(isset($canBorrow) && $canBorrow)
                    <p class="text-muted mb-3">
                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                        Alat ini tersedia untuk dipinjam
                    </p>
                    
                    <a href="{{ route($role_prefix . '.peminjaman.create', ['alat_id' => $item->id]) }}" class="btn-borrow text-center text-white text-decoration-none d-block">
                        <i class="bi bi-plus-circle me-2"></i>
                        Ajukan Peminjaman
                    </a>
                    
                    <div class="alert-custom alert-info-custom mt-3">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Info:</strong> Permohonan Anda akan diverifikasi oleh admin laboratorium sebelum disetujui.
                    </div>
                @else
                    <button class="btn-borrow" disabled>
                        <i class="bi bi-x-circle"></i>
                        Tidak Dapat Dipinjam
                    </button>
                    
                    @if($item->jumlah <= 0)
                        <div class="alert-custom alert-warning-custom">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Stok Habis:</strong> Alat ini sedang tidak tersedia karena stok habis.
                        </div>
                    @elseif($item->kondisi == 'Rusak Berat')
                        <div class="alert-custom alert-danger-custom">
                            <i class="bi bi-x-octagon me-2"></i>
                            <strong>Rusak Berat:</strong> Alat ini tidak dapat dipinjam karena kondisi rusak berat.
                        </div>
                    @elseif(!in_array($item->status, ['Tersedia', 'tersedia']))
                        <div class="alert-custom alert-warning-custom">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Tidak Tersedia:</strong> Alat ini sedang {{ $item->status }}.
                        </div>
                    @endif
                @endif
            </div>
            
            <!-- Warning Card -->
            <div class="sidebar-card">
                <div class="custom-sidebar-header">
                    <i class="bi bi-shield-exclamation"></i>
                    <h5>Perhatian</h5>
                </div>
                <p class="text-muted mb-0">
                    @if(in_array($item->kondisi, ['Rusak Ringan', 'Rusak Sedang']))
                        <i class="bi bi-exclamation-circle text-warning me-2"></i>
                        Alat ini memiliki kondisi <strong>{{ $item->kondisi }}</strong>. Gunakan dengan hati-hati dan laporkan jika terjadi kerusakan lebih lanjut.
                    @elseif($item->kondisi == 'Rusak Berat')
                        <i class="bi bi-x-circle text-danger me-2"></i>
                        Alat ini dalam kondisi <strong>Rusak Berat</strong> dan tidak boleh digunakan. Hubungi admin lab untuk perbaikan.
                    @else
                        <i class="bi bi-check-circle text-success me-2"></i>
                        Alat ini dalam kondisi baik. Jika Anda menemukan kerusakan, mohon untuk melaporkannya melalui fitur laporan kerusakan.
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        // Initialize tooltips if needed
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>
@endsection