    <!-- Navigation Bar -->
    <nav>
        <ul>
            <li><a href="{{ route('laboratorium.link') }}">Beranda Lab</a></li>
            <li><a href="{{ route('lab.index') }}">Profil</a></li>
            <li><a href="{{ route('lab.jadwal') }}">Jadwal</a></li>
            <li><a href="{{ route('inv.index') }}">Inventaris</a></li>
            <li><a href="{{ route('inv.laporan') }}">Lapor Rusak</a></li>
            @if(Auth::check())
                <li><a href="{{ route('lab.dashboard') }}">Dashboard Admin</a></li>
            @else
                <li><a href="{{ route('login') }}">Login</a></li>
            @endif
        </ul>
    </nav>
