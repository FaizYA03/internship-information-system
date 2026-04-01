@extends('admin.layouts.main')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Daftar Pengajuan Pengadaan Alat</h5>
                <a href="{{ route('lab.kepala_lab.pengadaan.create') }}" class="btn btn-primary btn-sm rounded-pill px-4">Buat Pengajuan Baru</a>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0">Nama Barang</th>
                                <th class="border-0">Spek & Jumlah</th>
                                <th class="border-0">Estimasi Harga</th>
                                <th class="border-0">Urgensi</th>
                                <th class="border-0">Status</th>
                                <th class="border-0">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pengadaan as $item)
                                <tr>
                                    <td class="fw-bold text-primary">{{ $item->nama_barang }}</td>
                                    <td>
                                        <div class="small">{{ $item->spesifikasi }}</div>
                                        <div class="badge bg-light text-dark">{{ $item->jumlah }} Unit</div>
                                    </td>
                                    <td>Rp {{ number_format($item->estimasi_harga, 0, ',', '.') }}</td>
                                    <td>
                                        @if($item->urgensi == 'tinggi')
                                            <span class="badge bg-danger">Tinggi</span>
                                        @elseif($item->urgensi == 'sedang')
                                            <span class="badge bg-warning text-dark">Sedang</span>
                                        @else
                                            <span class="badge bg-info text-white">Rendah</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->status == 'pending')
                                            <span class="badge bg-soft-warning text-warning border border-warning-subtle px-3 rounded-pill">Menunggu Persetujuan</span>
                                        @elseif($item->status == 'approved')
                                            <span class="badge bg-soft-success text-success border border-success-subtle px-3 rounded-pill">Disetujui Kepsek</span>
                                        @else
                                            <span class="badge bg-soft-danger text-danger border border-danger-subtle px-3 rounded-pill">Ditolak</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-light rounded-circle" title="Detail"><i class="bi bi-eye"></i></button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">Belum ada riwayat pengajuan pengadaan.</td>
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
