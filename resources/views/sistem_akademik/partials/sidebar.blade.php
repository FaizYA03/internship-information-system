<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo SMK" height="32">
            <span>Sistem Akademik</span>
        </a>
    </div>

    <div class="menu-items">
        <ul>
            <li class="{{ request()->routeIs('sistem_akademik.dashboard') ? 'active' : '' }}">
                <a href="{{ route('sistem_akademik.dashboard') }}" data-title="Dashboard">
                    <i class="bi bi-speedometer2"></i>
                    <span class="menu-text">Dashboard</span>
                </a>
            </li>

            @if(Auth::user()->role == 'super_admin' || Auth::user()->role == 'admin_sa')
            <li class="{{ request()->routeIs('sistem_akademik.berita.*') ? 'active' : '' }}">
                <a href="{{ route('sistem_akademik.berita.index') }}" data-title="Kelola Berita">
                    <i class="bi bi-newspaper"></i>
                    <span class="menu-text">Kelola Berita</span>
                </a>
            </li>

            <li class="{{ request()->routeIs('sistem_akademik.guru.*') ? 'active' : '' }}">
                <a href="{{ route('sistem_akademik.guru.index') }}" data-title="Kelola Guru">
                    <i class="bi bi-person-workspace"></i>
                    <span class="menu-text">Kelola Guru</span>
                </a>
            </li>

            <li class="{{ request()->routeIs('sistem_akademik.siswa.*') ? 'active' : '' }}">
                <a href="{{ route('sistem_akademik.siswa.index') }}" data-title="Kelola Siswa">
                    <i class="bi bi-people"></i>
                    <span class="menu-text">Kelola Siswa</span>
                </a>
            </li>

            <li class="{{ request()->routeIs('sistem_akademik.kelas.*') ? 'active' : '' }}">
                <a href="{{ route('sistem_akademik.kelas.index') }}" data-title="Kelola Kelas">
                    <i class="bi bi-building"></i>
                    <span class="menu-text">Kelola Kelas</span>
                </a>
            </li>
            
            <li class="{{ request()->routeIs('sistem_akademik.jurusan.*') ? 'active' : '' }}">
                <a href="{{ route('sistem_akademik.jurusan.index') }}" data-title="Kelola Jurusan">
                    <i class="bi bi-diagram-3"></i>
                    <span class="menu-text">Kelola Jurusan</span>
                </a>
            </li>

            <li class="{{ request()->routeIs('sistem_akademik.mapels.*') ? 'active' : '' }}">
                <a href="{{ route('sistem_akademik.mapels.index') }}" data-title="Data Mata Pelajaran">
                    <i class="bi bi-book"></i>
                    <span class="menu-text">Data Mata Pelajaran</span>
                </a>
            </li>

            <li class="{{ request()->routeIs('sistem_akademik.ruangans.*') ? 'active' : '' }}">
                <a href="{{ route('sistem_akademik.ruangans.index') }}" data-title="Data Ruangan">
                    <i class="bi bi-door-open"></i>
                    <span class="menu-text">Data Ruangan</span>
                </a>
            </li>

            <li class="{{ request()->routeIs('sistem_akademik.mata_pelajaran.*') ? 'active' : '' }}">
                <a href="{{ route('sistem_akademik.mata_pelajaran.index') }}" data-title="Pengampu Mapel">
                    <i class="bi bi-person-video3"></i>
                    <span class="menu-text">Pengampu Mapel</span>
                </a>
            </li>

            <li class="{{ request()->routeIs('sistem_akademik.peminatan.*') ? 'active' : '' }}">
                <a href="{{ route('sistem_akademik.peminatan.index') }}" data-title="Kelola Peminatan">
                    <i class="bi bi-cash-coin"></i>
                    <span class="menu-text">Kelola Peminatan</span>
                </a>
            </li>

            <li class="{{ request()->routeIs('sistem_akademik.course.*') ? 'active' : '' }}">
                <a href="{{ route('sistem_akademik.course.index') }}" data-title="Kelola Course">
                    <i class="bi bi-journal-text"></i>
                    <span class="menu-text">Kelola Jadwal</span>
                </a>
            </li>
            @endif

            @if(Auth::user()->role == 'guru')
            <li class="{{ request()->routeIs('sistem_akademik.course.*') ? 'active' : '' }}">
                <a href="{{ route('sistem_akademik.course.index') }}" data-title="Course Saya">
                    <i class="bi bi-journal-text"></i>
                    <span class="menu-text">Course Saya</span>
                </a>
            </li>

            <li class="{{ request()->routeIs('sistem_akademik.mata_pelajaran.*') ? 'active' : '' }}">
                <a href="{{ route('sistem_akademik.mata_pelajaran.index') }}" data-title="Mata Pelajaran">
                    <i class="bi bi-book"></i>
                    <span class="menu-text">Mata Pelajaran</span>
                </a>
            </li>

            <li class="{{ request()->routeIs('sistem_akademik.peminatan.*') ? 'active' : '' }}">
                <a href="{{ route('sistem_akademik.peminatan.index') }}" data-title="Peminatan">
                    <i class="bi bi-journal-check"></i>
                    <span class="menu-text">Peminatan</span>
                </a>
            </li>
            @endif

            @if(Auth::user()->role == 'siswa')
            <li class="{{ request()->routeIs('sistem_akademik.course.*') ? 'active' : '' }}">
                <a href="{{ route('sistem_akademik.course.index') }}" data-title="Course Saya">
                    <i class="bi bi-journal-text"></i>
                    <span class="menu-text">Course Saya</span>
                </a>
            </li>

            <li class="{{ request()->routeIs('sistem_akademik.peminatan.*') ? 'active' : '' }}">
                <a href="{{ route('sistem_akademik.peminatan.index') }}" data-title="Peminatan Saya">
                    <i class="bi bi-cash-coin"></i>
                    <span class="menu-text">Peminatan Saya</span>
                </a>
            </li>
            @endif

            <li class="{{ request()->routeIs('sistem_akademik.profile') ? 'active' : '' }}">
                <a href="{{ route('sistem_akademik.profile') }}" data-title="Profil Saya">
                    <i class="bi bi-person-circle"></i>
                    <span class="menu-text">Profil Saya</span>
                </a>
            </li>
        </ul>
    </div>
</div>