@extends('magang.layouts.main')

@section('css')
<style>
    .form-section {
        background-color: #fff;
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .form-section-title {
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #e9ecef;
        font-weight: 600;
    }

    .guidelines {
        background-color: #f8f9fa;
        border-left: 4px solid var(--primary);
        border-radius: var(--radius);
        padding: 1rem;
        margin-bottom: 1rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="page-title">Edit Laporan</h1>
        <a href="{{ route('magang.siswa.laporan.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">

            {{-- 🔥 FORM EDIT --}}
            <form action="{{ route('magang.siswa.laporan.update', $laporan->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-section">
                    <h4 class="form-section-title">Informasi Dasar</h4>

                    <div class="mb-3">
                        <label class="form-label">Judul Laporan</label>
                        <input type="text"
                               name="judul"
                               class="form-control @error('judul') is-invalid @enderror"
                               value="{{ old('judul', $laporan->judul) }}" required>

                        @error('judul')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Hari Ke</label>
                            <input type="number"
                                   name="minggu_ke"
                                   class="form-control"
                                   value="{{ old('minggu_ke', $laporan->minggu_ke) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tanggal Kegiatan</label>
                            <input type="date"
                                   name="tanggal_mulai"
                                   class="form-control"
                                   value="{{ old('tanggal_mulai', $laporan->tanggal_mulai) }}" required>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h4 class="form-section-title">Isi Laporan</h4>

                    <textarea name="deskripsi"
                              class="form-control @error('deskripsi') is-invalid @enderror"
                              rows="10" required>{{ old('deskripsi', $laporan->deskripsi) }}</textarea>

                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-section">
                    <h4 class="form-section-title">Status Laporan</h4>

                    <div class="form-check">
                        <input type="radio" name="status" value="draft"
                            {{ old('status', $laporan->status) == 'draft' ? 'checked' : '' }}>
                        Draft
                    </div>

                    <div class="form-check mt-2">
                        <input type="radio" name="status" value="submitted"
                            {{ old('status', $laporan->status) == 'submitted' ? 'checked' : '' }}>
                        Submit
                    </div>
                </div>

                <div class="d-flex justify-content-end mb-4">
                    <a href="{{ route('magang.siswa.laporan.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Update Laporan</button>
                </div>

            </form>
        </div>

        {{-- SIDEBAR TETAP --}}
        <div class="col-lg-4">
            <div class="form-section">
                <h4 class="form-section-title">
                    <i class="bi bi-info-circle me-1"></i> Panduan
                </h4>

                <div class="guidelines">
                    <p>Perbaiki laporan sesuai revisi dari pembimbing.</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection