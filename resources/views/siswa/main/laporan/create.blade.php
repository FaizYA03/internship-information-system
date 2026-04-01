@extends('siswa.layouts.main')

@php
    $role_prefix = Auth::check() && Auth::user()->role == 'guru' ? 'guru' : 'siswa';
@endphp

@section('css')
<style>
    :root {
        --primary-color: #4361ee;
        --secondary-color: #3f37c9;
        --border-radius: 12px;
        --card-bg: #ffffff;
        --text-color: #2b2d42;
        --text-muted: #8d99ae;
    }

    /* Form Container */
    .form-card {
        background: var(--card-bg);
        border-radius: var(--border-radius);
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        padding: 2rem;
        border: 1px solid rgba(0,0,0,0.05);
    }

    .form-header {
        margin-bottom: 2rem;
        border-bottom: 2px solid #f1f5f9;
        padding-bottom: 1rem;
    }

    .form-label {
        font-weight: 600;
        color: var(--text-color);
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }
    
    .form-control-custom, .form-select-custom {
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: all 0.2s;
        background-color: #f8fafc;
    }

    .form-control-custom:focus, .form-select-custom:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        background-color: white;
    }

    /* Damage Level Selection */
    .damage-options {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }

    .damage-option {
        position: relative;
    }

    .damage-radio {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }

    .damage-card {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 1rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background-color: white;
    }

    .damage-radio:checked + .damage-card {
        border-color: var(--primary-color);
        background-color: rgba(67, 97, 238, 0.05);
    }
    
    .level-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.5rem;
        font-size: 1.25rem;
    }
    
    .damage-radio[value="Ringan"] + .damage-card .level-icon { background-color: #fef3c7; color: #d97706; }
    .damage-radio[value="Sedang"] + .damage-card .level-icon { background-color: #ffedd5; color: #ea580c; }
    .damage-radio[value="Berat"] + .damage-card .level-icon { background-color: #fee2e2; color: #dc2626; }
    
    .damage-title { font-weight: 700; margin-bottom: 0.25rem; font-size: 0.9rem; }
    .damage-desc { font-size: 0.75rem; color: var(--text-muted); line-height: 1.2; }

    /* File Upload */
    .file-upload-area {
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        background-color: #f8fafc;
        position: relative;
    }

    .file-upload-area:hover {
        border-color: var(--primary-color);
        background-color: rgba(67, 97, 238, 0.05);
    }

    .upload-icon {
        font-size: 2rem;
        color: var(--text-muted);
        margin-bottom: 0.5rem;
    }

    .upload-text {
        font-size: 0.9rem;
        color: var(--text-color);
        font-weight: 500;
    }
    
    .upload-subtext {
        font-size: 0.75rem;
        color: var(--text-muted);
    }
    
    /* Character Counter */
    .char-counter {
        text-align: right;
        font-size: 0.75rem;
        color: var(--text-muted);
        margin-top: 0.25rem;
    }

    /* Info Panel */
    .info-panel {
        background: linear-gradient(145deg, #ffffff, #f8fafc);
        border-radius: var(--border-radius);
        padding: 1.5rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        border: 1px solid rgba(0,0,0,0.05);
        height: 100%;
    }

    .info-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        color: var(--primary-color);
        font-weight: 700;
    }

    .info-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .info-item {
        display: flex;
        gap: 0.75rem;
        margin-bottom: 1.25rem;
    }

    .info-item i {
        color: var(--primary-color);
        margin-top: 0.2rem;
    }

    .info-item h6 {
        font-weight: 600;
        font-size: 0.95rem;
        margin-bottom: 0.25rem;
    }

    .info-item p {
        font-size: 0.85rem;
        color: var(--text-muted);
        margin: 0;
        line-height: 1.5;
    }
    
    .step-indicator {
        width: 24px;
        height: 24px;
        background-color: var(--primary-color);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .btn-primary-custom {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        border: none;
        padding: 0.8rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        width: 100%;
        transition: all 0.3s;
        box-shadow: 0 4px 6px rgba(67, 97, 238, 0.25);
    }
    
    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(67, 97, 238, 0.3);
        color: white;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title fs-3 fw-bold ps-4">Laporan Baru</h1>
        </div>
        <a href="{{ route($role_prefix . '.laporan.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="row g-4">
        <!-- Form Column -->
        <div class="col-lg-8">
            <div class="form-card">
                <form action="{{ route($role_prefix . '.laporan.store') }}" method="POST" enctype="multipart/form-data" id="laporanForm">
                    @csrf
                    <input type="hidden" name="nama_pelapor" value="{{ Auth::user()->nama }}">
                    <input type="hidden" name="tanggal_laporan" value="{{ date('Y-m-d') }}">

                    <div class="row g-3 mb-4">
                        <!-- Lab Selection -->
                        <div class="col-md-6">
                            <label for="lokasi" class="form-label">Laboratorium <span class="text-danger">*</span></label>
                            <select class="form-select form-select-custom @error('lokasi') is-invalid @enderror" id="lokasi" name="lokasi" required>
                                <option value="" disabled selected>Pilih Laboratorium...</option>
                                @foreach($laborList as $lab)
                                    <option value="{{ $lab->nama_labor }}" {{ old('lokasi') == $lab->nama_labor ? 'selected' : '' }}>
                                        {{ $lab->nama_labor }}
                                    </option>
                                @endforeach
                            </select>
                            @error('lokasi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tool Name -->
                        <div class="col-md-6">
                            <label for="nama_alat" class="form-label">Nama Alat <span class="text-danger">*</span></label>
                            <select class="form-select form-select-custom @error('nama_alat') is-invalid @enderror" id="inventaris_id" name="inventaris_id" required>
                                <option value="" disabled selected>Pilih Laboratorium Terlebih Dahulu...</option>
                            </select>
                            <input type="hidden" name="nama_alat" id="nama_alat_hidden">
                            @error('nama_alat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Class (Only for Guru) -->
                        @if(Auth::user()->role === 'guru')
                        <div class="col-md-6">
                            <label for="kelas" class="form-label">Kelas Saat Penggunaan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-custom @error('kelas') is-invalid @enderror" id="kelas" name="kelas" value="{{ old('kelas') }}" placeholder="Contoh: XII RPL 1" required>
                            @error('kelas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @endif
                    </div>

                    <!-- Damage Level Selection (Cards) -->
                    <div class="mb-4">
                        <label class="form-label d-block mb-3">Tingkat Kerusakan <span class="text-danger">*</span></label>
                        <div class="damage-options">
                            <div class="damage-option">
                                <input type="radio" name="tingkat_kerusakan" id="level_ringan" value="Ringan" class="damage-radio" {{ old('tingkat_kerusakan') == 'Ringan' ? 'checked' : '' }} required>
                                <label for="level_ringan" class="damage-card">
                                    <div class="level-icon"><i class="bi bi-bandaid"></i></div>
                                    <div class="damage-title">Ringan</div>
                                    <div class="damage-desc">Goresan, lecet, fungsi tidak terganggu signifikan</div>
                                </label>
                            </div>
                            <div class="damage-option">
                                <input type="radio" name="tingkat_kerusakan" id="level_sedang" value="Sedang" class="damage-radio" {{ old('tingkat_kerusakan') == 'Sedang' ? 'checked' : '' }}>
                                <label for="level_sedang" class="damage-card">
                                    <div class="level-icon"><i class="bi bi-exclamation-triangle"></i></div>
                                    <div class="damage-title">Sedang</div>
                                    <div class="damage-desc">Fungsi terganggu, butuh perbaikan segera</div>
                                </label>
                            </div>
                            <div class="damage-option">
                                <input type="radio" name="tingkat_kerusakan" id="level_berat" value="Berat" class="damage-radio" {{ old('tingkat_kerusakan') == 'Berat' ? 'checked' : '' }}>
                                <label for="level_berat" class="damage-card">
                                    <div class="level-icon"><i class="bi bi-x-octagon"></i></div>
                                    <div class="damage-title">Berat</div>
                                    <div class="damage-desc">Tidak berfungsi total, bahaya jika digunakan</div>
                                </label>
                            </div>
                        </div>
                        @error('tingkat_kerusakan')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label for="deskripsi_kerusakan" class="form-label">Deskripsi Kerusakan <span class="text-danger">*</span></label>
                        <textarea class="form-control form-control-custom @error('deskripsi_kerusakan') is-invalid @enderror" id="deskripsi_kerusakan" name="deskripsi_kerusakan" rows="4" placeholder="Jelaskan detail kerusakan..." onkeyup="countChars(this)" required>{{ old('deskripsi_kerusakan') }}</textarea>
                        <div class="char-counter"><span id="charCount">0</span>/500 karakter</div>
                        @error('deskripsi_kerusakan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text text-muted smaller">
                            Semakin detail laporan, semakin cepat diproses oleh teknisi.
                        </div>
                    </div>

                    <!-- Photo Upload -->
                    <div class="mb-4">
                        <label class="form-label">Foto Bukti <span class="text-muted fw-normal">(Opsional)</span></label>
                        <div class="file-upload-area" id="dropArea" onclick="document.getElementById('foto_bukti').click()">
                            <input type="file" name="foto_bukti" id="foto_bukti" class="d-none" accept="image/*" onchange="previewFile(this)">
                            <div id="uploadPlaceholder">
                                <i class="bi bi-cloud-arrow-up upload-icon"></i>
                                <div class="upload-text">Klik atau tarik foto ke sini</div>
                                <div class="upload-subtext">Maksimal 2MB (JPG, PNG)</div>
                            </div>
                            <div id="filePreview" class="d-none mt-3">
                                <img id="previewImg" src="#" alt="Preview" style="max-height: 150px; border-radius: 8px;">
                                <div id="fileName" class="mt-2 small fw-bold text-muted"></div>
                                <button type="button" class="btn btn-sm btn-outline-danger mt-2" onclick="removeFile(event)">Hapus</button>
                            </div>
                        </div>
                        @error('foto_bukti')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary-custom">
                        <i class="bi bi-send-fill me-2"></i> Kirim Laporan
                    </button>
                </form>
            </div>
        </div>

        <!-- Info Panel Column -->
        <div class="col-lg-4">
            <div class="info-panel">
                <div class="info-header">
                    <i class="bi bi-info-circle-fill fs-4"></i>
                    <h5 class="mb-0">Panduan Pelaporan</h5>
                </div>
                
                <div class="info-list">
                    <div class="info-item">
                        <div class="step-indicator">1</div>
                        <div>
                            <h6>Identifikasi Alat</h6>
                            <p>Pastikan nama alat dan laboratorium sesuai dengan stiker inventaris yang tertempel.</p>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="step-indicator">2</div>
                        <div>
                            <h6>Dokumentasikan</h6>
                            <p>Ambil foto kerusakan dengan jelas. Foto sangat membantu teknisi mempersiapkan peralatan.</p>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="step-indicator">3</div>
                        <div>
                            <h6>Pilih Kategori</h6>
                            <p>Tentukan tingkat kerusakan dengan bijak agar prioritas perbaikan tepat sasaran.</p>
                        </div>
                    </div>

                    <hr class="my-4" style="border-top: 1px dashed #cbd5e1;">

                    <div class="alert alert-secondary border-0 bg-soft-primary mb-0">
                        <h6 class="fw-bold mb-2"><i class="bi bi-shield-check me-2"></i> Tanggung Jawab</h6>
                        <p class="small mb-0 text-muted">
                            Laporan palsu atau penyalahgunaan fitur ini dapat dikenakan sanksi akademik. Mari jaga fasilitas bersama.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    function countChars(obj){
        document.getElementById("charCount").innerHTML = obj.value.length;
    }
    
    function previewFile(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                document.getElementById('previewImg').setAttribute('src', e.target.result);
                document.getElementById('uploadPlaceholder').classList.add('d-none');
                document.getElementById('filePreview').classList.remove('d-none');
                document.getElementById('fileName').innerHTML = input.files[0].name;
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeFile(e) {
        e.stopPropagation();
        var input = document.getElementById('foto_bukti');
        input.value = "";
        document.getElementById('uploadPlaceholder').classList.remove('d-none');
        document.getElementById('filePreview').classList.add('d-none');
    }
    
    // Drag and Drop support
    var dropArea = document.getElementById('dropArea');
    
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, unhighlight, false);
    });
    
    function highlight(e) {
        dropArea.style.borderColor = 'var(--primary-color)';
        dropArea.style.backgroundColor = 'rgba(67, 97, 238, 0.05)';
    }
    
    function unhighlight(e) {
        dropArea.style.borderColor = '#cbd5e1';
        dropArea.style.backgroundColor = '#f8fafc';
    }
    
    dropArea.addEventListener('drop', handleDrop, false);
    
    function handleDrop(e) {
        var dt = e.dataTransfer;
        var files = dt.files;
        
        document.getElementById('foto_bukti').files = files;
        previewFile(document.getElementById('foto_bukti'));
    }

    // Dynamic Inventory Loading
    document.getElementById('lokasi').addEventListener('change', function() {
        const labName = this.value;
        const inventarisSelect = document.getElementById('inventaris_id');
        
        // Clear current options
        inventarisSelect.innerHTML = '<option value="" disabled selected>Memuat alat...</option>';
        
        if (labName) {
            fetch(`{{ route($role_prefix . '.laporan.getInventarisByLab') }}?nama_labor=${encodeURIComponent(labName)}`)
                .then(response => response.json())
                .then(data => {
                    inventarisSelect.innerHTML = '<option value="" disabled selected>Pilih Alat...</option>';
                    
                    if (data.length > 0) {
                        data.forEach(item => {
                            const option = document.createElement('option');
                            option.value = item.id; 
                            option.textContent = `${item.nama_inventaris} (${item.kode_inventaris})`;
                            option.dataset.nama = item.nama_inventaris;
                            inventarisSelect.appendChild(option);
                        });
                    } else {
                        inventarisSelect.innerHTML = '<option value="" disabled selected>Tidak ada inventaris alat di lab ini</option>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching inventory:', error);
                    inventarisSelect.innerHTML = '<option value="" disabled selected>Gagal memuat data</option>';
                });
        } else {
            inventarisSelect.innerHTML = '<option value="" disabled selected>Pilih Laboratorium Terlebih Dahulu...</option>';
            document.getElementById('nama_alat_hidden').value = '';
        }
    });

    document.getElementById('inventaris_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        document.getElementById('nama_alat_hidden').value = selectedOption.dataset.nama || '';
    });

    // Handle old value if validation fails
    @if(old('lokasi'))
        window.addEventListener('DOMContentLoaded', () => {
            const event = new Event('change');
            document.getElementById('lokasi').dispatchEvent(event);
            
            // Wait a bit for AJAX to complete then set the old tool name
            setTimeout(() => {
                const oldTool = "{{ old('nama_alat') }}";
                if (oldTool) {
                    const alatSelect = document.getElementById('nama_alat');
                    alatSelect.value = oldTool;
                }
            }, 1000);
        });
    @endif
</script>
@endsection
