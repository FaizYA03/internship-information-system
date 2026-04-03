@extends('lab.layouts.unified', ['title' => 'Input Manual Ruangan'])

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-small">
        <li class="breadcrumb-item"><a href="{{ route('lab.admin_new.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Input Manual - Pinjam Ruangan Guru</li>
    </ol>
</nav>
@endsection

@section('content')

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="fw-bold mb-0">Input Manual Peminjaman Ruangan - Guru</h5>
                <p class="text-muted small mb-0">Input peminjaman ruangan laboratorium atas nama guru</p>
            </div>
            <div class="card-body p-4">
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form action="{{ route('lab.admin_new.manual_input.ruangan_guru.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jurusan / Departemen <span class="text-danger">*</span></label>
                        <select id="jurusanSelect" class="form-select">
                            <option value="">-- Pilih Jurusan (Opsional) --</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept }}">{{ $dept }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Pilih jurusan untuk mempersempit daftar nama guru</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Guru <span class="text-danger">*</span></label>
                        <select name="guru_id" id="guruSelect" class="form-select @error('guru_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Guru --</option>
                            @foreach($gurus as $guru)
                                <option value="{{ $guru->id }}" data-jurusan="{{ $guru->computed_jurusans }}" {{ old('guru_id') == $guru->id ? 'selected' : '' }}>
                                    {{ $guru->nama }}{{ $guru->nip ? " ($guru->nip)" : "" }}
                                </option>
                            @endforeach
                        </select>
                        @error('guru_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Laboratorium <span class="text-danger">*</span></label>
                        <select name="labor_id" class="form-select @error('labor_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Laboratorium --</option>
                            @foreach($laboratories as $lab)
                                <option value="{{ $lab->id }}" {{ old('labor_id') == $lab->id ? 'selected' : '' }}>
                                    {{ $lab->nama_labor }} (Kapasitas: {{ $lab->kapasitas ?? 30 }} orang)
                                </option>
                            @endforeach
                        </select>
                        @error('labor_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal Pinjam <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" 
                                   value="{{ old('tanggal', date('Y-m-d')) }}" required>
                            @error('tanggal')
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
                                  rows="3" required placeholder="Contoh: Praktikum pemrograman web kelas XII RPL 1, materi Laravel">{{ old('keperluan') }}</textarea>
                        @error('keperluan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kelas/Kegiatan</label>
                        <input type="text" name="kelas" class="form-control @error('kelas') is-invalid @enderror" 
                               value="{{ old('kelas') }}" placeholder="Contoh: XII RPL 1 (Opsional)">
                        @error('kelas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Opsional - Nama kelas atau kegiatan</small>
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Peminjaman akan langsung disetujui (auto-approved) karena diinput oleh admin.
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('lab.admin_new.dashboard') }}" class="btn btn-light rounded-pill px-4">
                            <i class="bi bi-x-circle me-2"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                            <i class="bi bi-check-circle me-2"></i>Simpan Peminjaman
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
// Filter teachers by department
document.getElementById('jurusanSelect').addEventListener('change', function() {
    const selectedJurusan = this.value;
    const guruSelect = document.getElementById('guruSelect');
    const options = guruSelect.querySelectorAll('option');
    
    options.forEach(option => {
        if (option.value === "") return; // Skip placeholder
        
        const teacherJurusan = option.getAttribute('data-jurusan') || '';
        if (!selectedJurusan || teacherJurusan.includes(selectedJurusan)) {
            option.style.display = '';
        } else {
            option.style.display = 'none';
        }
    });

    // Reset selection to placeholder if current selection is hidden
    const currentOption = guruSelect.options[guruSelect.selectedIndex];
    if (currentOption.style.display === 'none') {
        guruSelect.value = "";
    }
});
</script>
@endsection
