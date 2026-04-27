@extends('perpustakaan.layouts.main')

@section('content')
<div class="container py-5">
    <div class="row align-items-center mb-4">
        <div class="col-md-8">
            <h1 class="page-title mb-1 fw-bold text-dark">
                <i class="bi bi-sliders text-primary me-2"></i> {{ $title }}
            </h1>
            <p class="text-muted mb-0">Pusat Kendali Anggaran & Aturan Operasional Perpustakaan</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('kepsek.dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row g-4">
        <!-- Budget Control -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-2">
                    <h5 class="fw-bold text-success mb-0"><i class="bi bi-wallet2 me-2"></i> Kendali Anggaran ({{ \Carbon\Carbon::now()->year }})</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-6">
                            <div class="p-3 bg-light rounded text-center">
                                <small class="text-muted d-block text-uppercase fw-semibold mb-1">Total Pagu</small>
                                <h4 class="mb-0 fw-bold text-dark">Rp {{ number_format($budgetTahunIni->total_anggaran, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-success bg-opacity-10 rounded text-center">
                                <small class="text-success d-block text-uppercase fw-semibold mb-1">Sisa Anggaran</small>
                                <h4 class="mb-0 fw-bold text-success">Rp {{ number_format($budgetTahunIni->sisa_anggaran, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('perpustakaan.policy.budget.update') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Set Pagu Anggaran Tahunan</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted">Rp</span>
                                <input type="number" name="total_anggaran" class="form-control" value="{{ $budgetTahunIni->total_anggaran }}" required min="{{ $budgetTahunIni->terpakai }}">
                            </div>
                            <small class="text-muted mt-1 d-block"><i class="bi bi-info-circle"></i> Anggaran tidak dapat di-set lebih rendah dari total yang sudah terpakai (Rp {{ number_format($budgetTahunIni->terpakai, 0, ',', '.') }})</small>
                        </div>
                        <button type="submit" class="btn btn-success w-100 fw-bold">Update Anggaran</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Rules Control -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-2">
                    <h5 class="fw-bold text-primary mb-0"><i class="bi bi-shield-check me-2"></i> Kebijakan & Aturan Sistem</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('perpustakaan.policy.update') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Maksimal Lama Peminjaman (Hari)</label>
                            <input type="number" name="max_borrow_days" class="form-control" value="{{ $policies['max_borrow_days'] ?? 7 }}" required>
                            <small class="text-muted">Standar: 7 Hari</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Denda Keterlambatan per Minggu (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted">Rp</span>
                                <input type="number" name="fine_per_week" class="form-control" value="{{ $policies['fine_per_week'] ?? 5000 }}" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Prioritas Otomatis Rekomendasi Pengadaan</label>
                            <select name="procurement_urgency" class="form-select">
                                <option value="stok_habis" {{ ($policies['procurement_urgency'] ?? '') == 'stok_habis' ? 'selected' : '' }}>Fokus pada Buku Stok Habis (Urgent)</option>
                                <option value="tren_baca" {{ ($policies['procurement_urgency'] ?? '') == 'tren_baca' ? 'selected' : '' }}>Fokus pada Buku Terlaris (Trend)</option>
                                <option value="merata" {{ ($policies['procurement_urgency'] ?? '') == 'merata' ? 'selected' : '' }}>Sebaran Merata tiap Kategori / Jurusan</option>
                            </select>
                            <small class="text-muted"><i class="bi bi-info-circle"></i> Mempengaruhi bagaimana sistem memberikan saran buku saat admin membuat pengajuan.</small>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold" style="background-color: var(--primary); border:none;">Simpan Kebijakan</button>
                    </form>
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection
