@extends('perpustakaan.layouts.main')

@section('content')
<div class="container-fluid py-4">
    <div class="row align-items-end mb-4">
        <div class="col-md-7">
            <h2 class="fw-bold mb-1"><i class="bi bi-graph-up text-primary me-2"></i>{{ $header }}</h2>
            <p class="text-muted mb-0">Analisis aktivitas peminjaman buku berdasarkan peranan akademik dan program keahlian.</p>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Teacher vs Student -->
        <div class="col-md-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="fw-bold">Rasio Aktivitas (Berdasarkan Role)</h5>
                </div>
                <div class="card-body">
                    <div class="row mt-2">
                        <div class="col-6 text-center border-end">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                                <i class="bi bi-person-video3 fs-1 text-primary"></i>
                            </div>
                            <h2 class="fw-bold mb-0">{{ $peminjamanOlehGuru }}</h2>
                            <p class="text-muted small text-uppercase fw-semibold tracking-wide">Guru</p>
                        </div>
                        <div class="col-6 text-center">
                            <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                                <i class="bi bi-people fs-1 text-success"></i>
                            </div>
                            <h2 class="fw-bold mb-0">{{ $peminjamanOlehSiswa }}</h2>
                            <p class="text-muted small text-uppercase fw-semibold tracking-wide">Siswa</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="progress" style="height: 10px;">
                            @php
                                $totalRole = $peminjamanOlehGuru + $peminjamanOlehSiswa;
                                $pctGuru = $totalRole > 0 ? ($peminjamanOlehGuru / $totalRole) * 100 : 0;
                                $pctSiswa = $totalRole > 0 ? ($peminjamanOlehSiswa / $totalRole) * 100 : 0;
                            @endphp
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $pctGuru }}%"></div>
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $pctSiswa }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Per Jurusan -->
        <div class="col-md-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h5 class="fw-bold">Peminjaman Buku Siswa per Jurusan</h5>
                </div>
                <div class="card-body">
                     <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Jurusan</th>
                                    <th class="text-center">Total Transaksi Pinjam</th>
                                    <th>Status Literasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($aktivitasPerJurusan as $aktivitas)
                                <tr>
                                    <td class="fw-medium">{{ $aktivitas->jurusan ?: 'Tidak Diketahui' }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary rounded-pill px-3 fs-6">{{ $aktivitas->total }}</span>
                                    </td>
                                    <td>
                                        @if($aktivitas->total < 5)
                                            <span class="text-danger fw-semibold">Rendah</span>
                                        @elseif($aktivitas->total < 20)
                                            <span class="text-warning fw-semibold">Sedang</span>
                                        @else
                                            <span class="text-success fw-semibold">Aktif</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">Belum ada data aktivitas peminjaman.</td>
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
