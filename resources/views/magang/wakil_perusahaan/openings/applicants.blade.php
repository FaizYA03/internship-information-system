@extends('magang.layouts.main')

@section('css')
<style>
    .program-info-card {
        border-radius: var(--radius-lg);
        background-color: var(--bg-light);
        box-shadow: var(--shadow);
        overflow: hidden;
        margin-bottom: 2rem;
    }
    
    .program-header {
        background-color: var(--primary);
        color: white;
        padding: 1.5rem;
    }
    
    .program-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .program-meta {
        display: flex;
        gap: 1.5rem;
        flex-wrap: wrap;
    }
    
    .program-meta-item {
        display: flex;
        align-items: center;
        font-size: 0.9rem;
        opacity: 0.9;
    }
    
    .program-meta-item i {
        margin-right: 0.5rem;
    }
    
    .program-body {
        padding: 1.5rem;
    }
    
    .program-description {
        color: var(--text-dark);
        margin-bottom: 1.5rem;
    }
    
    .program-skills {
        margin-bottom: 1rem;
    }
    
    .program-skills h5 {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.75rem;
        color: var(--text-dark);
    }
    
    .skill-badge {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        background-color: rgba(78, 205, 196, 0.15);
        color: var(--secondary-dark);
        border-radius: 50px;
        font-size: 0.8rem;
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
    }
    
    .applicant-card {
        border-radius: var(--radius);
        background-color: var(--bg-light);
        box-shadow: var(--shadow);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }
    
    .applicant-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
    }
    
    .applicant-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }
    
    .applicant-name {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 0.25rem;
    }
    
    .applicant-email {
        font-size: 0.9rem;
        color: var(--text-muted);
    }
    
    .status-badge {
        padding: 0.35rem 0.75rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
    }
    
    .status-pending {
        background-color: rgba(255, 191, 0, 0.15);
        color: #cc9700;
    }
    
    .status-approved {
        background-color: rgba(78, 205, 196, 0.15);
        color: var(--secondary-dark);
    }
    
    .status-rejected {
        background-color: rgba(231, 76, 60, 0.15);
        color: #c0392b;
    }
    
    .status-badge i {
        margin-right: 0.35rem;
    }
    
    .applicant-meta {
        display: flex;
        gap: 1.5rem;
        flex-wrap: wrap;
        margin-bottom: 1rem;
    }
    
    .applicant-meta-item {
        display: flex;
        align-items: center;
        font-size: 0.9rem;
        color: var(--text-muted);
    }
    
    .applicant-meta-item i {
        margin-right: 0.5rem;
        color: var(--secondary);
    }
    
    .action-buttons {
        display: flex;
        gap: 0.75rem;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem 1.5rem;
    }
    
    .empty-icon {
        font-size: 3rem;
        color: var(--bg-gray);
        margin-bottom: 1rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="page-title">{{ $header }}</h1>
                <a href="{{ route('magang.wakil_perusahaan.openings.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left me-2"></i> Kembali
                </a>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="program-info-card">
                <div class="program-header">
                    <h2 class="program-title">{{ $opening->posisi }}</h2>
                    <div class="program-meta">
                        <div class="program-meta-item">
                            <i class="bi bi-calendar-date"></i>
                            <span>{{ \Carbon\Carbon::parse($opening->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($opening->tanggal_selesai)->format('d M Y') }}</span>
                        </div>
                        <div class="program-meta-item">
                            <i class="bi bi-people"></i>
                            <span>{{ $opening->jumlah_posisi }} posisi</span>
                        </div>
                        <div class="program-meta-item">
                            <i class="bi bi-building"></i>
                            <span>{{ $wakilPerusahaan->nama_perusahaan }}</span>
                        </div>
                    </div>
                </div>
                <div class="program-body">
                    <div class="program-description">
                        <p>{{ $opening->deskripsi }}</p>
                    </div>
                    
                    @if($opening->keahlian)
                        <div class="program-skills">
                            <h5>Keahlian yang Dibutuhkan:</h5>
                            @foreach(explode(',', $opening->keahlian) as $skill)
                                <span class="skill-badge">{{ trim($skill) }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Daftar Pelamar ({{ count($applicants) }})</h5>
                </div>
                <div class="card-body">
                    @if(count($applicants) > 0)
                        @foreach($applicants as $applicant)
                            <div class="applicant-card">
                                <div class="applicant-header">
                                    <div>
                                        <h3 class="applicant-name">{{ $applicant->nama }}</h3>
                                        <p class="applicant-email">{{ $applicant->email }}</p>
                                    </div>
                                    <div>
                                        @if($applicant->status == 'Menunggu')
                                            <span class="status-badge status-pending">
                                                <i class="bi bi-hourglass"></i> Menunggu
                                            </span>
                                        @elseif($applicant->status == 'Disetujui')
                                            <span class="status-badge status-approved">
                                                <i class="bi bi-check-circle"></i> Disetujui
                                            </span>
                                        @elseif($applicant->status == 'Ditolak')
                                            <span class="status-badge status-rejected">
                                                <i class="bi bi-x-circle"></i> Ditolak
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="applicant-meta">
                                    <div class="applicant-meta-item">
                                        <i class="bi bi-calendar-date"></i>
                                        <span>Daftar: {{ \Carbon\Carbon::parse($applicant->created_at)->format('d M Y') }}</span>
                                    </div>
                                    <div class="applicant-meta-item">
                                        <i class="bi bi-telephone"></i>
                                        <span>{{ $applicant->no_hp ?? 'Tidak ada nomor' }}</span>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-end">
                                    <div class="action-buttons">
                                        <a href="{{ route('magang.wakil_perusahaan.interns.show', $applicant->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye me-1"></i> Detail
                                        </a>
                                        
                                        @if($applicant->status == 'Menunggu')
                                            <form action="{{ route('magang.wakil_perusahaan.interns.approve', $applicant->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="bi bi-check-circle me-1"></i> Setujui
                                                </button>
                                            </form>
                                            
                                            <form action="{{ route('magang.wakil_perusahaan.interns.reject', $applicant->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="bi bi-x-circle me-1"></i> Tolak
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="bi bi-people"></i>
                            </div>
                            <h4>Belum Ada Pelamar</h4>
                            <p class="text-muted">Program magang ini belum memiliki pelamar.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection