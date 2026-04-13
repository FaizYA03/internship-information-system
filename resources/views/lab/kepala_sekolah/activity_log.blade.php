@extends('lab.layouts.unified', ['title' => 'Log Aktivitas Sistem Lab'])

@section('content')

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('lab.kepala_sekolah.dashboard') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
        <i class="bi bi-arrow-left me-1"></i> Dashboard
    </a>
    <div>
        <h5 class="fw-bold mb-0">Log Aktivitas Sistem</h5>
        <p class="small text-muted mb-0">Aktivitas penting yang tercatat dalam sistem laboratorium.</p>
    </div>
</div>

{{-- Tabs Filter Role --}}
<div class="mb-3 overflow-auto">
    <ul class="nav nav-pills flex-nowrap g-2">
        @php $currentRole = request('role', 'semua'); @endphp
        <li class="nav-item me-2">
            <a class="nav-link small fw-medium px-4 text-nowrap {{ $currentRole === 'semua' ? 'active rounded-pill shadow-sm' : 'text-dark rounded-pill bg-white border border-light shadow-sm' }}" 
               href="{{ route('lab.kepala_sekolah.activity_log', array_merge(request()->except(['role', 'page']), ['role' => 'semua'])) }}">
                <i class="bi bi-people me-1"></i> Semua Role
            </a>
        </li>
        <li class="nav-item me-2">
            <a class="nav-link small fw-medium px-4 text-nowrap {{ $currentRole === 'kepala_sekolah' ? 'active rounded-pill shadow-sm' : 'text-dark rounded-pill bg-white border border-light shadow-sm' }}" 
               href="{{ route('lab.kepala_sekolah.activity_log', array_merge(request()->except(['role', 'page']), ['role' => 'kepala_sekolah'])) }}">
                Kepala Sekolah
            </a>
        </li>
        <li class="nav-item me-2">
            <a class="nav-link small fw-medium px-4 text-nowrap {{ $currentRole === 'kepala_lab' ? 'active rounded-pill shadow-sm' : 'text-dark rounded-pill bg-white border border-light shadow-sm' }}" 
               href="{{ route('lab.kepala_sekolah.activity_log', array_merge(request()->except(['role', 'page']), ['role' => 'kepala_lab'])) }}">
                Kepala Lab
            </a>
        </li>
        <li class="nav-item me-2">
            <a class="nav-link small fw-medium px-4 text-nowrap {{ $currentRole === 'admin_lab' ? 'active rounded-pill shadow-sm' : 'text-dark rounded-pill bg-white border border-light shadow-sm' }}" 
               href="{{ route('lab.kepala_sekolah.activity_log', array_merge(request()->except(['role', 'page']), ['role' => 'admin_lab'])) }}">
                Admin Lab
            </a>
        </li>
        <li class="nav-item me-2">
            <a class="nav-link small fw-medium px-4 text-nowrap {{ $currentRole === 'guru' ? 'active rounded-pill shadow-sm' : 'text-dark rounded-pill bg-white border border-light shadow-sm' }}" 
               href="{{ route('lab.kepala_sekolah.activity_log', array_merge(request()->except(['role', 'page']), ['role' => 'guru'])) }}">
                Guru
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link small fw-medium px-4 text-nowrap {{ $currentRole === 'siswa' ? 'active rounded-pill shadow-sm' : 'text-dark rounded-pill bg-white border border-light shadow-sm' }}" 
               href="{{ route('lab.kepala_sekolah.activity_log', array_merge(request()->except(['role', 'page']), ['role' => 'siswa'])) }}">
                Siswa
            </a>
        </li>
    </ul>
</div>

{{-- Filter --}}
<div class="card border-0 rounded-4 shadow-sm mb-4 bg-white">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('lab.kepala_sekolah.activity_log') }}" class="row g-2 align-items-end">
            <input type="hidden" name="role" value="{{ request('role', 'semua') }}">
            <div class="col-sm-6 col-md-3">
                <label class="form-label small fw-medium mb-1">Tanggal Dari</label>
                <input type="date" name="tanggal_dari" class="form-control form-control-sm rounded-3 bg-light border-0" value="{{ request('tanggal_dari') }}">
            </div>
            <div class="col-sm-6 col-md-3">
                <label class="form-label small fw-medium mb-1">Tanggal Sampai</label>
                <input type="date" name="tanggal_sampai" class="form-control form-control-sm rounded-3 bg-light border-0" value="{{ request('tanggal_sampai') }}">
            </div>
            <div class="col-sm-6 col-md-4">
                <label class="form-label small fw-medium mb-1">Kategori Aktivitas</label>
                <select name="kategori" class="form-select form-select-sm rounded-3 bg-light border-0">
                    <option value="semua" {{ request('kategori', 'semua') === 'semua' ? 'selected' : '' }}>Semua Aktivitas</option>
                    <option value="approved" {{ request('kategori') === 'approved' ? 'selected' : '' }}>Persetujuan</option>
                    <option value="rejected" {{ request('kategori') === 'rejected' ? 'selected' : '' }}>Penolakan</option>
                    <option value="eskalasi" {{ request('kategori') === 'eskalasi' ? 'selected' : '' }}>Eskalasi</option>
                    <option value="damage_reported" {{ request('kategori') === 'damage_reported' ? 'selected' : '' }}>Laporan Kerusakan</option>
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex gap-2 align-items-end">
                <button type="submit" class="btn btn-primary btn-sm rounded-3 w-100 fw-medium">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
            </div>
            
            <div class="col-12 mt-3 d-flex justify-content-between border-top pt-3">
                <a href="{{ route('lab.kepala_sekolah.activity_log', ['role' => request('role', 'semua')]) }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                    <i class="bi bi-x me-1"></i> Reset Filter
                </a>
                <a href="{{ route('lab.kepala_sekolah.activity_log', array_merge(request()->all(), ['export' => 'csv'])) }}"
                   class="btn btn-success btn-sm rounded-pill px-3">
                    <i class="bi bi-filetype-csv me-1"></i> Export CSV
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Log Timeline --}}
<div class="card border-0 rounded-4 shadow-sm">
    <div class="card-body p-3">
        @forelse($logs as $log)
        @php
            $badgeStyle = match(true) {
                str_contains($log->action, 'approved')       => ['bg' => '#DCFCE7', 'text' => '#166534', 'icon' => 'bi-check-circle-fill', 'label' => 'Disetujui'],
                str_contains($log->action, 'rejected')       => ['bg' => '#FEE2E2', 'text' => '#991B1B', 'icon' => 'bi-x-circle-fill',     'label' => 'Ditolak'],
                str_contains($log->action, 'eskalasi')       => ['bg' => '#FEF3C7', 'text' => '#92400E', 'icon' => 'bi-arrow-up-circle-fill','label' => 'Eskalasi'],
                str_contains($log->action, 'damage')         => ['bg' => '#FEF2F2', 'text' => '#DC2626', 'icon' => 'bi-tools',              'label' => 'Kerusakan'],
                str_contains($log->action, 'pengadaan')      => ['bg' => '#EFF6FF', 'text' => '#1D4ED8', 'icon' => 'bi-cart-plus-fill',     'label' => 'Pengadaan'],
                default                                      => ['bg' => '#F3F4F6', 'text' => '#374151', 'icon' => 'bi-info-circle-fill',   'label' => ucfirst($log->action)],
            };
        @endphp
        <div class="d-flex align-items-start gap-3 mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
            <div class="rounded-circle flex-shrink-0 d-flex align-items-center justify-content-center"
                 style="background:{{ $badgeStyle['bg'] }};color:{{ $badgeStyle['text'] }};width:38px;height:38px;">
                <i class="bi {{ $badgeStyle['icon'] }} small"></i>
            </div>
            <div class="flex-grow-1">
                <p class="small fw-semibold text-dark mb-0">{{ $log->description ?? $log->action }}</p>
                <p class="small text-muted mb-0">
                    <span class="fw-medium">{{ $log->user->nama ?? 'System' }}</span>
                    @if($log->user && $log->user->role)
                    <span class="badge rounded-pill ms-1" style="background:#F1F5F9;color:#475569;font-size:.7rem;">{{ ucfirst(str_replace('_', ' ', $log->user->role)) }}</span>
                    @endif
                    &bull; {{ \Carbon\Carbon::parse($log->created_at)->isoFormat('D MMM Y, HH:mm') }}
                </p>
            </div>
            <span class="badge rounded-pill flex-shrink-0 small px-2"
                  style="background:{{ $badgeStyle['bg'] }};color:{{ $badgeStyle['text'] }};">
                {{ $badgeStyle['label'] }}
            </span>
        </div>
        @empty
        <div class="text-center py-5 text-muted">
            <i class="bi bi-journal-text fs-2 d-block mb-2"></i>
            <p class="small mb-0">Tidak ada log aktivitas yang ditemukan.</p>
        </div>
        @endforelse
    </div>
    @if($logs->hasPages())
    <div class="card-footer bg-white border-0 px-3 pb-3">
        {{ $logs->links('pagination::bootstrap-4') }}
    </div>
    @endif
</div>

@endsection
