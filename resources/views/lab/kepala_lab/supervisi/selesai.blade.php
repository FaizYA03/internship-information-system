@extends('lab.layouts.unified', ['title' => 'Supervisi Perbaikan Selesai'])

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1 text-dark">Arsip Perbaikan Selesai</h4>
                <p class="text-muted small mb-0">Memantau riwayat pemeliharaan dan perbaikan aset laboratorium yang telah tuntas.</p>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-muted small fw-bold">ALAT / LABORATORIUM</th>
                        <th class="py-3 text-muted small fw-bold">PELAPOR</th>
                        <th class="py-3 text-muted small fw-bold">TINDAKAN PERBAIKAN</th>
                        <th class="py-3 text-muted small fw-bold">STATUS ESKALASI</th>
                        <th class="py-3 text-muted small fw-bold">TANGGAL SELESAI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporan as $l)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ $l->inventaris?->nama_inventaris ?? $l->nama_alat }}</div>
                                <div class="text-muted small">{{ $l->inventaris?->labor?->nama_labor ?? 'N/A' }}</div>
                            </td>
                            <td>
                                <div class="small text-dark">{{ $l->reporter_info }}</div>
                            </td>
                            <td>
                                <div class="small text-success">
                                    {{ $l->tindakan_perbaikan ?? 'Perbaikan standar' }}
                                </div>
                            </td>
                            <td>
                                @if($l->is_eskalasi)
                                    <x-ui.badge variant="success">DISETUJUI PIMPINAN</x-ui.badge>
                                @else
                                    <span class="text-muted small">Tanpa Eskalasi</span>
                                @endif
                            </td>
                            <td>
                                <div class="small text-muted">{{ $l->updated_at->format('d M Y') }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="text-muted">Tidak ada riwayat perbaikan selesai.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">
            {{ $laporan->links() }}
        </div>
    </div>
</div>
@endsection
