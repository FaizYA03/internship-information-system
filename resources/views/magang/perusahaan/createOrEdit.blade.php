@extends('magang.layouts.main')

@section('content')
<style>
    .company-form-page {
        min-height: 100vh;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        padding: 2rem 0;
    }

    .form-header {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        border-radius: 20px;
        padding: 2.5rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .form-header h1 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .form-header p {
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

    .company-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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
        word-break: break-word;
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

    .form-section {
        margin-bottom: 2rem;
    }

    .section-title {
        font-size: 1rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e5e7eb;
    }

    .section-title i {
        color: #6366f1;
        font-size: 1rem;
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
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-label i {
        color: #6366f1;
        font-size: 0.9rem;
    }

    .form-input,
    .form-textarea {
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
    .form-textarea:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99,102,241,0.1);
        background: #f8fafc;
    }

    .form-input::placeholder,
    .form-textarea::placeholder {
        color: #94a3b8;
    }

    .form-textarea {
        resize: vertical;
        min-height: 100px;
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
        justify-content: flex-end;
    }

    .btn-submit {
        padding: 1rem 2rem;
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
        min-width: 140px;
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

    .btn-update {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        color: white;
    }

    .btn-update:hover {
        background: linear-gradient(135deg, #047857 0%, #065f46 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(5, 150, 105, 0.4);
    }

    .btn-cancel {
        background: white;
        color: #374151;
        border: 2px solid #e5e7eb;
        padding: 1rem 2rem;
    }

    .btn-cancel:hover {
        background: #f8fafc;
        border-color: #6366f1;
        color: #6366f1;
    }

    .global-errors {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }

    .global-errors ul {
        margin: 0;
        padding-left: 1.5rem;
    }

    .global-errors li {
        color: #dc2626;
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }

    .global-errors li:last-child {
        margin-bottom: 0;
    }
</style>

<div class="company-form-page">
    <div class="container-fluid">
        <!-- Header -->
        <div class="form-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1>
                        <div class="header-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        {{ isset($wakil) ? 'Edit Perusahaan' : 'Tambah Perusahaan' }}
                    </h1>
                    <p>{{ isset($wakil) ? 'Perbarui informasi perusahaan dan wakil perusahaan' : 'Tambahkan perusahaan baru ke dalam sistem magang' }}</p>
                </div>
            </div>
        </div>

        @if(isset($wakil))
        <!-- Company Info Cards (Edit Mode) -->
        <div class="company-info-grid">
            <div class="info-card">
                <div class="info-label">
                    <i class="fas fa-building"></i>
                    Nama Perusahaan
                </div>
                <div class="info-value">{{ $wakil->nama_perusahaan }}</div>
            </div>

            <div class="info-card">
                <div class="info-label">
                    <i class="fas fa-user-tie"></i>
                    Wakil Perusahaan
                </div>
                <div class="info-value">{{ $wakil->nama }}</div>
            </div>

            <div class="info-card">
                <div class="info-label">
                    <i class="fas fa-envelope"></i>
                    Email
                </div>
                <div class="info-value">{{ $wakil->email }}</div>
            </div>

            <div class="info-card">
                <div class="info-label">
                    <i class="fas fa-phone"></i>
                    No. Perusahaan
                </div>
                <div class="info-value">{{ $wakil->no_perusahaan }}</div>
            </div>
        </div>
        @endif

        <!-- Form Container -->
        <div class="form-container">
            <div class="form-title">
                <i class="fas fa-edit"></i>
                {{ isset($wakil) ? 'Form Edit Perusahaan' : 'Form Tambah Perusahaan' }}
            </div>

            @if ($errors->any())
            <div class="global-errors">
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                    <i class="fas fa-exclamation-triangle" style="color: #dc2626;"></i>
                    <strong style="color: #dc2626;">Terjadi Kesalahan:</strong>
                </div>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ isset($wakil) ? route('magang.perusahaan.update', $wakil->id) : route('magang.perusahaan.store') }}" method="POST">
                @csrf
                @if(isset($wakil))
                    @method('PUT')
                @endif

                <!-- Personal Information Section -->
                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-user"></i>
                        Informasi Wakil Perusahaan
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-user"></i>
                            Nama Lengkap <span style="color: #dc2626;">*</span>
                        </label>
                        <input type="text" name="nama" class="form-input" value="{{ old('nama', $wakil->nama ?? '') }}" placeholder="Masukkan nama lengkap wakil perusahaan" required>
                        @error('nama')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-envelope"></i>
                            Email <span style="color: #dc2626;">*</span>
                        </label>
                        <input type="email" name="email" class="form-input" value="{{ old('email', $wakil->email ?? '') }}" placeholder="contoh@email.com" required>
                        @error('email')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-lock"></i>
                            Password {{ isset($wakil) ? '(Kosongkan jika tidak ingin mengubah)' : '' }}
                        </label>
                        <input type="password" name="password" class="form-input" placeholder="Minimal 8 karakter" {{ isset($wakil) ? '' : 'required' }}>
                        @error('password')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-lock"></i>
                            Konfirmasi Password {{ isset($wakil) ? '(Kosongkan jika tidak ingin mengubah)' : '' }}
                        </label>
                        <input type="password" name="password_confirmation" class="form-input" placeholder="Ulangi password" {{ isset($wakil) ? '' : 'required' }}>
                        @error('password_confirmation')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Company Information Section -->
                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-building"></i>
                        Informasi Perusahaan
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-building"></i>
                            Nama Perusahaan <span style="color: #dc2626;">*</span>
                        </label>
                        <input type="text" name="nama_perusahaan" class="form-input" value="{{ old('nama_perusahaan', $wakil->nama_perusahaan ?? '') }}" placeholder="Masukkan nama perusahaan" required>
                        @error('nama_perusahaan')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-map-marker-alt"></i>
                            Alamat Lengkap <span style="color: #dc2626;">*</span>
                        </label>
                        <textarea name="alamat" class="form-textarea" placeholder="Masukkan alamat lengkap perusahaan" required>{{ old('alamat', $wakil->alamat ?? '') }}</textarea>
                        @error('alamat')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-phone"></i>
                            No. Telepon Perusahaan <span style="color: #dc2626;">*</span>
                        </label>
                        <input type="text" name="no_perusahaan" class="form-input" value="{{ old('no_perusahaan', $wakil->no_perusahaan ?? '') }}" placeholder="Contoh: 021-12345678" required>
                        @error('no_perusahaan')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="form-actions">
                    <a href="{{ route('magang.perusahaan.index') }}" class="btn-submit btn-cancel">
                        <i class="fas fa-times"></i>
                        Batal
                    </a>
                    <button type="submit" class="btn-submit {{ isset($wakil) ? 'btn-update' : 'btn-save' }}">
                        <i class="fas fa-{{ isset($wakil) ? 'save' : 'plus' }}"></i>
                        {{ isset($wakil) ? 'Update Perusahaan' : 'Simpan Perusahaan' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
