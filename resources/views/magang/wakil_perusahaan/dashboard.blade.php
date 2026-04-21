@extends('magang.layouts.main')

@section('css')
<style>
    .dashboard-welcome {
        position: relative;
        padding: 2rem;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        border-radius: var(--radius-lg);
        margin-bottom: 2rem;
        overflow: hidden;
        box-shadow: var(--shadow-lg);
        color: var(--text-light);
    }

    .dashboard-welcome::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
        transform: translate(50%, -50%);
    }

    .dashboard-welcome::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 150px;
        height: 150px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
        transform: translate(-30%, 30%);
    }

    .dashboard-welcome h1 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
    }

    .dashboard-welcome p {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 0;
    }

    .dashboard-welcome .user-info {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .dashboard-welcome .current-date {
        font-size: 0.95rem;
        opacity: 0.8;
    }

    .stat-card {
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow);
        padding: 1.5rem;
        background-color: var(--bg-light);
        height: 100%;
        border: none;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
        position: relative;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
    }

    .stat-icon {
        font-size: 1.8rem;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        background-color: rgba(78, 205, 196, 0.15);
        color: var(--secondary);
    }

    .stat-value {
        font-size: 1.8rem;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 0.5rem;
        color: var(--text-dark);
    }

    .stat-label {
        color: var(--text-muted);
        font-size: 0.9rem;
        margin-bottom: 0;
    }

    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 0;
        display: flex;
        align-items: center;
    }

    .section-title i {
        margin-right: 0.75rem;
        color: var(--secondary);
    }

    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .action-card {
        text-align: center;
        padding: 1.25rem 1rem;
        border-radius: var(--radius-lg);
        background-color: var(--bg-light);
        box-shadow: var(--shadow);
        transition: all 0.3s ease;
        cursor: pointer;
        border: none;
        height: 100%;
        text-decoration: none;
        color: var(--text-dark);
    }

    .action-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
        background-color: var(--secondary-light);
        color: var(--text-dark);
    }

    .action-card:hover .action-icon {
        background-color: var(--secondary);
        color: white;
    }

    .action-icon {
        font-size: 1.5rem;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        background-color: var(--bg-gray);
        color: var(--primary);
        transition: all 0.3s ease;
    }

    .action-title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .action-subtitle {
        font-size: 0.9rem;
        color: var(--text-muted);
    }

    .student-list {
        margin-top: 1.5rem;
    }

    .student-card {
        border-radius: var(--radius);
        padding: 1rem;
        background-color: var(--bg-light);
        margin-bottom: 1rem;
        box-shadow: var(--shadow);
        border: none;
        transition: all 0.3s ease;
    }

    .student-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-lg);
    }

    .student-name {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 0.25rem;
    }

    .student-info {
        display: flex;
        font-size: 0.9rem;
        color: var(--text-muted);
        margin-bottom: 0.5rem;
    }

    .student-info span {
        display: flex;
        align-items: center;
    }

    .student-info span:not(:last-child) {
        margin-right: 1rem;
    }

    .student-info i {
        margin-right: 0.35rem;
    }

    .status-badge {
        padding: 0.35rem 0.75rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
    }

    .status-badge i {
        margin-right: 0.35rem;
    }

    .status-pending {
        background-color: rgba(255, 191, 0, 0.15);
        color: #cc9700;
    }

    .status-active {
        background-color: rgba(78, 205, 196, 0.15);
        color: var(--secondary-dark);
    }

    .status-completed {
        background-color: rgba(52, 152, 219, 0.15);
        color: #2980b9;
    }

    .chart-container {
        height: 300px;
        margin-bottom: 2rem;
    }

    .company-info-card {
        border-radius: var(--radius-lg);
        background-color: var(--bg-light);
        box-shadow: var(--shadow);
        padding: 1.5rem;
        border: none;
        margin-bottom: 2rem;
    }

    .company-info-card h3 {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
    }

    .company-info-card h3 i {
        margin-right: 0.75rem;
        color: var(--secondary);
    }

    .company-info-item {
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }

    .company-info-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .info-label {
        font-size: 0.9rem;
        color: var(--text-muted);
        margin-bottom: 0.35rem;
    }

    .info-value {
        font-weight: 600;
        color: var(--text-dark);
    }

    .notifications-container {
        margin-top: 1.5rem;
    }

    .notification-item {
        display: flex;
        padding: 1rem;
        border-radius: var(--radius);
        background-color: var(--bg-light);
        margin-bottom: 1rem;
        box-shadow: var(--shadow);
        border: none;
        transition: all 0.3s ease;
    }

    .notification-item:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-lg);
    }

    .notification-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: rgba(78, 205, 196, 0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--secondary);
        font-size: 1.25rem;
        margin-right: 1rem;
    }

    .notification-content {
        flex: 1;
    }

    .notification-title {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 0.25rem;
    }

    .notification-text {
        font-size: 0.9rem;
        color: var(--text-muted);
        margin-bottom: 0.5rem;
    }

    .notification-time {
        font-size: 0.8rem;
        color: var(--text-muted);
        display: flex;
        align-items: center;
    }

    .notification-time i {
        margin-right: 0.35rem;
    }

    /* Animation classes */
    .fade-in-up {
        animation: fadeInUp 0.5s ease forwards;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 768px) {
        .dashboard-welcome {
            padding: 1.5rem;
        }

        .quick-actions {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Welcome Section -->
    <div class="dashboard-welcome fade-in-up">
        <div class="row">
            <div class="col-md-8">
                <h1>Dashboard Mitra Magang</h1>
                @if(Auth::check())
                    <p class="user-info">{{ Auth::user()->name ?? Auth::user()->nama }} &bullet; {{ $wakilPerusahaan->nama_perusahaan }}</p>
                @endif
                <p class="current-date">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</p>
                <p>Kelola program magang untuk perusahaan Anda dan pantau perkembangan siswa magang di sini.</p>
            </div>
            <div class="col-md-4 d-none d-md-flex justify-content-end align-items-center">
                <img src="{{ asset('assets/images/dashboard-illustration.png') }}" alt="Mitra Magang" class="img-fluid" style="max-height: 130px; opacity: 0.9;">
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="row mb-4">
        <div class="col-md-4 col-sm-6 mb-4 mb-md-0">
            <div class="stat-card fade-in-up" style="animation-delay: 0.1s">
                <div class="stat-icon">
                    <i class="bi bi-people"></i>
                </div>
                <div class="stat-value">{{ $totalInterns }}</div>
                <p class="stat-label">Total Siswa Magang</p>
            </div>
        </div>
        <div class="col-md-4 col-sm-6 mb-4 mb-md-0">
            <div class="stat-card fade-in-up" style="animation-delay: 0.2s">
                <div class="stat-icon">
                    <i class="bi bi-person-check"></i>
                </div>
                <div class="stat-value">{{ $activeInterns }}</div>
                <p class="stat-label">Siswa Aktif</p>
            </div>
        </div>
        
        <div class="col-md-4 col-sm-6">
            <div class="stat-card fade-in-up" style="animation-delay: 0.3s">
                <div class="stat-icon">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <div class="stat-value">{{ $totalPrograms }}</div>
                <p class="stat-label">Program Magang</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="section-header">
        <h2 class="section-title"><i class="bi bi-lightning-charge"></i> Aksi Cepat</h2>
    </div>

    <div class="quick-actions mb-4">
        <a href="{{ route('magang.wakil_perusahaan.openings.create') }}" class="action-card fade-in-up" style="animation-delay: 0.1s">
            <div class="action-icon">
                <i class="bi bi-plus-circle"></i>
            </div>
            <div class="action-title">Tambah Program</div>
            <div class="action-subtitle">Buat program magang baru</div>
        </a>

        <a href="{{ route('magang.wakil_perusahaan.interns') }}" class="action-card fade-in-up" style="animation-delay: 0.2s">
            <div class="action-icon">
                <i class="bi bi-people"></i>
            </div>
            <div class="action-title">Siswa Magang</div>
            <div class="action-subtitle">Kelola siswa magang</div>
        </a>

        <a href="{{ route('magang.wakil_perusahaan.profile') }}" class="action-card fade-in-up" style="animation-delay: 0.3s">
            <div class="action-icon">
                <i class="bi bi-building-gear"></i>
            </div>
            <div class="action-title">Profil Perusahaan</div>
            <div class="action-subtitle">Kelola informasi perusahaan</div>
        </a>

        <a href="{{ route('magang.wakil_perusahaan.reports') }}" class="action-card fade-in-up" style="animation-delay: 0.4s">
            <div class="action-icon">
                <i class="bi bi-file-earmark-text"></i>
            </div>
            <div class="action-title">Laporan</div>
            <div class="action-subtitle">Lihat laporan kegiatan</div>
        </a>
    </div>

    <div class="row">
        <!-- Perusahaan Info -->
        <div class="col-lg-4 mb-4">
            <div class="company-info-card fade-in-up" style="animation-delay: 0.1s">
                <h3><i class="bi bi-building"></i> Informasi Perusahaan</h3>

                <div class="company-info-item">
                    <div class="info-label">Nama Perusahaan</div>
                    <div class="info-value">{{ $wakilPerusahaan->nama_perusahaan }}</div>
                </div>

                <div class="company-info-item">
                    <div class="info-label">Alamat</div>
                    <div class="info-value">{{ $wakilPerusahaan->alamat }}</div>
                </div>

                <div class="company-info-item">
                    <div class="info-label">Kontak</div>
                    <div class="info-value">{{ $wakilPerusahaan->no_perusahaan }}</div>
                </div>

                <div class="company-info-item">
                    <div class="info-label">Nama Pembimbing</div>
                    <div class="info-value">{{ $wakilPerusahaan->nama ?? 'Tidak ada' }}</div>
                </div>

                <div class="company-info-item">
                    <div class="info-label">Status</div>
                    <div class="info-value">
                        <span class="badge bg-success">Aktif</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Magang Stats -->
        <div class="col-lg-8 mb-4">
            <div class="card fade-in-up" style="animation-delay: 0.2s">
                <div class="card-header">
                    <h5 class="mb-0">Siswa Magang Terbaru</h5>
                </div>
                <div class="card-body">
                    <div class="student-list">
                        @forelse($recentInterns as $intern)
                            <div class="student-card">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h5 class="student-name">{{ $intern->nama }}</h5>
                                        <div class="student-info">
                                            <span><i class="bi bi-calendar-date"></i> Mulai: {{ \Carbon\Carbon::parse($intern->tanggal_mulai)->format('d M Y') }}</span>
                                            <span><i class="bi bi-calendar-check"></i> Selesai: {{ \Carbon\Carbon::parse($intern->tanggal_selesai)->format('d M Y') }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        @if($intern->status == 'Menunggu')
                                            <span class="status-badge status-pending">
                                                <i class="bi bi-hourglass"></i> Menunggu Konfirmasi
                                            </span>
                                        @elseif($intern->status == 'Disetujui')
                                            <span class="status-badge status-active">
                                                <i class="bi bi-check-circle"></i> Aktif
                                            </span>
                                        @elseif($intern->status == 'Selesai')
                                            <span class="status-badge status-completed">
                                                <i class="bi bi-trophy"></i> Selesai
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-2">
                                    <a href="{{ route('magang.wakil_perusahaan.interns.show', $intern->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i> Belum ada siswa magang pada perusahaan Anda.
                            </div>
                        @endforelse
                    </div>

                    @if(count($recentInterns) > 0)
                        <div class="text-center mt-3">
                            <a href="{{ route('magang.wakil_perusahaan.interns') }}" class="btn btn-sm btn-secondary">
                                Lihat Semua Siswa <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications -->
   

        
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add staggered animation for elements with animation-delay
        const fadeElements = document.querySelectorAll('.fade-in-up');
        fadeElements.forEach(element => {
            element.style.opacity = '0';
            setTimeout(() => {
                element.style.opacity = '1';
            }, 100);
        });
    });
</script>
@endsection
