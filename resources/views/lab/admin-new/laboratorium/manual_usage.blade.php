@extends('lab.layouts.unified', ['title' => 'Input Penggunaan Manual'])

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-small">
        <li class="breadcrumb-item"><a href="{{ route('lab.admin_new.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('lab.admin_new.laboratorium.index') }}">Laboratorium</a></li>
        <li class="breadcrumb-item"><a href="{{ route('lab.admin_new.laboratorium.show', $labor->id) }}">{{ $labor->nama_labor }}</a></li>
        <li class="breadcrumb-item active">Input Penggunaan Manual</li>
    </ol>
</nav>
@endsection

@section('content')

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="fw-bold mb-0">Input Penggunaan Laboratorium Manual</h5>
                <p class="text-muted small mb-0">{{ $labor->nama_labor }}</p>
            </div>
            <div class="card-body p-4">
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form action="{{ route('lab.admin_new.laboratorium.manual_usage.store', $labor->id) }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Guru Penanggung Jawab <span class="text-danger">*</span></label>
                        <select name="guru_id" class="form-select @error('guru_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Guru --</option>
                            @foreach($gurus as $guru)
                                <option value="{{ $guru->id }}" {{ old('guru_id') == $guru->id ? 'selected' : '' }}>
                                    {{ $guru->nama }} ({{ $guru->nip }})
                                </option>
                            @endforeach
                        </select>
                        @error('guru_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kelas <span class="text-danger">*</span></label>
                        <input type="text" name="kelas" class="form-control @error('kelas') is-invalid @enderror" 
                               value="{{ old('kelas') }}" placeholder="Contoh: XII RPL 1" required>
                        @error('kelas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" 
                               value="{{ old('tanggal', date('Y-m-d')) }}" required>
                        @error('tanggal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jam Mulai <span class="text-danger">*</span></label>
                            <input type="time" name="jam_mulai" class="form-control @error('jam_mulai') is-invalid @enderror" 
                                   value="{{ old('jam_mulai') }}" required>
                            @error('jam_mulai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jam Selesai <span class="text-danger">*</span></label>
                            <input type="time" name="jam_selesai" class="form-control @error('jam_selesai') is-invalid @enderror" 
                                   value="{{ old('jam_selesai') }}" required>
                            @error('jam_selesai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" 
                                  rows="3" placeholder="Contoh: Praktikum jaringan komputer, materi routing">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Opsional - Tambahan informasi tentang kegiatan</small>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('lab.admin_new.laboratorium.show', $labor->id) }}" class="btn btn-light rounded-pill px-4">
                            <i class="bi bi-x-circle me-2"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                            <i class="bi bi-check-circle me-2"></i>Simpan Penggunaan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
