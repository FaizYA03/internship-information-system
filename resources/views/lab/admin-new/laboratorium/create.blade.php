@extends('lab.layouts.unified', ['title' => 'Tambah Laboratorium Baru'])

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-small">
        <li class="breadcrumb-item"><a href="{{ route('lab.admin_new.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('lab.admin_new.laboratorium.index') }}">Laboratorium</a></li>
        <li class="breadcrumb-item active">Tambah Baru</li>
    </ol>
</nav>
@endsection

@section('css')
<style>
    .kode-input-group .input-group-text {
        background: #f0f4ff;
        border-color: #c7d5f8;
        color: #4361ee;
        font-weight: 600;
        font-size: 0.8rem;
        letter-spacing: 0.05em;
    }
    .kode-input-group input {
        font-family: 'Courier New', monospace;
        font-weight: 700;
        color: #2d3748;
        letter-spacing: 0.1em;
        background: #f8faff;
    }
    .btn-generate {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        color: white;
        border: none;
        font-size: 0.78rem;
        font-weight: 600;
        letter-spacing: 0.04em;
        transition: all 0.2s;
    }
    .btn-generate:hover {
        background: linear-gradient(135deg, #3a0ca3, #4361ee);
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(67,97,238,0.3);
    }
    .kode-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #e8efff;
        border: 1px dashed #4361ee;
        color: #4361ee;
        border-radius: 8px;
        padding: 4px 10px;
        font-size: 0.78rem;
        font-weight: 700;
        font-family: 'Courier New', monospace;
        letter-spacing: 0.08em;
    }
    .jenis-option-card {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 16px;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .jenis-option-card:hover {
        border-color: #4361ee;
        background: #f0f4ff;
        transform: translateY(-1px);
    }
    .jenis-option-card.selected {
        border-color: #4361ee;
        background: #e8efff;
    }
    .jenis-option-card .jenis-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }
    .form-section-title {
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #4361ee;
        padding-bottom: 8px;
        border-bottom: 2px solid #e8efff;
        margin-bottom: 16px;
    }
</style>
@endsection

@section('content')

{{-- Alerts --}}
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <strong>Validasi gagal:</strong>
        <ul class="mb-0 mt-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Page Header --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1 text-dark">
                    <i class="bi bi-plus-circle-fill me-2 text-primary"></i>Tambah Laboratorium Baru
                </h4>
                <p class="text-muted small mb-0">Isi form berikut untuk mendaftarkan laboratorium baru ke sistem.</p>
            </div>
            <a href="{{ route('lab.admin_new.laboratorium.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body p-4">

                <form action="{{ route('lab.admin_new.laboratorium.store') }}"
                      method="POST"
                      enctype="multipart/form-data"
                      id="createLaboratoryForm">
                    @csrf

                    {{-- ====== SEKSI 1: JENIS LABORATORIUM ====== --}}
                    <div class="mb-4">
                        <div class="form-section-title">
                            <i class="bi bi-grid-3x3-gap me-2"></i>Jenis Laboratorium <span class="text-danger">*</span>
                        </div>

                        {{-- Hidden select untuk submit ke server --}}
                        <select name="jenis_labor" id="jenis_labor" class="d-none" required>
                            @foreach($jenisOptions as $jenis)
                                <option value="{{ $jenis->nama }}"
                                    {{ old('jenis_labor', $jenisOptions->first()->nama) === $jenis->nama ? 'selected' : '' }}>
                                    {{ $jenis->nama }}
                                </option>
                            @endforeach
                        </select>

                        @php
                        $selectedJenis = old('jenis_labor', $jenisOptions->first()->nama ?? 'Lainnya');
                        $warnaHex = [
                            'primary'   => ['hex' => '#4361ee', 'bg' => '#e8efff'],
                            'danger'    => ['hex' => '#e63946', 'bg' => '#ffeef0'],
                            'warning'   => ['hex' => '#f4a261', 'bg' => '#fff4e8'],
                            'success'   => ['hex' => '#2a9d8f', 'bg' => '#e8f8f6'],
                            'purple'    => ['hex' => '#7b2d8b', 'bg' => '#f5e8ff'],
                            'info'      => ['hex' => '#0dcaf0', 'bg' => '#e8f7ff'],
                            'secondary' => ['hex' => '#6c757d', 'bg' => '#f0f0f0'],
                        ];
                        @endphp

                        <div class="row g-2" id="jenisCards">
                            @foreach($jenisOptions as $jenis)
                                @php
                                    $wCfg = $warnaHex[$jenis->warna] ?? $warnaHex['secondary'];
                                    // Prefix is now solely generated based on name akronim, not from DB prefix
                                @endphp
                                <div class="col-md-4 col-6">
                                    <div class="jenis-option-card {{ $selectedJenis === $jenis->nama ? 'selected' : '' }}"
                                         data-jenis="{{ $jenis->nama }}"
                                         onclick="selectJenis('{{ addslashes($jenis->nama) }}')">
                                        <div class="jenis-icon" style="background: {{ $wCfg['bg'] }}; color: {{ $wCfg['hex'] }}">
                                            <i class="bi {{ $jenis->ikon ?? 'bi-building' }}"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold small" style="color: #2d3748;">{{ $jenis->nama }}</div>
                                            <div class="text-muted" style="font-size: 0.7rem;">Jenis {{ $loop->iteration }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @error('jenis_labor')
                            <div class="text-danger small mt-2"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- ====== SEKSI 2: KODE LABORATORIUM ====== --}}
                    <div class="mb-4">
                        <div class="form-section-title">
                            <i class="bi bi-upc-scan me-2"></i>Kode Laboratorium
                        </div>

                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="kode-badge" id="kodeSuggestion">
                                <i class="bi bi-tag-fill"></i>
                                <span id="kodeSuggestionText">{{ $kodeSuggestion }}</span>
                            </span>
                            <small class="text-muted">← Kode yang akan di-generate otomatis jika kosong</small>
                        </div>

                        <label for="kode" class="form-label fw-semibold small">
                            Kode Lab Otomatis / Bisa diubah
                            <span class="badge bg-light text-muted border ms-1" style="font-size: 0.65rem;">Otomatis jika kosong</span>
                        </label>
                        <div class="input-group kode-input-group">
                            <span class="input-group-text">
                                <i class="bi bi-tag-fill me-1"></i> KODE
                            </span>
                            <input type="text"
                                   id="kode"
                                   name="kode"
                                   class="form-control @error('kode') is-invalid @enderror"
                                   value="{{ old('kode') }}"
                                   placeholder="Kosongkan untuk generate otomatis"
                                   style="font-family: monospace; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase;"
                                   maxlength="50">
                            <button type="button"
                                    class="btn btn-generate"
                                    id="btnGenerate"
                                    onclick="generateKode()">
                                <i class="bi bi-magic me-1"></i> Generate
                            </button>
                        </div>
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>
                            Kosongkan untuk generate otomatis, atau isi manual.
                        </div>
                        @error('kode')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- ====== SEKSI 3: INFORMASI DASAR ====== --}}
                    <div class="mb-4">
                        <div class="form-section-title">
                            <i class="bi bi-info-circle me-2"></i>Informasi Dasar
                        </div>

                        <div class="row g-3">
                            <div class="col-md-8">
                                <label for="nama_labor" class="form-label fw-semibold small">
                                    Nama Laboratorium <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       id="nama_labor"
                                       name="nama_labor"
                                       class="form-control @error('nama_labor') is-invalid @enderror"
                                       value="{{ old('nama_labor') }}"
                                       placeholder="Contoh: Laboratorium Komputer 1"
                                       required>
                                @error('nama_labor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="kapasitas" class="form-label fw-semibold small">Kapasitas (Orang)</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-people"></i></span>
                                    <input type="number"
                                           id="kapasitas"
                                           name="kapasitas"
                                           class="form-control @error('kapasitas') is-invalid @enderror"
                                           value="{{ old('kapasitas', 30) }}"
                                           min="1" max="500"
                                           placeholder="30">
                                    @error('kapasitas')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="lokasi" class="form-label fw-semibold small">Lokasi / Gedung</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                    <input type="text"
                                           id="lokasi"
                                           name="lokasi"
                                           class="form-control @error('lokasi') is-invalid @enderror"
                                           value="{{ old('lokasi') }}"
                                           placeholder="Contoh: Gedung A Lantai 2">
                                    @error('lokasi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ====== SEKSI TAMBAHAN: STATUS PENGGUNAAN ====== --}}
                    <div class="mb-4">
                        <div class="form-section-title">
                            <i class="bi bi-activity me-2"></i>Status Operasional
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="status_penggunaan" class="form-label fw-semibold small">Status Penggunaan Awal</label>
                                <select name="status_penggunaan" id="status_penggunaan" class="form-select @error('status_penggunaan') is-invalid @enderror">
                                    <option value="kosong" {{ old('status_penggunaan') == 'kosong' ? 'selected' : '' }}>Tersedia / Kosong</option>
                                    <option value="digunakan" {{ old('status_penggunaan') == 'digunakan' ? 'selected' : '' }}>Sedang Digunakan</option>
                                </select>
                                <div class="form-text small mt-2">
                                    <i class="bi bi-info-circle me-1"></i> Default: Tersedia.
                                </div>
                                @error('status_penggunaan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- ====== SEKSI 4: PENANGGUNG JAWAB ====== --}}
                    <div class="mb-4">
                        <div class="form-section-title">
                            <i class="bi bi-person-badge me-2"></i>Penanggung Jawab
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="penanggung_jawab" class="form-label fw-semibold small">ID Penanggung Jawab</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person-check"></i></span>
                                    <input type="text"
                                           id="penanggung_jawab"
                                           name="penanggung_jawab"
                                           class="form-control @error('penanggung_jawab') is-invalid @enderror"
                                           value="{{ old('penanggung_jawab') }}"
                                           placeholder="ID Pengguna Penanggung Jawab">
                                    @error('penanggung_jawab')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="teknisi" class="form-label fw-semibold small">ID Teknisi</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-wrench"></i></span>
                                    <input type="text"
                                           id="teknisi"
                                           name="teknisi"
                                           class="form-control @error('teknisi') is-invalid @enderror"
                                           value="{{ old('teknisi') }}"
                                           placeholder="ID Pengguna Teknisi">
                                    @error('teknisi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ====== SEKSI 5: DESKRIPSI & FASILITAS ====== --}}
                    <div class="mb-4">
                        <div class="form-section-title">
                            <i class="bi bi-card-text me-2"></i>Deskripsi & Fasilitas
                        </div>

                        <div class="row g-3">
                            <div class="col-12">
                                <label for="deskripsi" class="form-label fw-semibold small">Deskripsi</label>
                                <textarea id="deskripsi"
                                          name="deskripsi"
                                          class="form-control @error('deskripsi') is-invalid @enderror"
                                          rows="3"
                                          placeholder="Deskripsi singkat tentang laboratorium ini...">{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="fasilitas" class="form-label fw-semibold small">Fasilitas</label>
                                <textarea id="fasilitas"
                                          name="fasilitas"
                                          class="form-control @error('fasilitas') is-invalid @enderror"
                                          rows="3"
                                          placeholder="Contoh: 30 unit komputer, AC, Proyektor, Jaringan LAN...">{{ old('fasilitas') }}</textarea>
                                @error('fasilitas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- ====== SEKSI 6: FOTO ====== --}}
                    <div class="mb-4">
                        <div class="form-section-title">
                            <i class="bi bi-image me-2"></i>Foto Laboratorium
                        </div>

                        <label for="foto" class="form-label fw-semibold small">Upload Foto (Opsional)</label>
                        <input type="file"
                               id="foto"
                               name="foto"
                               class="form-control @error('foto') is-invalid @enderror"
                               accept="image/jpeg,image/jpg,image/png,image/gif">
                        <div class="form-text">Format: JPEG, PNG, JPG, GIF. Maksimal 2MB.</div>
                        @error('foto')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div id="fotoPreviewContainer" class="mt-3 d-none">
                            <label class="form-label fw-semibold small text-muted">Preview:</label>
                            <div>
                                <img id="fotoPreview" src="#" alt="Preview"
                                     class="img-thumbnail"
                                     style="max-height: 180px; object-fit: cover; border-radius: 12px;">
                            </div>
                        </div>
                    </div>

                    {{-- ====== ACTION BUTTONS ====== --}}
                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('lab.admin_new.laboratorium.index') }}"
                           class="btn btn-outline-secondary rounded-pill px-4">
                            <i class="bi bi-x-lg me-2"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary rounded-pill px-5" id="submitBtn">
                            <i class="bi bi-plus-lg me-2"></i>Tambah Laboratorium
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    // ======================================================
    // Konfigurasi jenis lab & prefix kode
    // ======================================================
    // Bangun jenisConfig dari data PHP (dinamis dari DB)
    const jenisConfig = {
        @foreach($jenisOptions as $jenis)
        '{{ addslashes($jenis->nama) }}': { prefix: '{{ 'LAB-' . strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $jenis->nama), 0, 3)) }}' },
        @endforeach
    };

    let selectedJenis = '{{ old('jenis_labor', $jenisOptions->first()->nama ?? 'Lainnya') }}';

    // ======================================================
    // Init: tampilkan kode suggestion awal
    // ======================================================
    window.addEventListener('DOMContentLoaded', function () {
        updateSuggestionBadge(jenisConfig[selectedJenis]?.prefix ?? 'LAB-LAB');
    });

    // ======================================================
    // Pilih jenis laboratorium (klik card)
    // ======================================================
    function selectJenis(jenis) {
        selectedJenis = jenis;
        const prefix = jenisConfig[jenis]?.prefix || 'LAB-LAB';

        // Update hidden select
        document.getElementById('jenis_labor').value = jenis;

        // Update visual cards
        document.querySelectorAll('.jenis-option-card').forEach(card => {
            card.classList.remove('selected');
        });
        document.querySelector(`[data-jenis="${jenis}"]`).classList.add('selected');

        // Update suggestion badge
        updateSuggestionBadge(prefix);

        // Jika kode sudah ada yang di-generate (bukan isian manual), reset
        const kodeInput = document.getElementById('kode');
        if (kodeInput.dataset.autoGenerated === '1') {
            kodeInput.value = '';
            kodeInput.dataset.autoGenerated = '0';
        }
    }

    function updateSuggestionBadge(prefix) {
        const suggestionEl = document.getElementById('kodeSuggestionText');
        if (suggestionEl) {
            suggestionEl.textContent = prefix + '-XXX (otomatis)';
        }
    }

    // ======================================================
    // Tombol Generate — AJAX ke server untuk kode unik
    // ======================================================
    function generateKode() {
        const btn      = document.getElementById('btnGenerate');
        const kodeInput = document.getElementById('kode');
        const namaInput = document.getElementById('nama_labor');
        const namaVal   = (namaInput ? namaInput.value.trim() : '');

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Generating...';

        const params = new URLSearchParams({
            jenis : selectedJenis,
            nama  : namaVal,
        });
        const url = `/lab/admin-new/laboratorium/generate-kode?${params.toString()}`;

        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.kode) {
                kodeInput.value = data.kode;
                kodeInput.dataset.autoGenerated = '1';

                // Update suggestion badge dengan akronim yang dipakai
                const suggestionEl = document.getElementById('kodeSuggestionText');
                if (suggestionEl) suggestionEl.textContent = data.kode;

                // Tampilkan badge akronim
                if (data.akronim) showAkronimBadge(data.akronim, namaVal);

                // Animasi flash
                kodeInput.style.transition = 'background 0.4s';
                kodeInput.style.background = '#d4edff';
                setTimeout(() => { kodeInput.style.background = '#f8faff'; }, 600);
            }
        })
        .catch(() => {
            // Fallback lokal: akronim dari nama atau prefix jenis
            const akronim = namaVal ? extractAkronim(namaVal) : null;
            const prefix  = akronim ? ('LAB-' + akronim) : (jenisConfig[selectedJenis]?.prefix ?? 'LAB-LAB');
            kodeInput.value = prefix + '-' + String(Math.floor(Math.random() * 999) + 1).padStart(3, '0');
            kodeInput.dataset.autoGenerated = '1';
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-magic me-1"></i> Generate';
        });
    }

    // ======================================================
    // Helper: ekstrak akronim dari nama (mirror logic server)
    // ======================================================
    function extractAkronim(nama) {
        const stopwords = ['laboratorium','lab','dan','atau','the','of','di','ke','dari','untuk','dengan','pada'];
        const kata = nama.replace(/[^a-zA-Z\s]/g,' ')
                         .split(/\s+/)
                         .filter(k => k.length > 0 && !stopwords.includes(k.toLowerCase()));
        if (!kata.length) return 'LAB';
        if (kata.length === 1) return kata[0].substring(0,3).toUpperCase();
        return kata.map(k => k[0].toUpperCase()).join('').substring(0,5);
    }

    // Tampilkan badge informasi akronim
    function showAkronimBadge(akronim, namaAsli) {
        let badge = document.getElementById('akronimInfoBadge');
        if (!badge) {
            badge = document.createElement('div');
            badge.id = 'akronimInfoBadge';
            badge.className = 'mt-2';
            document.getElementById('kode').parentNode.parentNode.appendChild(badge);
        }
        badge.innerHTML = `<small class="text-info"><i class="bi bi-info-circle me-1"></i>Akronim <strong>"${akronim}"</strong> diambil dari nama: <em>${namaAsli}</em></small>`;
    }

    // ======================================================
    // Live preview: saat user ketik nama, tampilkan akronim
    // ======================================================
    document.addEventListener('DOMContentLoaded', function() {
        const namaInput = document.getElementById('nama_labor');
        if (namaInput) {
            namaInput.addEventListener('input', function() {
                const nama = this.value.trim();
                const btn  = document.getElementById('btnGenerate');
                if (nama.length >= 3 && btn) {
                    const akronim = extractAkronim(nama);
                    btn.title = `Akan generate: LAB-${akronim}-XXX`;
                    btn.innerHTML = `<i class="bi bi-magic me-1"></i> Generate <span class="badge bg-light text-dark ms-1" style="font-size:0.7rem;">${akronim}</span>`;
                } else if (btn) {
                    btn.title = '';
                    btn.innerHTML = '<i class="bi bi-magic me-1"></i> Generate';
                }
            });
        }
    });

    // ======================================================
    // Preview foto sebelum upload
    // ======================================================
    document.getElementById('foto').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (event) {
                document.getElementById('fotoPreview').src = event.target.result;
                document.getElementById('fotoPreviewContainer').classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        } else {
            document.getElementById('fotoPreviewContainer').classList.add('d-none');
        }
    });

    // ======================================================
    // Loading state saat submit
    // ======================================================
    document.getElementById('createLaboratoryForm').addEventListener('submit', function () {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';
    });

    // ======================================================
    // Uppercase otomatis pada kode input
    // ======================================================
    document.getElementById('kode').addEventListener('input', function () {
        this.value = this.value.toUpperCase();
        this.dataset.autoGenerated = '0'; // Jika user ketik sendiri, tandai manual
    });
</script>
@endsection
