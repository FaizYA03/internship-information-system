<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="toggle-container">
        <button id="sidebarToggle" aria-label="Toggle Sidebar">
            <i class="bi bi-chevron-left"></i>
        </button>
    </div>
    
    <div class="navbar-content ms-auto">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <i class="bi bi-three-dots-vertical text-white"></i>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="d-none d-md-flex flex-column text-end me-2">
                            <span class="fw-bold" style="font-size: 0.9rem;">{{ Auth::user()->nama }}</span>
                            @if(Auth::user()->role === 'siswa' && Auth::user()->siswa)
                                @php
                                    $siswa = Auth::user()->siswa;
                                    // Handle relationship 'kelas' vs string field 'kelas'
                                    $kelasObj = $siswa->kelas;
                                    $namaKelas = '';
                                    $jurusan = '';
                                    
                                    if ($kelasObj instanceof \App\Models\Kelas) {
                                        $namaKelas = $kelasObj->nama_kelas;
                                        $jurusan = $kelasObj->jurusan;
                                    } else {
                                        $namaKelas = $siswa->kelas; // Fallback to string if relationship not loaded/empty
                                        $jurusan = $siswa->jurusan;
                                    }
                                @endphp
                                @if($namaKelas)
                                    <small style="font-size: 0.7rem; color: rgba(255,255,255,0.8);">
                                        {{ $namaKelas }} {{ $jurusan ? '- ' . $jurusan : '' }}
                                    </small>
                                @endif
                            @endif
                        </div>
                        <i class="bi bi-person-circle fs-4"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('sistem_akademik.profile') }}">
                                <i class="bi bi-person me-2"></i> Profil
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
                                <i class="bi bi-box-arrow-right me-2"></i> Keluar
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>