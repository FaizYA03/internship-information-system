@extends('lab.layouts.unified', ['title' => 'Monitoring Jadwal Lab'])

@section('breadcrumb')
<p class="breadcrumb-small mb-0">Dashboard › Monitoring Jadwal</p>
@endsection

@section('css')
<style>
    .readonly-badge { background: #EFF6FF; color: #1D4ED8; font-size: 0.68rem; border-radius: 20px; padding: 3px 10px; font-weight: 600; border: 1px solid #BFDBFE; }
    .jadwal-row { font-size: 0.85rem; transition: background 0.15s; }
    .jadwal-row:hover { background: #F8FAFC; }
    .filter-bar { background: white; border: 1px solid #E2E8F0; border-radius: 12px; padding: 16px; margin-bottom: 20px; }
</style>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-0">Monitoring Jadwal Laboratorium</h4>
        <small class="text-muted">Pantau jadwal tetap mingguan laboratorium · <span class="readonly-badge"><i class="bi bi-eye me-1"></i>Read-Only</span></small>
    </div>
    <span class="badge bg-light text-muted border" style="font-size:0.75rem; padding:6px 12px;">
        <i class="bi bi-calendar-week me-1 text-info"></i>
        Total: {{ $jadwal->total() }} sesi terjadwal
    </span>
</div>

{{-- Filter --}}
<form method="GET" action="{{ route('lab.kepala_lab.monitoring.jadwal') }}" class="filter-bar">
    <div class="row g-2 align-items-end">
        <div class="col-md-4">
            <label class="form-label small fw-600 text-muted mb-1">Laboratorium</label>
            <select name="lab_id" class="form-select form-select-sm">
                <option value="">Semua Lab</option>
                @foreach($labs as $lab)
                    <option value="{{ $lab->id }}" {{ request('lab_id') == $lab->id ? 'selected' : '' }}>
                        {{ $lab->nama_labor }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label small fw-600 text-muted mb-1">Hari</label>
            <select name="hari" class="form-select form-select-sm">
                <option value="">Semua Hari</option>
                @foreach($hariOptions as $hari)
                    <option value="{{ $hari }}" {{ request('hari') == $hari ? 'selected' : '' }}>{{ $hari }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm px-3">
                <i class="bi bi-filter me-1"></i> Filter
            </button>
            <a href="{{ route('lab.kepala_lab.monitoring.jadwal') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
        </div>
    </div>
</form>

@if($jadwal->isEmpty())
    <div class="text-center py-5">
        <i class="bi bi-calendar-x opacity-25" style="font-size:4rem;"></i>
        <p class="mt-3 text-muted">Tidak ada jadwal ditemukan untuk filter yang dipilih.</p>
    </div>
@else
<x-ui.card>
    <div class="table-responsive">
        <table class="table table-sm align-middle mb-0">
            <thead style="background:#F8FAFC;">
                <tr>
                    <th class="small fw-600 text-muted py-2 border-0">#</th>
                    <th class="small fw-600 text-muted py-2 border-0">Laboratorium</th>
                    <th class="small fw-600 text-muted py-2 border-0">Hari</th>
                    <th class="small fw-600 text-muted py-2 border-0">Waktu</th>
                    <th class="small fw-600 text-muted py-2 border-0">Mata Pelajaran</th>
                    <th class="small fw-600 text-muted py-2 border-0">Kelas</th>
                    <th class="small fw-600 text-muted py-2 border-0">Guru</th>
                </tr>
            </thead>
            <tbody>
                @foreach($jadwal as $i => $j)
                <tr class="jadwal-row">
                    <td class="py-2 text-muted">{{ $jadwal->firstItem() + $i }}</td>
                    <td class="py-2">
                        <span class="fw-semibold">{{ $j->labor->nama_labor ?? '—' }}</span>
                    </td>
                    <td class="py-2">
                        @php
                            $hariNow = now()->locale('id')->isoFormat('dddd');
                        @endphp
                        <span class="{{ $j->hari == $hariNow ? 'fw-bold text-primary' : 'text-muted' }}">
                            {{ $j->hari }}
                            @if($j->hari == $hariNow)
                                <span class="badge bg-primary" style="font-size:0.65rem;">Hari Ini</span>
                            @endif
                        </span>
                    </td>
                    <td class="py-2 text-muted">{{ $j->jam_mulai }} – {{ $j->jam_selesai }}</td>
                    <td class="py-2">{{ $j->mata_pelajaran ?? '—' }}</td>
                    <td class="py-2 text-muted">{{ $j->kelas ?? '—' }}</td>
                    <td class="py-2 text-muted">{{ $j->guru->nama ?? ($j->guru->name ?? '—') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $jadwal->withQueryString()->links() }}
    </div>
</x-ui.card>
@endif

@endsection
