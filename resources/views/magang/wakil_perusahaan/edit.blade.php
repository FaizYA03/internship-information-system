@extends('magang.layouts.main')

@section('content')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .edit-page {
        min-height: 100vh;
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
        padding: 2rem 0;
        position: relative;
        overflow-x: hidden;
        width: 100%;
    }

    .edit-page::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 20% 50%, rgba(99, 102, 241, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 80%, rgba(59, 130, 246, 0.1) 0%, transparent 50%);
        pointer-events: none;
        z-index: 0;
    }

    .container {
        max-width: 800px;
        margin: 0 auto;
        padding: 0 1rem;
        position: relative;
        z-index: 1;
        width: 100%;
        box-sizing: border-box;
    }

    @media (max-width: 768px) {
        .container {
            padding: 0 0.75rem;
        }
    }

    .profile-box {
        background: white;
        border-radius: 20px;
        padding: 3rem;
        box-shadow: 0 20px 50px rgba(0,0,0,0.15);
        border: 1px solid rgba(226, 232, 240, 0.5);
        backdrop-filter: blur(10px);
    }

    .profile-title {
        font-size: 2.2rem;
        font-weight: 800;
        background: linear-gradient(135deg, #6366f1 0%, #3b82f6 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 0.5rem;
        letter-spacing: -0.5px;
    }

    .profile-subtitle {
        font-size: 0.95rem;
        color: #64748b;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group.full {
        grid-column: span 2;
    }

    .profile-label {
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.75rem;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.7px;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .profile-label::before {
        content: '';
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6366f1 0%, #3b82f6 100%);
    }

    .input-field {
        width: 100%;
        padding: 0.95rem 1.25rem;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 0.95rem;
        background-color: #f8fafc;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-family: inherit;
        color: #0f172a;
    }

    .input-field::placeholder {
        color: #cbd5e1;
    }

    .input-field:focus {
        border-color: #6366f1;
        outline: none;
        background-color: #fff;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1), 0 8px 20px rgba(99, 102, 241, 0.15);
    }

    .input-field:hover:not(.readonly) {
        border-color: #cbd5e1;
        background-color: white;
    }

    .input-field.readonly {
        background-color: #f1f5f9;
        cursor: not-allowed;
        color: #64748b;
        border-color: #e2e8f0;
    }

    textarea.input-field {
        resize: vertical;
        min-height: 120px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        line-height: 1.5;
    }

    .input-field.file {
        padding: 1rem;
        background-color: #f8fafc;
        border: 2px dashed #cbd5e1;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .input-field.file:hover {
        border-color: #6366f1;
        background-color: rgba(99, 102, 241, 0.05);
    }

    .input-field.file::file-selector-button {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        color: white;
        padding: 0.6rem 1.5rem;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-right: 1rem;
    }

    .input-field.file::file-selector-button:hover {
        background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
    }

    .form-note {
        font-size: 0.8rem;
        color: #64748b;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .form-note::before {
        content: '?';
        color: #6366f1;
        font-weight: bold;
    }

    .error-message {
        color: #dc2626;
        font-size: 0.8rem;
        margin-top: 0.4rem;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .error-message::before {
        content: '?';
        font-weight: bold;
    }

    .success-message {
        color: #059669;
        font-size: 0.8rem;
        margin-top: 0.4rem;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .success-message::before {
        content: '?';
        font-weight: bold;
    }

    .button-group {
        grid-column: span 2;
        display: flex;
        justify-content: flex-end;
        gap: 1.5rem;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 2px solid #e2e8f0;
        flex-wrap: wrap;
    }

    .btn {
        padding: 1rem 2.5rem;
        font-weight: 700;
        border-radius: 12px;
        text-align: center;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        font-size: 0.95rem;
        border: none;
        cursor: pointer;
        min-width: 150px;
    }

    .btn-green {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        color: white;
        box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3);
    }

    .btn-green:hover {
        background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(99, 102, 241, 0.4);
    }

    .btn-green:active {
        transform: translateY(-1px);
    }

    .btn-outline-green {
        border: 2px solid #6366f1;
        color: #6366f1;
        background-color: white;
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.1);
    }

    .btn-outline-green:hover {
        background: linear-gradient(135deg, #e0e7ff 0%, #f0f4ff 100%);
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(99, 102, 241, 0.2);
    }

    .btn-outline-green:active {
        transform: translateY(-1px);
    }

    .form-divider {
        grid-column: span 2;
        height: 1px;
        background: linear-gradient(90deg, transparent, #e2e8f0, transparent);
        margin: 1rem 0;
    }

    .form-section-title {
        grid-column: span 2;
        font-size: 1.1rem;
        font-weight: 700;
        color: #0f172a;
        margin-top: 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #e2e8f0;
    }

    .form-section-title i {
        color: #6366f1;
        font-size: 1.3rem;
    }

    /* Responsive for tablet and larger screens */
    @media (max-width: 1024px) {
        .profile-box {
            padding: 2.5rem;
        }

        .profile-title {
            font-size: 2rem;
        }

        .form-grid {
            gap: 1.5rem;
        }
    }

    @media (max-width: 768px) {
        .edit-page {
            padding: 1.5rem 0;
        }

        .profile-box {
            padding: 2rem;
        }

        .profile-title {
            font-size: 1.75rem;
        }

        .form-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .form-group.full {
            grid-column: span 1;
        }
    }

    @media (max-width: 640px) {
        .profile-box {
            padding: 1.5rem;
        }

        .profile-title {
            font-size: 1.5rem;
        }

        .form-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .form-group.full,
        .button-group,
        .form-divider,
        .form-section-title {
            grid-column: span 1;
        }

        .button-group {
            flex-direction: column;
            justify-content: stretch;
        }

        .btn {
            width: 100%;
        }
    }

    .edit-page {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    .container {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 0;
    }

    .input-field::-webkit-autofill,
    .input-field::-webkit-autofill:hover,
    .input-field::-webkit-autofill:focus,
    .input-field::-webkit-autofill:active {
        -webkit-box-shadow: 0 0 0 30px white inset !important;
        box-shadow: 0 0 0 30px white inset !important;
    }

    .input-field::-webkit-autofill {
        -webkit-text-fill-color: #0f172a !important;
    }
</style>

<div class="edit-page">
    <div class="container">
        <section class="profile-box">
            <h2 class="profile-title">
                <i class="fas fa-edit"></i> Edit Profil Perusahaan
            </h2>
            <p class="profile-subtitle">
                <i class="fas fa-info-circle"></i> Perbarui informasi perusahaan Anda dengan data terkini
            </p>

            <form action="{{ route('magang.wakil_perusahaan.profile.update') }}" method="POST" enctype="multipart/form-data" class="form-grid">
                @csrf
                @method('PUT')

                <div class="form-section-title">
                    <i class="fas fa-user-circle"></i> Informasi Pribadi
                </div>

                <div class="form-group">
                    <label class="profile-label">Nama Lengkap</label>
                    <input type="text" name="nama" class="input-field" value="{{ old('nama', $wakil->nama) }}" placeholder="Masukkan nama lengkap Anda" required>
                    @error('nama')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="profile-label">Email</label>
                    <input type="email" name="email" class="input-field readonly" value="{{ $wakil->email }}" readonly>
                    <small class="form-note">Email tidak dapat diubah</small>
                </div>

                <div class="form-divider"></div>

                <div class="form-section-title">
                    <i class="fas fa-building"></i> Informasi Perusahaan
                </div>

                <div class="form-group">
                    <label class="profile-label">Nama Perusahaan</label>
                    <input type="text" name="nama_perusahaan" class="input-field" value="{{ old('nama_perusahaan', $wakil->nama_perusahaan) }}" placeholder="Nama resmi perusahaan Anda" required>
                    @error('nama_perusahaan')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="profile-label">Nomor Telepon Perusahaan</label>
                    <input type="tel" name="no_perusahaan" class="input-field" value="{{ old('no_perusahaan', $wakil->no_perusahaan) }}" placeholder="Contoh: 02112345678" required>
                    @error('no_perusahaan')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group full">
                    <label class="profile-label">Alamat Perusahaan</label>
                    <textarea name="alamat" class="input-field" placeholder="Masukkan alamat lengkap perusahaan Anda..." required>{{ old('alamat', $wakil->alamat) }}</textarea>
                    @error('alamat')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-divider"></div>

                <div class="form-section-title">
                    <i class="fas fa-file-pdf"></i> Dokumen Pendukung
                </div>

                <div class="form-group full">
                    <label class="profile-label">Bukti Lampiran (PDF)</label>
                    <input type="file" name="bukti_lampiran" accept=".pdf" class="input-field file" id="bukti_lampiran">
                    <small class="form-note">Format: PDF, Maksimal ukuran: 5MB. Kosongkan jika tidak ingin mengganti.</small>
                    @error('bukti_lampiran')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="button-group">
                    <a href="{{ route('magang.wakil_perusahaan.profile') }}" class="btn btn-outline-green">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-green">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </section>
    </div>
</div>

<script>
    // File input visual feedback
    const fileInput = document.getElementById('bukti_lampiran');
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                const fileName = this.files[0].name;
                this.style.borderColor = '#6366f1';
                this.style.backgroundColor = 'rgba(99, 102, 241, 0.05)';
            }
        });
    }
</script>
@endsection
