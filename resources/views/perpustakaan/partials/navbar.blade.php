<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
            <div class="brand-logo-container">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Logo SMK">
            </div>
            <span class="ms-2 text-white fw-bold d-none d-sm-inline">Perpustakaan SMKN 5 Padang</span>
            <span class="ms-2 text-white fw-bold d-sm-none">Perpustakaan</span>
        </a>
        
        <button class="navbar-toggler custom-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard') }}">
                        <i class="bi bi-house-door me-1"></i><span>Beranda</span>
                    </a>
                </li>
                
                @if(!Auth::check() || (Auth::user()->role != 'kepala_sekolah' && Auth::user()->role != 'kepsek'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('perpustakaan.buku.index') }}">
                        <i class="bi bi-book me-1"></i><span>Data Buku</span>
                    </a>
                </li>
                @endif
                
                @if(Auth::check())
                    @if(Auth::user()->role != 'kepala_sekolah' && Auth::user()->role != 'kepsek')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('perpustakaan.kategori.index') }}">
                            <i class="bi bi-tags me-1"></i><span>Kategori Buku</span>
                        </a>
                    </li>
                    @endif
                
                    @if(Auth::user()->role == 'admin_perpus' || Auth::user()->role == 'super_admin')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('perpustakaan.peminjaman.index') }}">
                            <i class="bi bi-people me-1"></i><span>Data Peminjam</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('perpustakaan.pengadaan.index') }}">
                            <i class="bi bi-cart-plus me-1"></i><span>Pengadaan</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('perpustakaan.vendor.index') }}">
                            <i class="bi bi-shop me-1"></i><span>Vendor</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('perpustakaan.admin.ews.index') }}">
                            <i class="bi bi-exclamation-triangle me-1"></i><span>Instruksi EWS</span>
                        </a>
                    </li>
                    @elseif(Auth::user()->role == 'kepala_sekolah' || Auth::user()->role == 'kepsek')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('kepsek.dashboard') }}">
                            <i class="bi bi-speedometer2 me-1"></i><span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('kepsek.peminjaman') }}">
                            <i class="bi bi-list-columns-reverse me-1"></i><span>Data Peminjaman</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('kepsek.laporan') }}">
                            <i class="bi bi-file-earmark-check me-1"></i><span>Laporan</span>
                        </a>
                    </li>
                    @elseif(Auth::user()->role == 'waka' || Auth::user()->role == 'waka_akademik')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('perpustakaan.waka.dashboard') }}">
                            <i class="bi bi-speedometer2 me-1"></i><span>Dashboard Waka</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('perpustakaan.waka.rekomendasi.index') }}">
                            <i class="bi bi-bookmark-plus me-1"></i><span>Rekomendasi Buku</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('perpustakaan.waka.mapping.index') }}">
                            <i class="bi bi-diagram-3 me-1"></i><span>Mapping Kurikulum</span>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-graph-up me-1"></i><span>Monitoring</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('perpustakaan.waka.relevansi') }}">Relevansi Koleksi</a></li>
                            <li><a class="dropdown-item" href="{{ route('perpustakaan.waka.literasi') }}">Aktivitas Literasi</a></li>
                        </ul>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('perpustakaan.peminjaman.create') }}">
                            <i class="bi bi-journal-arrow-up me-1"></i><span>Pinjam Buku</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('perpustakaan.peminjaman.history') }}">
                            <i class="bi bi-clock-history me-1"></i><span>Riwayat</span>
                        </a>
                    </li>
                    @endif
                    
                    <li class="nav-item dropdown ms-lg-2">
                        <a class="nav-link dropdown-toggle user-dropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-1"></i><span>{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('sistem_akademik.profile') }}">
                                    <i class="bi bi-person me-1"></i> Profil Saya
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="Perpustakaan.logout()">
                                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item ms-lg-2">
                        <a class="login-btn nav-link d-inline-block" href="{{ route('login') }}">
                            <i class="bi bi-person-circle me-1"></i> Login
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>

<form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>