@extends('lab.layouts.unified', ['title' => 'Dashboard Siswa'])

@section('content')
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="ui-card bg-primary text-white border-0" style="background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%) !important;">
            <div class="ui-card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="fw-bold mb-2">Selamat Datang, {{ auth()->user()->nama }}!</h2>
                        <p class="mb-0 opacity-75">Akses layanan laboratorium SMK Negeri 5 Padang. Cek jadwal praktikum dan status inventaris kamu di sini.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <x-ui.card :hover="true">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 rounded-4 bg-primary-soft text-primary">
                    <i class="bi bi-building-fill fs-3"></i>
                </div>
                <div>
                    <p class="text-muted mb-0 small fw-medium">Total Laboratorium</p>
                    <h3 class="fw-bold mb-0 text-dark">3</h3>
                </div>
            </div>
        </x-ui.card>
    </div>
    <div class="col-md-4">
        <x-ui.card :hover="true">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 rounded-4" style="background-color: #DCFCE7; color: #16A34A;">
                    <i class="bi bi-calendar-check-fill fs-3"></i>
                </div>
                <div>
                    <p class="text-muted mb-0 small fw-medium">Jadwal Hari Ini</p>
                    <h3 class="fw-bold mb-0 text-dark">{{ App\Models\Laboratorium::whereDate('start', now())->count() }}</h3>
                </div>
            </div>
        </x-ui.card>
    </div>
    <div class="col-md-4">
        <x-ui.card :hover="true">
            <div class="d-flex align-items-center gap-3">
                <div class="p-3 rounded-4" style="background-color: #E0F2FE; color: #0369A1;">
                    <i class="bi bi-box-seam-fill fs-3"></i>
                </div>
                <div>
                    <p class="text-muted mb-0 small fw-medium">Total Inventaris</p>
                    <h3 class="fw-bold mb-0 text-dark">{{ App\Models\Inventaris::count() }}</h3>
                </div>
            </div>
        </x-ui.card>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <x-ui.card title="Pengumuman Terbaru" :hover="false">
            <div class="announcement-list">
                <div class="p-3 border-bottom d-flex gap-3 align-items-start">
                    <div class="p-2 rounded-3 bg-primary-soft text-primary">
                        <i class="bi bi-megaphone-fill"></i>
                    </div>
                    <div>
                        <span class="text-muted small">Hari ini, 09:30</span>
                        <h6 class="fw-bold text-dark mt-1">Jadwal Praktikum Diperbaharui</h6>
                        <p class="mb-0 small text-muted">Kelas XI RPL 2 jadwal praktikum pemrograman web dipindahkan ke hari Jumat di Lab RPL 1.</p>
                    </div>
                </div>
                <div class="p-3 border-bottom d-flex gap-3 align-items-start">
                    <div class="p-2 rounded-3 bg-success-soft text-success">
                        <i class="bi bi-info-circle-fill"></i>
                    </div>
                    <div>
                        <span class="text-muted small">Kemarin, 14:30</span>
                        <h6 class="fw-bold text-dark mt-1">Penambahan Inventaris Baru</h6>
                        <p class="mb-0 small text-muted">5 unit komputer baru spesifikasi tinggi telah ditambahkan ke Laboratorium RPL untuk menunjang praktikum.</p>
                    </div>
                </div>
                <div class="p-3 d-flex gap-3 align-items-start">
                    <div class="p-2 rounded-3 bg-danger-soft text-danger">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <div>
                        <span class="text-muted small">3 Mei 2025, 10:15</span>
                        <h6 class="fw-bold text-dark mt-1">Pemeliharaan Lab TKJ</h6>
                        <p class="mb-0 small text-muted">Lab TKJ akan ditutup pada hari Sabtu untuk pemeliharaan rutin jaringan wifi dan server.</p>
                    </div>
                </div>
            </div>
            <x-slot name="footer">
                <div class="text-center">
                    <a href="#" class="text-primary fw-semibold text-decoration-none small">Lihat Semua Pengumuman</a>
                </div>
            </x-slot>
        </x-ui.card>
    </div>
    
    <div class="col-lg-5">
        <x-ui.card title="Jadwal Hari Ini" :hover="false">
            <div class="schedule-list">
                <div class="p-3 border rounded-4 mb-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold text-dark mb-1">Lab RPL</h6>
                        <p class="mb-0 small text-muted">Pemrograman Web - XI RPL 2</p>
                    </div>
                    <x-ui.badge variant="success">08:00 - 10:30</x-ui.badge>
                </div>
                <div class="p-3 border rounded-4 mb-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold text-dark mb-1">Lab Multimedia</h6>
                        <p class="mb-0 small text-muted">Desain Grafis - X MM 1</p>
                    </div>
                    <x-ui.badge variant="info">10:30 - 12:00</x-ui.badge>
                </div>
                <div class="p-3 border rounded-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold text-dark mb-1">Lab TKJ</h6>
                        <p class="mb-0 small text-muted">Jaringan Komputer - XII TKJ 1</p>
                    </div>
                    <x-ui.badge variant="warning">13:00 - 15:30</x-ui.badge>
                </div>
            </div>
            <x-slot name="footer">
                <a href="{{ route('siswa.jadwal.index') }}" class="ui-btn ui-btn-primary w-100 justify-content-center">
                    Lihat Selengkapnya
                </a>
            </x-slot>
        </x-ui.card>
    </div>
</div>
@endsection
