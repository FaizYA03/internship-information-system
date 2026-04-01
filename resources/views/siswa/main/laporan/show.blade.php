@extends('siswa.layouts.main')

@php
    $role_prefix = Auth::check() && Auth::user()->role == 'guru' ? 'guru' : 'siswa';
@endphp

@section('css')
<style>
    :root {
        --primary-color: #4361ee;
        --secondary-color: #3f37c9;
        --border-radius: 12px;
        --card-bg: #ffffff;
        --text-color: #2b2d42;
        --text-muted: #8d99ae;
    }

    .detail-container {
        max-width: 900px;
        margin: 0 auto;
    }

    .detail-card {
        background: var(--card-bg);
        border-radius: var(--border-radius);
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        border: 1px solid rgba(0,0,0,0.05);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .detail-header {
        padding: 2rem;
        background: linear-gradient(to right, #f8f9fa, #ffffff);
        border-bottom: 1px solid #f1f5f9;
        position: relative;
    }

    .header-badges {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .tool-title {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--text-color);
        margin-bottom: 0.5rem;
    }

    .lab-location {
        color: var(--text-muted);
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .detail-body {
        padding: 2rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .info-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-muted);
        margin-bottom: 0.25rem;
        font-weight: 600;
    }

    .info-value {
        font-size: 1rem;
        color: var(--text-color);
        font-weight: 500;
    }

    .description-box {
        background-color: #f8fafc;
        border-radius: 8px;
        padding: 1.5rem;
        border-left: 4px solid var(--primary-color);
        margin-bottom: 2rem;
    }

    .photo-evidence {
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        max-width: 100%;
    }

    .photo-evidence img {
        width: 100%;
        height: auto;
        display: block;
    }

    .response-card {
        background: #f0fdf4; /* Light green background for response */
        border: 1px solid #bbf7d0;
        border-radius: var(--border-radius);
        padding: 1.5rem;
        margin-top: 2rem;
    }

    .response-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
        color: #15803d;
        font-weight: 700;
    }

    .badge-custom {
        padding: 0.35rem 0.75rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    /* Status Colors */
    .status-pending { background-color: #f1f5f9; color: #475569; }
    .status-process { background-color: #dbeafe; color: #1e40af; }
    .status-completed { background-color: #dcfce7; color: #166534; }
    .status-rejected { background-color: #fee2e2; color: #991b1b; }

    /* Severity Colors */
    .severity-ringan { background-color: #fef3c7; color: #d97706; border: 1px solid #fde68a; }
    .severity-sedang { background-color: #ffedd5; color: #ea580c; border: 1px solid #fed7aa; }
    .severity-berat { background-color: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }

    .btn-back {
        color: var(--text-muted);
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: color 0.2s;
    }

    .btn-back:hover {
        color: var(--primary-color);
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="detail-container">
        <div class="mb-4">
            <a href="{{ route($role_prefix . '.laporan.index') }}" class="btn-back">
                <i class="bi bi-arrow-left"></i> Kembali ke Daftar Laporan
            </a>
        </div>

        <div class="detail-card">
            <!-- Header -->
            <div class="detail-header">
                <div class="header-badges">
                    <!-- Status Badge -->
                    @php
                        $statusClass = 'status-pending';
                        $statusText = 'Menunggu Verifikasi';
                        if($laporan->status == 'process' || $laporan->status == 'repairing') {
                            $statusClass = 'status-process';
                            $statusText = 'Sedang Diproses';
                        } elseif($laporan->status == 'completed') {
                            $statusClass = 'status-completed';
                            $statusText = 'Selesai';
                        } elseif($laporan->status == 'rejected') {
                            $statusClass = 'status-rejected';
                            $statusText = 'Ditolak';
                        }

                        $severityClass = 'severity-ringan'; // default
                        if($laporan->tingkat_kerusakan == 'Sedang') $severityClass = 'severity-sedang';
                        if($laporan->tingkat_kerusakan == 'Berat') $severityClass = 'severity-berat';
                    @endphp
                    <span class="badge-custom {{ $statusClass }}">
                        <i class="bi bi-info-circle me-1"></i> {{ $statusText }}
                    </span>
                    
                    @if($laporan->tingkat_kerusakan)
                    <span class="badge-custom {{ $severityClass }}">
                        <i class="bi bi-exclamation-triangle me-1"></i> {{ $laporan->tingkat_kerusakan }}
                    </span>
                    @endif
                </div>

                <h1 class="tool-title">{{ $laporan->nama_alat }}</h1>
                <div class="lab-location">
                    <i class="bi bi-geo-alt-fill text-danger"></i> 
                    {{ $laporan->lokasi ?? 'Laboratorium Umum' }}
                </div>
            </div>

            <!-- Body -->
            <div class="detail-body">
                <div class="info-grid">
                    <div>
                        <div class="info-label">Tanggal Lapor</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($laporan->tanggal_laporan)->format('d F Y') }}</div>
                    </div>
                    <div>
                        <div class="info-label">Pelapor</div>
                        <div class="info-value">{{ $laporan->nama_pelapor }}</div>
                    </div>
                    <div>
                        <div class="info-label">ID Laporan</div>
                        <div class="info-value">#LAP-{{ str_pad($laporan->id, 5, '0', STR_PAD_LEFT) }}</div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="info-label mb-2">Deskripsi Kerusakan</div>
                    <div class="description-box">
                        {{ $laporan->deskripsi_kerusakan }}
                    </div>
                </div>

                @if($laporan->foto_bukti)
                <div class="mb-4">
                    <div class="info-label mb-2">Foto Bukti</div>
                    <div class="photo-evidence">
                        <img src="{{ Storage::url('laporan_kerusakan/' . $laporan->foto_bukti) }}" alt="Bukti Kerusakan">
                    </div>
                </div>
                @endif

                <!-- Response Section -->
                @if($laporan->tanggapan)
                <div class="response-card">
                    <div class="response-header">
                        <i class="bi bi-check-circle-fill"></i> Tanggapan Admin / Teknisi
                    </div>
                    <div class="info-value">
                        {{ $laporan->tanggapan }}
                    </div>
                    @if($laporan->updated_at != $laporan->created_at)
                    <div class="mt-3 text-muted small">
                         <i class="bi bi-clock me-1"></i> Diperbarui pada {{ \Carbon\Carbon::parse($laporan->updated_at)->format('d F Y, H:i') }}
                    </div>
                    @endif
                </div>
                @endif
                
                @if($laporan->status == 'completed')
                <div class="mt-4 text-center">
                    <span class="badge bg-success rounded-pill px-3 py-2">
                        <i class="bi bi-check-lg me-1"></i> Masalah Telah Ditangani
                    </span>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection