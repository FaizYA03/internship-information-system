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
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
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
    }
    
    .action-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
        background-color: var(--secondary-light);
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
        color: var(--text-dark);
    }
    
    .company-card {
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
        background-color: var(--bg-light);
        border: none;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
    }
    
    .company-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
    }
    
    .company-icon {
        font-size: 1.8rem;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        background-color: rgba(78, 205, 196, 0.15);
        color: var(--secondary);
    }
    
    .company-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 0.75rem;
    }
    
    .company-info {
        color: var(--text-muted);
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }
    
    .company-info i {
        width: 20px;
        color: var(--secondary);
        margin-right: 0.5rem;
    }
    
    .company-action {
        margin-top: auto;
        background-color: var(--secondary);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: var(--radius);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .company-action:hover {
        background-color: var(--secondary-dark);
        color: white;
        transform: translateY(-2px);
    }
    
    .company-action i {
        margin-left: 0.5rem;
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
    
    <!-- Stats Section 
    <div class="row mb-4">
        <div class="col-md-4 col-sm-6 mb-4 mb-md-0">
            <div class="stat-card fade-in-up" style="animation-delay: 0.1s">
                <div class="stat-icon">
                    <i class="bi bi-building"></i>
                </div>
                <div class="stat-value">{{ count($perusahaan) }}</div>
                <p class="stat-label">Perusahaan Partner</p>
            </div>
        </div>
        <div class="col-md-4 col-sm-6 mb-4 mb-md-0">
            <div class="stat-card fade-in-up" style="animation-delay: 0.2s">
                <div class="stat-icon">
                    <i class="bi bi-people"></i>
                </div>
                <div class="stat-value">150+</div>
                <p class="stat-label">Siswa Magang</p>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="stat-card fade-in-up" style="animation-delay: 0.3s">
                <div class="stat-icon">
                    <i class="bi bi-award"></i>
                </div>
                <div class="stat-value">95%</div>
                <p class="stat-label">Tingkat Keberhasilan</p>
            </div>
        </div>
    </div>
    -->
    <!-- Quick Actions -->
    <div class="section-header">
        <h2 class="section-title"><i class="bi bi-lightning-charge"></i> Aksi Cepat</h2>
    </div>
    
    <div class="quick-actions mb-4">
        <a href="{{ Auth::check() ? route('magang.magang.create') : route('login', ['from' => 'magang']) }}" class="action-card fade-in-up" style="animation-delay: 0.1s">
            <div class="action-icon">
                <i class="bi bi-briefcase"></i>
            </div>
            <div class="action-title">Daftar Magang</div>
        </a>
        
        @if(Auth::check() && in_array(Auth::user()->role, ['super_admin', 'admin_magang']))
            <a href="{{ route('magang.magang.index') }}" class="action-card fade-in-up" style="animation-delay: 0.2s">
                <div class="action-icon">
                    <i class="bi bi-list-check"></i>
                </div>
                <div class="action-title">Kelola Magang</div>
            </a>
            <a href="{{ route('magang.perusahaan.index') }}" class="action-card fade-in-up" style="animation-delay: 0.3s">
                <div class="action-icon">
                    <i class="bi bi-building-add"></i>
                </div>
                <div class="action-title">Kelola Perusahaan</div>
            </a>
        @endif
        
         <a href="{{ asset('assets/files/Panduan_Magang_Siswa.pdf') }}" download class="action-card fade-in-up" style="animation-delay: 0.4s">
        <div class="action-icon">
        <i class="bi bi-journal-bookmark"></i> <!-- Ganti icon biar lebih cocok -->
        </div>
        <div class="action-title">Panduan Magang</div>
        </a>

       @if(Auth::check() && Auth::user()->role === 'siswa')
        <a href="{{ route('magang.pengajuan_judul.create') }}" class="action-card fade-in-up" style="animation-delay: 0.4s">
            <div class="action-icon">
                <i class="bi bi-pencil-square"></i>
            </div>
            <div class="action-title">Ajukan Judul Laporan Akhir Magang</div>
        </a>
    @endif


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