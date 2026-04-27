@extends('perpustakaan.layouts.main')

@section('content')
<div class="container-fluid py-4">
    <div class="row align-items-end mb-4">
        <div class="col-md-7">
            <h2 class="fw-bold mb-1"><i class="bi bi-diagram-3 text-primary me-2"></i>{{ $header }}</h2>
            <p class="text-muted mb-0">Hubungkan buku-buku di perpustakaan dengan mata pelajaran dan jurusan untuk analisis kurikulum.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible auto-dismiss fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4 mb-4">
        <!-- Mapping Form -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="fw-bold"><i class="bi bi-plus-circle-dotted text-success me-2"></i>Buat Relasi Baru</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('perpustakaan.waka.mapping.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-medium">Pilih Buku (Belum Mapped)</label>
                            <select name="buku_id" class="form-select select2" required>
                                <option value="">-- Cari Judul Buku --</option>
                                @foreach($bukuBelumMapping as $buku)
                                    <option value="{{ $buku->id }}">{{ \Illuminate\Support\Str::limit($buku->judul, 40) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium">Mata Pelajaran (Opsional)</label>
                            <select name="mapel_id" class="form-select select2">
                                <option value="">-- Pilih Mata Pelajaran --</option>
                                @foreach($mapels as $mapel)
                                    <option value="{{ $mapel->id }}">{{ $mapel->nama_mapel }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium">Jurusan Khusus (Opsional)</label>
                            <select name="jurusan_id" class="form-select">
                                <option value="">-- Semua Jurusan / Umum --</option>
                                @foreach($jurusans as $jurusan)
                                    <option value="{{ $jurusan->id }}">{{ $jurusan->nama_jurusan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-medium">Kompetensi Dasar Terkait</label>
                            <input type="text" class="form-control" name="kompetensi_dasar" placeholder="Cth: Mengingat sejarah (Bab 3)">
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary d-flex align-items-center justify-content-center">
                                <i class="bi bi-link-45deg fs-5 me-1"></i> Tautkan ke Kurikulum
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Mapping List -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Judul Buku Ter-Map</th>
                                    <th>Kurikulum / Jurusan</th>
                                    <th>Kompetensi</th>
                                    <th>Dibuat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bukuMaps as $map)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold">{{ $map->buku->judul }}</div>
                                    </td>
                                    <td>
                                        @if($map->mapel)
                                            <span class="badge bg-primary mb-1">{{ $map->mapel->nama_mapel }}</span><br>
                                        @endif
                                        @if($map->jurusan)
                                            <span class="badge bg-warning text-dark">{{ $map->jurusan->nama_jurusan }}</span>
                                        @else
                                            <span class="badge bg-secondary">Umum</span>
                                        @endif
                                    </td>
                                    <td class="text-muted small">
                                        {{ $map->kompetensi_dasar ?: '-' }}
                                    </td>
                                    <td>{{ $map->created_at->format('d M y') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">Buku koleksi belum dihubungkan dengan kurikulum apa pun.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Pagination -->
                @if($bukuMaps->hasPages())
                <div class="card-footer bg-white mt-1 border-0 pb-0">
                    {{ $bukuMaps->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
