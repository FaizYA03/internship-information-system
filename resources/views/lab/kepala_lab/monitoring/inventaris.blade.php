@extends('lab.layouts.unified', ['title' => 'Monitoring Inventaris'])

@section('breadcrumb')
<p class="breadcrumb-small mb-0">Dashboard › Monitoring Inventaris</p>
@endsection

@section('css')
<style>
    .readonly-badge { background: #EFF6FF; color: #1D4ED8; font-size: 0.68rem; border-radius: 20px; padding: 3px 10px; font-weight: 600; border: 1px solid #BFDBFE; }
    .kondisi-baik { background: #DCFCE7; color: #15803D; }
    .kondisi-rusak_ringan { background: #FEF3C7; color: #B45309; }
    .kondisi-rusak_berat { background: #FFE4E6; color: #BE123C; }
    .kondisi-dalam_perbaikan { background: #E0F2FE; color: #0369A1; }
    .kondisi-badge { font-size: 0.7rem; padding: 4px 10px; border-radius: 20px; font-weight: 600; }
    .stat-pill { border-radius: 10px; padding: 12px 16px; }
    .filter-bar { background: white; border: 1px solid #E2E8F0; border-radius: 12px; padding: 16px; margin-bottom: 20px; }
</style>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-0">Monitoring Inventaris</h4>
        <small class="text-muted">Pantau kondisi peralatan di semua laboratorium · <span class="readonly-badge"><i class="bi bi-eye me-1"></i>Read-Only</span></small>
    </div>
    <span class="badge bg-light text-muted border" style="font-size:0.75rem; padding:6px 12px;">
        <i class="bi bi-box-seam me-1 text-success"></i>
        Total: {{ $inventaris->total() }} item
    </span>
</div>

{{-- Statistik Kondisi --}}
<div class="row g-2 mb-4">
    @php
        $kondisiMap = [
            'Sangat Baik'   => ['icon' => 'bi-check-circle-fill', 'bg' => '#DCFCE7', 'color' => '#15803D', 'label' => 'Sangat Baik'],
            'Baik'          => ['icon' => 'bi-check-circle-fill', 'bg' => '#A7F3D0', 'color' => '#065F46', 'label' => 'Baik'],
            'Rusak Ringan'  => ['icon' => 'bi-exclamation-circle-fill', 'bg' => '#FEF3C7', 'color' => '#B45309', 'label' => 'Rusak Ringan'],
            'Rusak Sedang'  => ['icon' => 'bi-exclamation-circle-fill', 'bg' => '#FED7AA', 'color' => '#C2410C', 'label' => 'Rusak Sedang'],
            'Rusak Berat'   => ['icon' => 'bi-x-circle-fill', 'bg' => '#FFE4E6', 'color' => '#BE123C', 'label' => 'Rusak Berat'],
        ];
    @endphp
    @foreach($kondisiMap as $key => $map)
    <div class="col-6 col-md-3 col-xl-2">
        <div class="stat-pill" style="background: {{ $map['bg'] }};">
            <div class="d-flex align-items-center gap-2">
                <i class="bi {{ $map['icon'] }}" style="color:{{ $map['color'] }}; font-size:1.2rem;"></i>
                <div>
                    <div class="fw-bold" style="color:{{ $map['color'] }}; font-size:1.1rem;">{{ $stats_kondisi[$key] ?? 0 }}</div>
                    <div style="font-size:0.72rem; color:{{ $map['color'] }}; opacity:0.9;">{{ $map['label'] }}</div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Filter --}}
<form method="GET" action="{{ route('lab.kepala_lab.monitoring.inventaris') }}" class="filter-bar">
    <div class="row g-2 align-items-end">
        <div class="col-md-4">
            <label class="form-label small fw-600 text-muted mb-1">Cari Nama</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Nama inventaris…" value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3">
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
        <div class="col-md-3">
            <label class="form-label small fw-600 text-muted mb-1">Kondisi</label>
            <select name="kondisi" class="form-select form-select-sm">
                <option value="">Semua Kondisi</option>
                @foreach($kondisiMap as $key => $map)
                    <option value="{{ $key }}" {{ request('kondisi') == $key ? 'selected' : '' }}>{{ $map['label'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm px-3">
                <i class="bi bi-filter me-1"></i> Filter
            </button>
            <a href="{{ route('lab.kepala_lab.monitoring.inventaris') }}" class="btn btn-outline-secondary btn-sm">×</a>
        </div>
    </div>
</form>

@if($inventaris->isEmpty())
    <div class="text-center py-5">
        <i class="bi bi-box-seam opacity-25" style="font-size:4rem;"></i>
        <p class="mt-3 text-muted">Tidak ada inventaris ditemukan.</p>
    </div>
@else
<x-ui.card>
    <div class="table-responsive">
        <table class="table table-sm align-middle mb-0">
            <thead style="background:#F8FAFC;">
                <tr>
                    <th class="small fw-600 text-muted py-2 border-0">#</th>
                    <th class="small fw-600 text-muted py-2 border-0">Nama Inventaris</th>
                    <th class="small fw-600 text-muted py-2 border-0">Kode</th>
                    <th class="small fw-600 text-muted py-2 border-0">Laboratorium</th>
                    <th class="small fw-600 text-muted py-2 border-0">Jumlah</th>
                    <th class="small fw-600 text-muted py-2 border-0">Kondisi</th>
                    <th class="small fw-600 text-muted py-2 border-0">Kategori</th>
                    <th class="small fw-600 text-muted py-2 border-0">Tahun Pengadaan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inventaris as $i => $inv)
                <tr style="font-size:0.84rem;">
                    <td class="py-2 text-muted">{{ $inventaris->firstItem() + $i }}</td>
                    <td class="py-2 fw-semibold">{{ $inv->nama_inventaris }}</td>
                    <td class="py-2 text-muted">{{ $inv->kode_inventaris ?? '—' }}</td>
                    <td class="py-2">{{ $inv->labor->nama_labor ?? '—' }}</td>
                    <td class="py-2">{{ $inv->jumlah ?? 1 }}</td>
                    <td class="py-2">
                        @php
                            $k = $inv->kondisi ?? 'Baik';
                            $kMap = ['Sangat Baik' => ['DCFCE7','15803D'], 'Baik' => ['A7F3D0','065F46'], 'Rusak Ringan' => ['FEF3C7','B45309'], 'Rusak Sedang' => ['FED7AA','C2410C'], 'Rusak Berat' => ['FFE4E6','BE123C']];
                            $kC = $kMap[$k] ?? ['F1F5F9','475569'];
                        @endphp
                        <span class="kondisi-badge" style="background:#{{ $kC[0] }};color:#{{ $kC[1] }};">{{ $k }}</span>
                    </td>
                    <td class="py-2 text-muted">{{ $inv->kategori ?? $inv->jenis ?? '—' }}</td>
                    <td class="py-2 text-muted">{{ $inv->tahun_perolehan ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $inventaris->withQueryString()->links() }}
    </div>
</x-ui.card>
@endif

@endsection
