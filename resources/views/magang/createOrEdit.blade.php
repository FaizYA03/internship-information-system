@extends('magang.layouts.main')

@section('css')
<style>
    .form-label {
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 8px;
    }
    
    .form-control {
        border-radius: 5px;
        padding: 10px 15px;
        border: 1px solid #ced4da;
        transition: all 0.3s;
    }
    
    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
    }
    
    .submit-btn {
        background-color: var(--primary-color);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .submit-btn:hover {
        background-color: var(--secondary-color);
    }
    
    .card {
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .page-title {
        color: var(--dark-color);
        margin-bottom: 20px;
    }
</style>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="mb-0">{{ isset($magang) ? 'Edit Data Magang' : 'Tambah Data Magang' }}</h4>
    </div>
    <div class="card-body">
        <form action="{{ isset($magang) ? route('magang.magang.update', $magang->id) : route('magang.magang.store') }}" method="POST">
            @csrf
            @if(isset($magang))
                @method('PUT') 
            @endif
        
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Siswa</label>
                <input 
                type="text" 
                name="nama" 
                id="nama" 
                class="form-control @error('nama') is-invalid @enderror" 
                value="{{ old('nama', $magang->nama ?? '') }}" 
                required>
                @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        
            <div class="mb-3">
                <label for="perusahaan_id" class="form-label">Perusahaan</label>
                <select 
                name="perusahaan_id" 
                id="perusahaan_id" 
                class="form-control @error('perusahaan_id') is-invalid @enderror" 
                required>
                    <option value="" disabled selected>-- Pilih Perusahaan --</option>
                    @foreach($perusahaan as $data)
                        <option value="{{ $data->id }}" {{ old('perusahaan_id', $magang->perusahaan_id ?? '') == $data->id ? 'selected' : '' }}>
                            {{ $data->nama_perusahaan }}
                        </option>
                    @endforeach
                </select>
                @error('perusahaan_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        
            <div class="mb-3">
                <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                <input 
                type="date" 
                name="tanggal_mulai" 
                id="tanggal_mulai" 
                class="form-control @error('tanggal_mulai') is-invalid @enderror" 
                value="{{ old('tanggal_mulai', $magang->tanggal_mulai ?? '') }}" 
                required>
                @error('tanggal_mulai')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        
            <div class="mb-3">
                <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                <input 
                type="date" 
                name="tanggal_selesai" 
                id="tanggal_selesai" 
                class="form-control @error('tanggal_selesai') is-invalid @enderror" 
                value="{{ old('tanggal_selesai', $magang->tanggal_selesai ?? '') }}" 
                required>
                @error('tanggal_selesai')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            @if(isset($magang))
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select 
                    name="status" 
                    id="status" 
                    class="form-control @error('status') is-invalid @enderror" 
                    required>
                        <option value="" disabled selected>-- Pilih Status --</option>
                        @foreach($status as $data)
                            <option value="{{ $data }}" {{ old('status', $magang->status ?? '') == $data ? 'selected' : '' }}>
                                {{ $data }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <hr class="my-4">
                
                <h5 class="text-secondary mb-3"><i class="bi bi-info-circle"></i> Informasi Lanjutan Magang</h5>
                
                <div class="mb-3">
                    <label for="tugas_singkat" class="form-label">Tugas Pekerjaan Singkat (Jobdesc)</label>
                    <textarea 
                    name="tugas_singkat" 
                    id="tugas_singkat" 
                    rows="4" 
                    class="form-control @error('tugas_singkat') is-invalid @enderror" 
                    placeholder="Contoh: Fokus pada pengembangan front-end menggunakan framework modern, membantu desain UI/UX, dsb.">{{ old('tugas_singkat', $magang->tugas_singkat ?? '') }}</textarea>
                    <small class="text-muted">Isi dengan deskripsi tugas singkat yang akan/sedang dikerjakan oleh peserta magang.</small>
                    @error('tugas_singkat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Guru Pembimbing Sekolah</label>
                        <input type="text" class="form-control bg-light" readonly value="{{ optional(optional($magang->pembimbing)->guru)->nama ?? 'Belum ada pembimbing (Kelola di menu Kelola Pembimbing)' }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Supervisor / Pembimbing Lapangan Mitra</label>
                        <input type="text" class="form-control bg-light" readonly value="{{ optional($magang->mitraSupervisor)->nama_lengkap ?? (optional($magang->wakilPerusahaan)->nama ? optional($magang->wakilPerusahaan)->nama . ' (Mitra)' : 'Belum terhubung ke supervisor (Ditentukan di sisi perusahaan)') }}">
                    </div>
                </div>
            @endif

            <div class="d-flex justify-content-end">
                <a href="{{ route('magang.magang.index') }}" class="btn btn-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-primary px-4">{{ isset($magang) ? 'Update' : 'Simpan' }}</button>
            </div>
        </form>
    </div>
</div>
@endsection