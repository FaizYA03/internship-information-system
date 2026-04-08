@extends('lab.layouts.unified', ['title' => 'Admin Dashboard'])

@section('content')
<div class="row mb-4">
    <!-- Hero Section -->
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #2563eb, #1e40af); color: white;">
            <div class="card-body p-4 p-md-5 position-relative overflow-hidden">
                <div class="row align-items-center position-relative z-1">
                    <div class="col-md-8">
                        <h2 class="fw-bold mb-3 fs-1 tracking-tight">Selamat Datang, Admin!</h2>
                        <p class="mb-0 fs-6" style="color: #bfdbfe; max-width: 600px; line-height: 1.6;">
                            Kelola operasional harian laboratorium SMK Negeri 5 Padang dengan mudah dan profesional melalui akses terpusat.
                        </p>
                    </div>
                </div>
                <!-- Background decoration icon -->
                <i class="bi bi-shield-check position-absolute" style="font-size: 10rem; opacity: 0.1; right: -20px; top: -30px;"></i>
            </div>
        </div>
    </div>
</div>

<!-- Stats Grid -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card h-100 border-0 shadow-sm hover-elevate">
            <div class="card-body p-4 d-flex align-items-center gap-3">
                <div class="p-3 rounded-3" style="background: #eff6ff; color: #2563eb;">
                    <i class="bi bi-door-open-fill fs-3"></i>
                </div>
                <div>
                    <p class="text-muted small mb-1 fw-medium text-uppercase tracking-wider">Laboratorium</p>
                    <h3 class="fw-bold mb-0 text-dark">{{ $stats['total_laboratorium'] }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card h-100 border-0 shadow-sm hover-elevate">
            <div class="card-body p-4 d-flex align-items-center gap-3">
                <div class="p-3 rounded-3" style="background: #fffbeb; color: #d97706;">
                    <i class="bi bi-hourglass-split fs-3"></i>
                </div>
                <div>
                    <p class="text-muted small mb-1 fw-medium text-uppercase tracking-wider">Pinjam Pending</p>
                    <h3 class="fw-bold mb-0 text-dark">{{ $stats['pinjam_pending'] }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card h-100 border-0 shadow-sm hover-elevate">
            <div class="card-body p-4 d-flex align-items-center gap-3">
                <div class="p-3 rounded-3" style="background: #fef2f2; color: #dc2626;">
                    <i class="bi bi-exclamation-triangle-fill fs-3"></i>
                </div>
                <div>
                    <p class="text-muted small mb-1 fw-medium text-uppercase tracking-wider">Inventaris Rusak</p>
                    <h3 class="fw-bold mb-0 text-dark">{{ $stats['barang_rusak'] }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card h-100 border-0 shadow-sm hover-elevate">
            <div class="card-body p-4 d-flex align-items-center gap-3">
                <div class="p-3 rounded-3" style="background: #ecfdf5; color: #059669;">
                    <i class="bi bi-check-circle-fill fs-3"></i>
                </div>
                <div>
                    <p class="text-muted small mb-1 fw-medium text-uppercase tracking-wider">Alat Tersedia</p>
                    <h3 class="fw-bold mb-0 text-dark">{{ $stats['alat_tersedia'] }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions and Info -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                <h5 class="fw-bold text-dark mb-0">Aksi Cepat Menu</h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <!-- Laboratorium -->
                    <div class="col-6 col-md-3">
                        <a href="{{ route('lab.admin_new.laboratorium.index') }}" class="text-decoration-none h-100 d-block p-3 border rounded-3 text-center transition-all quick-action-card">
                            <div class="mx-auto flex items-center justify-center rounded-circle d-flex align-items-center justify-content-center mb-3 quick-action-icon" style="width: 48px; height: 48px; background: #f0f9ff; color: #0284c7;">
                                <i class="bi bi-building fs-5"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-1">Laboratorium</h6>
                            <small class="text-muted d-block">Ruangan</small>
                        </a>
                    </div>
                    <!-- Jadwal -->
                    <div class="col-6 col-md-3">
                        <a href="{{ route('lab.admin_new.jadwal.index') }}" class="text-decoration-none h-100 d-block p-3 border rounded-3 text-center transition-all quick-action-card">
                            <div class="mx-auto flex items-center justify-center rounded-circle d-flex align-items-center justify-content-center mb-3 quick-action-icon" style="width: 48px; height: 48px; background: #f5f3ff; color: #7c3aed;">
                                <i class="bi bi-calendar-event fs-5"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-1">Jadwal</h6>
                            <small class="text-muted d-block">Praktikum</small>
                        </a>
                    </div>
                    <!-- Inventaris -->
                    <div class="col-6 col-md-3">
                        <a href="{{ route('lab.admin_new.inventaris.index') }}" class="text-decoration-none h-100 d-block p-3 border rounded-3 text-center transition-all quick-action-card">
                            <div class="mx-auto flex items-center justify-center rounded-circle d-flex align-items-center justify-content-center mb-3 quick-action-icon" style="width: 48px; height: 48px; background: #ecfdf5; color: #059669;">
                                <i class="bi bi-box-seam fs-5"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-1">Inventaris</h6>
                            <small class="text-muted d-block">Alat & Bahan</small>
                        </a>
                    </div>
                    <!-- Peminjaman -->
                    <div class="col-6 col-md-3">
                        <a href="{{ route('lab.admin_new.peminjaman.internal.index') }}" class="text-decoration-none h-100 d-block p-3 border rounded-3 text-center transition-all quick-action-card">
                            <div class="mx-auto flex items-center justify-center rounded-circle d-flex align-items-center justify-content-center mb-3 quick-action-icon" style="width: 48px; height: 48px; background: #eff6ff; color: #2563eb;">
                                <i class="bi bi-clipboard-check fs-5"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-1">Peminjaman</h6>
                            <small class="text-muted d-block">Kelola Pinjaman</small>
                        </a>
                    </div>
                    <!-- Kategori -->
                    <div class="col-6 col-md-3">
                        <a href="{{ route('lab.admin_new.inventaris.kategori.index') }}" class="text-decoration-none h-100 d-block p-3 border rounded-3 text-center transition-all quick-action-card">
                            <div class="mx-auto flex items-center justify-center rounded-circle d-flex align-items-center justify-content-center mb-3 quick-action-icon" style="width: 48px; height: 48px; background: #fffbeb; color: #d97706;">
                                <i class="bi bi-tags fs-5"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-1">Kategori</h6>
                            <small class="text-muted d-block">Struktur Tipe</small>
                        </a>
                    </div>
                    <!-- Jenis Lab -->
                    <div class="col-6 col-md-3">
                        <a href="{{ route('lab.admin_new.jenis_lab.index') }}" class="text-decoration-none h-100 d-block p-3 border rounded-3 text-center transition-all quick-action-card">
                            <div class="mx-auto flex items-center justify-center rounded-circle d-flex align-items-center justify-content-center mb-3 quick-action-icon" style="width: 48px; height: 48px; background: #f8fafc; color: #475569;">
                                <i class="bi bi-grid-3x3-gap fs-5"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-1">Jenis Lab</h6>
                            <small class="text-muted d-block">Klasifikasi</small>
                        </a>
                    </div>
                    <!-- Kerusakan -->
                    <div class="col-6 col-md-3">
                        <a href="{{ route('lab.admin_new.kerusakan.index') }}" class="text-decoration-none h-100 d-block p-3 border rounded-3 text-center transition-all quick-action-card">
                            <div class="mx-auto flex items-center justify-center rounded-circle d-flex align-items-center justify-content-center mb-3 quick-action-icon" style="width: 48px; height: 48px; background: #fef2f2; color: #dc2626;">
                                <i class="bi bi-exclamation-triangle fs-5"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-1">Kerusakan</h6>
                            <small class="text-muted d-block">Monitor Laporan</small>
                        </a>
                    </div>
                    <!-- Perbaikan Selesai -->
                    <div class="col-6 col-md-3">
                        <a href="{{ route('lab.admin_new.kerusakan.selesai') }}" class="text-decoration-none h-100 d-block p-3 border rounded-3 text-center transition-all quick-action-card">
                            <div class="mx-auto flex items-center justify-center rounded-circle d-flex align-items-center justify-content-center mb-3 quick-action-icon" style="width: 48px; height: 48px; background: #f0fdf4; color: #16a34a;">
                                <i class="bi bi-patch-check fs-5"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-1">Selesai</h6>
                            <small class="text-muted d-block">Riwayat Perbaikan</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Lainnya -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100 d-flex flex-column">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                <h5 class="fw-bold text-dark mb-0">Info Lainnya</h5>
            </div>
            <div class="card-body p-4 flex-grow-1">
                <div class="d-flex flex-column gap-4">
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-3">
                        <div class="d-flex align-items-center text-muted">
                            <i class="bi bi-box me-2"></i>
                            <span class="fs-6">Total Inventaris</span>
                        </div>
                        <span class="fw-bold fs-5 text-dark">{{ $stats['total_barang'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-3">
                        <div class="d-flex align-items-center text-muted">
                            <i class="bi bi-door-closed me-2"></i>
                            <span class="fs-6">Pinjaman Ruangan</span>
                        </div>
                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fs-7">
                            {{ $stats['pinjam_ruangan_pending'] }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-3">
                        <div class="d-flex align-items-center text-muted">
                            <i class="bi bi-exclamation-circle me-2"></i>
                            <span class="fs-6">Kerusakan Aktif</span>
                        </div>
                        <span class="badge bg-danger px-3 py-2 rounded-pill fs-7">
                            {{ $stats['kerusakan_aktif'] }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white border-top-0 p-4 pt-0">
                <a href="{{ route('lab.admin_new.master_data.index') }}" class="btn btn-light w-100 py-2 fw-medium border text-secondary" style="border-radius: 8px;">
                    <i class="bi bi-gear me-2"></i> Pengaturan Data Statis
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Manual Input Forms -->
<div class="row mb-4">
    <div class="col-12">
        <h4 class="fw-bold mb-4 text-dark fs-5">Input Peminjaman Manual</h4>
        <div class="row g-3">
            <div class="col-sm-6 col-md-4">
                <a href="{{ route('lab.admin_new.manual_input.alat_siswa') }}" class="btn btn-primary d-flex align-items-center justify-content-center p-3 w-100 shadow-sm" style="border-radius: 10px;">
                    <i class="bi bi-person-plus fs-4 me-3"></i>
                    <span class="fw-semibold fs-6">Pinjam Alat Siswa</span>
                </a>
            </div>
            <div class="col-sm-6 col-md-4">
                <a href="{{ route('lab.admin_new.manual_input.alat_guru') }}" class="btn btn-outline-secondary bg-white text-dark w-100 d-flex align-items-center justify-content-center p-3 shadow-sm" style="border-radius: 10px; border-color: #dee2e6;">
                    <i class="bi bi-person-badge fs-4 me-3 text-secondary"></i>
                    <span class="fw-semibold fs-6">Pinjam Alat Guru</span>
                </a>
            </div>
            <div class="col-sm-6 col-md-4">
                <a href="{{ route('lab.admin_new.manual_input.ruangan_guru') }}" class="btn text-warning-emphasis bg-warning-subtle border border-warning-subtle w-100 d-flex align-items-center justify-content-center p-3 shadow-sm hover-warning" style="border-radius: 10px;">
                    <i class="bi bi-door-open fs-4 me-3"></i>
                    <span class="fw-semibold fs-6">Pinjam Ruangan</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    .hover-elevate {
        transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .hover-elevate:hover {
        transform: translateY(-5px);
    }
    .quick-action-card:hover {
        border-color: #2563eb !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    .quick-action-card:hover .quick-action-icon {
        transform: scale(1.1);
        transition: transform 0.2s ease-in-out;
    }
    .hover-warning:hover {
        background-color: #ffc107 !important;
        color: #000 !important;
        border-color: #ffc107 !important;
    }
</style>
@endsection
