<aside class="sa-sidebar">
    <div class="sa-sidebar-header">
        <a href="{{ route('admin.manage.index') }}" class="sa-logo">
            <div class="sa-logo-img">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" width="30">
            </div>
            <span class="sa-logo-text">Super Admin</span>
        </a>
    </div>
    
    <div class="sa-sidebar-body">
        <ul class="sa-nav">
            <li class="sa-nav-item">
                <a href="{{ route('admin.manage.index') }}" class="sa-nav-link {{ request()->routeIs('admin.manage.index') ? 'active' : '' }}">
                    <span class="sa-nav-icon"><i class="bi bi-grid-1x2"></i></span>
                    <span class="sa-nav-text">Dashboard</span>
                </a>
            </li>
            <li class="sa-nav-item">
                <a href="{{ route('admin.manage.users') }}" class="sa-nav-link {{ request()->routeIs('admin.manage.users*') ? 'active' : '' }}">
                    <span class="sa-nav-icon"><i class="bi bi-people"></i></span>
                    <span class="sa-nav-text">User Management</span>
                </a>
            </li>
            
            <span class="sa-nav-title">System Modules</span>
            
            <li class="sa-nav-item">
                <a href="{{ route('ppdb.index') }}" class="sa-nav-link {{ request()->routeIs('ppdb.*') ? 'active' : '' }}">
                    <span class="sa-nav-icon"><i class="bi bi-journal-text"></i></span>
                    <span class="sa-nav-text">PPDB</span>
                </a>
            </li>
            
            <li class="sa-nav-item">
                <a href="{{ route('sistem_akademik.dashboard') }}" class="sa-nav-link {{ request()->routeIs('sistem_akademik.*') ? 'active' : '' }}">
                    <span class="sa-nav-icon"><i class="bi bi-mortarboard"></i></span>
                    <span class="sa-nav-text">Academic System</span>
                </a>
            </li>
            
            <li class="sa-nav-item">
                <a href="{{ route('perpustakaan.buku.index') }}" class="sa-nav-link {{ request()->routeIs('perpustakaan.*') ? 'active' : '' }}">
                    <span class="sa-nav-icon"><i class="bi bi-book"></i></span>
                    <span class="sa-nav-text">Library</span>
                </a>
            </li>
            
            <li class="sa-nav-item">
                <a href="{{ route('lab.dashboard') }}" class="sa-nav-link {{ request()->routeIs('lab.*') ? 'active' : '' }}">
                    <span class="sa-nav-icon"><i class="bi bi-cpu"></i></span>
                    <span class="sa-nav-text">Laboratory</span>
                </a>
            </li>
            
            <li class="sa-nav-item">
                <a href="{{ route('magang.dashboard') }}" class="sa-nav-link {{ request()->routeIs('magang.*') ? 'active' : '' }}">
                    <span class="sa-nav-icon"><i class="bi bi-briefcase"></i></span>
                    <span class="sa-nav-text">Internship</span>
                </a>
            </li>
            
            <span class="sa-nav-title">keluar</span>
            
            <li class="sa-nav-item">
                <a href="javascript:void(0)" onclick="SuperAdmin.logout()" class="sa-nav-link">
                    <span class="sa-nav-icon"><i class="bi bi-box-arrow-right"></i></span>
                    <span class="sa-nav-text">Log Out</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
