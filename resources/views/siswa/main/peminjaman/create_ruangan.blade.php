@extends('siswa.layouts.main')

@php
    $role_prefix = Auth::check() && Auth::user()->role == 'guru' ? 'guru' : 'siswa';
@endphp

@section('css')
<style>
    :root {
        --primary-color: #4361ee;
        --secondary-color: #3f37c9;
        --border-radius: 12px;
        --card-bg: #ffffff;
        --text-color: #2b2d42;
        --text-muted: #8d99ae;
    }

    /* Form Container */
    .form-card {
        background: var(--card-bg);
        border-radius: var(--border-radius);
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        padding: 2rem;
        border: 1px solid rgba(0,0,0,0.05);
    }

    .form-label {
        font-weight: 600;
        color: var(--text-color);
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }
    
    .form-control-custom, .form-select-custom {
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: all 0.2s;
        background-color: #f8fafc;
    }

    .form-control-custom:focus, .form-select-custom:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        background-color: white;
    }

    /* Info Panel */
    .info-panel {
        background: linear-gradient(145deg, #ffffff, #f8fafc);
        border-radius: var(--border-radius);
        padding: 1.5rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        border: 1px solid rgba(0,0,0,0.05);
        height: 100%;
    }

    .info-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        color: var(--primary-color);
        font-weight: 700;
    }

    .info-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .info-item {
        display: flex;
        gap: 0.75rem;
        margin-bottom: 1.25rem;
    }

    .info-item h6 {
        font-weight: 600;
        font-size: 0.95rem;
        margin-bottom: 0.25rem;
    }

    .info-item p {
        font-size: 0.85rem;
        color: var(--text-muted);
        margin: 0;
        line-height: 1.5;
    }
    
    .step-indicator {
        width: 24px;
        height: 24px;
        background-color: var(--primary-color);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .btn-primary-custom {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        border: none;
        padding: 0.8rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        width: 100%;
        transition: all 0.3s;
        box-shadow: 0 4px 6px rgba(67, 97, 238, 0.25);
    }
    
    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(67, 97, 238, 0.3);
        color: white;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title fs-3 fw-bold ps-4">Ajukan Peminjaman Ruangan</h1>
        </div>
        <a href="{{ route($role_prefix . '.labor.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="row g-4">
        <!-- Form Column -->
        <div class="col-lg-8">
            <div class="form-card">
                <form action="{{ route($role_prefix . '.peminjaman.ruangan.store') }}" method="POST" id="peminjamanRuanganForm">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="labor_id" class="form-label">Pilih Laboratorium <span class="text-danger">*</span></label>
                        <select class="form-select form-select-custom @error('labor_id') is-invalid @enderror" id="labor_id" name="labor_id" required>
                            <option value="" disabled selected>Pilih Laboratorium...</option>
                            @foreach($laborList as $lab)
                                <option value="{{ $lab->id }}" {{ (old('labor_id') == $lab->id || (isset($selectedLabor) && $selectedLabor->id == $lab->id)) ? 'selected' : '' }}>
                                    {{ $lab->nama_labor }} ({{ $lab->jenis_labor }})
                                </option>
                            @endforeach
                        </select>
                        @error('labor_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label for="tanggal" class="form-label">Tanggal Pemakaian <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" id="tanggal" class="form-control form-control-custom @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                            @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="jam_pinjam" class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                            <input type="time" name="jam_pinjam" id="jam_pinjam" class="form-control form-control-custom @error('jam_pinjam') is-invalid @enderror" value="{{ old('jam_pinjam') }}" required>
                            @error('jam_pinjam')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="jam_kembali" class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                            <input type="time" name="jam_kembali" id="jam_kembali" class="form-control form-control-custom @error('jam_kembali') is-invalid @enderror" value="{{ old('jam_kembali') }}" required>
                            @error('jam_kembali')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="kelas" class="form-label">Kelas <span class="text-danger">*</span></label>
                            <input type="text" name="kelas" id="kelas" class="form-control form-control-custom @error('kelas') is-invalid @enderror" value="{{ old('kelas') }}" placeholder="Contoh: XII RPL 1" required>
                            @error('kelas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="mata_pelajaran" class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                            <input type="text" name="mata_pelajaran" id="mata_pelajaran" class="form-control form-control-custom @error('mata_pelajaran') is-invalid @enderror" value="{{ old('mata_pelajaran') }}" placeholder="Contoh: Pemrograman Web" required>
                            @error('mata_pelajaran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="keperluan" class="form-label">Keperluan / Alasan <span class="text-danger">*</span></label>
                        <textarea class="form-control form-control-custom @error('keperluan') is-invalid @enderror" id="keperluan" name="keperluan" rows="4" placeholder="Contoh: Praktikum tambahan materi jaringan komputer untuk kelas XII..." required>{{ old('keperluan') }}</textarea>
                        @error('keperluan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary-custom py-3">
                        <i class="bi bi-send-fill me-2"></i> Kirim Permohonan Peminjaman Ruangan
                    </button>
                </form>
            </div>
        </div>

        <!-- Info Panel Column -->
        <div class="col-lg-4">
            <div class="info-panel">
                <div class="info-header">
                    <i class="bi bi-info-circle-fill fs-4"></i>
                    <h5 class="mb-0">Ketentuan Peminjaman</h5>
                </div>
                
                <div class="info-list">
                    <div class="info-item">
                        <div class="step-indicator">1</div>
                        <div>
                            <h6>Cek Jadwal</h6>
                            <p>Pastikan laboratorium tidak sedang digunakan oleh kelas lain pada waktu yang Anda pilih.</p>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="step-indicator">2</div>
                        <div>
                            <h6>Persetujuan</h6>
                            <p>Permohonan akan ditinjau oleh Kepala Laboratorium atau Admin terkait jadwal bentrok.</p>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="step-indicator">3</div>
                        <div>
                            <h6>Tanggung Jawab</h6>
                            <p>Peminjam wajib menjaga kebersihan dan ketertiban selama berada di dalam laboratorium.</p>
                        </div>
                    </div>

                    <hr class="my-4" style="border-top: 1px dashed #cbd5e1;">

                    <div class="alert alert-info border-0 bg-soft-primary mb-0">
                        <h6 class="fw-bold mb-2"><i class="bi bi-shield-check me-2"></i> Keamanan</h6>
                        <p class="small mb-0 text-muted">
                            Pastikan mematikan semua peralatan listrik dan mengunci pintu setelah selesai digunakan.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
