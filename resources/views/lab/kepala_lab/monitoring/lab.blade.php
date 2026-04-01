@extends('lab.layouts.unified', ['title' => 'Monitoring Laboratorium'])

@section('breadcrumb')
<p class="breadcrumb-small mb-0">Dashboard › Monitoring Lab</p>
@endsection

@section('css')
<style>
    .lab-card { border-radius: 14px; border: 1.5px solid #E2E8F0; transition: all 0.2s; }
    .lab-card:hover { border-color: #3B82F6; box-shadow: 0 4px 16px rgba(37,99,235,0.1); transform: translateY(-2px); }
    .lab-status-badge { font-size: 0.7rem; padding: 4px 10px; border-radius: 20px; font-weight: 600; }
    .status-aktif { background: #DCFCE7; color: #15803D; }
    .status-nonaktif { background: #F1F5F9; color: #64748B; }
    .status-maintenance { background: #FEF3C7; color: #B45309; }
    .info-row { font-size: 0.82rem; color: #64748B; }
    .readonly-badge { background: #EFF6FF; color: #1D4ED8; font-size: 0.68rem; border-radius: 20px; padding: 3px 10px; font-weight: 600; border: 1px solid #BFDBFE; }
</style>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-0">Monitoring Laboratorium</h4>
        <small class="text-muted">Pantau status dan kondisi semua laboratorium · <span class="readonly-badge"><i class="bi bi-eye me-1"></i>Read-Only</span></small>
    </div>
    {{-- Info: tidak ada tombol tambah/edit/hapus --}}
    <div class="d-flex align-items-center gap-2">
        <span class="badge bg-light text-muted border" style="font-size:0.75rem; padding:6px 12px;">
            <i class="bi bi-building-fill me-1 text-primary"></i>
            Total: {{ $labs->total() }} Lab
        </span>
    </div>
</div>

{{-- Filter Search --}}
<form method="GET" action="{{ route('lab.kepala_lab.monitoring.lab') }}" class="mb-4">
    <div class="input-group" style="max-width: 360px;">
        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari nama laboratorium…" value="{{ request('search') }}">
        @if(request('search'))
            <a href="{{ route('lab.kepala_lab.monitoring.lab') }}" class="btn btn-outline-secondary">×</a>
        @endif
    </div>
</form>

@if($labs->isEmpty())
    <div class="text-center py-5">
        <i class="bi bi-building opacity-25" style="font-size:4rem;"></i>
        <p class="mt-3 text-muted">Tidak ada data laboratorium ditemukan.</p>
    </div>
@else
<div class="row g-3">
    @foreach($labs as $lab)
    <div class="col-12 col-md-6 col-xl-4">
        <div class="lab-card p-4 bg-white h-100">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:42px;height:42px;background:#EFF6FF;">
                        <i class="bi bi-building-fill text-primary fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0" style="font-size:0.9rem;">{{ $lab->nama_labor }}</h6>
                        <small class="text-muted" style="font-size:0.72rem;">{{ $lab->kode ?? '—' }}</small>
                    </div>
                </div>
                @php
                    $status = strtolower($lab->status_penggunaan ?? 'kosong');
                    $statusClass = ['aktif' => 'status-aktif', 'kosong' => 'status-aktif', 'digunakan' => 'status-maintenance', 'maintenance' => 'status-maintenance', 'nonaktif' => 'status-nonaktif'][$status] ?? 'status-nonaktif';
                    $statusLabel = ['aktif' => 'Aktif', 'kosong' => 'Tersedia', 'digunakan' => 'Digunakan', 'maintenance' => 'Maintenance', 'nonaktif' => 'Non-Aktif'][$status] ?? ucfirst($status);
                @endphp
                <span class="lab-status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
            </div>

            <div class="info-row mb-2">
                <i class="bi bi-person-fill me-1 text-muted"></i>
                <strong>Penanggungjawab:</strong> {{ $lab->penanggung_jawab ?? '—' }}
            </div>
            <div class="info-row mb-2">
                <i class="bi bi-person-gear me-1 text-muted"></i>
                <strong>Teknisi:</strong> {{ $lab->teknisi ?? '—' }}
            </div>
            <div class="info-row mb-2">
                <i class="bi bi-geo-alt-fill me-1 text-muted"></i>
                <strong>Lokasi:</strong> {{ $lab->lokasi ?? '—' }}
            </div>
            <div class="info-row mb-2">
                <i class="bi bi-people-fill me-1 text-muted"></i>
                <strong>Kapasitas:</strong> {{ $lab->kapasitas ?? '—' }} orang
            </div>
            <div class="info-row mb-2">
                <i class="bi bi-grid-fill me-1 text-muted"></i>
                <strong>Jenis Lab:</strong> {{ $lab->jenis_labor ?? '—' }}
            </div>
            <div class="info-row mb-3">
                <i class="bi bi-calendar-check-fill me-1 text-muted"></i>
                <strong>Jadwal Hari Ini:</strong>
                <span class="{{ ($lab->jadwal_hari_ini ?? 0) > 0 ? 'text-primary fw-semibold' : 'text-muted' }}">
                    {{ $lab->jadwal_hari_ini ?? 0 }} sesi
                </span>
            </div>

            @if($lab->deskripsi)
            <p class="text-muted mb-0" style="font-size:0.78rem; line-height:1.4;">
                {{ Str::limit($lab->deskripsi, 80) }}
            </p>
            @endif

            <hr class="my-2">
            <small class="text-muted" style="font-size:0.7rem;">
                <i class="bi bi-info-circle me-1"></i>
                Data: hanya dapat dipantau, tidak dapat diubah
            </small>
        </div>
    </div>
    @endforeach
</div>

<div class="mt-4">
    {{ $labs->withQueryString()->links() }}
</div>
@endif

@endsection
