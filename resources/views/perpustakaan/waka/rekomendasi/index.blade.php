@extends('perpustakaan.layouts.main')

@section('content')
<div class="container-fluid py-4">
    <div class="row pe-0">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-1"><i class="bi bi-bookmark-plus text-primary me-2"></i>{{ $header }}</h2>
                    <p class="text-muted mb-0">Daftar buku yang diajukan oleh Waka Kurikulum untuk melengkapi kebutuhan belajar mengajar.</p>
                </div>
                <a href="{{ route('perpustakaan.waka.rekomendasi.create') }}" class="btn btn-primary shadow-sm">
                    <i class="bi bi-plus-lg me-2"></i>Buat Rekomendasi
                </a>
            </div>

            @if(session('success'))
            <div class="alert alert-success alert-dismissible auto-dismiss fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Judul Buku</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Jurusan</th>
                                    <th>Prioritas</th>
                                    <th>Status</th>
                                    <th>Tanggal Pengajuan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rekomendasi as $req)
                                <tr>
                                    <td class="ps-4 fw-medium">
                                        {{ $req->judul_buku }}
                                        <div class="text-muted small">{{ $req->pengarang ?? 'Tidak diketahui' }} | {{ $req->penerbit ?? 'Tidak diketahui' }}</div>
                                    </td>
                                    <td>{{ $req->mapel ? $req->mapel->nama_mapel : '-' }}</td>
                                    <td>{{ $req->jurusan ? $req->jurusan->nama_jurusan : 'Umum' }}</td>
                                    <td>
                                        @if($req->prioritas == 'High')
                                            <span class="badge bg-danger">Tinggi</span>
                                        @elseif($req->prioritas == 'Medium')
                                            <span class="badge bg-warning text-dark">Sedang</span>
                                        @else
                                            <span class="badge bg-info text-dark">Rendah</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($req->status == 'Draft')
                                            <span class="badge bg-secondary"><i class="bi bi-hourglass me-1"></i>Draft Admin</span>
                                        @elseif($req->status == 'Diproses')
                                            <span class="badge bg-primary"><i class="bi bi-gear-fill me-1"></i>Diproses Kepsek</span>
                                        @elseif($req->status == 'Disetujui Kepala Sekolah')
                                            <span class="badge bg-success"><i class="bi bi-check-all me-1"></i>Disetujui</span>
                                        @elseif($req->status == 'Ditolak')
                                            <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Ditolak</span>
                                        @elseif($req->status == 'Tersedia')
                                            <span class="badge bg-info text-dark"><i class="bi bi-check-circle me-1"></i>Sudah Tersedia</span>
                                        @endif
                                    </td>
                                    <td>{{ $req->created_at->format('d M Y') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Belum ada rekomendasi buku yang diajukan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
