@extends('lab.layouts.unified', ['title' => 'Log Monitoring Aktivitas'])

@section('content')

{{-- Header --}}
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="ui-card border-0" style="background: linear-gradient(135deg,#374151,#1F2937); color:white;">
            <div class="ui-card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <div class="rounded-3 p-2" style="background:rgba(255,255,255,0.15);">
                                <i class="bi bi-journal-text fs-4"></i>
                            </div>
                            <div>
                                <p class="mb-0 opacity-75 small fw-bold">AUDIT TRAIL</p>
                                <h4 class="fw-bold mb-0">Log Monitoring Aktivitas</h4>
                            </div>
                        </div>
                        <p class="mb-0 opacity-75 small">Jejak digital seluruh aktivitas sistem laboratorium. Filter berdasarkan role & tanggal.</p>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <a href="{{ route('lab.waka_akademik.dashboard') }}" class="btn btn-light btn-sm rounded-pill px-3">
                            <i class="bi bi-arrow-left me-1"></i>Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Daily Summary --}}
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="ui-card border-0">
            <div class="ui-card-body p-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-calendar-day me-2 text-primary"></i>Ringkasan Aktivitas Hari Ini</h6>
                <div class="row g-3">
                    @php
                        $summaryItems = [
                            ['label' => 'Total Aktivitas', 'value' => $todaySummary['total'],    'color' => 'primary',   'icon' => 'bi-activity'],
                            ['label' => 'Dibuat',          'value' => $todaySummary['created'],  'color' => 'success',   'icon' => 'bi-plus-circle'],
                            ['label' => 'Diubah',          'value' => $todaySummary['updated'],  'color' => 'info',      'icon' => 'bi-pencil'],
                            ['label' => 'Dihapus',         'value' => $todaySummary['deleted'],  'color' => 'danger',    'icon' => 'bi-trash'],
                            ['label' => 'Disetujui',       'value' => $todaySummary['approved'], 'color' => 'success',   'icon' => 'bi-check-circle'],
                            ['label' => 'Ditolak',         'value' => $todaySummary['rejected'], 'color' => 'danger',    'icon' => 'bi-x-circle'],
                        ];
                    @endphp
                    @foreach($summaryItems as $item)
                    <div class="col-6 col-md-2 text-center">
                        <div class="rounded-3 p-3" style="background:#F8FAFC;">
                            <i class="bi {{ $item['icon'] }} text-{{ $item['color'] }} fs-5 d-block mb-1"></i>
                            <h5 class="fw-bold mb-0 text-{{ $item['color'] }}">{{ $item['value'] }}</h5>
                            <small class="text-muted">{{ $item['label'] }}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="ui-card border-0">
            <div class="ui-card-body p-4">
                <form method="GET" action="{{ route('lab.waka_akademik.monitoring') }}" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Filter Berdasarkan Role</label>
                        <select name="role" class="form-select rounded-3">
                            <option value="">Semua Role</option>
                            @foreach($roles as $role)
                            <option value="{{ $role }}" {{ $roleFilter === $role ? 'selected' : '' }}>
                                {{ ucwords(str_replace('_', ' ', $role)) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Filter Berdasarkan Tanggal</label>
                        <input type="date" name="tanggal" class="form-control rounded-3" value="{{ $tanggal }}">
                    </div>
                    <div class="col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary rounded-3 flex-grow-1">
                            <i class="bi bi-funnel me-1"></i>Filter
                        </button>
                        <a href="{{ route('lab.waka_akademik.monitoring') }}" class="btn btn-outline-secondary rounded-3">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Active Filter indicator --}}
@if($roleFilter || $tanggal)
<div class="mb-3 d-flex gap-2 align-items-center">
    <small class="text-muted">Filter aktif:</small>
    @if($roleFilter)
    <span class="badge bg-primary rounded-pill px-3">Role: {{ ucwords(str_replace('_', ' ', $roleFilter)) }}</span>
    @endif
    @if($tanggal)
    <span class="badge bg-info rounded-pill px-3">Tanggal: {{ $tanggal }}</span>
    @endif
</div>
@endif

{{-- Log Table --}}
<div class="row">
    <div class="col-12">
        <div class="ui-card border-0">
            <div class="ui-card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 ps-4">Waktu</th>
                                <th class="border-0">Pengguna</th>
                                <th class="border-0">Role</th>
                                <th class="border-0 text-center">Tindakan</th>
                                <th class="border-0">Deskripsi</th>
                                <th class="border-0">Subject</th>
                                <th class="border-0">IP Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activities as $log)
                            @php
                                $rowClass = '';
                                $badgeClass = 'bg-secondary';
                                $isImportant = false;
                                if(in_array($log->action, ['created','approved'])) {
                                    $badgeClass = 'bg-success';
                                    if($log->action === 'approved') { $rowClass = 'table-success-soft'; $isImportant = true; }
                                }
                                if(in_array($log->action, ['deleted','rejected'])) {
                                    $badgeClass = 'bg-danger';
                                    $rowClass = 'table-danger-soft';
                                    $isImportant = true;
                                }
                                if($log->action == 'updated') $badgeClass = 'bg-info';
                            @endphp
                            <tr class="{{ $rowClass }}">
                                <td class="ps-4">
                                    <div class="small fw-semibold">{{ $log->created_at->format('d/m/Y') }}</div>
                                    <div class="small text-muted">{{ $log->created_at->format('H:i:s') }}</div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($isImportant)
                                        <i class="bi bi-star-fill text-warning" style="font-size:0.7rem;" title="Aktivitas penting"></i>
                                        @endif
                                        <div class="fw-semibold small">{{ $log->user->nama ?? 'Sistem' }}</div>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $roleColor = [
                                            'admin_lab'      => 'primary',
                                            'kepala_lab'     => 'info',
                                            'waka_akademik'  => 'purple',
                                            'kepala_sekolah' => 'dark',
                                            'guru'           => 'success',
                                            'siswa'          => 'secondary',
                                            'super_admin'    => 'danger',
                                        ];
                                        $role = $log->user->role ?? '';
                                        $rc = $roleColor[$role] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $rc === 'purple' ? 'light' : 'light' }} text-{{ $rc === 'purple' ? 'dark' : $rc }} rounded-pill border" style="{{ $rc === 'purple' ? 'border-color:#8B5CF6!important;color:#8B5CF6!important;' : '' }}">
                                        {{ ucwords(str_replace('_', ' ', $role)) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $badgeClass }} px-3 rounded-pill">{{ strtoupper($log->action) }}</span>
                                </td>
                                <td>
                                    <small class="text-truncate d-block" style="max-width:250px;" title="{{ $log->description }}">
                                        {{ $log->description }}
                                    </small>
                                </td>
                                <td>
                                    <div class="small text-truncate text-muted" style="max-width:120px;">
                                        {{ class_basename($log->subject_type ?? '') }}
                                        @if($log->subject_id) <span class="text-dark">#{{ $log->subject_id }}</span> @endif
                                    </div>
                                </td>
                                <td><code class="small">{{ $log->ip_address }}</code></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-journal-x fs-1 d-block mb-2 opacity-50"></i>
                                    Tidak ada catatan aktivitas yang ditemukan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($activities->hasPages())
                <div class="p-4 border-top">
                    {{ $activities->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@section('css')
<style>
    .table-success-soft { background-color: rgba(16,185,129,0.06) !important; }
    .table-danger-soft  { background-color: rgba(239,68,68,0.06) !important; }
</style>
@endsection
