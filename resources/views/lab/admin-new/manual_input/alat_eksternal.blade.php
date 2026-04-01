@extends('lab.layouts.unified', ['title' => 'Input Manual Pinjam Alat Eksternal'])

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-small">
        <li class="breadcrumb-item"><a href="{{ route('lab.admin_new.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('lab.admin_new.peminjaman.internal.index') }}">Peminjaman</a></li>
        <li class="breadcrumb-item active">Input Manual - Pinjam Alat Eksternal</li>
    </ol>
</nav>
@endsection

@section('content')

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="fw-bold mb-0">Input Manual Peminjaman Alat - Eksternal</h5>
                <p class="text-muted small mb-0">Catat peminjaman alat oleh pihak luar sekolah (Orang Luar/Instansi)</p>
            </div>
            <div class="card-body p-4">
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form action="{{ route('lab.admin_new.manual_input.alat_eksternal.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Peminjam <span class="text-danger">*</span></label>
                            <input type="text" name="nama_peminjam" class="form-control @error('nama_peminjam') is-invalid @enderror" 
                                   placeholder="Nama lengkap peminjam" value="{{ old('nama_peminjam') }}" required>
                            @error('nama_peminjam')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Instansi/Lembaga</label>
                            <input type="text" name="instansi" class="form-control @error('instansi') is-invalid @enderror" 
                                   placeholder="Contoh: UNP, SMK 2, dll." value="{{ old('instansi') }}">
                            @error('instansi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kontak/No. HP <span class="text-danger">*</span></label>
                        <input type="text" name="kontak" class="form-control @error('kontak') is-invalid @enderror" 
                               placeholder="08xxxxxxxxxx" value="{{ old('kontak') }}" required>
                        @error('kontak')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Laboratorium <span class="text-danger">*</span></label>
                        <select id="laborSelect" name="labor_id" class="form-select" required>
                            <option value="">-- Pilih Laboratorium --</option>
                            @foreach($laboratories as $lab)
                                <option value="{{ $lab->id }}" {{ old('labor_id') == $lab->id ? 'selected' : '' }}>
                                    {{ $lab->nama_labor }}
                                </option>
                            @endforeach
                        </select>
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
                        <label class="form-label fw-semibold">Jumlah <span class="text-danger">*</span></label>
                        <input type="number" name="jumlah" class="form-control @error('jumlah') is-invalid @enderror" 
                               value="{{ old('jumlah', 1) }}" min="1" required>
                        @error('jumlah')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal Pinjam <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_pinjam" class="form-control @error('tanggal_pinjam') is-invalid @enderror" 
                                   value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required>
                            @error('tanggal_pinjam')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal Kembali <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_kembali" class="form-control @error('tanggal_kembali') is-invalid @enderror" 
                                   value="{{ old('tanggal_kembali', date('Y-m-d')) }}" required>
                            @error('tanggal_kembali')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jam Pinjam <span class="text-danger">*</span></label>
                            <input type="time" name="jam_pinjam" class="form-control @error('jam_pinjam') is-invalid @enderror" 
                                   value="{{ old('jam_pinjam') }}" required>
                            @error('jam_pinjam')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jam Kembali <span class="text-danger">*</span></label>
                            <input type="time" name="jam_kembali" class="form-control @error('jam_kembali') is-invalid @enderror" 
                                   value="{{ old('jam_kembali') }}" required>
                            @error('jam_kembali')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keperluan <span class="text-danger">*</span></label>
                        <textarea name="keperluan" class="form-control @error('keperluan') is-invalid @enderror" 
                                  rows="3" required placeholder="Contoh: Penelitian skripsi atau kegiatan komunitas">{{ old('keperluan') }}</textarea>
                        @error('keperluan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Surat Permohonan (Opsional)</label>
                        <input type="file" name="surat_permohonan" class="form-control @error('surat_permohonan') is-invalid @enderror" accept=".pdf,.jpg,.png">
                        <small class="text-muted">Format: PDF/JPG/PNG. Max: 2MB</small>
                        @error('surat_permohonan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-info border-0 rounded-4">
                        <i class="bi bi-info-circle me-2"></i>
                        Peminjaman eksternal lewat admin akan langsung dianggap <strong>disetujui</strong>.
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('lab.admin_new.peminjaman.internal.index') }}" class="btn btn-light rounded-pill px-4">
                            <i class="bi bi-x-circle me-2"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                            <i class="bi bi-check-circle me-2"></i>Simpan & Setujui
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
const inventarisData = @json($inventaris->groupBy('labor_id'));

document.getElementById('laborSelect').addEventListener('change', function() {
    const laborId = this.value;
    const inventarisSelect = document.getElementById('inventarisSelect');
    
    if (!laborId) {
        inventarisSelect.innerHTML = '<option value="">-- Pilih Laboratorium Terlebih Dahulu --</option>';
        inventarisSelect.disabled = true;
        return;
    }
    
    inventarisSelect.innerHTML = '<option value="">-- Pilih Alat/Inventaris --</option>';
    const items = inventarisData[laborId] || [];
    
    if (items.length === 0) {
        inventarisSelect.innerHTML = '<option value="">-- Tidak ada inventaris tersedia --</option>';
        inventarisSelect.disabled = true;
        return;
    }
    
    items.forEach(item => {
        const option = document.createElement('option');
        option.value = item.id;
        option.textContent = `${item.nama_inventaris} (${item.jenis}) - Tersedia: ${item.jumlah}`;
        inventarisSelect.appendChild(option);
    });
    
    inventarisSelect.disabled = false;
});
</script>
@endsection
