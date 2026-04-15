@extends('magang.layouts.main')

@section('css')
<style>
    .nav-tabs .nav-link {
        color: #495057;
        font-weight: 500;
    }
    .nav-tabs .nav-link.active {
        color: var(--primary);
        border-bottom: 2px solid var(--primary);
        font-weight: 600;
    }

    .intern-card {
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        margin-bottom: 20px;
        transition: 0.2s;
    }
    .intern-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }

    .intern-header {
        background: #f8f9fa;
        padding: 15px;
        border-bottom: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .intern-body {
        padding: 15px;
    }

    .intern-meta {
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 10px;
    }

    .intern-meta div {
        margin-bottom: 5px;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .status-pending { background: #fff3cd; color: #856404; }
    .status-accepted { background: #cce5ff; color: #004085; }
    .status-approved { background: #d4edda; color: #155724; }
    .status-rejected { background: #f8d7da; color: #721c24; }

    .empty-state {
        text-align: center;
        padding: 40px;
        color: #888;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
<div class="card">

<div class="card-header">
    <h5>Daftar Siswa Magang</h5>
</div>

<div class="card-body">

<!-- TAB -->
<ul class="nav nav-tabs mb-4">
    <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="tab" href="#pending">
            Menunggu <span class="badge bg-warning">{{ $pendingInterns->count() }}</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#accepted">
            Diterima Mitra <span class="badge bg-primary">{{ $acceptedInterns->count() }}</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#final">
            Disetujui Admin <span class="badge bg-success">{{ $finalInterns->count() }}</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#rejected">
            Ditolak <span class="badge bg-danger">{{ $rejectedInterns->count() }}</span>
        </a>
    </li>
</ul>

<div class="tab-content">

{{-- ================= LOOP FUNCTION TEMPLATE ================= --}}
@php
function renderCard($intern, $statusClass, $statusText) {
@endphp

<div class="intern-card">
    <div class="intern-header">
        <strong>{{ $intern->nama }}</strong>
        <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
    </div>

    <div class="intern-body">

        <!-- POSISI -->
        <div class="fw-bold mb-2">
            {{ optional($intern->opening)->posisi ?? 'Program tidak tersedia' }}
        </div>

        <!-- META -->
        <div class="intern-meta">
            <div><i class="bi bi-envelope"></i> {{ $intern->email ?? '-' }}</div>
            <div><i class="bi bi-telephone"></i> {{ $intern->no_hp ?? '-' }}</div>
        </div>

        <!-- TANGGAL -->
        <div class="intern-meta">
            <div>
                <i class="bi bi-calendar-event"></i>
                Mulai:
                {{ $intern->tanggal_mulai ? \Carbon\Carbon::parse($intern->tanggal_mulai)->format('d M Y') : '-' }}
            </div>
            <div>
                <i class="bi bi-calendar-check"></i>
                Selesai:
                {{ $intern->tanggal_selesai ? \Carbon\Carbon::parse($intern->tanggal_selesai)->format('d M Y') : '-' }}
            </div>
        </div>

        <!-- ACTION -->
        @if($intern->status == 'Menunggu')
        <form action="{{ route('magang.wakil_perusahaan.interns.approve',$intern->id) }}" method="POST" class="d-inline">
            @csrf @method('PUT')
            <button class="btn btn-success btn-sm">✔ Terima</button>
        </form>

        <form action="{{ route('magang.wakil_perusahaan.interns.reject',$intern->id) }}" method="POST" class="d-inline">
            @csrf @method('PUT')
            <input type="hidden" name="alasan" value="Tidak sesuai">
            <button class="btn btn-danger btn-sm">✖ Tolak</button>
        </form>
        @endif

        <!-- CATATAN -->
        @if($intern->status == 'Ditolak')
        <div class="mt-2 text-danger small">
            <strong>Alasan:</strong> {{ $intern->catatan ?? '-' }}
        </div>
        @endif

    </div>
</div>

@php } @endphp

{{-- ================= PENDING ================= --}}
<div class="tab-pane fade show active" id="pending">
@forelse($pendingInterns as $intern)
    @php renderCard($intern,'status-pending','Menunggu') @endphp
@empty
    <div class="empty-state">Tidak ada data</div>
@endforelse
</div>

{{-- ================= ACCEPTED ================= --}}
<div class="tab-pane fade" id="accepted">
@forelse($acceptedInterns as $intern)
    @php renderCard($intern,'status-accepted','Menunggu Admin') @endphp
@empty
    <div class="empty-state">Belum ada</div>
@endforelse
</div>

{{-- ================= FINAL ================= --}}
<div class="tab-pane fade" id="final">
@forelse($finalInterns as $intern)
    @php renderCard($intern,'status-approved','Final') @endphp
@empty
    <div class="empty-state">Belum ada</div>
@endforelse
</div>

{{-- ================= REJECTED ================= --}}
<div class="tab-pane fade" id="rejected">
@forelse($rejectedInterns as $intern)
    @php renderCard($intern,'status-rejected','Ditolak') @endphp
@empty
    <div class="empty-state">Belum ada</div>
@endforelse
</div>

</div>
</div>
</div>
</div>
@endsection