@extends('perpustakaan.layouts.main')

@section('content')
<div class="container-fluid py-4">
    <div class="row align-items-end mb-4">
        <div class="col-md-7">
            <h2 class="fw-bold mb-1"><i class="bi bi-shield-check text-warning me-2"></i>{{ $header }}</h2>
            <p class="text-muted mb-0">Monitor buku-buku yang kemungkinan sudah usang atau tidak relevan lagi dengan tuntutan kurikulum.</p>
        </div>
    </div>

    <!-- Alert / Summary -->
    <div class="alert alert-warning d-flex align-items-center shadow-sm mb-4 border-0" role="alert">
        <i class="bi bi-exclamation-triangle-fill flex-shrink-0 fs-3 me-3 text-warning"></i>
        <div>
            <h5 class="alert-heading fw-bold mb-1">Perhatian Evaluasi Koleksi</h5>
            <p class="mb-0">Daftar buku di bawah ini adalah buku perpustakaan yang belum dimasukkan ke dalam pemetaan relasi komponen kurikulum manapun. Sebaiknya waka kurikulum mengevaluasi apakah buku-buku tersebut masih layak pakai atau masuk zona kadaluarsa kurikulum.</p>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0 pt-4 pb-2">
            <h5 class="card-title fw-bold mb-0">Daftar Indikasi Buku Tidak Relevan (Belum ter-Mapping)</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Judul Buku</th>
                            <th>Tahun Terbit</th>
                            <th>Kategori</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bukuTidakRelevan as $index => $buku)
                        <tr>
                            <td class="ps-4">{{ $bukuTidakRelevan->firstItem() + $index }}</td>
                            <td class="fw-medium">
                                {{ $buku->judul }}
                                <div class="text-muted small">{{ $buku->pengarang }}</div>
                            </td>
                            <td>
                                @if((date('Y') - $buku->tahun_terbit) > 5)
                                    <span class="text-danger fw-bold" title="Buku ini sudah cukup tua (> 5 Tahun)">{{ $buku->tahun_terbit }}</span>
                                @else
                                    {{ $buku->tahun_terbit }}
                                @endif
                            </td>
                            <td>{{ $buku->kategori ? $buku->kategori->nama_kategori : 'Tidak Ada' }}</td>
                            <td>
                                <a href="{{ route('perpustakaan.waka.mapping.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Petakan Kurikulum</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Selamat! Seluruh buku telah terpetakan dengan baik dalam kurikulum.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($bukuTidakRelevan->hasPages())
        <div class="card-footer bg-white border-0 pt-3">
            {{ $bukuTidakRelevan->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
