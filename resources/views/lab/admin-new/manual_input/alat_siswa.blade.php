@extends('lab.layouts.unified', ['title' => 'Input Manual Pinjam Alat'])

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-small">
        <li class="breadcrumb-item"><a href="{{ route('lab.admin_new.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Input Manual - Pinjam Alat Siswa</li>
    </ol>
</nav>
@endsection

@section('content')

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="fw-bold mb-0">Input Manual Peminjaman Alat - Siswa</h5>
                <p class="text-muted small mb-0">Input peminjaman alat atas nama siswa</p>
            </div>
            <div class="card-body p-4">
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form action="{{ route('lab.admin_new.manual_input.alat_siswa.store') }}" method="POST">
                    @csrf

                    {{-- Step 1: Filter Jurusan --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jurusan <span class="text-danger">*</span></label>
                        <select id="jurusanSelect" class="form-select" required>
                            <option value="">-- Pilih Jurusan --</option>
                            @foreach($jurusanList as $jurusan)
                                <option value="{{ $jurusan }}">{{ $jurusan }}</option>
                            @endforeach
                        </select>
                        <div class="form-text text-muted"><i class="bi bi-info-circle me-1"></i>Pilih jurusan dahulu untuk mempersempit daftar kelas.</div>
                    </div>

                    {{-- Step 2: Pilih Kelas (filtered by Jurusan) --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kelas <span class="text-danger">*</span></label>
                        <select id="kelasSelect" class="form-select" required disabled>
                            <option value="">-- Pilih Jurusan Terlebih Dahulu --</option>
                        </select>
                    </div>

                    {{-- Step 3: Pilih Siswa (filtered by Kelas) --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Siswa <span class="text-danger">*</span></label>
                        <select name="siswa_id" id="siswaSelect" class="form-select @error('siswa_id') is-invalid @enderror" required disabled>
                            <option value="">-- Pilih Kelas Terlebih Dahulu --</option>
                        </select>
                        @error('siswa_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Laboratorium <span class="text-danger">*</span></label>
                        <select id="laborSelect" name="labor_id" class="form-select @error('labor_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Laboratorium --</option>
                            @foreach($laboratories as $lab)
                                <option value="{{ $lab->id }}" {{ old('labor_id') == $lab->id ? 'selected' : '' }}>
                                    {{ $lab->nama_labor }}
                                </option>
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
                                  rows="3" required placeholder="Contoh: Praktikum pemrograman web">{{ old('keperluan') }}</textarea>
                        @error('keperluan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
// Data dari server
const kelasData = @json($classes);  // array of {id, nama_kelas, jurusan}
const siswaData = @json($siswa->groupBy('kelas_id'));
const inventarisData = @json($inventaris->groupBy('labor_id'));

// --- Step 1: Jurusan dipilih → tampilkan Kelas yang sesuai ---
document.getElementById('jurusanSelect').addEventListener('change', function() {
    const jurusan = this.value;
    const kelasSelect = document.getElementById('kelasSelect');
    const siswaSelect = document.getElementById('siswaSelect');

    // Reset downstream
    siswaSelect.innerHTML = '<option value="">-- Pilih Kelas Terlebih Dahulu --</option>';
    siswaSelect.disabled = true;

    if (!jurusan) {
        kelasSelect.innerHTML = '<option value="">-- Pilih Jurusan Terlebih Dahulu --</option>';
        kelasSelect.disabled = true;
        return;
    }

    // Filter kelas berdasarkan jurusan
    const filtered = kelasData.filter(k => k.jurusan === jurusan);
    kelasSelect.innerHTML = '<option value="">-- Pilih Kelas --</option>';

    if (filtered.length === 0) {
        kelasSelect.innerHTML = '<option value="">-- Tidak ada kelas untuk jurusan ini --</option>';
        kelasSelect.disabled = true;
        return;
    }

    filtered.forEach(k => {
        const opt = document.createElement('option');
        opt.value = k.id;
        opt.textContent = k.nama_kelas;
        kelasSelect.appendChild(opt);
    });
    kelasSelect.disabled = false;
});

// --- Step 2: Kelas dipilih → tampilkan Siswa ---
document.getElementById('kelasSelect').addEventListener('change', function() {
    const kelasId = this.value;
    const siswaSelect = document.getElementById('siswaSelect');

    if (!kelasId) {
        siswaSelect.innerHTML = '<option value="">-- Pilih Kelas Terlebih Dahulu --</option>';
        siswaSelect.disabled = true;
        return;
    }

    siswaSelect.innerHTML = '<option value="">-- Pilih Siswa --</option>';
    const students = siswaData[kelasId] || [];

    if (students.length === 0) {
        siswaSelect.innerHTML = '<option value="">-- Tidak ada siswa di kelas ini --</option>';
        siswaSelect.disabled = true;
        return;
    }

    students.forEach(s => {
        const opt = document.createElement('option');
        opt.value = s.id;
        const nisText = s.nis ? ` (${s.nis})` : '';
        opt.textContent = s.nama + nisText;
        siswaSelect.appendChild(opt);
    });
    siswaSelect.disabled = false;
});

// --- Inventaris: Laboratorium dipilih → tampilkan Alat ---
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
        const opt = document.createElement('option');
        opt.value = item.id;
        opt.textContent = `${item.nama_inventaris} (${item.jenis}) - Tersedia: ${item.jumlah}`;
        inventarisSelect.appendChild(opt);
    });
    inventarisSelect.disabled = false;
});
</script>
@endsection
