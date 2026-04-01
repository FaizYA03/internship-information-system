@extends('lab.layouts.unified', ['title' => 'Peminjaman Eksternal'])

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1 text-dark">Peminjaman Pihak Luar</h4>
                <p class="text-muted small mb-0">Kelola peminjaman alat oleh instansi atau perorangan eksternal.</p>
            </div>
            <a href="{{ route('lab.admin_new.eksternal.create') }}" class="ui-btn ui-btn-primary"><i class="bi bi-plus-lg"></i> Peminjaman Baru</a>
        </div>
    </div>
</div>

<x-ui.card class="border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="bg-light">
                <tr>
                    <th class="border-0 small fw-bold text-muted">PEMINJAM</th>
                    <th class="border-0 small fw-bold text-muted">BARANG</th>
                    <th class="border-0 small fw-bold text-muted">TANGGAL PINJAM</th>
                    <th class="border-0 small fw-bold text-muted">STATUS</th>
                    <th class="border-0 small fw-bold text-muted">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjaman as $p)
                    <tr>
                        <td>
                            <div class="fw-bold text-dark">{{ $p->nama_peminjam }}</div>
                            <small class="text-muted">{{ $p->instansi }}</small>
                        </td>
                        <td>{{ $p->inventaris->nama_inventaris ?? 'N/A' }}</td>
                        <td class="small">{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d M Y') }}</td>
                        <td><x-ui.badge variant="{{ $p->status == 'aktif' ? 'warning' : 'success' }}">{{ strtoupper($p->status) }}</x-ui.badge></td>
                        <td>
                            <button class="btn btn-light btn-sm rounded-circle"><i class="bi bi-eye"></i></button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted small">Tidak ada data peminjaman eksternal.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-ui.card>
@endsection
