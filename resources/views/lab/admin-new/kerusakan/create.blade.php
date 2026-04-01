@extends('lab.layouts.unified', ['title' => 'Tambah Laporan Kerusakan'])

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-small">
        <li class="breadcrumb-item"><a href="{{ route('lab.admin_new.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('lab.admin_new.kerusakan.index') }}">Laporan Kerusakan</a></li>
        <li class="breadcrumb-item active">Tambah Laporan</li>
    </ol>
</nav>
@endsection

@section('content')

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="fw-bold mb-0">Tambah Laporan Kerusakan</h5>
                <p class="text-muted small mb-0">Laporkan kerusakan alat laboratorium</p>
            </div>
            <div class="card-body p-4">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('lab.admin_new.kerusakan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Laboratorium <span class="text-danger">*</span></label>
                        <select id="laboratoriumSelect" class="form-select @error('labor_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Laboratorium --</option>
                            @foreach($laboratories as $lab)
                                <option value="{{ $lab->id }}">{{ $lab->nama_labor }}</option>
                            @endforeach
                        </select>
                        @error('labor_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Alat/Inventaris <span class="text-danger">*</span></label>
                        <select name="inventaris_id" id="inventarisSelect" class="form-select @error('inventaris_id') is-invalid @enderror" required disabled>
                            <option value="">-- Pilih Laboratorium Terlebih Dahulu --</option>
                        </select>
                        @error('inventaris_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tingkat Kerusakan <span class="text-danger">*</span></label>
                        <select name="tingkat_kerusakan" class="form-select @error('tingkat_kerusakan') is-invalid @enderror" required>
                            <option value="">-- Pilih Tingkat Kerusakan --</option>
                            <option value="Rusak Ringan" {{ old('tingkat_kerusakan') == 'Rusak Ringan' ? 'selected' : '' }}>
                                Rusak Ringan (Dapat diperbaiki sendiri)
                            </option>
                            <option value="Rusak Sedang" {{ old('tingkat_kerusakan') == 'Rusak Sedang' ? 'selected' : '' }}>
                                Rusak Sedang (Perlu bantuan teknisi)
                            </option>
                            <option value="Rusak Berat" {{ old('tingkat_kerusakan') == 'Rusak Berat' ? 'selected' : '' }}>
                                Rusak Berat (Tidak dapat digunakan)
                            </option>
                        </select>
                        @error('tingkat_kerusakan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi Kerusakan <span class="text-danger">*</span></label>
                        <textarea name="deskripsi_kerusakan" class="form-control @error('deskripsi_kerusakan') is-invalid @enderror" 
                                  rows="4" required placeholder="Jelaskan detail kerusakan, gejala yang terjadi, dan kondisi saat kerusakan ditemukan">{{ old('deskripsi_kerusakan') }}</textarea>
                        @error('deskripsi_kerusakan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Foto Bukti</label>
                        <input type="file" name="foto_bukti" class="form-control @error('foto_bukti') is-invalid @enderror" 
                               accept="image/*">
                        @error('foto_bukti')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Format: JPG, PNG, max 2MB (Opsional)</small>
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Catatan:</strong> Laporan kerusakan akan dikirim ke Kepala Laboratorium. 
                        Untuk kerusakan sedang/berat, notifikasi juga akan dikirim ke Waka Akademik dan Kepala Sekolah.
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('lab.admin_new.kerusakan.index') }}" class="btn btn-light rounded-pill px-4">
                            <i class="bi bi-x-circle me-2"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-danger rounded-pill px-4">
                            <i class="bi bi-exclamation-triangle me-2"></i>Kirim Laporan
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
// Dynamic inventaris loading based on laboratory selection
const inventarisData = @json($inventaris->groupBy('labor_id'));

document.getElementById('laboratoriumSelect').addEventListener('change', function() {
    const laborId = this.value;
    const inventarisSelect = document.getElementById('inventarisSelect');
    
    // Clear and disable if no lab selected
    if (!laborId) {
        inventarisSelect.innerHTML = '<option value="">-- Pilih Laboratorium Terlebih Dahulu --</option>';
        inventarisSelect.disabled = true;
        return;
    }
    
    // Clear options
    inventarisSelect.innerHTML = '<option value="">-- Pilih Alat/Inventaris --</option>';
    
    // Get inventaris for selected lab
    const items = inventarisData[laborId] || [];
    
    if (items.length === 0) {
        inventarisSelect.innerHTML = '<option value="">-- Tidak ada inventaris di lab ini --</option>';
        inventarisSelect.disabled = true;
        return;
    }
    
    // Add options
    items.forEach(item => {
        const option = document.createElement('option');
        option.value = item.id;
        option.textContent = `${item.nama_inventaris} (${item.jenis}) - Kondisi: ${item.kondisi || 'N/A'}`;
        inventarisSelect.appendChild(option);
    });
    
    inventarisSelect.disabled = false;
});
</script>
@endsection
