@extends('magang.layouts.main')

@section('content')
<style>
    .edit-page {
        min-height: 100vh;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        padding: 2rem 0;
    }

    .edit-header {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        border-radius: 20px;
        padding: 2.5rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .edit-header h1 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .edit-header p {
        margin: 0.5rem 0 0;
        color: rgba(255,255,255,0.8);
    }

    .header-icon {
        width: 3.5rem;
        height: 3.5rem;
        background: rgba(255,255,255,0.15);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .info-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        border: 1px solid rgba(99,102,241,0.1);
        transition: all 0.3s ease;
    }

    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }

    .info-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        color: #64748b;
        font-weight: 600;
        margin-bottom: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .info-label i {
        font-size: 1rem;
        color: #6366f1;
    }

    .info-value {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
    }

    .form-container {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        border: 1px solid rgba(99,102,241,0.1);
    }

    .form-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .form-title i {
        color: #6366f1;
        font-size: 1.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.75rem;
        font-size: 0.95rem;
    }

    .form-input,
    .form-select {
        width: 100%;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.875rem 1.25rem;
        font-size: 1rem;
        font-family: inherit;
        background: white;
        transition: all 0.3s ease;
        color: #1e293b;
    }

    .form-input:focus,
    .form-select:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99,102,241,0.1);
        background: #f8fafc;
    }

    .form-input::placeholder {
        color: #94a3b8;
    }

    .error-message {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #dc2626;
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }

    .error-message i {
        font-size: 1rem;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .btn-submit {
        flex: 1;
        padding: 1rem;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        height: auto;
    }

    .btn-submit i {
        font-size: 1.1rem;
    }

    .btn-save {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        color: white;
    }

    .btn-save:hover {
        background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(99,102,241,0.4);
    }

    .btn-cancel {
        background: white;
        color: #374151;
        border: 2px solid #e5e7eb;
    }

    .btn-cancel:hover {
        background: #f8fafc;
        border-color: #6366f1;
        color: #6366f1;
    }
</style>

<div class="edit-page">
    <div class="container-fluid">
        <!-- Header -->
        <div class="edit-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1>
                        <div class="header-icon">
                            <i class="fas fa-edit"></i>
                        </div>
                        Edit Pembimbing
                    </h1>
                    <p>Perbarui guru pembimbing siswa magang dengan data yang akurat</p>
                </div>
            </div>
        </div>

        <!-- Student Info Cards -->
        <div class="info-grid">
            <div class="info-card">
                <div class="info-label">
                    <i class="fas fa-user"></i>
                    Nama Siswa
                </div>
                <div class="info-value">{{ $magang->nama }}</div>
            </div>

            <div class="info-card">
                <div class="info-label">
                    <i class="fas fa-briefcase"></i>
                    Posisi Magang
                </div>
                <div class="info-value">{{ optional($magang->opening)->posisi ?? '-' }}</div>
            </div>

            <div class="info-card">
                <div class="info-label">
                    <i class="fas fa-building"></i>
                    Perusahaan
                </div>
                <div class="info-value text-truncate" title="{{ optional($magang->opening)->perusahaan }}">
                    {{ optional($magang->opening)->perusahaan ?? '-' }}
                </div>
            </div>
        </div>

        <!-- Form Container -->
        <div class="form-container">
            <div class="form-title">
                <i class="fas fa-user-tie"></i>
                Pilih Guru Pembimbing
            </div>

            <form method="POST" action="{{ $magang->pembimbing ? url('/admin/pembimbing/'.$magang->pembimbing->id.'/update') : url('/admin/pembimbing/store') }}">
                @csrf
                <input type="hidden" name="magang_id" value="{{ $magang->id }}">

                <div class="form-group">
                    <label class="form-label">Guru Pembimbing <span style="color: #dc2626;">*</span></label>
                    <select name="guru_id" class="form-select" required>
                        <option disabled selected>-- Pilih Guru --</option>
                        @foreach($gurus as $guru)
                            <option value="{{ $guru->id }}" {{ optional($magang->pembimbing)->guru_id == $guru->id ? 'selected' : '' }}>
                                {{ $guru->nama }}
                            </option>
                        @endforeach
                    </select>
                    
                    @error('guru_id')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="form-actions">
                    <button type="submit" class="btn-submit btn-save">
                        <i class="fas fa-save"></i>
                        Simpan Perubahan
                    </button>
                    <a href="{{ url('/admin/pembimbing') }}" class="btn-submit btn-cancel" style="text-decoration: none;">
                        <i class="fas fa-times"></i>
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection