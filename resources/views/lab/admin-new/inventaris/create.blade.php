@extends('lab.layouts.unified', ['title' => 'Tambah Inventaris'])

@section('css')
<style>
    .form-section {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        padding: 28px;
        margin-bottom: 24px;
    }
    .section-title {
        font-size: 0.82rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #2563EB;
        border-bottom: 2px solid #EFF6FF;
        padding-bottom: 10px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .form-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 6px;
    }
    .form-control, .form-select {
        border-radius: 10px;
        border: 1.5px solid #E5E7EB;
        font-size: 0.875rem;
        padding: 9px 14px;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-control:focus, .form-select:focus {
        border-color: #2563EB;
        box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
    }
    .form-control.is-invalid, .form-select.is-invalid {
        border-color: #DC2626;
    }
    .preview-placeholder {
        width: 100%;
        height: 180px;
        border-radius: 10px;
        border: 2px dashed #D1D5DB;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: #F9FAFB;
        color: #9CA3AF;
        cursor: pointer;
        transition: all 0.2s;
    }
    .preview-placeholder:hover {
        border-color: #2563EB;
        background: #EFF6FF;
        color: #2563EB;
    }
    .preview-image {
        width: 100%;
        max-height: 200px;
        object-fit: cover;
        border-radius: 10px;
        border: 2px solid #2563EB;
    }
    .btn-save {
        background: linear-gradient(135deg, #2563EB, #1D4ED8);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 10px 28px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.2s;
    }
    .btn-save:hover {
        background: linear-gradient(135deg, #1D4ED8, #1E40AF);
        color: #fff;
        box-shadow: 0 4px 14px rgba(37,99,235,0.3);
        transform: translateY(-1px);
    }
    .required-star { color: #DC2626; }
    .char-count { font-size: 0.72rem; color: #9CA3AF; float: right; }
    .kode-auto-badge {
        font-size: 0.72rem;
        background: #EFF6FF;
        color: #2563EB;
        padding: 2px 8px;
        border-radius: 20px;
        font-weight: 600;
    }
</style>
@endsection

@section('content')

{{-- Header --}}
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('lab.admin_new.inventaris.index') }}"
       class="btn btn-light rounded-circle p-2" style="width:38px;height:38px;display:flex;align-items:center;justify-content:center;">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h4 class="fw-bold mb-0 text-dark">Tambah Inventaris Baru</h4>
        <p class="text-muted small mb-0">Daftarkan peralatan atau bahan laboratorium baru ke sistem</p>
    </div>
</div>

{{-- Alert --}}
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4">
    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4">
    <i class="bi bi-exclamation-triangle me-2"></i><strong>Validasi gagal:</strong>
    <ul class="mb-0 mt-1 small">
        @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<form action="{{ route('lab.admin_new.inventaris.store') }}"
      method="POST" enctype="multipart/form-data" id="createForm">
    @csrf

    <div class="row g-4">

        {{-- KOLOM KIRI --}}
        <div class="col-lg-8">

            {{-- 1. Informasi Dasar --}}
            <div class="form-section">
                <div class="section-title">
                    <i class="bi bi-info-circle-fill"></i> Informasi Dasar
                </div>
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Nama Inventaris <span class="required-star">*</span></label>
                        <input type="text" name="nama_inventaris" id="namaInventaris"
                               class="form-control @error('nama_inventaris') is-invalid @enderror"
                               value="{{ old('nama_inventaris') }}"
                               placeholder="Contoh: Kompresor AC, Multimeter Digital..."
                               maxlength="255" required autofocus>
                        <span class="char-count" id="namaCount">0/255</span>
                        @error('nama_inventaris')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">
                            Kode Inventaris
                            <span class="kode-auto-badge ms-1">Auto-generate</span>
                        </label>
                        <input type="text" name="kode_inventaris"
                               class="form-control @error('kode_inventaris') is-invalid @enderror"
                               value="{{ old('kode_inventaris') }}"
                               placeholder="Kosongkan = otomatis"
                               maxlength="50">
                        <small class="text-muted">Biarkan kosong untuk kode otomatis</small>
                        @error('kode_inventaris')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jenis <span class="required-star">*</span></label>
                        <select name="jenis" class="form-select @error('jenis') is-invalid @enderror" required>
                            <option value="Alat" {{ old('jenis') == 'Alat' ? 'selected' : '' }}>Alat</option>
                            <option value="Bahan" {{ old('jenis') == 'Bahan' ? 'selected' : '' }}>Bahan</option>
                        </select>
                        @error('jenis')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label d-flex justify-content-between">
                            <span>Kategori <span class="required-star">*</span></span>
                            <a href="{{ route('lab.admin_new.inventaris.kategori.index') }}" class="text-primary small text-decoration-none">
                                <i class="bi bi-plus-circle me-1"></i>Kelola
                            </a>
                        </label>
                        <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->nama }}" {{ old('kategori') == $cat->nama ? 'selected' : '' }}>
                                    {{ $cat->nama }}
                                </option>
                            @endforeach
                            @if($categories->isEmpty())
                                <option value="Umum">Umum (Default)</option>
                            @endif
                        </select>
                        @error('kategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- 2. Lokasi & Status --}}
            <div class="form-section">
                <div class="section-title">
                    <i class="bi bi-building-fill"></i> Lokasi & Status
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Laboratorium</label>
                        <select name="labor_id" class="form-select @error('labor_id') is-invalid @enderror" id="laborSelect">
                            <option value="">-- Pilih Laboratorium --</option>
                            @foreach($laboratories as $lab)
                                <option value="{{ $lab->id }}"
                                    {{ old('labor_id') == $lab->id ? 'selected' : '' }}>
                                    {{ $lab->nama_labor }}
                                </option>
                            @endforeach
                        </select>
                        @error('labor_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Lokasi Spesifik</label>
                        <input type="text" name="lokasi"
                               class="form-control @error('lokasi') is-invalid @enderror"
                               value="{{ old('lokasi') }}"
                               id="lokasiInput"
                               placeholder="Rak A, Lemari B, Meja Kerja...">
                        @error('lokasi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Kondisi <span class="required-star">*</span></label>
                        <select name="kondisi" class="form-select @error('kondisi') is-invalid @enderror" required>
                            <option value="">-- Pilih Kondisi --</option>
                            @foreach(['Sangat Baik','Baik','Rusak Ringan','Rusak Sedang','Rusak Berat'] as $k)
                                <option value="{{ $k }}"
                                    {{ old('kondisi') == $k ? 'selected' : '' }}>
                                    {{ $k }}
                                </option>
                            @endforeach
                        </select>
                        @error('kondisi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status <span class="required-star">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="Tersedia" {{ old('status', 'Tersedia') == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                            <option value="Tidak Tersedia" {{ old('status') == 'Tidak Tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
                            <option value="Dipinjam" {{ old('status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Jumlah <span class="required-star">*</span></label>
                        <input type="number" name="jumlah" min="1"
                               class="form-control @error('jumlah') is-invalid @enderror"
                               value="{{ old('jumlah', 1) }}"
                               placeholder="1" required>
                        @error('jumlah')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- 3. Deskripsi & Spesifikasi --}}
            <div class="form-section">
                <div class="section-title">
                    <i class="bi bi-card-text"></i> Deskripsi & Spesifikasi
                </div>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" rows="3"
                                  class="form-control @error('deskripsi') is-invalid @enderror"
                                  placeholder="Tuliskan deskripsi singkat tentang alat ini...">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Spesifikasi Teknis</label>
                        <textarea name="spesifikasi" rows="3"
                                  class="form-control @error('spesifikasi') is-invalid @enderror"
                                  placeholder="Contoh: Voltase: 220V, Daya: 500W, Frekuensi: 50Hz...">{{ old('spesifikasi') }}</textarea>
                        @error('spesifikasi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

        </div>

        {{-- KOLOM KANAN --}}
        <div class="col-lg-4">

            {{-- Gambar --}}
            <div class="form-section">
                <div class="section-title">
                    <i class="bi bi-image-fill"></i> Foto Alat
                </div>
                <div onclick="document.getElementById('gambarInput').click()">
                    <div class="preview-placeholder" id="previewPlaceholder">
                        <i class="bi bi-cloud-upload fs-2 mb-2"></i>
                        <span class="small fw-semibold">Klik untuk upload foto</span>
                        <span class="small">JPG, PNG (max 2MB)</span>
                    </div>
                    <img src="" class="preview-image d-none" id="previewImg" alt="Preview">
                </div>
                <input type="file" name="gambar" id="gambarInput" class="d-none"
                       accept="image/jpeg,image/png,image/jpg">
                @error('gambar')
                    <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                @enderror
            </div>

            {{-- Data Pengadaan --}}
            <div class="form-section">
                <div class="section-title">
                    <i class="bi bi-receipt-cutoff"></i> Data Pengadaan
                </div>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Tanggal Pengadaan <span class="required-star">*</span></label>
                        <input type="date" name="tanggal_pengadaan"
                               class="form-control @error('tanggal_pengadaan') is-invalid @enderror"
                               value="{{ old('tanggal_pengadaan', date('Y-m-d')) }}"
                               required>
                        @error('tanggal_pengadaan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Tahun Perolehan</label>
                        <input type="number" name="tahun_perolehan" min="2000" max="{{ date('Y') }}"
                               class="form-control @error('tahun_perolehan') is-invalid @enderror"
                               value="{{ old('tahun_perolehan', date('Y')) }}"
                               placeholder="{{ date('Y') }}">
                        @error('tahun_perolehan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Sumber Dana</label>
                        <select name="sumber_dana" class="form-select @error('sumber_dana') is-invalid @enderror">
                            <option value="">-- Pilih Sumber --</option>
                            @foreach(['APBN','BOS','Hibah','Lainnya'] as $s)
                                <option value="{{ $s }}" {{ old('sumber_dana') == $s ? 'selected' : '' }}>
                                    {{ $s }}
                                </option>
                            @endforeach
                        </select>
                        @error('sumber_dana')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Stok Minimum</label>
                        <input type="number" name="stok_minimum" min="0"
                               class="form-control @error('stok_minimum') is-invalid @enderror"
                               value="{{ old('stok_minimum', 0) }}"
                               placeholder="0">
                        <small class="text-muted">Notifikasi jika stok di bawah angka ini</small>
                        @error('stok_minimum')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="d-flex flex-column gap-2">
                <button type="submit" class="btn-save w-100">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Inventaris
                </button>
                <a href="{{ route('lab.admin_new.inventaris.index') }}"
                   class="btn btn-outline-secondary w-100 rounded-3">
                    <i class="bi bi-x me-2"></i>Batal
                </a>
            </div>

        </div>
    </div>
</form>

@endsection

@section('script')
<script>
    // Preview gambar
    document.getElementById('gambarInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file terlalu besar! Maksimum 2MB.');
            this.value = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = function(ev) {
            document.getElementById('previewImg').src = ev.target.result;
            document.getElementById('previewImg').classList.remove('d-none');
            document.getElementById('previewPlaceholder').classList.add('d-none');
        };
        reader.readAsDataURL(file);
    });

    // Character counter
    const namaInput = document.getElementById('namaInventaris');
    const namaCount = document.getElementById('namaCount');
    namaInput.addEventListener('input', function() {
        namaCount.textContent = this.value.length + '/255';
    });

    // Saat laboratorium dipilih, isi lokasi otomatis jika kosong
    document.getElementById('laborSelect').addEventListener('change', function() {
        const lokasiInput = document.getElementById('lokasiInput');
        if (!lokasiInput.value) {
            lokasiInput.value = this.options[this.selectedIndex].text;
        }
    });

    // Validasi sebelum submit
    document.getElementById('createForm').addEventListener('submit', function(e) {
        const required = this.querySelectorAll('[required]');
        let valid = true;
        required.forEach(function(field) {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                valid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });
        if (!valid) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });
</script>
@endsection
