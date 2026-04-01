@extends('lab.layouts.unified', ['title' => 'Dashboard Kepala Laboratorium'])

@section('breadcrumb')
<p class="breadcrumb-small mb-0">Lab System › Kepala Laboratorium</p>
@endsection

@section('css')
<style>
    .stat-card {
        border-radius: 16px;
        border: none;
        padding: 20px;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,0.1); }
    .stat-icon {
        width: 52px; height: 52px; border-radius: 14px;
        display: flex; align-items: center; justify-content: center; font-size: 1.4rem;
    }
    .stat-val { font-size: 1.9rem; font-weight: 700; line-height: 1; margin: 6px 0 2px; }
    .stat-label { font-size: 0.78rem; color: #64748B; font-weight: 500; }

    .badge-role {
        display: inline-flex; align-items: center; gap: 6px;
        background: linear-gradient(135deg, #EFF6FF, #DBEAFE);
        border: 1px solid #BFDBFE; border-radius: 30px;
        padding: 5px 14px; font-size: 0.78rem; font-weight: 600; color: #1D4ED8;
    }
    .alert-eskalasi {
        border-left: 4px solid #F59E0B; background: #FFFBEB; border-radius: 8px;
    }
    .item-laporan { transition: background 0.15s; }
    .item-laporan:hover { background: #F8FAFC; }
    .quick-nav { border-radius: 12px; border: 1.5px solid #E2E8F0; padding: 18px;
        text-align: center; text-decoration: none; display: block; transition: all 0.2s; }
    .quick-nav:hover { border-color: #3B82F6; background: #EFF6FF; transform: translateY(-2px); }
    .quick-nav i { font-size: 2rem; display: block; margin-bottom: 8px; }
    .quick-nav span { font-size: 0.82rem; font-weight: 600; color: #374151; display: block; }
    .quick-nav small { font-size: 0.72rem; color: #9CA3AF; }
</style>
@endsection

@section('content')

{{-- ── Welcome Banner ── --}}
<div class="ui-card mb-4 border-0" style="background: linear-gradient(135deg, #1E40AF 0%, #2563EB 60%, #3B82F6 100%); border-radius:18px;">
    <div class="ui-card-body p-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="badge-role mb-3" style="background:rgba(255,255,255,0.15); border-color:rgba(255,255,255,0.3); color:white;">
                    <i class="bi bi-eye-fill"></i> Mode Monitoring & Supervisi
                </div>
                <h2 class="fw-bold text-white mb-1">Halo, {{ auth()->user()->nama ?? auth()->user()->name }}!</h2>
                <p class="mb-0 text-white opacity-75">Pantau dan awasi operasional laboratorium SMK Negeri 5 Padang secara real-time.</p>
            </div>
            <div class="col-md-4 text-end d-none d-md-block">
                <i class="bi bi-binoculars-fill opacity-25 text-white" style="font-size:6rem;"></i>
            </div>
        </div>
    </div>
</div>

{{-- ── Alert Eskalasi Menunggu ── --}}
@if($stats['kerusakan_eskalasi'] > 0)
<div class="alert-eskalasi p-3 mb-4 d-flex align-items-center gap-3">
    <i class="bi bi-exclamation-circle-fill text-warning fs-4"></i>
    <div>
        <strong class="text-warning-dark">Ada {{ $stats['kerusakan_eskalasi'] }} eskalasi kerusakan</strong> menunggu persetujuan Anda.
        <a href="{{ route('lab.kepala_lab.supervisi.kerusakan') }}" class="ms-2 fw-semibold text-warning-dark text-decoration-underline">Tinjau sekarang →</a>
    </div>
</div>
@endif

@if($stats['eksternal_menunggu'] > 0)
<div class="p-3 mb-4 d-flex align-items-center gap-3" style="border-left: 4px solid #0EA5E9; background: #F0F9FF; border-radius: 8px;">
    <i class="bi bi-patch-check-fill text-info fs-4"></i>
    <div>
        <strong class="text-info">{{ $stats['eksternal_menunggu'] }} permohonan peminjaman eksternal</strong> menunggu rekomendasi Anda.
        <a href="{{ route('lab.kepala_lab.approval.eksternal') }}" class="ms-2 fw-semibold text-info text-decoration-underline">Lihat →</a>
    </div>
</div>
@endif

{{-- ── Stats Grid ── --}}
<div class="row g-3 mb-4">
    {{-- Laboratorium --}}
    <div class="col-6 col-md-3">
        <a href="{{ route('lab.kepala_lab.monitoring.lab') }}" class="text-decoration-none">
            <div class="stat-card h-100" style="background:#EFF6FF;">
                <div class="stat-icon" style="background:#DBEAFE; color:#2563EB;"><i class="bi bi-building-fill"></i></div>
                <div class="stat-val text-dark">{{ $stats['total_lab'] }}</div>
                <div class="stat-label">Total Lab</div>
                <small class="text-success fw-500" style="font-size:0.72rem;"><i class="bi bi-check-circle me-1"></i>{{ $stats['lab_aktif'] }} aktif</small>
            </div>
        </a>
    </div>
    {{-- Inventaris --}}
    <div class="col-6 col-md-3">
        <a href="{{ route('lab.kepala_lab.monitoring.inventaris') }}" class="text-decoration-none">
            <div class="stat-card h-100" style="background:#F0FDF4;">
                <div class="stat-icon" style="background:#DCFCE7; color:#16A34A;"><i class="bi bi-box-seam-fill"></i></div>
                <div class="stat-val text-dark">{{ $stats['total_inventaris'] }}</div>
                <div class="stat-label">Total Inventaris</div>
                @if($stats['inventaris_rusak'] > 0)
                    <small class="text-danger" style="font-size:0.72rem;"><i class="bi bi-exclamation-circle me-1"></i>{{ $stats['inventaris_rusak'] }} rusak</small>
                @else
                    <small class="text-success" style="font-size:0.72rem;"><i class="bi bi-check-circle me-1"></i>Kondisi baik</small>
                @endif
            </div>
        </a>
    </div>
    {{-- Peminjaman --}}
    <div class="col-6 col-md-3">
        <a href="{{ route('lab.kepala_lab.monitoring.peminjaman') }}" class="text-decoration-none">
            <div class="stat-card h-100" style="background:#FFFBEB;">
                <div class="stat-icon" style="background:#FEF3C7; color:#D97706;"><i class="bi bi-clipboard-data-fill"></i></div>
                <div class="stat-val text-dark">{{ $stats['peminjaman_aktif'] }}</div>
                <div class="stat-label">Peminjaman Aktif</div>
                @if($stats['peminjaman_pending'] > 0)
                    <small class="text-warning" style="font-size:0.72rem;"><i class="bi bi-hourglass me-1"></i>{{ $stats['peminjaman_pending'] }} pending</small>
                @else
                    <small class="text-muted" style="font-size:0.72rem;">Tidak ada pending</small>
                @endif
            </div>
        </a>
    </div>
    {{-- Kerusakan --}}
    <div class="col-6 col-md-3">
        <a href="{{ route('lab.kepala_lab.supervisi.kerusakan') }}" class="text-decoration-none">
            <div class="stat-card h-100" style="background:#FFF1F2;">
                <div class="stat-icon" style="background:#FFE4E6; color:#E11D48;"><i class="bi bi-tools"></i></div>
                <div class="stat-val text-dark">{{ $stats['kerusakan_aktif'] }}</div>
                <div class="stat-label">Kerusakan Aktif</div>
                @if($stats['kerusakan_eskalasi'] > 0)
                    <small class="text-danger fw-600" style="font-size:0.72rem;"><i class="bi bi-arrow-up-circle me-1"></i>{{ $stats['kerusakan_eskalasi'] }} perlu tindakan</small>
                @else
                    <small class="text-muted" style="font-size:0.72rem;">Tidak ada eskalasi</small>
                @endif
            </div>
        </a>
    </div>
</div>

{{-- ── Akses Cepat ── --}}
<div class="row g-4 mb-4">
    <div class="col-md-8">
        <x-ui.card title="Akses Cepat" class="h-100">
            <div class="row g-2">
                <div class="col-6 col-md-4">
                    <a href="{{ route('lab.kepala_lab.monitoring.lab') }}" class="quick-nav">
                        <i class="bi bi-building-fill text-primary"></i>
                        <span>Monitoring Lab</span>
                        <small>Lihat status semua lab</small>
                    </a>
                </div>
                <div class="col-6 col-md-4">
                    <a href="{{ route('lab.kepala_lab.monitoring.jadwal') }}" class="quick-nav">
                        <i class="bi bi-calendar-week-fill text-info"></i>
                        <span>Jadwal Lab</span>
                        <small>Jadwal minggu ini</small>
                    </a>
                </div>
                <div class="col-6 col-md-4">
                    <a href="{{ route('lab.kepala_lab.monitoring.inventaris') }}" class="quick-nav">
                        <i class="bi bi-box-seam-fill text-success"></i>
                        <span>Inventaris</span>
                        <small>Kondisi peralatan</small>
                    </a>
                </div>
                <div class="col-6 col-md-4">
                    <a href="{{ route('lab.kepala_lab.monitoring.peminjaman') }}" class="quick-nav">
                        <i class="bi bi-clipboard-data-fill text-warning"></i>
                        <span>Peminjaman</span>
                        <small>Semua peminjaman</small>
                    </a>
                </div>
                <div class="col-6 col-md-4">
                    <a href="{{ route('lab.kepala_lab.supervisi.kerusakan') }}" class="quick-nav">
                        <i class="bi bi-tools text-danger"></i>
                        <span>Supervisi Kerusakan</span>
                        <small>Laporan & eskalasi</small>
                    </a>
                </div>
                <div class="col-6 col-md-4">
                    <a href="{{ route('lab.kepala_lab.approval.eksternal') }}" class="quick-nav">
                        <i class="bi bi-patch-check-fill" style="color:#0EA5E9;"></i>
                        <span>Rekomendasi Ext.</span>
                        <small>Peminjaman eksternal</small>
                    </a>
                </div>
            </div>
        </x-ui.card>
    </div>

    <div class="col-md-4">
        <x-ui.card title="Ringkasan Hari Ini" class="h-100">
            <ul class="list-group list-group-flush">
                <li class="list-group-item px-0 border-0 d-flex justify-content-between align-items-center py-2">
                    <span class="text-muted small"><i class="bi bi-calendar-check me-2 text-primary"></i>Jadwal Hari Ini</span>
                    <span class="badge bg-primary rounded-pill">{{ $stats['jadwal_hari_ini'] }}</span>
                </li>
                <li class="list-group-item px-0 border-0 d-flex justify-content-between align-items-center py-2">
                    <span class="text-muted small"><i class="bi bi-arrow-up-circle me-2 text-warning"></i>Eskalasi Menunggu</span>
                    <span class="badge {{ $stats['kerusakan_eskalasi'] > 0 ? 'bg-warning text-dark' : 'bg-secondary' }} rounded-pill">
                        {{ $stats['kerusakan_eskalasi'] }}
                    </span>
                </li>
                <li class="list-group-item px-0 border-0 d-flex justify-content-between align-items-center py-2">
                    <span class="text-muted small"><i class="bi bi-people me-2 text-info"></i>Pinjam Eksternal</span>
                    <span class="badge {{ $stats['eksternal_menunggu'] > 0 ? 'bg-info' : 'bg-secondary' }} rounded-pill">
                        {{ $stats['eksternal_menunggu'] }}
                    </span>
                </li>
                <li class="list-group-item px-0 border-0 d-flex justify-content-between align-items-center py-2">
                    <span class="text-muted small"><i class="bi bi-wrench me-2 text-danger"></i>Kerusakan Aktif</span>
                    <span class="badge {{ $stats['kerusakan_aktif'] > 0 ? 'bg-danger' : 'bg-success' }} rounded-pill">
                        {{ $stats['kerusakan_aktif'] }}
                    </span>
                </li>
                <li class="list-group-item px-0 border-0 d-flex justify-content-between align-items-center py-2">
                    <span class="text-muted small"><i class="bi bi-check-circle me-2 text-success"></i>Pinjam Aktif</span>
                    <span class="badge bg-success rounded-pill">{{ $stats['peminjaman_aktif'] }}</span>
                </li>
            </ul>
            <div class="mt-3 p-3 rounded-3" style="background:#F1F5F9; font-size:0.75rem; color:#64748B;">
                <i class="bi bi-info-circle me-2"></i>
                Anda hanya dapat <strong>memantau</strong> dan <strong>memberikan persetujuan/rekomendasi</strong>. Penginputan data dilakukan oleh Admin Lab.
            </div>
        </x-ui.card>
    </div>
</div>

{{-- ── Preview Laporan Kerusakan Terbaru ── --}}
@if($kerusakan_terbaru->count() > 0)
<x-ui.card title="Laporan Kerusakan Terbaru">
    <div class="table-responsive">
        <table class="table table-sm align-middle mb-0">
            <thead style="background:#F8FAFC;">
                <tr>
                    <th class="fw-600 text-muted small py-2 border-0">Inventaris</th>
                    <th class="fw-600 text-muted small py-2 border-0">Dilaporkan Oleh</th>
                    <th class="fw-600 text-muted small py-2 border-0">Tingkat</th>
                    <th class="fw-600 text-muted small py-2 border-0">Status</th>
                    <th class="fw-600 text-muted small py-2 border-0">Eskalasi</th>
                    <th class="fw-600 text-muted small py-2 border-0">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kerusakan_terbaru as $k)
                <tr class="item-laporan">
                    <td class="py-2">
                        <span class="fw-semibold" style="font-size:0.85rem;">
                            {{ $k->inventaris->nama_inventaris ?? 'N/A' }}
                        </span>
                    </td>
                    <td class="py-2 text-muted" style="font-size:0.82rem;">
                        {{ $k->reporter_info }}
                    </td>
                    <td class="py-2">
                        @php
                            $lvl = $k->tingkat_kerusakan ?? $k->level ?? 'ringan';
                            $lvlColor = ['ringan' => 'warning', 'sedang' => 'orange', 'berat' => 'danger'][$lvl] ?? 'secondary';
                        @endphp
                        <span class="badge bg-{{ $lvlColor == 'orange' ? 'warning' : $lvlColor }}" style="font-size:0.7rem;">
                            {{ ucfirst($lvl) }}
                        </span>
                    </td>
                    <td class="py-2">
                        @php
                            $st = $k->status ?? 'dilaporkan';
                            $stMap = ['dilaporkan' => ['warning','Dilaporkan'], 'diproses' => ['info','Diproses'], 'selesai' => ['success','Selesai'], 'ditolak' => ['secondary','Ditolak']];
                            [$stColor, $stLabel] = $stMap[$st] ?? ['secondary', ucfirst($st)];
                        @endphp
                        <span class="badge bg-{{ $stColor }}" style="font-size:0.7rem;">{{ $stLabel }}</span>
                    </td>
                    <td class="py-2">
                        @if($k->is_eskalasi && $k->eskalasi_ke == 'kepala_lab' && $k->eskalasi_status == 'menunggu')
                            <span class="badge bg-danger" style="font-size:0.7rem;"><i class="bi bi-arrow-up me-1"></i>Perlu Tindakan</span>
                        @elseif($k->is_eskalasi)
                            <span class="badge bg-secondary" style="font-size:0.7rem;">Dieskalasi</span>
                        @else
                            <span class="text-muted small">—</span>
                        @endif
                    </td>
                    <td class="py-2 text-muted" style="font-size:0.8rem;">
                        {{ \Carbon\Carbon::parse($k->created_at)->format('d M Y') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        <a href="{{ route('lab.kepala_lab.supervisi.kerusakan') }}" class="btn btn-sm btn-outline-primary">
            Lihat Semua Laporan <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>
</x-ui.card>
@endif

@endsection
