@extends('lab.layouts.unified', ['title' => 'Dashboard Kepala Sekolah'])

@section('content')
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="ui-card bg-primary text-white border-0" style="background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%) !important;">
            <div class="ui-card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="fw-bold mb-2">Selamat Datang, Bapak Kepala Sekolah!</h2>
                        <p class="mb-0 opacity-75">Otoritas akhir untuk persetujuan peminjaman eksternal dan pengadaan fasilitas laboratorium SMK Negeri 5 Padang.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <x-ui.card :hover="true">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 rounded-4" style="background-color: #FEE2E2; color: #DC2626;">
                    <i class="bi bi-person-badge-fill fs-3"></i>
                </div>
                <div>
                    <p class="text-muted mb-0 small fw-medium">Approval Peminjaman Eksternal</p>
                    <h3 class="fw-bold mb-0 text-dark">{{ $stats['approval_eksternal'] }}</h3>
                </div>
            </div>
            <p class="small text-danger mt-2 mb-0"><i class="bi bi-clock"></i> Membutuhkan keputusan segera</p>
            <x-slot name="footer">
                <a href="{{ route('lab.kepala_sekolah.approval.eksternal') }}" class="ui-btn ui-btn-danger w-100 justify-content-center btn-sm">
                    Buka Menu Approval
                </a>
            </x-slot>
        </x-ui.card>
    </div>
    <div class="col-md-6">
        <x-ui.card :hover="true">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 rounded-4" style="background-color: #FEF3C7; color: #D97706;">
                    <i class="bi bi-box2-fill fs-3"></i>
                </div>
                <div>
                    <p class="text-muted mb-0 small fw-medium">Approval Pengadaan Alat</p>
                    <h3 class="fw-bold mb-0 text-dark">{{ $stats['approval_pengadaan'] }}</h3>
                </div>
            </div>
            <p class="small text-warning mt-2 mb-0"><i class="bi bi-cash-stack"></i> Estimasi anggaran tersedia</p>
            <x-slot name="footer">
                <a href="{{ route('lab.kepala_sekolah.approval.pengadaan.index') }}" class="ui-btn ui-btn-primary w-100 justify-content-center btn-sm" style="background-color: #F59E0B;">
                    Buka Menu Pengadaan
                </a>
            </x-slot>
        </x-ui.card>
    </div>
</div>

<x-ui.card title="Ringkasan Operasional Laboratorium">
    <div class="row text-center py-2">
        <div class="col-md-4">
            <p class="text-muted mb-2 small fw-medium">Status Operasional</p>
            <x-ui.badge variant="success">Aktif & Terkendali</x-ui.badge>
        </div>
        <div class="col-md-4 border-start border-end">
            <p class="text-muted mb-2 small fw-medium">Total Inventaris</p>
            <h5 class="fw-bold mb-0">{{ App\Models\Inventaris::count() }} Unit</h5>
        </div>
        <div class="col-md-4">
            <p class="text-muted mb-2 small fw-medium">Laporan Kerusakan</p>
            <h5 class="fw-bold text-danger mb-0">{{ App\Models\Lab\LaporanKerusakan::where('status_perbaikan', 'pending')->count() }} Item</h5>
        </div>
    </div>
</x-ui.card>
@endsection

