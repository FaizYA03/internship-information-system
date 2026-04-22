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
            <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}">
                    <i class="bi bi-house-door"></i>
                    <span class="menu-text">Beranda</span>
                </a>
            </li>
            <!-- DASHBOARD -->
            @if(!auth()->check() || auth()->user()->role !== 'wakil_perusahaan')
            <li class="{{ request()->routeIs('magang.dashboard') ? 'active' : '' }}">
                <a href="{{ route('magang.dashboard') }}">
                    <i class="bi bi-speedometer2"></i>
                    <span class="menu-text">Dashboard</span>
                </a>
            </li>
            @endif
            
            @auth

                {{-- ================= WAKIL PERUSAHAAN ================= --}}
                @if(Auth::user()->role === 'wakil_perusahaan')

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

                {{-- ================= SUPER ADMIN ================= --}}
                @elseif(Auth::user()->role === 'super_admin')

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

                {{-- ================= ADMIN MAGANG ================= --}}
                @elseif(Auth::user()->role === 'admin_magang')

                    <li class="{{ request()->routeIs('magang.magang.index') ? 'active' : '' }}">
                        <a href="{{ route('magang.magang.index') }}">
                            <i class="bi bi-list-check"></i>
                            <span class="menu-text">Kelola Magang</span>
                        </a>
                    </li>

                    <li class="{{ request()->routeIs('admin.pembimbing.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.pembimbing.index') }}">
                            <i class="bi bi-person-check"></i>
                            <span class="menu-text">Kelola Pembimbing</span>
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

                    
                {{-- ================= GURU ================= --}}
                @elseif(Auth::user()->role === 'guru')

                    <li class="{{ request()->routeIs('guru.siswa*') ? 'active' : '' }}">
                        <a href="{{ route('guru.siswa.index') }}">
                            <i class="bi bi-people"></i>
                            <span class="menu-text">Siswa Bimbingan</span>
                        </a>
                    </li>


                    <li class="{{ request()->routeIs('magang.wakil_perusahaan.nilaiakhir*') ? 'active' : '' }}">
                        <a href="{{ route('magang.wakil_perusahaan.nilaiakhir.index') }}">
                            <i class="bi bi-clipboard-check"></i>
                            <span class="menu-text">Penilaian</span>
                        </a>
                    </li>

                    <li class="{{ request()->routeIs('magang.admin.pengajuan_judul*') ? 'active' : '' }}">
                        <a href="{{ route('magang.admin.pengajuan_judul.index') }}">
                            <i class="bi bi-pencil-square"></i>
                            <span class="menu-text">Kelola Judul</span>
                        </a>
                    </li>


                {{-- ================= SISWA ================= --}}
                @elseif(Auth::user()->role === 'siswa')

                    @php
                        $magangSiswa = \App\Models\MagangSiswa::where('user_id', Auth::id())
                            ->where('status', 'Disetujui Admin')
                            ->first();
                    @endphp

                    <li class="{{ request()->routeIs('magang.magang.index') ? 'active' : '' }}">
                        <a href="{{ route('magang.magang.index') }}">
                            <i class="bi bi-briefcase"></i>
                            <span class="menu-text">Program Magang</span>
                        </a>
                    </li>

                    @if($magangSiswa)
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
                        <li class="{{ request()->routeIs('magang.siswa.nilaimagang.*') ? 'active' : '' }}">
                            <a href="{{ route('magang.siswa.nilai.index') }}" data-title="Nilai">
                                <i class="bi bi-graph-up"></i>
                                <span class="menu-text">Nilai Magang</span>
                            </a>
                        </li>
                    @endif

                @endif

            @else
                {{-- ================= GUEST ================= --}}
                <li class="{{ request()->routeIs('magang.magang.create') ? 'active' : '' }}">
                    <a href="{{ route('magang.magang.create') }}">
                        <i class="bi bi-briefcase"></i>
                        <span class="menu-text">Daftar Magang</span>
                    </a>
                </li>
            @endauth
            
            <!-- LOGOUT -->
            @auth
            <li>
                <a href="javascript:void(0)" onclick="document.getElementById('logoutForm').submit();">
                    <i class="bi bi-box-arrow-right"></i>
                    <span class="menu-text">Logout</span>
                </a>
            </li>
            @endauth
        </ul>
    </div>
</div>