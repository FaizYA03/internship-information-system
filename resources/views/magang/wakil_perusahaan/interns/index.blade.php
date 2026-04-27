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

{{-- ================= PENDING ================= --}}
<div class="tab-pane fade show active" id="pending">
@forelse($pendingInterns as $intern)
    @include('magang.wakil_perusahaan.interns._intern_card', [
        'intern'           => $intern,
        'statusClass'      => 'status-pending',
        'statusText'       => 'Menunggu',
        'supervisors'      => $supervisors,
        'wakilPerusahaan'  => $wakilPerusahaan,
    ])
@empty
    <div class="empty-state">Tidak ada data</div>
@endforelse
</div>

{{-- ================= ACCEPTED ================= --}}
<div class="tab-pane fade" id="accepted">
@forelse($acceptedInterns as $intern)
    @include('magang.wakil_perusahaan.interns._intern_card', [
        'intern'           => $intern,
        'statusClass'      => 'status-accepted',
        'statusText'       => 'Menunggu Admin',
        'supervisors'      => $supervisors,
        'wakilPerusahaan'  => $wakilPerusahaan,
    ])
@empty
    <div class="empty-state">Belum ada</div>
@endforelse
</div>

{{-- ================= FINAL ================= --}}
<div class="tab-pane fade" id="final">
@forelse($finalInterns as $intern)
    @include('magang.wakil_perusahaan.interns._intern_card', [
        'intern'           => $intern,
        'statusClass'      => 'status-approved',
        'statusText'       => 'Final',
        'supervisors'      => $supervisors,
        'wakilPerusahaan'  => $wakilPerusahaan,
    ])
@empty
    <div class="empty-state">Belum ada</div>
@endforelse
</div>

{{-- ================= REJECTED ================= --}}
<div class="tab-pane fade" id="rejected">
@forelse($rejectedInterns as $intern)
    @include('magang.wakil_perusahaan.interns._intern_card', [
        'intern'           => $intern,
        'statusClass'      => 'status-rejected',
        'statusText'       => 'Ditolak',
        'supervisors'      => $supervisors,
        'wakilPerusahaan'  => $wakilPerusahaan,
    ])
@empty
    <div class="empty-state">Belum ada</div>
@endforelse
</div>

</div>
</div>
</div>
</div>
@endsection