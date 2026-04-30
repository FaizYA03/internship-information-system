@extends('sistem_akademik.layouts.main', ['title' => isset($guru) ? 'Edit Guru' : 'Tambah Guru'])

@section('css')
<style>
    .section-divider {
        font-size: 0.7rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 1px; color: #94a3b8;
        border-bottom: 1px solid #e2e8f0; padding-bottom: 6px;
        margin-bottom: 16px; margin-top: 24px;
    }
    .form-label { font-size: 0.82rem; font-weight: 600; color: #475569; margin-bottom: 5px; }
    .form-control, .form-select {
        border-radius: 8px; border: 1px solid #e2e8f0; font-size: 0.9rem;
    }
    .form-control:focus, .form-select:focus {
        border-color: #f97316; box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
    }
    .photo-upload-area { display: flex; align-items: center; gap: 20px; }
    .photo-preview-wrap { position: relative; width: 110px; flex-shrink: 0; }
    #foto-preview {
        width: 110px; height: 110px; border-radius: 50%; object-fit: cover;
        border: 3px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .photo-change-btn {
        position: absolute; bottom: 0; right: 0;
        width: 30px; height: 30px; border-radius: 50%;
        background: #f97316; color: white; border: none;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.75rem; cursor: pointer;
        box-shadow: 0 2px 6px rgba(249,115,22,0.4); transition: all 0.2s;
    }
    .photo-change-btn:hover { background: #ea580c; transform: scale(1.1); }
    .photo-info { font-size: 0.78rem; color: #94a3b8; }
    .photo-info strong { display: block; color: #475569; font-size: 0.85rem; margin-bottom: 2px; }
    .btn-save { background: #f97316; color: white; border: none; border-radius: 8px; padding: 10px 28px; font-weight: 600; transition: all 0.2s; }
    .btn-save:hover { background: #ea580c; color: white; }
    .btn-cancel { border-radius: 8px; padding: 10px 20px; font-weight: 600; }
</style>
@endsection

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 border-bottom">
        <h5 class="mb-0 fw-bold">
            <i class="bi bi-person-workspace text-primary me-2"></i>
            {{ isset($guru) ? 'Edit Data Guru' : 'Tambah Guru Baru' }}
        </h5>
    </div>
    <div class="card-body p-4">
        <form action="{{ isset($guru) ? route('sistem_akademik.guru.update', $guru->id) : route('sistem_akademik.guru.store') }}"
              method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($guru)) @method('PUT') @endif

            {{-- FOTO --}}
            <div class="section-divider">Foto Profil</div>
            <div class="photo-upload-area mb-4">
                <div class="photo-preview-wrap">
                    <img id="foto-preview"
                         src="{{ isset($guru) && $guru->foto
                                    ? asset('storage/' . $guru->foto)
                                    : asset('assets/images/default_avatar.png') }}"
                         alt="Foto Guru">
                    <button type="button" class="photo-change-btn"
                            onclick="document.getElementById('foto-input').click()" title="Ganti Foto">
                        <i class="bi bi-camera-fill"></i>
                    </button>
                </div>
                <div class="photo-info">
                    <strong>Foto Profil Guru</strong>
                    Format: JPG, JPEG, PNG<br>
                    Ukuran maksimal: 2MB<br>
                    <span class="text-muted" style="font-size:0.73rem;">Klik ikon kamera untuk mengganti foto</span>
                </div>
            </div>
            <input type="file" id="foto-input" name="foto" accept="image/jpg,image/jpeg,image/png" class="d-none">

            {{-- DATA PRIBADI --}}
            <div class="section-divider">Data Pribadi</div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="nama"
                           value="{{ old('nama', $guru->user->nama ?? '') }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-select">
                        <option value="">-- Pilih --</option>
                        <option value="Laki-laki" {{ old('jenis_kelamin', $guru->jenis_kelamin ?? '') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin', $guru->jenis_kelamin ?? '') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Agama</label>
                    <select name="agama" class="form-select">
                        <option value="">-- Pilih --</option>
                        @foreach(['Islam', 'Kristen Protestan', 'Kristen Katolik', 'Hindu', 'Buddha', 'Konghucu'] as $ag)
                            <option value="{{ $ag }}" {{ old('agama', $guru->agama ?? '') == $ag ? 'selected' : '' }}>{{ $ag }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">NIP <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="nip"
                           value="{{ old('nip', $guru->nip ?? '') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" class="form-control" name="tanggal_lahir"
                           value="{{ old('tanggal_lahir', $guru->tanggal_lahir ?? '') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nomor HP</label>
                    <input type="text" class="form-control" name="no_hp"
                           value="{{ old('no_hp', $guru->no_hp ?? '') }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Alamat</label>
                    <textarea class="form-control" name="alamat" rows="2">{{ old('alamat', $guru->alamat ?? '') }}</textarea>
                </div>
            </div>

            {{-- DATA AKADEMIK --}}
            <div class="section-divider">Data Kepegawaian</div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Jurusan <span class="text-danger">*</span></label>
                    <select class="form-select" name="jurusan_id" required>
                        <option value="" disabled {{ !isset($guru) && !old('jurusan_id') ? 'selected' : '' }}>Pilih Jurusan</option>
                        @foreach($jurusans as $j)
                        <option value="{{ $j->id }}" {{ old('jurusan_id', $guru->jurusan_id ?? '') == $j->id ? 'selected' : '' }}>
                            {{ $j->nama_jurusan }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select class="form-select" name="status" required>
                        <option value="Aktif" {{ old('status', $guru->status ?? 'Aktif') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Nonaktif" {{ old('status', $guru->status ?? '') == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>

            {{-- DATA AKUN --}}
            <div class="section-divider">Data Akun</div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" name="email"
                           value="{{ old('email', $guru->user->email ?? '') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Password {{ isset($guru) ? '(Kosongkan jika tidak diubah)' : '' }} {{ !isset($guru) ? '*' : '' }}</label>
                    <input type="password" class="form-control" name="password"
                           placeholder="{{ isset($guru) ? 'Isi untuk mengubah password...' : '' }}"
                           {{ !isset($guru) ? 'required' : '' }}>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4 pt-3 border-top">
                <a href="{{ route('sistem_akademik.guru.index') }}" class="btn btn-light border btn-cancel">
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
    document.getElementById('foto-input').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = ev => document.getElementById('foto-preview').src = ev.target.result;
        reader.readAsDataURL(file);
    });
</script>
@endsection