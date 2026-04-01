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

    .date-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    @media (max-width: 768px) {
        .date-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title fs-3 fw-bold ps-4">Ajukan Peminjaman</h1>
        </div>
        <a href="{{ route($role_prefix . '.labor.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="row g-4">
        <!-- Form Column -->
        <div class="col-lg-8">
            <div class="form-card">
                <form action="{{ route($role_prefix . '.peminjaman.store') }}" method="POST" id="peminjamanForm">
                    @csrf
                    
                    <div class="row g-3 mb-4">
                        <!-- Lab Selection -->
                        <div class="col-md-6">
                            <label for="labor_id" class="form-label">Laboratorium <span class="text-danger">*</span></label>
                            <select class="form-select form-select-custom @error('labor_id') is-invalid @enderror" id="labor_id" name="labor_id" required>
                                <option value="" disabled selected>Pilih Laboratorium...</option>
                                @foreach($laborList as $lab)
                                    <option value="{{ $lab->id }}" {{ (old('labor_id') == $lab->id || (isset($selectedLabor) && $selectedLabor->id == $lab->id)) ? 'selected' : '' }}>
                                        {{ $lab->nama_labor }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tool Selection -->
                        <div class="col-md-6">
                            <label for="inventaris_id" class="form-label">Nama Alat <span class="text-danger">*</span></label>
                            <select class="form-select form-select-custom @error('inventaris_id') is-invalid @enderror" id="inventaris_id" name="inventaris_id" required>
                                @if(isset($selectedAlat))
                                    <option value="{{ $selectedAlat->id }}" selected>{{ $selectedAlat->nama_inventaris }} ({{ $selectedAlat->kode_inventaris }})</option>
                                @else
                                    <option value="" disabled selected>Pilih Laboratorium Terlebih Dahulu...</option>
                                @endif
                            </select>
                            @error('inventaris_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <!-- Quantity -->
                        <div class="col-md-6">
                            <label for="jumlah" class="form-label">Jumlah Pinjam <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="jumlah" id="jumlah" class="form-control form-control-custom @error('jumlah') is-invalid @enderror" value="{{ old('jumlah', 1) }}" min="1" required>
                                <span class="input-group-text bg-light">Unit</span>
                            </div>
                            @error('jumlah')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Date & Time Section -->
                    <div class="date-grid mb-4">
                        <div class="date-group">
                            <label for="tanggal_pinjam" class="form-label">Tanggal Pinjam <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" class="form-control form-control-custom @error('tanggal_pinjam') is-invalid @enderror" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required>
                            @error('tanggal_pinjam')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="date-group">
                            <label for="tanggal_kembali" class="form-label">Tanggal Kembali <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_kembali" id="tanggal_kembali" class="form-control form-control-custom @error('tanggal_kembali') is-invalid @enderror" value="{{ old('tanggal_kembali', date('Y-m-d', strtotime('+3 days'))) }}" required>
                            @error('tanggal_kembali')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="date-grid mb-4">
                        <div class="date-group">
                            <label for="jam_pinjam" class="form-label">Jam Pinjam <span class="text-muted small">(Opsional)</span></label>
                            <input type="time" name="jam_pinjam" id="jam_pinjam" class="form-control form-control-custom @error('jam_pinjam') is-invalid @enderror" value="{{ old('jam_pinjam') }}">
                        </div>
                        <div class="date-group">
                            <label for="jam_kembali" class="form-label">Jam Kembali <span class="text-muted small">(Opsional)</span></label>
                            <input type="time" name="jam_kembali" id="jam_kembali" class="form-control form-control-custom @error('jam_kembali') is-invalid @enderror" value="{{ old('jam_kembali') }}">
                        </div>
                    </div>

                    <!-- Purpose -->
                    <div class="mb-4">
                        <label for="keperluan" class="form-label">Keperluan / Alasan Pinjam <span class="text-danger">*</span></label>
                        <textarea class="form-control form-control-custom @error('keperluan') is-invalid @enderror" id="keperluan" name="keperluan" rows="3" placeholder="Contoh: Digunakan untuk praktikum mata pelajaran Fisika..." required>{{ old('keperluan') }}</textarea>
                        @error('keperluan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary-custom py-3">
                        <i class="bi bi-send-fill me-2"></i> Kirim Permohonan Peminjaman
                    </button>
                </form>
            </div>
        </div>

        <!-- Info Panel Column -->
        <div class="col-lg-4">
            <div class="info-panel">
                <div class="info-header">
                    <i class="bi bi-info-circle-fill fs-4"></i>
                    <h5 class="mb-0">Prosedur Peminjaman</h5>
                </div>
                
                <div class="info-list">
                    <div class="info-item">
                        <div class="step-indicator">1</div>
                        <div>
                            <h6>Ajukan Form</h6>
                            <p>Isi data peminjaman dengan lengkap, termasuk rincian waktu dan keperluan pemakaian alat.</p>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="step-indicator">2</div>
                        <div>
                            <h6>Proses Validasi</h6>
                            <p>Admin laboratorium akan meninjau ketersediaan alat dan memberikan persetujuan (paling lambat 1x24 jam).</p>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="step-indicator">3</div>
                        <div>
                            <h6>Pengambilan Alat</h6>
                            <p>Setelah status "Approved", silakan ambil alat di laboratorium terkait dengan menunjukkan kartu identitas.</p>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="step-indicator">4</div>
                        <div>
                            <h6>Pengembalian</h6>
                            <p>Kembalikan alat tepat waktu dalam kondisi yang sama untuk menghindari denda atau sanksi.</p>
                        </div>
                    </div>

                    <hr class="my-4" style="border-top: 1px dashed #cbd5e1;">

                    <div class="alert alert-warning border-0 bg-soft-warning mb-0">
                        <h6 class="fw-bold mb-2"><i class="bi bi-shield-check me-2"></i> Penting</h6>
                        <p class="small mb-0 text-muted">
                            Peminjam bertanggung jawab sepenuhnya atas kerusakan atau kehilangan alat selama masa peminjaman.
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
    // Dynamic Inventory Loading
    document.getElementById('labor_id').addEventListener('change', function() {
        const laborId = this.value;
        const inventarisSelect = document.getElementById('inventaris_id');
        
        inventarisSelect.innerHTML = '<option value="" disabled selected>Memuat alat...</option>';
        
        if (laborId) {
            fetch(`{{ route($role_prefix . '.laporan.getInventarisByLab') }}?labor_id=${laborId}`)
                .then(response => response.json())
                .then(data => {
                    inventarisSelect.innerHTML = '<option value="" disabled selected>Pilih Alat...</option>';
                    
                    if (data.length > 0) {
                        data.forEach(item => {
                            const option = document.createElement('option');
                            option.value = item.id; 
                            option.textContent = `${item.nama_inventaris} (${item.kode_inventaris}) - Tersedia: ${item.jumlah}`;
                            inventarisSelect.appendChild(option);
                        });
                    } else {
                        inventarisSelect.innerHTML = '<option value="" disabled selected>Tidak ada inventaris alat di lab ini</option>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    inventarisSelect.innerHTML = '<option value="" disabled selected>Gagal memuat data</option>';
                });
        }
    });

    // Handle initial state if validation fails or item pre-selected
    @if(old('labor_id') || isset($selectedLabor))
        window.addEventListener('DOMContentLoaded', () => {
            const laborId = "{{ old('labor_id', $selectedLabor->id ?? '') }}";
            const selectedItem = "{{ old('inventaris_id', $selectedAlat->id ?? '') }}";
            
            if (laborId && !selectedItem) {
                document.getElementById('labor_id').dispatchEvent(new Event('change'));
            }
        });
    @endif
</script>
@endsection
