@extends('siswa.layouts.main')

@php
    $role_prefix = Auth::check() && Auth::user()->role == 'guru' ? 'guru' : 'siswa';
@endphp

@section('css')
<style>
    .lab-detail-container {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .lab-header {
        position: relative;
        border-radius: var(--radius-lg);
        overflow: hidden;
        height: 250px;
        background-color: var(--primary);
    }

    .lab-cover {
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0.7;
    }

    .lab-header-content {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        padding: 2rem;
        background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
        color: white;
    }

    .lab-title {
        font-size: 2rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .lab-meta {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .lab-status {
        padding: 0.35rem 0.75rem;
        border-radius: var(--radius-sm);
        font-weight: 500;
    }

    .lab-status-available {
        background-color: #4ecdc4;
        color: white;
    }

    .lab-status-busy {
        background-color: #ff6b6b;
        color: white;
    }

    .lab-info-card {
        background-color: var(--bg-light);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow);
        padding: 1.5rem;
    }

    .lab-info-title {
        font-weight: 600;
        margin-bottom: 1rem;
        color: var(--primary);
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #e9ecef;
    }

    .lab-info-item {
        display: flex;
        margin-bottom: 1.25rem;
    }

    .lab-info-icon {
        flex: 0 0 40px;
        font-size: 1.25rem;
        color: var(--secondary);
    }

    .lab-info-content {
        flex: 1;
    }

    .lab-info-label {
        font-weight: 500;
        margin-bottom: 0.25rem;
        color: var(--text-dark);
    }

    .lab-info-value {
        color: var(--text-muted);
    }

    .schedule-title {
        font-weight: 600;
        margin-bottom: 1rem;
        color: var(--primary);
        display: flex;
        align-items: center;
    }

    .schedule-title i {
        margin-right: 0.75rem;
    }

    .schedule-item {
        padding: 1rem;
        border-radius: var(--radius-sm);
        background-color: #f8f9fa;
        margin-bottom: 0.75rem;
        border-left: 4px solid var(--secondary);
    }

    .schedule-item.busy {
        border-left-color: #ff6b6b;
    }

    .schedule-time {
        font-weight: 500;
        display: block;
        margin-bottom: 0.5rem;
    }

    .schedule-status {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 50px;
        font-weight: 500;
        margin-left: 0.5rem;
    }

    .schedule-description {
        color: var(--text-muted);
        margin-bottom: 0;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title">Detail Laboratorium</h1>
            <p class="text-muted">Informasi lengkap tentang laboratorium</p>
        </div>
        <a href="{{ route($role_prefix . '.labor.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="lab-detail-container">
        <div class="lab-header">
            <img src="{{ asset('assets/images/' . strtolower($labor->kode ?? 'lab') . '.jpg') }}" alt="{{ $labor->nama_labor }}" class="lab-cover">
            <div class="lab-header-content">
                <h1 class="lab-title">{{ $labor->nama_labor }}</h1>
                <div class="lab-meta">
                    <span class="lab-code">Kode: {{ $labor->kode }}</span>
                    </span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="lab-info-card h-100">
                    <h3 class="lab-info-title">Informasi Laboratorium</h3>

                    <div class="lab-info-item">
                        <div class="lab-info-icon">
                            <i class="bi bi-building"></i>
                        </div>
                        <div class="lab-info-content">
                            <div class="lab-info-label">Nama Laboratorium</div>
                            <div class="lab-info-value">{{ $labor->nama_labor }}</div>
                        </div>
                    </div>

                    <div class="lab-info-item">
                        <div class="lab-info-icon">
                            <i class="bi bi-tag"></i>
                        </div>
                        <div class="lab-info-content">
                            <div class="lab-info-label">Kode</div>
                            <div class="lab-info-value">{{ $labor->kode ?: 'Tidak ada kode' }}</div>
                        </div>
                    </div>

                    <div class="lab-info-item">
    <div class="lab-info-icon">
        <i class="bi bi-person-badge"></i>
    </div>
    <div class="lab-info-content">
        <div class="lab-info-label">Penanggung Jawab</div>
        <div class="lab-info-value">{{ $labor->penanggung_jawab->name ?? 'Belum ditentukan' }}</div>
    </div>
</div>

<div class="lab-info-item">
    <div class="lab-info-icon">
        <i class="bi bi-person"></i>
    </div>
    <div class="lab-info-content">
        <div class="lab-info-label">Teknisi</div>
        <div class="lab-info-value">{{ $labor->teknisi->name ?? 'Belum ditentukan' }}</div>
    </div>
</div>



                    @if($labor->deskripsi)
                    <div class="lab-info-item">
                        <div class="lab-info-icon">
                            <i class="bi bi-info-circle"></i>
                        </div>
                        <div class="lab-info-content">
                            <div class="lab-info-label">Deskripsi</div>
                            <div class="lab-info-value">{{ $labor->deskripsi }}</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-8">
                <div class="lab-info-card mb-4">
                    <h3 class="schedule-title"><i class="bi bi-calendar-day"></i> Jadwal Hari Ini</h3>

                    @if($jadwalToday->count() > 0)
                        @foreach($jadwalToday as $jadwal)
                                    {{ Carbon\Carbon::parse($jadwal->start)->format('H:i') }} - {{ Carbon\Carbon::parse($jadwal->end)->format('H:i') }}
                                    </span>
                                </span>
                                <p class="schedule-description">{{ $jadwal->keterangan ?: 'Tidak ada keterangan' }}</p>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">Tidak ada jadwal untuk hari ini.</p>
                    @endif
                </div>

                <div class="lab-info-card">
                    <h3 class="schedule-title"><i class="bi bi-calendar-week"></i> Jadwal Mendatang</h3>

                    @if($jadwalFuture->count() > 0)
                        @foreach($jadwalFuture as $jadwal)
                            <div class="schedule-item {{ $jadwal->status == 'terpakai' ? 'busy' : '' }}">
                                <span class="schedule-time">
                                    {{ Carbon\Carbon::parse($jadwal->start)->format('d M Y') }}, {{ Carbon\Carbon::parse($jadwal->start)->format('H:i') }} - {{ Carbon\Carbon::parse($jadwal->end)->format('H:i') }}
                                    <span class="schedule-status {{ $jadwal->status == 'terpakai' ? 'lab-status-busy' : 'lab-status-available' }}">
                                        {{ ucfirst($jadwal->status) }}
                                    </span>
                                </span>
                                <p class="schedule-description">{{ $jadwal->keterangan ?: 'Tidak ada keterangan' }}</p>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">Tidak ada jadwal mendatang.</p>
                    @endif

                    <div class="text-center mt-4">
                        <a href="{{ route($role_prefix . '.jadwal.index') }}?labor={{ $labor->kode }}" class="btn btn-secondary">
                            <i class="bi bi-calendar-week me-1"></i> Lihat Semua Jadwal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
