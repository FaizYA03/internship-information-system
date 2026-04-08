@extends('lab.layouts.unified', ['title' => 'Otorisasi Peminjaman Eksternal'])

@section('content')

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('lab.kepala_sekolah.dashboard') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
        <i class="bi bi-arrow-left me-1"></i> Dashboard
    </a>
    <div>
        <h5 class="fw-bold mb-0">Otorisasi Peminjaman Pihak Luar</h5>
        <p class="small text-muted mb-0">Pengajuan yang telah mendapat rekomendasi Kepala Lab dan menunggu keputusan akhir Anda.</p>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success border-0 rounded-3 shadow-sm mb-4">
    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="alert alert-danger border-0 rounded-3 shadow-sm mb-4">
    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
</div>
@endif

@forelse($requests as $item)
@php
    $priorityLabel = 'Normal';
    $priorityClass = 'success';
    $priorityBg    = '#ECFDF5';
    $priorityText  = '#065F46';
    $tujuan = strtolower($item->tujuan ?? $item->keperluan ?? '');
    if (str_contains($tujuan, 'urgent') || str_contains($tujuan, 'darurat') || str_contains($tujuan, 'segera')) {
        $priorityLabel = 'Urgent'; $priorityClass = 'danger'; $priorityBg = '#FEF2F2'; $priorityText = '#991B1B';
    } elseif (str_contains($tujuan, 'penting') || str_contains($tujuan, 'mendesak') || $item->jumlah >= 5) {
        $priorityLabel = 'Sedang'; $priorityClass = 'warning'; $priorityBg = '#FFFBEB'; $priorityText = '#92400E';
    }
@endphp
<div class="card border-0 rounded-4 shadow-sm mb-4">
    <div class="card-body p-4">

        {{-- Header baris: Peminjam + Prioritas --}}
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-3">
            <div>
                <h6 class="fw-bold mb-0 text-dark">{{ $item->nama_peminjam }}</h6>
                <p class="small text-muted mb-0">{{ $item->instansi }}</p>
            </div>
            <span class="badge rounded-pill px-3 py-2" style="background:{{ $priorityBg }};color:{{ $priorityText }};font-size:.78rem;">
                <i class="bi bi-circle-fill me-1"></i>{{ $priorityLabel }}
            </span>
        </div>

        <div class="row g-3 mb-3">
            {{-- Alat & Jumlah --}}
            <div class="col-sm-6 col-md-3">
                <p class="small text-muted mb-1 fw-medium">Alat Dipinjam</p>
                <p class="fw-semibold small mb-0">{{ $item->inventaris->nama_inventaris ?? 'N/A' }}</p>
                <p class="small text-muted mb-0">{{ $item->jumlah }} unit &bull; Stok: {{ $item->inventaris->jumlah ?? 0 }}</p>
            </div>
            {{-- Tanggal --}}
            <div class="col-sm-6 col-md-3">
                <p class="small text-muted mb-1 fw-medium">Tanggal Pinjam</p>
                <p class="fw-semibold small mb-0">{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->isoFormat('D MMM Y') }}</p>
                <p class="small text-muted mb-0">s/d {{ \Carbon\Carbon::parse($item->tanggal_kembali)->isoFormat('D MMM Y') }}</p>
            </div>
            {{-- Keperluan --}}
            <div class="col-sm-6 col-md-3">
                <p class="small text-muted mb-1 fw-medium">Keperluan</p>
                <p class="small mb-0">{{ $item->tujuan ?? $item->keperluan ?? '—' }}</p>
            </div>
            {{-- Rekomendasi Kalab --}}
            <div class="col-sm-6 col-md-3">
                <p class="small text-muted mb-1 fw-medium">Rekomendasi Kalab</p>
                <span class="badge rounded-pill" style="background:#DCFCE7;color:#166534;">
                    <i class="bi bi-check-circle-fill me-1"></i>Direkomendasikan
                </span>
                <p class="small text-muted mb-0 mt-1">
                    {{ $item->rekomendasiBy->nama ?? 'Kepala Lab' }}
                    &bull; {{ $item->rekomendasi_kalab_at ? \Carbon\Carbon::parse($item->rekomendasi_kalab_at)->isoFormat('D MMM Y') : '—' }}
                </p>
            </div>
        </div>

        {{-- Dampak jika ditolak --}}
        <div class="rounded-3 p-2 mb-3" style="background:#FFF7ED;border-left:3px solid #F97316;">
            <p class="small mb-0 fw-medium" style="color:#9A3412;">
                <i class="bi bi-info-circle me-1"></i>
                <strong>Dampak jika ditolak:</strong> Pihak luar tidak dapat meminjam alat, dan kebutuhan mereka tidak terpenuhi.
                @if($item->inventaris && $item->inventaris->jumlah >= $item->jumlah)
                    Stok tersedia cukup untuk memenuhi permintaan ini.
                @else
                    <span class="text-danger">Stok tidak mencukupi!</span>
                @endif
            </p>
        </div>

        {{-- Tombol keputusan --}}
        <div class="d-flex flex-wrap gap-2">
            <form action="{{ route('lab.kepala_sekolah.approval.eksternal.approve', $item->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success rounded-pill px-4 btn-sm"
                    onclick="return confirm('Setujui peminjaman oleh {{ $item->nama_peminjam }}?')">
                    <i class="bi bi-check-circle me-1"></i> Setujui
                </button>
            </form>
            <button type="button" class="btn btn-outline-danger rounded-pill px-4 btn-sm"
                data-bs-toggle="modal" data-bs-target="#rejectModal{{ $item->id }}">
                <i class="bi bi-x-circle me-1"></i> Tolak
            </button>
        </div>
    </div>
</div>

{{-- Reject Modal --}}
<div class="modal fade" id="rejectModal{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('lab.kepala_sekolah.approval.eksternal.reject', $item->id) }}" method="POST">
            @csrf
            <div class="modal-content border-0 rounded-4">
                <div class="modal-header border-0">
                    <h5 class="fw-bold text-danger"><i class="bi bi-x-circle me-2"></i>Tolak Peminjaman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted">Tolak pengajuan dari <strong>{{ $item->nama_peminjam }}</strong> ({{ $item->instansi }})?</p>
                    <label class="form-label small fw-medium">Alasan Penolakan (Opsional)</label>
                    <textarea name="catatan" class="form-control rounded-3 small" rows="3" placeholder="Misal: Alat sedang digunakan untuk kebutuhan internal sekolah."></textarea>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4 btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 btn-sm">Tolak Pengajuan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@empty
<div class="card border-0 rounded-4 shadow-sm">
    <div class="card-body text-center py-5">
        <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
        <h6 class="text-muted">Tidak ada pengajuan peminjaman eksternal</h6>
        <p class="small text-muted">Semua pengajuan telah diproses atau belum ada yang masuk.</p>
        <a href="{{ route('lab.kepala_sekolah.dashboard') }}" class="btn btn-sm btn-outline-primary rounded-pill mt-2">
            Kembali ke Dashboard
        </a>
    </div>
</div>
@endforelse

@endsection
