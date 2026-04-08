@extends('lab.layouts.unified', ['title' => 'Persetujuan Pengadaan Fasilitas Lab'])

@section('content')

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('lab.kepala_sekolah.dashboard') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
        <i class="bi bi-arrow-left me-1"></i> Dashboard
    </a>
    <div>
        <h5 class="fw-bold mb-0">Persetujuan Pengadaan Fasilitas Lab</h5>
        <p class="small text-muted mb-0">Kelola pengajuan pengadaan alat dari Kepala Laboratorium.</p>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success border-0 rounded-3 shadow-sm mb-4">
    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
</div>
@endif

@forelse($requests as $item)
@php
    $urgensi = strtolower($item->urgensi ?? 'normal');
    if (in_array($urgensi, ['urgent', 'mendesak', 'kritis'])) {
        $priorityLabel = 'Urgent';     $priorityBg = '#FEF2F2'; $priorityText = '#991B1B'; $priorityDot = '#DC2626';
    } elseif (in_array($urgensi, ['sedang', 'medium', 'penting'])) {
        $priorityLabel = 'Sedang';     $priorityBg = '#FFFBEB'; $priorityText = '#92400E'; $priorityDot = '#F59E0B';
    } else {
        $priorityLabel = 'Normal';     $priorityBg = '#ECFDF5'; $priorityText = '#065F46'; $priorityDot = '#16A34A';
    }
    $totalBiaya = ($item->estimasi_harga ?? 0) * ($item->jumlah ?? 1);
@endphp

<div class="card border-0 rounded-4 shadow-sm mb-4" style="border-top: 3px solid {{ $priorityDot }} !important;">
    <div class="card-body p-4">

        {{-- Header --}}
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <h6 class="fw-bold text-dark mb-0">{{ $item->nama_barang }}</h6>
                    <span class="badge rounded-pill px-2" style="background:{{ $priorityBg }};color:{{ $priorityText }};font-size:.75rem;">
                        <i class="bi bi-circle-fill me-1" style="font-size:.5rem;"></i>{{ $priorityLabel }}
                    </span>
                </div>
                <p class="small text-muted mb-0">
                    Diajukan oleh <strong>{{ $item->user->nama ?? 'Kepala Lab' }}</strong>
                    &bull; {{ \Carbon\Carbon::parse($item->created_at)->isoFormat('D MMM Y') }}
                </p>
            </div>
            <div class="text-end">
                <p class="fw-bold fs-5 mb-0 text-dark">Rp {{ number_format($totalBiaya, 0, ',', '.') }}</p>
                <p class="small text-muted mb-0">{{ $item->jumlah }} unit × Rp {{ number_format($item->estimasi_harga, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="row g-3 mb-3">
            {{-- Spesifikasi --}}
            <div class="col-sm-6">
                <p class="small text-muted fw-medium mb-1">Spesifikasi</p>
                <p class="small mb-0">{{ $item->spesifikasi ?? '—' }}</p>
            </div>
            {{-- Alasan Pengadaan --}}
            <div class="col-sm-6">
                <p class="small text-muted fw-medium mb-1">Alasan / Justifikasi</p>
                <p class="small mb-0">{{ $item->alasan ?? '—' }}</p>
            </div>
        </div>

        {{-- Dampak jika ditolak --}}
        <div class="rounded-3 p-2 mb-3" style="background:#FFF7ED;border-left:3px solid #F97316;">
            <p class="small mb-0" style="color:#9A3412;">
                <i class="bi bi-info-circle me-1"></i>
                <strong>Dampak jika ditolak:</strong> Operasional laboratorium dapat terganggu karena kebutuhan alat tidak terpenuhi.
                @if($priorityLabel === 'Urgent')
                Urgensi tinggi — alat ini sangat dibutuhkan segera.
                @elseif($priorityLabel === 'Sedang')
                Penundaan dapat mempengaruhi jadwal kegiatan lab dalam waktu dekat.
                @else
                Pengadaan bersifat perencanaan jangka menengah.
                @endif
            </p>
        </div>

        {{-- Link referensi --}}
        @if($item->link_referensi)
        <p class="small mb-3">
            <i class="bi bi-link-45deg text-primary me-1"></i>
            <a href="{{ $item->link_referensi }}" target="_blank" class="text-primary">Lihat referensi produk</a>
        </p>
        @endif

        {{-- Tombol aksi --}}
        <div class="d-flex flex-wrap gap-2">
            <button type="button" class="btn btn-success rounded-pill px-4 btn-sm"
                data-bs-toggle="modal" data-bs-target="#approveModal{{ $item->id }}">
                <i class="bi bi-check-circle me-1"></i> Setujui Pengadaan
            </button>
            <button type="button" class="btn btn-outline-danger rounded-pill px-4 btn-sm"
                data-bs-toggle="modal" data-bs-target="#rejectModal{{ $item->id }}">
                <i class="bi bi-x-circle me-1"></i> Tolak
            </button>
        </div>
    </div>
</div>

{{-- Approve Modal --}}
<div class="modal fade" id="approveModal{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('lab.kepala_sekolah.approval.pengadaan.approve', $item->id) }}" method="POST">
            @csrf
            <div class="modal-content border-0 rounded-4">
                <div class="modal-header border-0">
                    <h5 class="fw-bold">Konfirmasi Persetujuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted">Setujui pengadaan <strong>{{ $item->nama_barang }}</strong> ({{ $item->jumlah }} unit) dengan total estimasi:</p>
                    <h4 class="fw-bold text-success mb-3">Rp {{ number_format($totalBiaya, 0, ',', '.') }}</h4>
                    <label class="form-label small fw-medium">Catatan Persetujuan (Opsional)</label>
                    <textarea name="catatan" class="form-control rounded-3 small" rows="3"
                        placeholder="Contoh: Disetujui menggunakan dana BOS Tahap 2."></textarea>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4 btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4 btn-sm">Setujui Anggaran</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Reject Modal --}}
<div class="modal fade" id="rejectModal{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('lab.kepala_sekolah.approval.pengadaan.reject', $item->id) }}" method="POST">
            @csrf
            <div class="modal-content border-0 rounded-4">
                <div class="modal-header border-0">
                    <h5 class="fw-bold text-danger">Tolak Pengajuan Pengadaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted">Tolak pengadaan <strong>{{ $item->nama_barang }}</strong> dari {{ $item->user->nama ?? 'Kepala Lab' }}?</p>
                    <label class="form-label small fw-medium">Alasan Penolakan <span class="text-danger">*</span></label>
                    <textarea name="catatan" class="form-control rounded-3 small" rows="3"
                        placeholder="Contoh: Anggaran dialihkan untuk prioritas lain." required></textarea>
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
        <h6 class="text-muted">Tidak ada pengajuan pengadaan yang menunggu</h6>
        <p class="small text-muted">Semua pengajuan sudah diproses.</p>
        <a href="{{ route('lab.kepala_sekolah.dashboard') }}" class="btn btn-sm btn-outline-primary rounded-pill mt-2">
            Kembali ke Dashboard
        </a>
    </div>
</div>
@endforelse

@endsection
