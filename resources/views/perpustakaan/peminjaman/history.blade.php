@extends('perpustakaan.layouts.main')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="page-title">Riwayat Peminjaman Buku</h1>
            <p class="text-muted mb-4">Daftar buku yang pernah Anda pinjam dari perpustakaan SMK Negeri 5 Padang</p>

            @if(isset($hasUnpaidFine) && $hasUnpaidFine)
            <div class="alert alert-danger mb-4 shadow-sm" role="alert" style="border-left: 5px solid #dc3545;">
                <h4 class="alert-heading"><i class="bi bi-exclamation-triangle-fill"></i> Peringatan Penangguhan Akun!</h4>
                <p>Sistem mendeteksi Anda memiliki denda keterlambatan pengembalian buku yang belum dilunasi. Selama denda belum dibayarkan, hak Anda untuk meminjam buku baru akan ditangguhkan sementara.</p>
                <hr>
                <p class="mb-0">Silakan hubungi Admin Perpustakaan untuk menyelesaikan pembayaran denda Anda.</p>
            </div>
            @endif

            <div class="table-container">
                @if($peminjaman->count() > 0)
                <div class="table-responsive">
                    <table class="table" id="data-table">
                        <thead>
                            <tr>
                                <th class="d-none d-md-table-cell">No</th>
                                <th>Buku</th>
                                <th>Tanggal Pinjam</th>
                                <th>Status</th>
                                <th class="d-none d-md-table-cell">Pengembalian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($peminjaman as $index => $p)
                            <tr>
                                <td class="d-none d-md-table-cell" data-label="No">{{ $index + 1 }}</td>
                                <td data-label="Buku">{{ $p->buku->judul }}</td>
                                <td data-label="Tanggal Pinjam">{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d/m/Y') }}</td>
                                <td data-label="Status">
                                    @if ($p->status == 'Menunggu')
                                        <span class="status-badge status-pending">Menunggu</span>
                                    @elseif ($p->status == 'Ditolak')
                                        <span class="status-badge status-rejected">Ditolak</span>
                                    @elseif ($p->status == 'Disetujui')
                                        <span class="status-badge status-approved">Disetujui</span>
                                    @elseif ($p->status == 'Dikembalikan')
                                        <span class="status-badge status-returned">Dikembalikan</span>
                                    @elseif ($p->status == 'Terlambat')
                                        <span class="badge bg-danger">Terlambat</span>
                                    @endif
                                </td>
                                <td class="d-none d-md-table-cell" data-label="Pengembalian">
                                    @if(in_array($p->status, ['Dikembalikan', 'Terlambat']) && $p->tanggal_dikembalikan)
                                        <b class="text-success">{{ \Carbon\Carbon::parse($p->tanggal_dikembalikan)->format('d/m/Y') }} (Aktual)</b><br>
                                        <small class="text-muted">Target: {{ \Carbon\Carbon::parse($p->tanggal_kembali)->format('d/m/Y') }}</small>
                                        @if($p->denda > 0)
                                            <br><span class="fw-bold {{ $p->denda_dibayar ? 'text-success' : 'text-danger' }}">
                                                <i class="bi bi-cash-coin"></i> Denda: Rp {{ number_format($p->denda, 0, ',', '.') }} 
                                                ({{ $p->denda_dibayar ? 'Lunas' : 'Belum Dibayar' }})
                                            </span>
                                        @endif
                                    @else
                                        @if($p->tanggal_kembali)
                                            {{ \Carbon\Carbon::parse($p->tanggal_kembali)->format('d/m/Y') }} <small class="text-muted">(Target)</small>
                                        @else
                                            -
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="empty-state">
                    <i class="bi bi-journal-x"></i>
                    <p>Anda belum memiliki riwayat peminjaman buku</p>
                    <a href="{{ route('perpustakaan.peminjaman.create') }}" class="btn-secondary-app mt-3">
                        <i class="bi bi-journal-arrow-up"></i> Pinjam Buku Sekarang
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
