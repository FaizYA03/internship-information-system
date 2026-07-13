@extends('magang.layouts.main')

@section('css')
<style>
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
        font-family: 'Inter', sans-serif;
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

    .content-section {
        background: var(--card-bg);
        border-radius: var(--border-radius);
        padding: 2rem;
        box-shadow: var(--shadow-sm);
        margin-bottom: 2rem;
        border: 1px solid rgba(0,0,0,0.05);
    }

    /* Status Badge Styles */
    .status-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 600;
        text-align: center;
        min-width: 180px;
    }

    .badge-waiting {
        background-color: rgba(248, 150, 30, 0.15);
        color: #f8961e;
        border: 1px solid rgba(248, 150, 30, 0.3);
    }

    .badge-approved {
        background-color: rgba(76, 201, 240, 0.15);
        color: #4cc9f0;
        border: 1px solid rgba(76, 201, 240, 0.3);
    }

    .badge-pending {
        background-color: rgba(248, 150, 30, 0.15);
        color: #f8961e;
        border: 1px solid rgba(248, 150, 30, 0.3);
    }

    /* Info Card Styles */
    .info-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: var(--border-radius);
        padding: 1.5rem;
        color: white;
        margin-bottom: 1.5rem;
        box-shadow: var(--shadow-md);
    }

    .info-card-title {
        font-size: 0.875rem;
        opacity: 0.95;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-card-value {
        font-size: 1.5rem;
        font-weight: 700;
    }

    /* Table Styles */
    .nilai-table {
        width: 100%;
        border-collapse: collapse;
    }

    .nilai-table thead {
        background-color: var(--light-bg);
        border-top: 2px solid var(--text-muted);
        border-bottom: 2px solid var(--text-muted);
    }

    .nilai-table th {
        padding: 1rem;
        text-align: left;
        color: var(--text-color);
        font-weight: 700;
        font-size: 0.95rem;
    }

    .nilai-table tbody tr {
        border-bottom: 1px solid rgba(0,0,0,0.08);
        transition: background-color 0.2s;
    }

    .nilai-table tbody tr:hover {
        background-color: rgba(67, 97, 238, 0.03);
    }

    .nilai-table td {
        padding: 1rem;
        color: var(--text-color);
    }

    .nilai-display {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--primary-color);
    }

    .nilai-display.large {
        font-size: 1.25rem;
    }

    /* Summary Stats */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-item {
        background: var(--card-bg);
        border-radius: var(--border-radius);
        padding: 1.5rem;
        border: 1px solid rgba(0,0,0,0.08);
        text-align: center;
    }

    .stat-label {
        color: var(--text-muted);
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text-color);
    }

    .stat-icon {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        opacity: 0.7;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: var(--text-muted);
    }

    .empty-state-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-state p {
        margin: 0;
        font-size: 1rem;
    }

    /* Detail Row */
    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 1rem 0;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }

    .detail-label {
        color: var(--text-muted);
        font-weight: 600;
    }

    .detail-value {
        color: var(--text-color);
        font-weight: 700;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-title {
            font-size: 1.5rem;
        }

        .content-section {
            padding: 1.25rem;
        }

        .nilai-table th,
        .nilai-table td {
            padding: 0.75rem;
            font-size: 0.9rem;
        }

        .status-badge {
            min-width: 140px;
            font-size: 0.8rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">{{ $header }}</h1>
        <p class="page-subtitle">Lihat status dan hasil penilaian magang Anda</p>
    </div>

    <!-- Alert Section -->
    @if (session('status'))
        <div class="alert alert-{{ session('status') == 'success' ? 'success' : 'danger' }} alert-dismissible fade show" role="alert">
            <strong>{{ session('title', '') }}</strong> {{ session('message', '') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Student Info Card -->
    @if (isset($nilaiData['magangSiswa']))
        <div class="info-card">
            <div class="info-card-title">Informasi Magang Anda</div>
            <div class="info-card-value">{{ $nilaiData['magangSiswa']->wakilPerusahaan->nama_perusahaan ?? 'Perusahaan' }}</div>
            <small style="opacity: 0.9;">
                {{ \Carbon\Carbon::parse($nilaiData['magangSiswa']->tanggal_mulai)->format('d M Y') }} - 
                {{ \Carbon\Carbon::parse($nilaiData['magangSiswa']->tanggal_selesai)->format('d M Y') }}
            </small>
        </div>
    @endif

    <!-- Main Content -->
    @if (isset($nilaiData['penilaian']) && $nilaiData['penilaian'])
        <!-- Nilai Stats Grid -->
        <div class="stats-grid">
            <!-- Nilai PKL Card -->
            <div class="stat-item">
                <div class="stat-icon">📊</div>
                <div class="stat-label">Nilai PKL (Mitra)</div>
                @if ($nilaiData['nilaiPKL'])
                    <div class="stat-value" style="color: #4cc9f0;">{{ $nilaiData['nilaiPKL'] }}</div>
                    <small style="color: #4cc9f0;">{{ $nilaiData['statusNilaiPKL'] }}</small>
                @else
                    <div class="stat-value" style="color: #f8961e; font-size: 0.9rem;">Belum Tersedia</div>
                    <small style="color: #f8961e;">{{ $nilaiData['statusNilaiPKL'] }}</small>
                @endif
            </div>

            <!-- Nilai Laporan Card -->
            <div class="stat-item">
                <div class="stat-icon">📝</div>
                <div class="stat-label">Nilai Laporan (Pembimbing)</div>
                @if ($nilaiData['nilaiLaporan'])
                    <div class="stat-value" style="color: #4cc9f0;">{{ $nilaiData['nilaiLaporan'] }}</div>
                    <small style="color: #4cc9f0;">{{ $nilaiData['statusNilaiLaporan'] }}</small>
                @else
                    <div class="stat-value" style="color: #f8961e; font-size: 0.9rem;">Belum Tersedia</div>
                    <small style="color: #f8961e;">{{ $nilaiData['statusNilaiLaporan'] }}</small>
                @endif
            </div>

            <!-- Nilai Akhir Card -->
            <div class="stat-item">
                <div class="stat-icon">🏆</div>
                <div class="stat-label">Nilai Akhir</div>
                @if ($nilaiData['nilaiAkhir'])
                    <div class="stat-value" style="color: #4cc9f0;">{{ $nilaiData['nilaiAkhir'] }}</div>
                    <small style="color: #4cc9f0;">{{ $nilaiData['statusNilaiAkhir'] }}</small>
                @else
                    <div class="stat-value" style="color: #f8961e; font-size: 0.9rem;">Belum Tersedia</div>
                    <small style="color: #f8961e;">{{ $nilaiData['statusNilaiAkhir'] }}</small>
                @endif
            </div>
        </div>

        <!-- Detailed Table -->
        <div class="content-section">
            <h5 class="mb-3" style="font-weight: 700; color: var(--text-color);">📋 Detail Penilaian</h5>
            
            <table class="nilai-table">
                <thead>
                    <tr>
                        <th>Komponen Penilaian</th>
                        <th>Nilai</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Nilai PKL Row -->
                    <tr>
                        <td style="font-weight: 600; color: var(--primary-color);">Penilaian PKL (dari Mitra)</td>
                        <td>
                            @if ($nilaiData['nilaiPKL'])
                                <span class="nilai-display large">{{ $nilaiData['nilaiPKL'] }}/100</span>
                            @else
                                <span style="color: var(--text-muted);">-</span>
                            @endif
                        </td>
                        <td>
                            @if ($nilaiData['nilaiPKL'])
                                <span class="status-badge badge-approved">✓ {{ $nilaiData['statusNilaiPKL'] }}</span>
                            @else
                                <span class="status-badge badge-waiting">⏳ {{ $nilaiData['statusNilaiPKL'] }}</span>
                            @endif
                        </td>
                    </tr>

                    <!-- Nilai Laporan Row -->
                    <tr>
                        <td style="font-weight: 600; color: var(--primary-color);">Penilaian Laporan (dari Pembimbing)</td>
                        <td>
                            @if ($nilaiData['nilaiLaporan'])
                                <span class="nilai-display large">{{ $nilaiData['nilaiLaporan'] }}/100</span>
                            @else
                                <span style="color: var(--text-muted);">-</span>
                            @endif
                        </td>
                        <td>
                            @if ($nilaiData['nilaiLaporan'])
                                <span class="status-badge badge-approved">✓ {{ $nilaiData['statusNilaiLaporan'] }}</span>
                            @else
                                <span class="status-badge badge-pending">⏳ {{ $nilaiData['statusNilaiLaporan'] }}</span>
                            @endif
                        </td>
                    </tr>

                    <!-- Nilai Akhir Row -->
                    <tr>
                        <td style="font-weight: 600; color: var(--primary-color);">Nilai Akhir Magang</td>
                        <td>
                            @if ($nilaiData['nilaiAkhir'])
                                <span class="nilai-display large">{{ $nilaiData['nilaiAkhir'] }}/100</span>
                            @else
                                <span style="color: var(--text-muted);">-</span>
                            @endif
                        </td>
                        <td>
                            @if ($nilaiData['nilaiAkhir'])
                                <span class="status-badge badge-approved">✓ {{ $nilaiData['statusNilaiAkhir'] }}</span>
                            @else
                                <span class="status-badge badge-pending">⏳ {{ $nilaiData['statusNilaiAkhir'] }}</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Timeline Info -->
            <div style="margin-top: 2rem; padding-top: 1rem; border-top: 1px solid rgba(0,0,0,0.08);">
                <h6 style="font-weight: 700; margin-bottom: 1rem;">📅 Catatan Proses Penilaian:</h6>
                <div style="font-size: 0.9rem; color: var(--text-muted); line-height: 1.6;">
                    <p><strong>1. Penilaian PKL (Mitra):</strong> Mitra/perusahaan akan memberikan penilaian berdasarkan performa Anda selama magang. Nilai ini mencakup hard skills dan soft skills.</p>
                    <p><strong>2. Penilaian Laporan (Pembimbing):</strong> Guru pembimbing akan menilai kualitas laporan magang Anda setelah mitra memberikan penilaian.</p>
                    <p><strong>3. Nilai Akhir:</strong> Nilai akhir akan dihitung otomatis setelah kedua penilaian di atas selesai (70% dari nilai PKL dan 30% dari nilai laporan).</p>
                </div>

                <!-- Action Button -->
                @if ($nilaiData['nilaiPKL'])
                    <div style="margin-top: 1.5rem; display: flex; gap: 10px;">
                        <a href="{{ route('magang.siswa.nilai.breakdown') }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-diagram-3"></i> Lihat Detail Breakdown Penilaian
                        </a>
                        
                        @if ($nilaiData['nilaiAkhir'])
                        <a href="{{ route('magang.siswa.nilai.download') }}" class="btn btn-sm btn-success" target="_blank">
                            <i class="bi bi-file-earmark-pdf"></i> Cetak/Unduh Nilai Akhir
                        </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="content-section">
            <div class="empty-state">
                <div class="empty-state-icon">📭</div>
                <p><strong>Belum Ada Data Penilaian</strong></p>
                <p style="font-size: 0.9rem; margin-top: 0.5rem;">Data penilaian Anda akan muncul setelah mitra/perusahaan memberikan penilaian.</p>
            </div>
        </div>
    @endif
</div>
@endsection
