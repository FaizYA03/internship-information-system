@extends('magang.layouts.main')

@section('css')
<style>
    .report-detail-card {
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    
    .report-detail-header {
        background-color: var(--primary);
        color: white;
        padding: 1.5rem;
        position: relative;
    }
    
    .report-detail-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .report-meta {
        display: flex;
        gap: 1.5rem;
        margin-top: 0.75rem;
        font-size: 0.9rem;
    }
    
    .report-detail-body {
        padding: 1.5rem;
    }
    
    .section-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid var(--border-color);
    }
    
    .student-profile {
        display: flex;
        gap: 1rem;
        align-items: center;
        margin-bottom: 1rem;
    }
    
    .student-avatar {
        width: 48px;
        height: 48px;
        background-color: var(--primary);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        font-weight: 600;
    }
    
    .week-badge {
        background-color: var(--primary);
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 500;
        position: absolute;
        top: 1.5rem;
        right: 1.5rem;
    }
    
    .status-badge {
        padding: 0.35rem 0.75rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-top: 0.5rem;
        display: inline-flex;
        align-items: center;
    }
    
    .status-submitted {
        background-color: #fff3cd;
        color: #856404;
    }
    
    .status-approved {
        background-color: #d4edda;
        color: #155724;
    }
    
    .status-rejected {
        background-color: #f8d7da;
        color: #721c24;
    }
    
    .description-section {
        background-color: #f8f9fa;
        border-radius: var(--radius);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .description-content {
        white-space: pre-line;
        line-height: 1.6;
    }
    
    .review-section {
        background-color: #2f6e0a;
        border-radius: var(--radius);
        border: 1px solid var(--border-color);
        padding: 1.5rem;
    }
    
    .actions-section {
        margin-top: 2rem;
    }
    
    .nav-tabs {
        margin-bottom: 1rem;
    }
    
    .review-form {
        padding: 1rem;
        border: 1px solid #dee2e6;
        border-radius: var(--radius);
        margin-top: 1rem;
    }
    
    .review-actions {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
    }
    
    .comment-section {
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--border-color);
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="page-title">{{ $header }}</h1>
        <a href="{{ route('magang.wakil_perusahaan.reports') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
        </a>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="report-detail-card">
                <div class="report-detail-header">
                    <span class="week-badge">Hari #{{ $laporan->minggu_ke }}</span>
                    <div class="report-detail-title">{{ $laporan->judul }}</div>
                    <div class="report-meta">
                        <span><i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::parse($laporan->tanggal_mulai)->format('d M Y') }}</span>
                        <span><i class="bi bi-clock"></i> {{ $laporan->created_at->format('d M Y, H:i') }}</span>
                    </div>
                </div>
                
                <div class="report-detail-body">
                    <div class="student-profile">
                        <div class="student-avatar">
                            {{ substr($laporan->magangSiswa->nama, 0, 1) }}
                        </div>
                        <div>
                            <h5 class="mb-0">{{ $laporan->magangSiswa->nama }}</h5>
                            <p class="mb-0 text-muted">{{ $laporan->magangSiswa->email }}</p>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        @if($laporan->status == 'submitted')
                            <span class="status-badge status-submitted">
                                <i class="bi bi-hourglass me-2"></i> Menunggu Review
                            </span>
                        @elseif($laporan->status == 'approved')
                            <span class="status-badge status-approved">
                                <i class="bi bi-check-circle me-2"></i> Disetujui pada {{ $laporan->updated_at->format('d M Y, H:i') }}
                            </span>
                        @elseif($laporan->status == 'rejected')
                            <span class="status-badge status-rejected">
                                <i class="bi bi-x-circle me-2"></i> Ditolak pada {{ $laporan->updated_at->format('d M Y, H:i') }}
                            </span>
                        @endif
                    </div>
                    
                    <div class="description-section">
                        <h3 class="section-title">Deskripsi Kegiatan</h3>
                        <div class="description-content">{{ $laporan->deskripsi }}</div>
                    </div>
                    
                    @if($laporan->status != 'submitted')
                        <div class="comment-section">
                            <h3 class="section-title">Komentar Pembimbing</h3>
                            @if($laporan->komentar)
                                <div class="alert {{ $laporan->status == 'approved' ? 'alert-success' : 'alert-danger' }}">
                                    <i class="bi {{ $laporan->status == 'approved' ? 'bi-check-circle' : 'bi-exclamation-circle' }} me-2"></i>
                                    {{ $laporan->komentar }}
                                </div>
                            @else
                                <div class="alert alert-light">
                                    <i class="bi bi-info-circle me-2"></i> Tidak ada komentar yang diberikan.
                                </div>
                            @endif
                        </div>
                    @endif
                    
                    @if($laporan->status == 'submitted')
                        <div class="actions-section">
                            <h3 class="section-title">Review Laporan</h3>
                            
                            <ul class="nav nav-tabs" id="reviewTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="approve-tab" data-bs-toggle="tab" data-bs-target="#approve" type="button" role="tab" aria-controls="approve" aria-selected="true">
                                        <i class="bi bi-check-circle me-1"></i> Setujui
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="reject-tab" data-bs-toggle="tab" data-bs-target="#reject" type="button" role="tab" aria-controls="reject" aria-selected="false">
                                        <i class="bi bi-x-circle me-1"></i> Tolak
                                    </button>
                                </li>
                            </ul>
                            
                            <div class="tab-content" id="reviewTabContent">
                                <div class="tab-pane fade show active" id="approve" role="tabpanel" aria-labelledby="approve-tab">
                                    <div class="review-form">
                                        <form action="{{ route('magang.wakil_perusahaan.reports.review', $laporan->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="approved">
                                            
                                            <div class="mb-3">
                                                <label for="komentar" class="form-label">Komentar (Opsional)</label>
                                                <textarea class="form-control" id="komentar" name="komentar" rows="4" placeholder="Berikan komentar untuk laporan ini..."></textarea>
                                            </div>
                                            
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-success">
                                                    <i class="bi bi-check-circle me-1"></i> Setujui Laporan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                
                                <div class="tab-pane fade" id="reject" role="tabpanel" aria-labelledby="reject-tab">
                                    <div class="review-form">
                                        <form action="{{ route('magang.wakil_perusahaan.reports.review', $laporan->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="rejected">
                                            
                                            <div class="mb-3">
                                                <label for="komentar_tolak" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                                                <textarea class="form-control" id="komentar_tolak" name="komentar" rows="4" placeholder="Berikan alasan penolakan dan saran perbaikan..." required></textarea>
                                                <div class="form-text">Alasan penolakan akan dilihat oleh siswa sebagai bahan perbaikan.</div>
                                            </div>
                                            
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="bi bi-x-circle me-1"></i> Tolak Laporan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Informasi Program Magang</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="text-muted small">Perusahaan</div>
                        <div class="fw-medium">{{ $laporan->magangSiswa->perusahaan->nama_perusahaan ?? 'Tidak tersedia' }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="text-muted small">Program Magang</div>
                        <div class="fw-medium">{{ $laporan->magangSiswa->opening->posisi ?? 'Tidak tersedia' }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="text-muted small">Periode Magang</div>
                        <div class="fw-medium">
                            {{ \Carbon\Carbon::parse($laporan->magangSiswa->tanggal_mulai)->format('d M Y') }} - 
                            {{ \Carbon\Carbon::parse($laporan->magangSiswa->tanggal_selesai)->format('d M Y') }}
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Panduan Review</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="bi bi-info-circle me-2"></i>Tips Review Laporan</h6>
                        <ul class="mb-0 ps-3">
                            <li>Periksa kesesuaian laporan dengan pekerjaan yang ditugaskan</li>
                            <li>Evaluasi kualitas dan kelengkapan informasi</li>
                            <li>Berikan komentar yang konstruktif</li>
                            <li>Ajukan pertanyaan jika ada hal yang kurang jelas</li>
                        </ul>
                    </div>
                    
                    <div class="mt-3">
                        <h6><i class="bi bi-tags me-2"></i>Status Laporan</h6>
                        <div class="d-flex flex-column gap-2 mt-2">
                            <span class="badge bg-warning text-dark d-inline-block text-start">
                                <i class="bi bi-hourglass me-1"></i> Menunggu Review - Laporan baru yang belum diperiksa
                            </span>
                            <span class="badge bg-success d-inline-block text-start">
                                <i class="bi bi-check-circle me-1"></i> Disetujui - Laporan yang sudah sesuai standar
                            </span>
                            <span class="badge bg-danger d-inline-block text-start">
                                <i class="bi bi-x-circle me-1"></i> Ditolak - Laporan yang perlu diperbaiki
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection