@extends('lab.layouts.unified', ['title' => 'Otorisasi Peminjaman Eksternal'])

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="fw-bold mb-0">Otorisasi Peminjaman Pihak Luar (Eksternal)</h5>
                <p class="small text-muted mb-0">Halaman ini menampilkan pengajuan yang sudah mendapatkan rekomendasi dari Kepala Lab.</p>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger border-0 shadow-sm mb-4">{{ session('error') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0">Pihak/Instansi</th>
                                <th class="border-0">Alat & Jumlah</th>
                                <th class="border-0">Keperluan</th>
                                <th class="border-0">Rekomendasi Kalab</th>
                                <th class="border-0 text-center">Keputusan Akhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requests as $item)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $item->nama_peminjam }}</div>
                                        <div class="small text-muted">{{ $item->instansi }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $item->inventaris->nama_inventaris ?? 'N/A' }}</div>
                                        <div class="small text-muted">{{ $item->jumlah }} Unit (Stok Saat Ini: {{ $item->inventaris->jumlah ?? 0 }})</div>
                                    </td>
                                    <td><small>{{ $item->tujuan }}</small></td>
                                    <td>
                                        <span class="badge bg-soft-success text-success border border-success-subtle">Direkomendasikan</span>
                                        <div class="small text-muted mt-1">{{ \Carbon\Carbon::parse($item->rekomendasi_kalab_at)->isoFormat('D MMM Y') }}</div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex gap-2 justify-content-center">
                                            <form action="{{ route('lab.kepala_sekolah.approval.eksternal.approve', $item->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm rounded-pill px-3">Setujui</button>
                                            </form>
                                            <form action="{{ route('lab.kepala_sekolah.approval.eksternal.reject', $item->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">Tolak</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">Tidak ada pengajuan eksternal yang menunggu persetujuan Anda.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
