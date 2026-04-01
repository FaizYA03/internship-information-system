@extends('lab.layouts.unified', ['title' => 'Riwayat Perbaikan Selesai'])

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1 text-dark">Riwayat Perbaikan Selesai</h4>
                <p class="text-muted small mb-0">Daftar aset yang telah berhasil diperbaiki dan dikembalikan ke kondisi baik.</p>
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
                        <th class="py-3 text-muted small fw-bold">DESKRIPSI KERUSAKAN</th>
                        <th class="py-3 text-muted small fw-bold">TINDAKAN PERBAIKAN</th>
                        <th class="py-3 text-muted small fw-bold">TANGGAL SELESAI</th>
                        <th class="pe-4 py-3 text-muted small fw-bold text-center">STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporanSelesai as $laporan)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ $laporan->inventaris?->nama_inventaris ?? $laporan->nama_alat }}</div>
                                <div class="text-muted small">{{ $laporan->inventaris?->labor?->nama_labor ?? 'N/A' }}</div>
                            </td>
                            <td>
                                <div class="small text-dark">{{ $laporan->reporter_info }}</div>
                            </td>
                            <td>
                                <div class="small text-muted text-truncate" style="max-width: 200px;" title="{{ $laporan->deskripsi_kerusakan }}">
                                    {{ $laporan->deskripsi_kerusakan }}
                                </div>
                            </td>
                            <td>
                                <div class="small text-success fw-medium italic">
                                    <i class="bi bi-check2-circle me-1"></i> {{ $laporan->tindakan_perbaikan ?? 'Perbaikan standar' }}
                                </div>
                            </td>
                            <td>
                                <div class="small text-muted">{{ $laporan->updated_at->format('d M Y') }}</div>
                            </td>
                            <td class="pe-4 text-center">
                                <x-ui.badge variant="success">SELESAI</x-ui.badge>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <x-ui.empty-state icon="bi-check-all" title="Belum ada riwayat" description="Belum ada laporan kerusakan yang diselesaikan melalui sistem ini." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    .italic { font-style: italic; }
</style>
@endsection
