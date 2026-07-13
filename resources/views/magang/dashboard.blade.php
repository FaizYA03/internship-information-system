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
        border-radius: 1rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        padding: 1.5rem;
        background-color: #ffffff;
        border: 1px solid #f3f4f6;
        height: 100%;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 1.25rem;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    
    .stat-icon {
        font-size: 1.5rem;
        border-radius: 0.75rem;
        width: 3.5rem;
        height: 3.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .icon-blue { background-color: #eff6ff; color: #3b82f6; }
    .icon-purple { background-color: #faf5ff; color: #a855f7; }
    .icon-green { background-color: #f0fdf4; color: #22c55e; }
    .icon-orange { background-color: #fff7ed; color: #f97316; }
    
    .stat-info {
        display: flex;
        flex-direction: column;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        line-height: 1.2;
        color: #111827;
    }
    
    .stat-label {
        color: #6b7280;
        font-size: 0.875rem;
        font-weight: 500;
        margin-bottom: 0;
    }
    
    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.25rem;
        margin-top: 2rem;
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0;
        display: flex;
        align-items: center;
    }
    
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
    }
    
    .action-card {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.25rem;
        border-radius: 0.75rem;
        background-color: #ffffff;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        transition: all 0.2s ease;
        cursor: pointer;
        text-decoration: none;
        color: #111827;
    }
    
    .action-card:hover {
        border-color: #d1d5db;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        background-color: #f9fafb;
        color: #111827;
    }
    
    .action-icon {
        font-size: 1.25rem;
        color: #6366f1;
    }
    
    .action-title {
        font-size: 0.95rem;
        font-weight: 600;
    }

    .modern-panel {
        background: #ffffff;
        border-radius: 1rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        border: 1px solid #f3f4f6;
        padding: 1.5rem;
        height: 100%;
    }

    .modern-table {
        width: 100%;
        border-collapse: collapse;
    }
    .modern-table th {
        background: #f9fafb;
        padding: 0.75rem 1rem;
        font-size: 0.75rem;
        text-transform: uppercase;
        color: #6b7280;
        font-weight: 600;
        border-bottom: 1px solid #e5e7eb;
    }
    .modern-table td {
        padding: 1rem;
        border-bottom: 1px solid #e5e7eb;
        font-size: 0.875rem;
        color: #374151;
        vertical-align: middle;
    }
    .modern-table tr:last-child td {
        border-bottom: none;
    }
    
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .status-badge.menunggu { background: #fef3c7; color: #92400e; }
    .status-badge.disetujui { background: #dcfce7; color: #166534; }
    .status-badge.ditolak { background: #fee2e2; color: #991b1b; }
    
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
    .internship-card {
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow);
    padding: 1.5rem;
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    color: white;
    height: 100%;
    position: relative;
    overflow: hidden;
}

.internship-card::after {
    content: '';
    position: absolute;
    right: -40px;
    bottom: -40px;
    width: 120px;
    height: 120px;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
}

.internship-title {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.internship-item {
    font-size: 0.9rem;
    margin-bottom: 0.4rem;
    display: flex;
    align-items: center;
}

.internship-item i {
    margin-right: 0.5rem;
    width: 18px;
}
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Welcome Section -->
    <div class="dashboard-welcome fade-in-up">
        <div class="row">
            <div class="col-md-8">
                <h1>Selamat Datang di Sistem Magang</h1>
                @if(Auth::check())
                    <p class="user-info">{{ Auth::user()->name ?? Auth::user()->nama }} &bullet; {{ ucfirst(Auth::user()->role) }}</p>
                @endif
                <p class="current-date">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</p>
                <p>Program magang mempersiapkan siswa dengan pengalaman kerja nyata di perusahaan partner kami.</p>
            </div>
            <div class="col-md-4 d-none d-md-flex justify-content-end align-items-center">
                <img src="{{ asset('assets/images/dashboard-illustration.png') }}" alt="Magang" class="img-fluid" style="max-height: 130px; opacity: 0.9;">
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
    <div class="col-md-3 col-sm-6 mb-4 mb-md-0">
        <div class="stat-card fade-in-up" style="animation-delay: 0.1s">
            <div class="stat-icon icon-blue">
                <i class="bi bi-building"></i>
            </div>
            <div class="stat-info">
                <p class="stat-label">Perusahaan Mitra</p>
                <div class="stat-value">{{ $perusahaanMitra }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-4 mb-md-0">
        <div class="stat-card fade-in-up" style="animation-delay: 0.2s">
            <div class="stat-icon icon-purple">
                <i class="bi bi-people"></i>
            </div>
            <div class="stat-info">
                <p class="stat-label">Total Pendaftar</p>
                <div class="stat-value">{{ $totalSiswaMagang }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-4 mb-md-0">
        <div class="stat-card fade-in-up" style="animation-delay: 0.3s">
            <div class="stat-icon icon-green">
                <i class="bi bi-person-check"></i>
            </div>
            <div class="stat-info">
                <p class="stat-label">Siswa Aktif</p>
                <div class="stat-value">{{ $sudahDisetujui }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="stat-card fade-in-up" style="animation-delay: 0.4s">
            <div class="stat-icon icon-orange">
                <i class="bi bi-award"></i>
            </div>
            <div class="stat-info">
                <p class="stat-label">Keberhasilan</p>
                <div class="stat-value">{{ $tingkatKeberhasilan }}%</div>
            </div>
        </div>
    </div>
</div>
    
    <!-- Quick Actions -->
    <div class="section-header">
        <h2 class="section-title"><i class="bi bi-lightning-charge"></i> Aksi Cepat</h2>
    </div>
    
    <div class="row mb-4">

    <!-- 🔥 AKSI CEPAT -->
    <div class="col-12 mb-4">
        <div class="quick-actions">
            @if(!Auth::check() || Auth::user()->role === 'siswa')
            <a href="{{ Auth::check() ? route('magang.magang.create') : route('login', ['from' => 'magang']) }}" class="action-card fade-in-up" style="animation-delay: 0.1s">
                <div class="action-icon"><i class="bi bi-briefcase"></i></div>
                <div class="action-title">Daftar Magang</div>
            </a>
            @endif
            
            @if(Auth::check() && in_array(Auth::user()->role, ['super_admin', 'admin_magang']))
                <a href="{{ route('magang.magang.index') }}" class="action-card fade-in-up" style="animation-delay: 0.2s">
                    <div class="action-icon"><i class="bi bi-list-check"></i></div>
                    <div class="action-title">Kelola Magang</div>
                </a>

                <a href="{{ route('magang.perusahaan.index') }}" class="action-card fade-in-up" style="animation-delay: 0.3s">
                    <div class="action-icon"><i class="bi bi-building-add"></i></div>
                    <div class="action-title">Kelola Perusahaan</div>
                </a>
            @endif
            
            <a href="{{ asset('assets/files/Panduan_Magang_Siswa.pdf') }}" download class="action-card fade-in-up" style="animation-delay: 0.4s">
                <div class="action-icon"><i class="bi bi-journal-bookmark"></i></div>
                <div class="action-title">Panduan Magang</div>
            </a>
        </div>
    </div>

    @auth
    @if(Auth::user()->role === 'siswa')
        <div class="col-md-12 mb-4">
            <div class="internship-card fade-in-up" style="padding: 2rem; border-radius: 1.25rem;">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 pb-3 border-bottom border-light border-opacity-25">
                    <div class="mb-3 mb-md-0">
                        <h3 style="font-weight: 700; font-size: 1.5rem; margin-bottom: 0.25rem;"><i class="bi bi-person-workspace me-2"></i> Data Magang Saya</h3>
                        <p style="opacity: 0.9; margin: 0; font-size: 0.95rem;">Informasi detail mengenai penempatan magang Anda</p>
                    </div>
                    @if($magangSiswa)
                    <span class="badge bg-light text-dark px-3 py-2 rounded-pill" style="font-size: 0.9rem; font-weight: 600; align-self: flex-start;">
                        {{ $magangSiswa->status }}
                    </span>
                    @endif
                </div>

                @if($magangSiswa)
                    <div class="row g-4">
                        <!-- Perusahaan & Posisi -->
                        <div class="col-md-6">
                            <div class="p-3 rounded h-100" style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);">
                                <div class="text-uppercase mb-2" style="font-size: 0.75rem; font-weight: 700; letter-spacing: 1px; opacity: 0.85;">Tempat Magang</div>
                                <div style="font-size: 1.15rem; font-weight: 600;" class="mb-1 text-truncate" title="{{ $magangSiswa->wakilPerusahaan->nama_perusahaan ?? '-' }}">
                                    <i class="bi bi-building me-2"></i> {{ $magangSiswa->wakilPerusahaan->nama_perusahaan ?? '-' }}
                                </div>
                                <div style="font-size: 0.95rem; opacity: 0.9;" class="text-truncate" title="{{ $magangSiswa->opening->posisi ?? 'Tidak ada posisi' }}">
                                    <i class="bi bi-briefcase me-2"></i> {{ $magangSiswa->opening->posisi ?? 'Tidak ada posisi' }}
                                </div>
                            </div>
                        </div>

                        <!-- Periode -->
                        <div class="col-md-6">
                            <div class="p-3 rounded h-100" style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);">
                                <div class="text-uppercase mb-2" style="font-size: 0.75rem; font-weight: 700; letter-spacing: 1px; opacity: 0.85;">Periode Pelaksanaan</div>
                                <div style="font-size: 1.15rem; font-weight: 600;" class="mb-1">
                                    <i class="bi bi-calendar-check me-2"></i> {{ \Carbon\Carbon::parse($magangSiswa->tanggal_mulai)->locale('id')->isoFormat('D MMMM Y') }}
                                </div>
                                <div style="font-size: 0.95rem; opacity: 0.9;">
                                    <i class="bi bi-arrow-right me-2"></i> s/d {{ \Carbon\Carbon::parse($magangSiswa->tanggal_selesai)->locale('id')->isoFormat('D MMMM Y') }}
                                </div>
                            </div>
                        </div>

                        <!-- Pembimbing Sekolah -->
                        <div class="col-md-6">
                            <div class="p-3 rounded h-100" style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);">
                                <div class="text-uppercase mb-2" style="font-size: 0.75rem; font-weight: 700; letter-spacing: 1px; opacity: 0.85;">Guru Pembimbing (Sekolah)</div>
                                <div style="font-size: 1.15rem; font-weight: 600;" class="text-truncate">
                                    <i class="bi bi-person-badge me-2"></i> {{ $magangSiswa->pembimbing?->guru?->nama ?? 'Belum ditentukan' }}
                                </div>
                            </div>
                        </div>

                        <!-- Supervisor Mitra -->
                        <div class="col-md-6">
                            <div class="p-3 rounded h-100" style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);">
                                <div class="text-uppercase mb-2" style="font-size: 0.75rem; font-weight: 700; letter-spacing: 1px; opacity: 0.85;">Pembimbing Lapangan (Mitra)</div>
                                <div style="font-size: 1.15rem; font-weight: 600;" class="text-truncate">
                                    <i class="bi bi-person-vcard me-2"></i> {{ $magangSiswa->mitraSupervisor->nama_lengkap ?? 'Belum ditentukan' }}
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="mt-4 p-5 text-center rounded" style="background: rgba(255,255,255,0.1); border: 1px dashed rgba(255,255,255,0.3);">
                        <i class="bi bi-info-circle mb-3" style="font-size: 2.5rem; opacity: 0.8;"></i>
                        <h4 style="font-weight: 600;">Belum Ada Data Magang</h4>
                        <p class="mb-0" style="opacity: 0.9;">Anda belum memiliki data magang yang disetujui saat ini.</p>
                    </div>
                @endif
            </div>
        </div>
    @elseif(in_array(Auth::user()->role, ['super_admin', 'admin_magang']))
        <!-- 🔥 MODERN DASHBOARD GRID (ADMIN) -->
        <div class="col-lg-8 mb-4 fade-in-up">
            <div class="modern-panel">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 style="font-size:1.1rem; font-weight:700; color:#111827; margin:0;">Pendaftar Terbaru</h4>
                    <a href="{{ route('magang.magang.index') }}" style="font-size:0.875rem; color:#6366f1; text-decoration:none; font-weight:600;">Lihat Semua &rarr;</a>
                </div>
                <div class="table-responsive">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>Nama Siswa</th>
                                <th>Tempat Magang</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendaftarTerbaru as $siswa)
                                <tr>
                                    <td>
                                        <div style="font-weight:600; color:#111827;">{{ $siswa->nama }}</div>
                                        <div style="font-size:0.75rem; color:#6b7280;">{{ $siswa->user->email ?? '-' }}</div>
                                    </td>
                                    <td>
                                        <div style="font-weight:500;">{{ optional($siswa->wakilPerusahaan)->nama_perusahaan ?? 'Mandiri' }}</div>
                                    </td>
                                    <td>
                                        @php
                                            $badgeClass = 'menunggu';
                                            if(in_array($siswa->status, ['Disetujui', 'Disetujui Admin', 'Diterima Mitra'])) $badgeClass = 'disetujui';
                                            if($siswa->status == 'Ditolak') $badgeClass = 'ditolak';
                                        @endphp
                                        <span class="status-badge {{ $badgeClass }}">{{ $siswa->status }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Belum ada pendaftar terbaru</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4 fade-in-up">
            <div class="modern-panel d-flex flex-column">
                <h4 style="font-size:1.1rem; font-weight:700; color:#111827; margin-bottom:1rem;">Status Pendaftaran</h4>
                <div style="flex-grow:1; position:relative; min-height: 250px;">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    @endif
@endauth

</div>

    
    </div>
    <div class="row">
        @foreach ($perusahaan as $index => $item)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="company-card fade-in-up" style="animation-delay: {{ 0.5 + $loop->index * 0.1 }}s">
                    <div class="company-icon">
                        <i class="bi bi-building"></i>
                    </div>
                    <h3 class="company-title">{{ $item->nama_perusahaan }}</h3>
                    
                    <div class="company-info">
                        <p><i class="bi bi-geo-alt"></i> {{ $item->alamat }}</p>
                        <p><i class="bi bi-telephone"></i> {{ $item->no_perusahaan }}</p>
                        <p><i class="bi bi-person"></i> {{ $item->nama_pembimbing }}</p>
                    </div>
                    
                    <a href="{{ Auth::check() ? route('magang.magang.create') : route('login', ['from' => 'magang']) }}" class="company-action">
                        Daftar Sekarang <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        @endforeach
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

        // Initialize Chart if element exists
        const ctx = document.getElementById('statusChart');
        if(ctx) {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Menunggu', 'Disetujui', 'Ditolak'],
                    datasets: [{
                        data: [
                            {{ isset($statStatus['Menunggu']) ? $statStatus['Menunggu'] : 0 }}, 
                            {{ isset($statStatus['Disetujui']) ? $statStatus['Disetujui'] : 0 }}, 
                            {{ isset($statStatus['Ditolak']) ? $statStatus['Ditolak'] : 0 }}
                        ],
                        backgroundColor: [
                            '#fbbf24', // Yellow
                            '#10b981', // Green
                            '#ef4444'  // Red
                        ],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                font: {
                                    family: "'Inter', sans-serif",
                                    size: 12
                                }
                            }
                        }
                    },
                    cutout: '70%'
                }
            });
        }
    });
</script>
@endsection