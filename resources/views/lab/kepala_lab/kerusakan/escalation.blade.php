@extends('lab.layouts.unified', ['title' => 'Eskalasi Laporan Kerusakan'])

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('lab.kepala_lab.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Eskalasi Laporan</li>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div>
                <h3 class="fw-bold text-dark mb-1">Eskalasi Laporan Kerusakan</h3>
                <p class="text-muted small mb-0">Laporan kerusakan alat yang membutuhkan perhatian atau persetujuan pimpinan.</p>
            </div>
        </div>
    </div>

    @forelse($escalations as $laporan)
        <div class="col-md-6 col-lg-4">
            <x-ui.card :hover="true" class="h-100">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="p-3 rounded-4 bg-danger-soft text-danger">
                        <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                    </div>
                    <x-ui.badge variant="{{ $laporan->eskalasi_status == 'disetujui' ? 'success' : ($laporan->eskalasi_status == 'ditolak' ? 'danger' : 'warning') }}">
                        {{ strtoupper($laporan->eskalasi_status) }}
                    </x-ui.badge>
                </div>

                <h5 class="fw-bold text-dark mb-1">{{ $laporan->inventaris?->nama_inventaris ?? $laporan->nama_alat }}</h5>
                <p class="text-muted small mb-3"><i class="bi bi-geo-alt me-1"></i> {{ $laporan->inventaris?->laboratorium?->nama_labor ?? 'Lokasi Tidak Diketahui' }}</p>

                <div class="mb-3">
                    <label class="form-label fw-bold text-muted small mb-1">DESKRIPSI KERUSAKAN</label>
                    <p class="mb-0 text-dark small">{{ $laporan->deskripsi_kerusakan }}</p>
                </div>

                <div class="mb-4 p-3 rounded-4 bg-light border border-danger border-opacity-10">
                    <label class="form-label fw-bold text-danger small mb-1">USER/TEKNISI NOTE:</label>
                    <p class="mb-0 text-dark small italic italic text-muted">"{{ $laporan->eskalasi_catatan }}"</p>
                    <div class="mt-2 text-end text-muted small">
                        <i class="bi bi-clock me-1"></i> {{ $laporan->eskalasi_tanggal ? date('d M Y, H:i', strtotime($laporan->eskalasi_tanggal)) : '-' }}
                    </div>
                </div>

                @if($laporan->eskalasi_status == 'menunggu')
                    <div class="row g-2">
                        <div class="col-6">
                            <form action="{{ route('lab.kepala_lab.eskalasi.reject', $laporan->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="ui-btn ui-btn-outline-danger w-100 justify-content-center">Tolak</button>
                            </form>
                        </div>
                        <div class="col-6">
                            <form action="{{ route('lab.kepala_lab.eskalasi.approve', $laporan->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="ui-btn ui-btn-primary w-100 justify-content-center">Setujui</button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="text-center p-2 rounded-4 bg-light text-muted small">
                        Laporan telah {{ $laporan->eskalasi_status == 'disetujui' ? 'disetujui' : 'ditolak' }}
                    </div>
                @endif
            </x-ui.card>
        </div>
    @empty
        <div class="col-12">
            <x-ui.empty-state icon="bi-check-circle-fill" title="Tidak ada eskalasi pending" description="Semua laporan eskalasi telah ditindaklanjuti." />
        </div>
    @endforelse
</div>
@endsection
