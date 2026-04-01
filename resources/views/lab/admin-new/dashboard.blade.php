@extends('lab.layouts.unified', ['title' => 'Admin Dashboard'])

@section('content')
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="ui-card bg-primary text-white border-0" style="background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%) !important;">
            <div class="ui-card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="fw-bold mb-2">Selamat Datang, Admin!</h2>
                        <p class="mb-0 opacity-75">Kelola operasional harian laboratorium SMK Negeri 5 Padang dengan mudah dan profesional.</p>
                    </div>
                    <div class="col-md-4 text-end d-none d-md-block">
                        <i class="bi bi-shield-check-fill opacity-25" style="font-size: 5rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-5">
    <!-- Stat Cards -->
    <div class="col-md-3">
        <x-ui.card :hover="true" class="h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 rounded-4 bg-primary-soft text-primary">
                    <i class="bi bi-door-open-fill fs-3"></i>
                </div>
                <div>
                    <p class="text-muted mb-0 small fw-medium">Laboratorium</p>
                    <h3 class="fw-bold mb-0 text-dark">{{ $stats['total_laboratorium'] }}</h3>
                </div>
            </div>
        </x-ui.card>
    </div>
    <div class="col-md-3">
        <x-ui.card :hover="true" class="h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 rounded-4 bg-warning-soft text-warning" style="background-color: #FEF3C7; color: #D97706;">
                    <i class="bi bi-hourglass-split fs-3"></i>
                </div>
                <div>
                    <p class="text-muted mb-0 small fw-medium">Pinjam Alat Pending</p>
                    <h3 class="fw-bold mb-0 text-dark">{{ $stats['pinjam_pending'] }}</h3>
                </div>
            </div>
        </x-ui.card>
    </div>
    <div class="col-md-3">
        <x-ui.card :hover="true" class="h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 rounded-4 bg-danger-soft text-danger" style="background-color: #FEE2E2; color: #DC2626;">
                    <i class="bi bi-exclamation-triangle-fill fs-3"></i>
                </div>
                <div>
                    <p class="text-muted mb-0 small fw-medium">Inventaris Rusak</p>
                    <h3 class="fw-bold mb-0 text-dark">{{ $stats['barang_rusak'] }}</h3>
                </div>
            </div>
        </x-ui.card>
    </div>
    <div class="col-md-3">
        <x-ui.card :hover="true" class="h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 rounded-4 bg-success-soft text-success" style="background-color: #DCFCE7; color: #16A34A;">
                    <i class="bi bi-check-circle-fill fs-3"></i>
                </div>
                <div>
                    <p class="text-muted mb-0 small fw-medium">Alat Tersedia</p>
                    <h3 class="fw-bold mb-0 text-dark">{{ $stats['alat_tersedia'] }}</h3>
                </div>
            </div>
        </x-ui.card>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-8">
        <x-ui.card title="Aksi Cepat Menu">
            <div class="row g-3">
                <div class="col-md-4">
                    <a href="{{ route('lab.admin_new.peminjaman.internal.index') }}" class="text-decoration-none">
                        <div class="p-4 rounded-4 border text-center hover-lift transition-all">
                            <i class="bi bi-clipboard-check-fill text-primary fs-1 mb-2 d-block"></i>
                            <span class="fw-bold text-dark d-block">Peminjaman</span>
                            <small class="text-muted">Kelola Pinjaman</small>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('lab.admin_new.laboratorium.index') }}" class="text-decoration-none">
                        <div class="p-4 rounded-4 border text-center hover-lift transition-all">
                            <i class="bi bi-building-fill text-info fs-1 mb-2 d-block"></i>
                            <span class="fw-bold text-dark d-block">Laboratorium</span>
                            <small class="text-muted">Detail Ruangan</small>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('lab.admin_new.kerusakan.index') }}" class="text-decoration-none">
                        <div class="p-4 rounded-4 border text-center hover-lift transition-all">
                            <i class="bi bi-wrench-adjustable-circle-fill text-danger fs-1 mb-2 d-block"></i>
                            <span class="fw-bold text-dark d-block">Kerusakan</span>
                            <small class="text-muted">Monitor Laporan</small>
                        </div>
                    </a>
                </div>
            </div>
        </x-ui.card>
    </div>
    <div class="col-md-4">
        <x-ui.card title="Info Lainnya">
            <ul class="list-group list-group-flush">
                <li class="list-group-item px-0 border-0 d-flex justify-content-between align-items-center">
                    <span class="text-muted"><i class="bi bi-box-seam me-2"></i> Total Inventaris</span>
                    <span class="fw-bold">{{ $stats['total_barang'] }}</span>
                </li>
                <li class="list-group-item px-0 border-0 d-flex justify-content-between align-items-center">
                    <span class="text-muted"><i class="bi bi-building me-2"></i> Pinjaman Ruangan</span>
                    <x-ui.badge variant="warning">{{ $stats['pinjam_ruangan_pending'] }}</x-ui.badge>
                </li>
                <li class="list-group-item px-0 border-0 d-flex justify-content-between align-items-center">
                    <span class="text-muted"><i class="bi bi-tools me-2"></i> Kerusakan Aktif</span>
                    <x-ui.badge variant="danger">{{ $stats['kerusakan_aktif'] }}</x-ui.badge>
                </li>
            </ul>
            <div class="mt-4">
                <a href="{{ route('lab.admin_new.master_data.index') }}" class="ui-btn ui-btn-secondary w-100 justify-content-center">
                    <i class="bi bi-gear-fill me-2"></i> Pengaturan Data Statis
                </a>
            </div>
        </x-ui.card>
    </div>
</div>

<div class="row g-4">
    <div class="col-12">
        <h5 class="fw-bold mb-3">Input Peminjaman Manual</h5>
        <div class="row g-3">
            <div class="col-md-4">
                <a href="{{ route('lab.admin_new.manual_input.alat_siswa') }}" class="ui-btn ui-btn-primary w-100 justify-content-center py-3">
                    <i class="bi bi-person-fill-add"></i> Pinjam Alat Siswa
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('lab.admin_new.manual_input.alat_guru') }}" class="ui-btn ui-btn-secondary w-100 justify-content-center py-3">
                    <i class="bi bi-person-badge-fill"></i> Pinjam Alat Guru
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('lab.admin_new.manual_input.ruangan_guru') }}" class="ui-btn ui-btn-secondary w-100 justify-content-center py-3" style="background-color: #FEF3C7; color: #D97706;">
                    <i class="bi bi-door-open-fill"></i> Pinjam Ruangan
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    .transition-all { transition: all 0.3s; }
    .hover-lift:hover { transform: translateY(-5px); box-shadow: var(--shadow-md); border-color: var(--primary); }
</style>
@endsection

