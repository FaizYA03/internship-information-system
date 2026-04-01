@extends('lab.layouts.unified', ['title' => 'Monitoring Peminjaman'])

@section('breadcrumb')
<p class="breadcrumb-small mb-0">Dashboard › Monitoring Peminjaman</p>
@endsection

@section('css')
<style>
    .readonly-badge { background: #EFF6FF; color: #1D4ED8; font-size: 0.68rem; border-radius: 20px; padding: 3px 10px; font-weight: 600; border: 1px solid #BFDBFE; }
    .status-badge { font-size: 0.7rem; padding: 4px 10px; border-radius: 20px; font-weight: 600; }
    .s-pending      { background:#FEF3C7; color:#B45309; }
    .s-approved     { background:#DCFCE7; color:#15803D; }
    .s-rejected     { background:#FFE4E6; color:#BE123C; }
    .s-returned     { background:#F1F5F9; color:#475569; }
    .s-recommended  { background:#E0F2FE; color:#0369A1; }
    .nav-tabs-wrapper .nav-link { border: none; padding: 10px 20px; font-size: 0.85rem; color: #64748B; border-bottom: 2px solid transparent; }
    .nav-tabs-wrapper .nav-link.active { color: #2563EB; border-bottom: 2px solid #2563EB; font-weight: 600; background: none; }
</style>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-0">Monitoring Peminjaman</h4>
        <small class="text-muted">Pantau semua transaksi peminjaman · <span class="readonly-badge"><i class="bi bi-eye me-1"></i>Read-Only</span></small>
    </div>
</div>

{{-- Filter Status --}}
<form method="GET" action="{{ route('lab.kepala_lab.monitoring.peminjaman') }}" class="mb-3">
    <div class="d-flex gap-2 align-items-center flex-wrap">
        <label class="text-muted small fw-600 mb-0">Filter Status:</label>
        @foreach(['', 'pending', 'approved', 'returned', 'rejected'] as $s)
        <button type="submit" name="status" value="{{ $s }}"
            class="btn btn-sm {{ request('status') === $s ? 'btn-primary' : 'btn-outline-secondary' }}">
            {{ $s === '' ? 'Semua' : ucfirst($s) }}
        </button>
        @endforeach
    </div>
</form>

{{-- Tabs --}}
<div class="nav-tabs-wrapper mb-3">
    <ul class="nav nav-tabs border-bottom border-0" id="pinjamTabs">
        <li class="nav-item">
            <a class="nav-link active" id="tab-alat" data-bs-toggle="tab" href="#panel-alat">
                <i class="bi bi-wrench me-1"></i> Peminjaman Alat
                <span class="badge bg-primary ms-1" style="font-size:0.65rem;">{{ $pinjamAlat->total() }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tab-eksternal" data-bs-toggle="tab" href="#panel-eksternal">
                <i class="bi bi-people me-1"></i> Peminjaman Eksternal
                <span class="badge bg-info ms-1" style="font-size:0.65rem;">{{ $pinjamEksternal->total() }}</span>
            </a>
        </li>
    </ul>
</div>

<div class="tab-content">
    {{-- Panel: Peminjaman Alat --}}
    <div class="tab-pane fade show active" id="panel-alat">
        @if($pinjamAlat->isEmpty())
            <div class="text-center py-4">
                <i class="bi bi-clipboard-x opacity-25" style="font-size:3rem;"></i>
                <p class="mt-2 text-muted small">Tidak ada data peminjaman alat.</p>
            </div>
        @else
        <x-ui.card>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead style="background:#F8FAFC;">
                        <tr>
                            <th class="small fw-600 text-muted py-2 border-0">#</th>
                            <th class="small fw-600 text-muted py-2 border-0">Peminjam</th>
                            <th class="small fw-600 text-muted py-2 border-0">Inventaris</th>
                            <th class="small fw-600 text-muted py-2 border-0">Jumlah</th>
                            <th class="small fw-600 text-muted py-2 border-0">Tgl Pinjam</th>
                            <th class="small fw-600 text-muted py-2 border-0">Tgl Kembali</th>
                            <th class="small fw-600 text-muted py-2 border-0">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pinjamAlat as $i => $p)
                        <tr style="font-size:0.84rem;">
                            <td class="py-2 text-muted">{{ $pinjamAlat->firstItem() + $i }}</td>
                            <td class="py-2 fw-semibold">{{ $p->user->nama ?? $p->user->name ?? '—' }}</td>
                            <td class="py-2">{{ $p->inventaris->nama_inventaris ?? '—' }}</td>
                            <td class="py-2">{{ $p->jumlah ?? 1 }}</td>
                            <td class="py-2 text-muted">{{ $p->tanggal_pinjam ? \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d M Y') : '—' }}</td>
                            <td class="py-2 text-muted">{{ $p->tanggal_kembali ? \Carbon\Carbon::parse($p->tanggal_kembali)->format('d M Y') : '—' }}</td>
                            <td class="py-2">
                                @php $st = strtolower($p->status ?? 'pending'); @endphp
                                <span class="status-badge s-{{ $st }}">{{ ucfirst($st) }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $pinjamAlat->withQueryString()->links() }}</div>
        </x-ui.card>
        @endif
    </div>

    {{-- Panel: Peminjaman Eksternal --}}
    <div class="tab-pane fade" id="panel-eksternal">
        @if($pinjamEksternal->isEmpty())
            <div class="text-center py-4">
                <i class="bi bi-clipboard-x opacity-25" style="font-size:3rem;"></i>
                <p class="mt-2 text-muted small">Tidak ada data peminjaman eksternal.</p>
            </div>
        @else
        <x-ui.card>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead style="background:#F8FAFC;">
                        <tr>
                            <th class="small fw-600 text-muted py-2 border-0">#</th>
                            <th class="small fw-600 text-muted py-2 border-0">Nama Peminjam</th>
                            <th class="small fw-600 text-muted py-2 border-0">Instansi</th>
                            <th class="small fw-600 text-muted py-2 border-0">Alat/Ruangan</th>
                            <th class="small fw-600 text-muted py-2 border-0">Tgl Pinjam</th>
                            <th class="small fw-600 text-muted py-2 border-0">Status</th>
                            <th class="small fw-600 text-muted py-2 border-0">Rekomendasi KaLab</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pinjamEksternal as $i => $p)
                        <tr style="font-size:0.84rem;">
                            <td class="py-2 text-muted">{{ $pinjamEksternal->firstItem() + $i }}</td>
                            <td class="py-2 fw-semibold">{{ $p->nama_peminjam ?? '—' }}</td>
                            <td class="py-2 text-muted">{{ $p->instansi ?? '—' }}</td>
                            <td class="py-2">{{ $p->inventaris->nama_inventaris ?? ($p->deskripsi ?? '—') }}</td>
                            <td class="py-2 text-muted">{{ $p->tanggal_pinjam ? \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d M Y') : '—' }}</td>
                            <td class="py-2">
                                @php $st = strtolower($p->status ?? 'pending'); @endphp
                                <span class="status-badge s-{{ $st }}">{{ ucfirst($st) }}</span>
                            </td>
                            <td class="py-2">
                                @if($p->status === 'pending')
                                    <a href="{{ route('lab.kepala_lab.approval.eksternal') }}" class="btn btn-xs btn-outline-primary" style="font-size:0.72rem; padding:3px 8px;">
                                        <i class="bi bi-patch-check me-1"></i>Beri Rekomendasi
                                    </a>
                                @elseif($p->status === 'recommended')
                                    <span class="text-success small"><i class="bi bi-check-circle me-1"></i>Direkomendasikan</span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $pinjamEksternal->withQueryString()->links() }}</div>
        </x-ui.card>
        @endif
    </div>
</div>

@endsection
