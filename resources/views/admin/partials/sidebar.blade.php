<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <a class="navbar-brand" href="{{ route('lab.dashboard') }}">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo SMK" height="32">
            <span>Sistem Laboratorium</span>
        </a>
    </div>
    
    <div class="menu-items">
        <ul>
            <li class="{{ request()->routeIs('lab.admin_new.dashboard') || request()->is('lab/kepala-lab') || request()->is('lab/kepala-sekolah') || request()->is('lab/waka-akademik') ? 'active' : '' }}">
                <a href="{{ Auth::user()->role == 'siswa' ? route('siswa.dashboard') : (Auth::user()->isKepalaLab() ? route('lab.kepala_lab.dashboard') : (Auth::user()->isKepalaSekolah() ? route('lab.kepala_sekolah.dashboard') : (Auth::user()->isWakaAkademik() ? route('lab.waka_akademik.dashboard') : route('lab.admin_new.dashboard')))) }}" data-title="Dashboard">
                    <i class="bi bi-speedometer2"></i>
                    <span class="menu-text">Dashboard</span>
                </a>
            </li>
            
            @if(Auth::user()->isAdminLab() || Auth::user()->role == 'siswa' || Auth::user()->isKepalaLab() || Auth::user()->isKepalaSekolah() || Auth::user()->isWakaAkademik())
                <!-- Menu Utama Lab -->
                <li class="{{ request()->routeIs('lab.admin_new.laboratorium.index') ? 'active' : '' }}">
                    <a href="{{ route('lab.admin_new.laboratorium.index') }}" data-title="Kelola Laboratorium">
                        <i class="bi bi-building"></i>
                        <span class="menu-text">Daftar Lab</span>
                    </a>
                </li>
                
                <li class="{{ request()->routeIs('lab.admin_new.jadwal.*') ? 'active' : '' }}">
                    <a href="{{ route('lab.admin_new.laboratorium.index') }}" data-title="Jadwal Laboratorium">
                        <i class="bi bi-calendar-week"></i>
                        <span class="menu-text">Jadwal Lab</span>
                    </a>
                </li>

                <!-- Menu Khusus Admin Lab -->
                @if(Auth::user()->isAdminLab())
                <li class="{{ request()->routeIs('lab.admin_new.inventaris.*') ? 'active' : '' }}">
                    <a href="{{ route('lab.admin_new.inventaris.index') }}" data-title="Kelola Inventaris">
                        <i class="bi bi-box-seam"></i>
                        <span class="menu-text">Inventaris Alat</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('lab.admin_new.peminjaman.internal.*') ? 'active' : '' }}">
                    <a href="{{ route('lab.admin_new.peminjaman.internal.index') }}" data-title="Peminjaman Alat">
                        <i class="bi bi-person-check"></i>
                        <span class="menu-text">Peminjaman Siswa</span>
                    </a>
                </li>
                @endif

                <!-- Menu Khusus Kepala Lab -->
                @if(Auth::user()->isKepalaLab())
                <li class="{{ request()->routeIs('lab.kepala_lab.approval.*') ? 'active' : '' }}">
                    <a href="{{ route('lab.kepala_lab.approval.eksternal') }}" data-title="Rekomendasi">
                        <i class="bi bi-check2-square"></i>
                        <span class="menu-text">Rekomendasi</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('lab.kepala_lab.pengadaan.*') ? 'active' : '' }}">
                    <a href="{{ route('lab.kepala_lab.pengadaan.index') }}" data-title="Pengadaan">
                        <i class="bi bi-cart"></i>
                        <span class="menu-text">Pengadaan Alat</span>
                    </a>
                </li>
                @endif

                <!-- Menu Khusus Kepala Sekolah -->
                @if(Auth::user()->isKepalaSekolah())
                <li class="{{ request()->routeIs('lab.kepala_sekolah.approval.eksternal') ? 'active' : '' }}">
                    <a href="{{ route('lab.kepala_sekolah.approval.eksternal') }}" data-title="Approval Pinjam">
                        <i class="bi bi-shield-check"></i>
                        <span class="menu-text">Approval Pinjam</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('lab.kepala_sekolah.approval.pengadaan.*') ? 'active' : '' }}">
                    <a href="{{ route('lab.kepala_sekolah.approval.pengadaan.index') }}" data-title="Approval Pengadaan">
                        <i class="bi bi-cash-stack"></i>
                        <span class="menu-text">Approval Pengadaan</span>
                    </a>
                </li>
                @endif

                <!-- Menu Khusus Waka Akademik -->
                @if(Auth::user()->isWakaAkademik())
                <li class="{{ request()->routeIs('lab.waka_akademik.monitoring') ? 'active' : '' }}">
                    <a href="{{ route('lab.waka_akademik.monitoring') }}" data-title="Monitoring">
                        <i class="bi bi-display"></i>
                        <span class="menu-text">Monitor Aktivitas</span>
                    </a>
                </li>
                @endif
                
                <li class="{{ request()->routeIs('lab.admin_new.kerusakan.*') ? 'active' : '' }}">
                    <a href="{{ route('lab.admin_new.kerusakan.index') }}" data-title="Laporan Kerusakan">
                        <i class="bi bi-journal-text"></i>
                        <span class="menu-text">Laporan Kerusakan</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>
</div>
