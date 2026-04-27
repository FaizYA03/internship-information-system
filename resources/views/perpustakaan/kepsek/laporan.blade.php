@extends('perpustakaan.layouts.main')

@section('content')
<div class="container py-5">
    <div class="row mb-5">
        <div class="col-12" data-aos="fade-up">
            <h2 class="fw-bold mb-1">Cetak Laporan Perpustakaan</h2>
            <p class="text-muted">Gunakan modul di bawah ini untuk mencetak arsip peminjaman ke dalam format dokumen yang Anda butuhkan (PDF atau Excel). Anda juga bisa mendapatkan data murni untuk satu rentang tanggal tertentu saja.</p>
        </div>
    </div>

    <div class="row g-4 justify-content-center">
        <div class="col-lg-8" data-aos="fade-up" data-aos-delay="100">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-header bg-white p-4 border-bottom">
                    <h5 class="fw-bold mb-0">Pengaturan Laporan Ekspor</h5>
                </div>
                
                <div class="card-body p-4">
                    <!-- Form Download PDF -->
                    <form action="{{ route('perpustakaan.peminjaman.export.pdf') }}" method="GET" class="mb-5 pb-4 border-bottom" target="_blank">
                        <div class="row align-items-end g-3">
                            <div class="col-12 mb-2">
                                <h6 class="fw-bold text-danger"><i class="bi bi-filetype-pdf me-2"></i>Cetak Dokumen PDF</h6>
                                <p class="text-muted small mb-0">Format tabel siap print, bagus untuk penyetoran laporan formal.</p>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label form-label-sm text-muted">Dari Tanggal (Opsional)</label>
                                <input type="date" class="form-control" name="dari_tanggal">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label form-label-sm text-muted">Sampai Tanggal (Opsional)</label>
                                <input type="date" class="form-control" name="sampai_tanggal">
                            </div>
                            <div class="col-md-4 mt-3 mt-md-0 d-flex justify-content-end">
                                <button type="submit" class="btn btn-outline-danger w-100 fw-bold">
                                    <i class="bi bi-download me-1"></i> Unduh PDF
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Form Download Excel -->
                    <form action="{{ route('perpustakaan.peminjaman.export.excel') }}" method="GET">
                        <div class="row align-items-end g-3">
                            <div class="col-12 mb-2">
                                <h6 class="fw-bold text-success"><i class="bi bi-filetype-xls me-2"></i>Export format Excel</h6>
                                <p class="text-muted small mb-0">Format spreadsheet mentah, sangat cocok untuk proses pendataan dan kalkulasi lanjut.</p>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label form-label-sm text-muted">Dari Tanggal (Opsional)</label>
                                <input type="date" class="form-control" name="dari_tanggal">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label form-label-sm text-muted">Sampai Tanggal (Opsional)</label>
                                <input type="date" class="form-control" name="sampai_tanggal">
                            </div>
                            <div class="col-md-4 mt-3 mt-md-0 d-flex justify-content-end">
                                <button type="submit" class="btn btn-outline-success w-100 fw-bold">
                                    <i class="bi bi-download me-1"></i> Export Excel
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
            <div class="card border-0 shadow-sm" style="border-radius: 12px; background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                <div class="card-body p-4 text-white">
                    <h5 class="fw-bold mb-4"><i class="bi bi-graph-up-arrow me-2 text-warning"></i> Analisa Kinerja {{ date('Y') }}</h5>
                    
                    <h6 class="mb-2 opacity-75 small text-uppercase fw-bold">Komparasi Semester</h6>
                    <div class="d-flex justify-content-between mb-1">
                        <span>Semester 1 (Jan-Jun)</span>
                        <span class="fw-bold fs-5">{{ $sem1 }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4 pb-3 border-bottom border-secondary border-opacity-50">
                        <span>Semester 2 (Jul-Des)</span>
                        <span class="fw-bold fs-5">{{ $sem2 }}</span>
                    </div>

                    <h6 class="mb-3 opacity-75 small text-uppercase fw-bold">Top 3 Kategori Terlaris</h6>
                    @if($kategoriLaris->count() > 0)
                        <ul class="list-unstyled mb-0">
                            @foreach($kategoriLaris as $index => $kategori)
                                <li class="mb-2 d-flex justify-content-between align-items-center">
                                    <span>{{ $index + 1 }}. {{ $kategori->nama_kategori }}</span>
                                    <span class="badge bg-white text-dark rounded-pill">{{ $kategori->total }} Peminjaman</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center opacity-50 py-2">
                            <small>Belum ada data</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
