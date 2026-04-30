@extends('sistem_akademik.layouts.main', ['title' => isset($siswa) ? 'Edit Siswa' : 'Tambah Siswa'])

@section('css')
<style>
    .section-divider {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #94a3b8;
        border-bottom: 1px solid #e2e8f0;
        padding-bottom: 6px;
        margin-bottom: 16px;
        margin-top: 24px;
    }
    .form-label {
        font-size: 0.82rem;
        font-weight: 600;
        color: #475569;
        margin-bottom: 5px;
    }
    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        font-size: 0.9rem;
    }
    .form-control:focus, .form-select:focus {
        border-color: #f97316;
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
    }

    /* Photo Upload */
    .photo-upload-area {
        display: flex;
        align-items: center;
        gap: 20px;
    }
    .photo-preview-wrap {
        position: relative;
        width: 110px;
        flex-shrink: 0;
    }
    #foto-preview {
        width: 110px;
        height: 110px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        background: #f8fafc;
    }
    .photo-change-btn {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #f97316;
        color: white;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(249,115,22,0.4);
        transition: all 0.2s;
    }
    .photo-change-btn:hover {
        background: #ea580c;
        transform: scale(1.1);
    }
    .photo-info {
        font-size: 0.78rem;
        color: #94a3b8;
    }
    .photo-info strong {
        display: block;
        color: #475569;
        font-size: 0.85rem;
        margin-bottom: 2px;
    }
    .btn-save {
        background: #f97316;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 10px 28px;
        font-weight: 600;
        transition: all 0.2s;
    }
    .btn-save:hover { background: #ea580c; color: white; }
    .btn-cancel {
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 600;
    }
</style>
@endsection

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 border-bottom">
        <h5 class="mb-0 fw-bold">
            <i class="bi bi-person-badge text-primary me-2"></i>
            {{ isset($siswa) ? 'Edit Data Siswa' : 'Tambah Siswa Baru' }}
        </h5>
    </div>
    <div class="card-body p-4">
        <form action="{{ isset($siswa) ? route('sistem_akademik.siswa.update', $siswa->id) : route('sistem_akademik.siswa.store') }}"
              method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($siswa)) @method('PUT') @endif

            {{-- ===== FOTO PROFIL ===== --}}
            <div class="section-divider">Foto Profil</div>
            <div class="photo-upload-area mb-4">
                <div class="photo-preview-wrap">
                    <img id="foto-preview"
                         src="{{ isset($siswa) && $siswa->foto
                                    ? asset('storage/' . $siswa->foto)
                                    : asset('assets/images/default_avatar.png') }}"
                         alt="Foto Siswa">
                    <button type="button" class="photo-change-btn" onclick="document.getElementById('foto-input').click()" title="Ganti Foto">
                        <i class="bi bi-camera-fill"></i>
                    </button>
                </div>
                <div class="photo-info">
                    <strong>Foto Profil Siswa</strong>
                    Format: JPG, JPEG, PNG<br>
                    Ukuran maksimal: 2MB<br>
                    <span class="text-muted" style="font-size:0.73rem;">Klik ikon kamera untuk mengganti foto</span>
                </div>
            </div>
            <input type="file" id="foto-input" name="foto" accept="image/jpg,image/jpeg,image/png" class="d-none">

            {{-- ===== DATA DIRI ===== --}}
            <div class="section-divider">Data Pribadi</div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="nama"
                           value="{{ old('nama', $siswa->user->nama ?? '') }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-select">
                        <option value="">-- Pilih --</option>
                        <option value="Laki-laki" {{ old('jenis_kelamin', $siswa->jenis_kelamin ?? '') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin', $siswa->jenis_kelamin ?? '') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Agama</label>
                    <select name="agama" class="form-select">
                        <option value="">-- Pilih --</option>
                        @foreach(['Islam', 'Kristen Protestan', 'Kristen Katolik', 'Hindu', 'Buddha', 'Konghucu'] as $ag)
                            <option value="{{ $ag }}" {{ old('agama', $siswa->agama ?? '') == $ag ? 'selected' : '' }}>{{ $ag }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">NIS <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="nis"
                           value="{{ old('nis', $siswa->nis ?? '') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" class="form-control" name="tanggal_lahir"
                           value="{{ old('tanggal_lahir', $siswa->tanggal_lahir ?? '') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nomor HP</label>
                    <input type="text" class="form-control" name="no_hp"
                           value="{{ old('no_hp', $siswa->no_hp ?? '') }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Alamat</label>
                    <textarea class="form-control" name="alamat" rows="2">{{ old('alamat', $siswa->alamat ?? '') }}</textarea>
                </div>
            </div>

            {{-- ===== DATA AKADEMIK ===== --}}
            <div class="section-divider">Data Akademik</div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Kelas <span class="text-danger">*</span></label>
                    <select name="kelas_id" class="form-select" required>
                        <option value="" disabled selected>-- Pilih Kelas --</option>
                        @foreach($kelas as $k)
                        <option value="{{ $k->id }}"
                            {{ (string) old('kelas_id', $siswa->kelas_id ?? '') == (string) $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kelas }} {{ $k->jurusan }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- ===== AKUN ===== --}}
            <div class="section-divider">Data Akun</div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" name="email"
                           value="{{ old('email', $siswa->user->email ?? '') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Password {{ isset($siswa) ? '(Kosongkan jika tidak diubah)' : '' }}</label>
                    <input type="password" class="form-control" name="password"
                           placeholder="{{ isset($siswa) ? 'Isi untuk mengubah password...' : '' }}"
                           {{ isset($siswa) ? '' : 'required' }}>
                </div>
            </div>

            {{-- Submit --}}
            <div class="d-flex gap-2 mt-4 pt-3 border-top">
                <a href="{{ route('sistem_akademik.siswa.index') }}" class="btn btn-light border btn-cancel">
                    <i class="bi bi-arrow-left me-1"></i> Batal
                </a>
                <button type="submit" class="btn btn-save">
                    <i class="bi bi-check-circle me-1"></i> Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script>
    // Live photo preview
    document.getElementById('foto-input').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function (ev) {
            document.getElementById('foto-preview').src = ev.target.result;
        };
        reader.readAsDataURL(file);
    });
</script>
@endsection