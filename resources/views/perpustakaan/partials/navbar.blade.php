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
                
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('perpustakaan.buku.index') }}">
                        <i class="bi bi-book me-1"></i><span>Data Buku</span>
                    </a>
                </li>
                
                @if(Auth::check())
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('perpustakaan.kategori.index') }}">
                        <i class="bi bi-tags me-1"></i><span>Kategori Buku</span>
                    </a>
                </li>
                
                    @if(Auth::user()->role == 'admin_perpus' || Auth::user()->role == 'super_admin')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('perpustakaan.peminjaman.index') }}">
                            <i class="bi bi-people me-1"></i><span>Data Peminjam</span>
                        </a>
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