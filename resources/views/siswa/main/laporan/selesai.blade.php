@extends('lab.layouts.unified', ['title' => 'Riwayat Laporan Saya'])

@section('content')
<div class="row mb-4">
    <div class="col-12 text-center text-md-start">
        <h4 class="fw-bold mb-1 text-dark">Perbaikan Selesai</h4>
        <p class="text-muted small mb-0">Laporan kerusakan yang telah ditindaklanjuti dan diperbaiki oleh teknisi.</p>
    </div>
</div>

<div class="row g-4">
    @forelse($laporan as $l)
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-4 border-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-<ctrl94> margin-bottom-3">
                        <h6 class="fw-bold text-dark mb-0">{{ $l->inventaris?->nama_inventaris ?? $l->nama_alat }}</h6>
                        <x-ui.badge variant="success">FIXED</x-ui.badge>
                    </div>
                    <p class="text-muted small mb-2"><i class="bi bi-calendar-check me-1"></i> Selesai pada {{ $l->updated_at->format('d M Y') }}</p>
                    <p class="text-muted small mb-3"><i class="bi bi-person me-1"></i> Pelapor: {{ $l->nama_pelapor ?? 'Siswa' }}</p>
                    
                    <div class="bg-light rounded-3 p-3 mb-3">
                        <p class="text-dark small mb-1"><strong>Masalah yang dilaporkan:</strong></p>
                        <p class="text-muted small mb-0">{{ $l->deskripsi_kerusakan }}</p>
                    </div>

                    @if($l->tindakan_perbaikan)
                        <div class="mb-0">
                            <p class="text-dark small mb-1"><strong>Hasil Perbaikan:</strong></p>
                            <p class="text-success small mb-0 italic">"{{ $l->tindakan_perbaikan }}"</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <x-ui.empty-state icon="bi-check2-all" title="Belum Ada Perbaikan Selesai" description="Laporan akan muncul di sini setelah statusnya ditandai selesai oleh admin." />
        </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $laporan->links() }}
</div>
@endsection

@section('css')
<style>
    .italic { font-style: italic; }
</style>
@endsection
