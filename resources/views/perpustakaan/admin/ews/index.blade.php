@extends('perpustakaan.layouts.main')

@section('content')
<div class="container-fluid py-4" style="background-color: #f8f9fa;">
    <!-- BEGIN: Header Halaman -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold text-dark mb-1">Dashboard Admin / Pustakawan</h2>
            <p class="text-muted">Panel eksekusi instruksi dari Kepala Sekolah (Early Warning System)</p>
        </div>
    </div>
    <!-- END: Header Halaman -->

    <!-- Alert Success -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- BEGIN: Card Container -->
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-2">
            <h5 class="fw-semibold text-primary m-0"><i class="bi bi-list-task me-2"></i> 👉 Daftar Instruksi Kepala Sekolah (EWS)</h5>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="text-center" style="width: 50px;">No</th>
                            <th scope="col">Judul Buku</th>
                            <th scope="col">Tindakan Diminta</th>
                            <th scope="col">Tanggal Instruksi</th>
                            <th scope="col" class="text-center" style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($instruksis as $index => $item)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="fw-bold">{{ $item->buku ? $item->buku->judul : 'Buku Dihapus/Tidak Ditemukan' }}</td>
                            <td>
                                @if(strtolower($item->jenis_tindakan) == 'promosi')
                                    <span class="badge bg-success">Promosi</span>
                                @elseif(strtolower($item->jenis_tindakan) == 'pemutihan')
                                    <span class="badge bg-danger">Pemutihan</span>
                                @else
                                    <span class="badge bg-warning text-dark">{{ $item->jenis_tindakan }}</span>
                                @endif
                                
                                @if($item->status == 'selesai')
                                    <span class="badge bg-secondary ms-1"><i class="bi bi-check-all"></i> Selesai</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}</td>
                            <td class="text-center">
                                @if($item->status == 'belum_diproses' || $item->status == 'pending')
                                <form method="POST" action="{{ route('perpustakaan.admin.ews.resolve') }}" style="display:inline-block; width: 100%;">
                                    @csrf
                                    <input type="hidden" name="id_instruksi" value="{{ $item->id_instruksi }}">
                                    <button type="submit" class="btn btn-success btn-sm w-100 fw-semibold" onclick="return confirm('Tandai eksekusi instruksi ini selesai?')">
                                        ✔ Tandai Selesai
                                    </button>
                                </form>
                                @else
                                    <button class="btn btn-outline-secondary btn-sm w-100 fw-semibold" disabled>
                                        Diselesaikan
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Tidak ada instruksi EWS dari Kepala Sekolah saat ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
