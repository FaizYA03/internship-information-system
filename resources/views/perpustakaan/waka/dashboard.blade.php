@extends('perpustakaan.layouts.main')

@section('content')
<div class="container-fluid py-4" style="background-color: #f8f9fa;">
    <!-- BEGIN: Header Title -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h2 class="fw-bold mb-1"><i class="bi bi-speedometer2 text-primary me-2"></i>Dashboard Waka Kurikulum</h2>
            <p class="text-muted mb-0">Rangkuman data sinkronisasi koleksi perpustakaan dengan kurikulum pembelajaran.</p>
        </div>
    </div>

    <!-- Alert Success/Error -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 border-start border-success border-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 border-start border-danger border-4" role="alert">
            <i class="bi bi-exclamation-octagon-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- BEGIN: EWS Instruction Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm border-start border-warning border-4">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="fw-bold text-dark mb-1"><i class="bi bi-shield-exclamation text-warning me-2"></i>Instruksi Evaluasi Kurikulum (EWS)</h5>
                    <p class="text-muted small">Tinjau buku-buku pasif untuk menentukan relevansinya dengan kurikulum saat ini.</p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle table-bordered">
                            <thead class="table-light text-secondary small text-uppercase">
                                <tr>
                                    <th class="text-center" style="width: 50px;">No</th>
                                    <th>Judul Buku</th>
                                    <th>Kategori / Jurusan</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center" style="width: 300px;">Keputusan Relevansi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($instruksiEws as $index => $ews)
                                <tr @if($ews->status == 'belum_diproses' || $ews->status == 'pending') class="table-warning table-opacity-10" @endif>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $ews->buku ? $ews->buku->judul : 'Buku Tidak Ditemukan' }}</div>
                                        <small class="text-muted">Masuk: {{ \Carbon\Carbon::parse($ews->created_at)->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $ews->buku && $ews->buku->category ? $ews->buku->category->nama_kategori : 'Umum' }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($ews->status == 'belum_diproses' || $ews->status == 'pending')
                                            <span class="badge bg-warning text-dark"><i class="bi bi-clock-history me-1"></i> Pending</span>
                                        @else
                                            @if($ews->hasil_evaluasi == 'relevan')
                                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Relevan</span>
                                            @else
                                                <span class="badge bg-danger"><i class="bi bi-trash me-1"></i> Usang</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($ews->status == 'belum_diproses' || $ews->status == 'pending')
                                        <form method="POST" action="{{ route('perpustakaan.waka.ews.proses') }}" class="d-flex justify-content-center gap-2">
                                            @csrf
                                            <input type="hidden" name="id_instruksi" value="{{ $ews->id_instruksi }}">
                                            
                                            <button type="submit" name="hasil" value="relevan" class="btn btn-success btn-sm fw-bold shadow-sm px-3">
                                                <i class="bi bi-check2-circle me-1"></i> Masih Relevan
                                            </button>
                                            
                                            <button type="submit" name="hasil" value="usang" class="btn btn-danger btn-sm fw-bold shadow-sm px-3" onclick="return confirm('Buku akan diteruskan ke Admin untuk pemutihan. Lanjutkan?')">
                                                <i class="bi bi-x-circle me-1"></i> Tidak Relevan
                                            </button>
                                        </form>
                                        @else
                                            <button class="btn btn-outline-secondary btn-sm disabled w-75">Sudah Dievaluasi</button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                        Belum ada instruksi evaluasi kurikulum EWS masuk.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BEGIN: Statistik Row -->
    <div class="row g-4">
        <!-- Statistik Kategori -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="card-title fw-bold m-0">Kategori Kurikulum</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @foreach($kategoriStats as $stat)
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent">
                            <span class="text-capitalize">{{ $stat->jenis_kategori }}</span>
                            <span class="badge bg-primary rounded-pill">{{ $stat->total_buku }} Buku</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <!-- Pemetaan per Jurusan -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4 d-flex justify-content-between align-items-center">
                    <h5 class="card-title fw-bold m-0">Pemetaan per Jurusan</h5>
                    <a href="{{ route('perpustakaan.waka.mapping.index') }}" class="btn btn-sm btn-outline-primary fw-bold">Atur Mapping</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light small">
                                <tr>
                                    <th>Nama Jurusan</th>
                                    <th class="text-center">Jumlah Buku</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bukuPerJurusan as $bj)
                                <tr>
                                    <td class="fw-medium">{{ $bj->nama_jurusan }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-info-subtle text-info border border-info border-opacity-25 rounded-pill px-3">{{ $bj->total_buku }}</span>
                                    </td>
                                    <td>
                                        @if($bj->total_buku < 5)
                                            <span class="text-danger small fw-bold"><i class="bi bi-exclamation-triangle-fill me-1"></i>Kurang</span>
                                        @elseif($bj->total_buku < 15)
                                            <span class="text-warning small fw-bold"><i class="bi bi-info-circle-fill me-1"></i>Cukup</span>
                                        @else
                                            <span class="text-success small fw-bold"><i class="bi bi-check-circle-fill me-1"></i>Sangat Baik</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .table-opacity-10 {
        --bs-table-bg: rgba(255, 193, 7, 0.05);
    }
    .badge {
        letter-spacing: 0.3px;
        padding: 0.5em 0.8em;
    }
</style>
@endsection
