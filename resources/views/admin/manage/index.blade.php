@extends('admin.layouts.superadmin')

@section('title', $title . ' - Super Admin Dashboard')

@section('page-title', 'Super Admin Dashboard')

@section('content')
<div class="sa-page-header">
    <h1 class="sa-page-header-title">Welcome to Super Admin Dashboard</h1>
    <p class="sa-page-header-subtitle">Manage your entire school management system from one place</p>
</div>

<!-- Stats Overview Section -->
<div class="sa-row sa-mb-4">
    <div class="sa-col sa-col-md-6 sa-col-lg-3 sa-mb-4">
        <div class="sa-stats-card primary">
            <div class="sa-stats-icon">
                <i class="bi bi-people"></i>
            </div>
            <div class="sa-stats-content">
                <h2 class="sa-stats-value">{{ \App\Models\User::count() }}</h2>
                <p class="sa-stats-label">Total Users</p>
            </div>
        </div>
    </div>

    <div class="sa-col sa-col-md-6 sa-col-lg-3 sa-mb-4">
        <div class="sa-stats-card success">
            <div class="sa-stats-icon">
                <i class="bi bi-person-vcard"></i>
            </div>
            <div class="sa-stats-content">
                <h2 class="sa-stats-value">{{ \App\Models\User::whereIn('role', ['super_admin', 'admin_ppdb', 'admin_sa', 'admin_perpus', 'admin_lab', 'admin_magang'])->count() }}</h2>
                <p class="sa-stats-label">Administrators</p>
            </div>
        </div>
    </div>

    <div class="sa-col sa-col-md-6 sa-col-lg-3 sa-mb-4">
        <div class="sa-stats-card warning">
            <div class="sa-stats-icon">
                <i class="bi bi-mortarboard"></i>
            </div>
            <div class="sa-stats-content">
                <h2 class="sa-stats-value">{{ \App\Models\User::where('role', 'guru')->count() }}</h2>
                <p class="sa-stats-label">Teachers</p>
            </div>
        </div>
    </div>

    <div class="sa-col sa-col-md-6 sa-col-lg-3 sa-mb-4">
        <div class="sa-stats-card danger">
            <div class="sa-stats-icon">
                <i class="bi bi-backpack"></i>
            </div>
            <div class="sa-stats-content">
                <h2 class="sa-stats-value">{{ \App\Models\User::where('role', 'siswa')->count() }}</h2>
                <p class="sa-stats-label">Students</p>
            </div>
        </div>
    </div>
</div>

<!-- Quick Access Section -->
<div class="sa-card sa-mb-4">
    <div class="sa-card-header">
        <h5 class="sa-card-header-title">Quick Actions</h5>
    </div>
    <div class="sa-card-body">
        <div class="sa-row">
            <div class="sa-col sa-col-md-3 sa-col-sm-6 sa-mb-3">
                <a href="{{ route('admin.manage.users.create') }}" class="sa-btn sa-btn-primary w-100">
                    <i class="bi bi-person-plus"></i> Add New User
                </a>
            </div>
            <div class="sa-col sa-col-md-3 sa-col-sm-6 sa-mb-3">
                <a href="{{ route('admin.manage.users') }}" class="sa-btn sa-btn-secondary w-100">
                    <i class="bi bi-people"></i> Manage Users
                </a>
            </div>
            <div class="sa-col sa-col-md-3 sa-col-sm-6 sa-mb-3">
                <a href="#" class="sa-btn sa-btn-secondary w-100">
                    <i class="bi bi-file-text"></i> View Logs
                </a>
            </div>
            <div class="sa-col sa-col-md-3 sa-col-sm-6 sa-mb-3">
                <a href="#" class="sa-btn sa-btn-secondary w-100">
                    <i class="bi bi-gear"></i> System Settings
                </a>
            </div>
        </div>
    </div>
</div>

<!-- System Modules Section -->
<h4 class="sa-mb-3">System Modules</h4>
<div class="sa-row">
    <div class="sa-col sa-col-lg-4 sa-col-md-6 sa-mb-4">
        <div class="sa-module-card">
            <div class="sa-module-header">
                <h5 class="sa-module-title">PPDB</h5>
                <span class="sa-module-badge">
                    <i class="bi bi-check-circle-fill"></i> Active
                </span>
            </div>
            <p class="sa-module-description">Student admission and registration system</p>
            <a href="{{ route('ppdb.index') }}" class="sa-module-action">
                <i class="bi bi-arrow-right"></i> Access Module
            </a>
        </div>
    </div>

    <div class="sa-col sa-col-lg-4 sa-col-md-6 sa-mb-4">
        <div class="sa-module-card">
            <div class="sa-module-header">
                <h5 class="sa-module-title">Academic System</h5>
                <span class="sa-module-badge">
                    <i class="bi bi-check-circle-fill"></i> Active
                </span>
            </div>
            <p class="sa-module-description">Comprehensive school academic management</p>
            <a href="{{ route('sistem_akademik.dashboard') }}" class="sa-module-action">
                <i class="bi bi-arrow-right"></i> Access Module
            </a>
        </div>
    </div>

    <div class="sa-col sa-col-lg-4 sa-col-md-6 sa-mb-4">
        <div class="sa-module-card">
            <div class="sa-module-header">
                <h5 class="sa-module-title">Library</h5>
                <span class="sa-module-badge">
                    <i class="bi bi-check-circle-fill"></i> Active
                </span>
            </div>
            <p class="sa-module-description">School library and book management system</p>
            <a href="{{ route('perpustakaan.buku.index') }}" class="sa-module-action">
                <i class="bi bi-arrow-right"></i> Access Module
            </a>
        </div>
    </div>

    <div class="sa-col sa-col-lg-4 sa-col-md-6 sa-mb-4">
        <div class="sa-module-card">
            <div class="sa-module-header">
                <h5 class="sa-module-title">Laboratory</h5>
                <span class="sa-module-badge">
                    <i class="bi bi-check-circle-fill"></i> Active
                </span>
            </div>
            <p class="sa-module-description">Laboratory equipment and facility management</p>
            <a href="{{ route('lab.dashboard') }}" class="sa-module-action">
                <i class="bi bi-arrow-right"></i> Access Module
            </a>
        </div>
    </div>

    <div class="sa-col sa-col-lg-4 sa-col-md-6 sa-mb-4">
        <div class="sa-module-card">
            <div class="sa-module-header">
                <h5 class="sa-module-title">Internship</h5>
                <span class="sa-module-badge">
                    <i class="bi bi-check-circle-fill"></i> Active
                </span>
            </div>
            <p class="sa-module-description">Student internship and industry placement management</p>
            <a href="{{ route('magang.dashboard') }}" class="sa-module-action">
                <i class="bi bi-arrow-right"></i> Access Module
            </a>
        </div>
    </div>
</div>
@endsection