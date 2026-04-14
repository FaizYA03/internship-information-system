<!-- filepath: resources/views/magang/partials/sidebar.blade.php -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <a class="navbar-brand" href="{{ route('magang.dashboard') }}">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo SMK" height="32">
            <span>Sistem Magang</span>
        </a>
    </div>
    
    <div class="menu-items">
        <ul>
            <!-- DASHBOARD -->
            <li class="{{ request()->routeIs('magang.dashboard') ? 'active' : '' }}">
                <a href="{{ route('magang.dashboard') }}">
                    <i class="bi bi-speedometer2"></i>
                    <span class="menu-text">Dashboard</span>
                </a>
            </li>
            
            @if(Auth::check() && Auth::user()->role == 'wakil_perusahaan')
                <!-- ================= WAKIL PERUSAHAAN ================= -->
                
                <li class="{{ request()->routeIs('magang.wakil_perusahaan.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('magang.wakil_perusahaan.dashboard') }}">
                        <i class="bi bi-grid-1x2"></i>
                        <span class="menu-text">Dashboard Perusahaan</span>
                    </a>
                </li>
                
                <li class="{{ request()->routeIs('magang.wakil_perusahaan.openings*') ? 'active' : '' }}">
                    <a href="{{ route('magang.wakil_perusahaan.openings.index') }}">
                        <i class="bi bi-briefcase"></i>
                        <span class="menu-text">Program Magang</span>
                    </a>
                </li>
                
                <li class="{{ request()->routeIs('magang.wakil_perusahaan.interns*') ? 'active' : '' }}">
                    <a href="{{ route('magang.wakil_perusahaan.interns') }}">
                        <i class="bi bi-people"></i>
                        <span class="menu-text">Siswa Magang</span>
                    </a>
                </li>
                
                <li class="{{ request()->routeIs('magang.wakil_perusahaan.reports*') ? 'active' : '' }}">
                    <a href="{{ route('magang.wakil_perusahaan.reports') }}">
                        <i class="bi bi-file-earmark-text"></i>
                        <span class="menu-text">Laporan Harian</span>
                    </a>
                </li>

                <li class="{{ request()->routeIs('magang.wakil_perusahaan.penilaian*') ? 'active' : '' }}">
                    <a href="{{ route('magang.wakil_perusahaan.penilaian.index') }}">
                        <i class="bi bi-clipboard-check"></i>
                        <span class="menu-text">Penilaian</span>
                    </a>
                </li>

            @elseif(Auth::check() && Auth::user()->role == 'super_admin')
                <!-- ================= SUPER ADMIN ================= -->
                
                <li class="{{ request()->routeIs('magang.magang.index') ? 'active' : '' }}">
                    <a href="{{ route('magang.magang.index') }}">
                        <i class="bi bi-list-check"></i>
                        <span class="menu-text">Kelola Magang</span>
                    </a>
                </li>
                
                <li class="{{ request()->routeIs('magang.perusahaan.*') ? 'active' : '' }}">
                    <a href="{{ route('magang.perusahaan.index') }}">
                        <i class="bi bi-building"></i>
                        <span class="menu-text">Kelola Perusahaan</span>
                    </a>
                </li>
                
                <li class="{{ request()->routeIs('admin.magang.wakil_perusahaan.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.magang.wakil_perusahaan.index') }}">
                        <i class="bi bi-person-badge"></i>
                        <span class="menu-text">Kelola Mitra</span>
                    </a>
                </li>

            @elseif(Auth::check() && Auth::user()->role == 'admin_magang')
                <!-- ================= ADMIN MAGANG ================= -->

                <li class="{{ request()->routeIs('magang.magang.index') ? 'active' : '' }}">
                    <a href="{{ route('magang.magang.index') }}">
                        <i class="bi bi-list-check"></i>
                        <span class="menu-text">Kelola Magang</span>
                    </a>
                </li>

                <!-- 🔥 MENU BARU: PEMBIMBING -->
                <li class="{{ request()->routeIs('admin.pembimbing.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.pembimbing.index') }}">
                        <i class="bi bi-person-check"></i>
                        <span class="menu-text">Manajemen Pembimbing</span>
                    </a>
                </li>

                <li class="{{ request()->routeIs('magang.perusahaan.*') ? 'active' : '' }}">
                    <a href="{{ route('magang.perusahaan.index') }}">
                        <i class="bi bi-building"></i>
                        <span class="menu-text">Kelola Perusahaan</span>
                    </a>
                </li>

                <li class="{{ request()->routeIs('admin.magang.wakil_perusahaan.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.magang.wakil_perusahaan.index') }}">
                        <i class="bi bi-person-badge"></i>
                        <span class="menu-text">Kelola Mitra</span>
                    </a>
                </li>

                <li class="{{ request()->routeIs('magang.wakil_perusahaan.nilaiakhir*') ? 'active' : '' }}">
                    <a href="{{ route('magang.wakil_perusahaan.nilaiakhir.index') }}">
                        <i class="bi bi-clipboard-check"></i>
                        <span class="menu-text">Penilaian</span>
                    </a>
                </li>

                <li class="{{ request()->routeIs('magang.wakil_perusahaan.reports*') ? 'active' : '' }}">
                    <a href="{{ route('magang.wakil_perusahaan.reports') }}">
                        <i class="bi bi-journal-text"></i>
                        <span class="menu-text">Laporan Harian</span>
                    </a>
                </li>

                <li class="{{ request()->routeIs('magang.admin.pengajuan_judul*') ? 'active' : '' }}">
                    <a href="{{ route('magang.admin.pengajuan_judul.index') }}">
                        <i class="bi bi-pencil-square"></i>
                        <span class="menu-text">Kelola Judul</span>
                    </a>
                </li>

            @elseif(Auth::check() && Auth::user()->role == 'siswa')
                <!-- ================= SISWA ================= -->

                @php
                    $magangSiswa = \App\Models\MagangSiswa::where('user_id', Auth::id())
                        ->where('status', 'Disetujui')
                        ->first();
                    $hasApprovedInternship = !is_null($magangSiswa);
                @endphp

                <li class="{{ request()->routeIs('magang.magang.index') ? 'active' : '' }}">
                    <a href="{{ route('magang.magang.index') }}">
                        <i class="bi bi-briefcase"></i>
                        <span class="menu-text">Program Magang</span>
                    </a>
                </li>

                @if($hasApprovedInternship)
                <li class="{{ request()->routeIs('magang.siswa.laporan*') ? 'active' : '' }}">
                    <a href="{{ route('magang.siswa.laporan.index') }}">
                        <i class="bi bi-journal-text"></i>
                        <span class="menu-text">Laporan Harian</span>
                    </a>
                </li>

                <li class="{{ request()->routeIs('magang.pengajuan_judul*') ? 'active' : '' }}">
                    <a href="{{ route('magang.pengajuan_judul.indexsiswa') }}">
                        <i class="bi bi-pencil"></i>
                        <span class="menu-text">Ajukan Judul</span>
                    </a>
                </li>
                @endif

            @else
                <!-- ================= USER UMUM ================= -->
                <li class="{{ request()->routeIs('magang.magang.create') ? 'active' : '' }}">
                    <a href="{{ route('magang.magang.create') }}">
                        <i class="bi bi-briefcase"></i>
                        <span class="menu-text">Daftar Magang</span>
                    </a>
                </li>
            @endif
            
            <!-- LOGOUT -->
            <li>
                <a href="javascript:void(0)" onclick="document.getElementById('logoutForm').submit();">
                    <i class="bi bi-box-arrow-right"></i>
                    <span class="menu-text">Logout</span>
                </a>
            </li>
        </ul>
    </div>
</div>