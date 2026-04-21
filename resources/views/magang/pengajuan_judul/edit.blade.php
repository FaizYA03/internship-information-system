@extends('magang.layouts.main')

@section('content')
<div class="container">
    <div class="mb-4">
        <h3 class="mb-1 fw-bold">Edit Pengajuan Judul Laporan Akhir Magang</h3>
        <p class="text-muted mb-0">Perbarui data pengajuan Anda sebelum direview oleh pembimbing.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('magang.pengajuan_judul.update', $pengajuan->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Nama -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Lengkap</label>
                    <input type="text" class="form-control" value="{{ Auth::user()->nama }}" disabled>
                </div>

                <!-- NIS -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">NIS/NISN</label>
                    <input type="text" class="form-control" value="{{ Auth::user()->nis_nip ?? 'Belum diatur' }}" disabled>
                </div>

                <!-- Jurusan -->
                <div class="mb-3">
                    <label for="jurusan" class="form-label fw-semibold">Jurusan</label>
                    <select name="jurusan" id="jurusan" class="form-select @error('jurusan') is-invalid @enderror" required>
                        <option value="">-- Pilih Jurusan --</option>
                        <option value="Bisnis Konstruksi dan Properti" {{ $pengajuan->jurusan == 'Bisnis Konstruksi dan Properti' ? 'selected' : '' }}>Bisnis Konstruksi dan Properti</option>
                        <option value="Desain Pemodelan dan Informasi Bangunan" {{ $pengajuan->jurusan == 'Desain Pemodelan dan Informasi Bangunan' ? 'selected' : '' }}>Desain Pemodelan dan Informasi Bangunan</option>
                        <option value="Teknik Audio Video A" {{ $pengajuan->jurusan == 'Teknik Audio Video A' ? 'selected' : '' }}>Teknik Audio Video A</option>
                        <option value="Teknik Elektronika Industri" {{ $pengajuan->jurusan == 'Teknik Elektronika Industri' ? 'selected' : '' }}>Teknik Elektronika Industri</option>
                        <option value="Teknik Instalasi Tenaga Listrik A" {{ $pengajuan->jurusan == 'Teknik Instalasi Tenaga Listrik A' ? 'selected' : '' }}>Teknik Instalasi Tenaga Listrik A</option>
                        <option value="Teknik Pemesinan A" {{ $pengajuan->jurusan == 'Teknik Pemesinan A' ? 'selected' : '' }}>Teknik Pemesinan A</option>
                        <option value="Teknik Kendaraan Ringan A" {{ $pengajuan->jurusan == 'Teknik Kendaraan Ringan A' ? 'selected' : '' }}>Teknik Kendaraan Ringan A</option>
                        <option value="Teknik Bodi Kendaraan Ringan" {{ $pengajuan->jurusan == 'Teknik Bodi Kendaraan Ringan' ? 'selected' : '' }}>Teknik Bodi Kendaraan Ringan</option>
                        <option value="Teknik Bisnis Sepeda Motor A" {{ $pengajuan->jurusan == 'Teknik Bisnis Sepeda Motor A' ? 'selected' : '' }}>Teknik Bisnis Sepeda Motor A</option>
                        <option value="Teknik Pendingin dan Tata Udara" {{ $pengajuan->jurusan == 'Teknik Pendingin dan Tata Udara' ? 'selected' : '' }}>Teknik Pendingin dan Tata Udara</option>
                        <option value="Teknik Komputer Jaringan A" {{ $pengajuan->jurusan == 'Teknik Komputer Jaringan A' ? 'selected' : '' }}>Teknik Komputer Jaringan A</option>
                    </select>
                    @error('jurusan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Perusahaan -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Perusahaan</label>
                    <input type="text" class="form-control" value="{{ $namaPerusahaan ?? 'Belum terhubung' }}" disabled>
                    <input type="hidden" name="wakil_perusahaan_id" value="{{ $wakilPerusahaanId }}">
                </div>

                <!-- Judul -->
                <div class="mb-3">
                    <label for="judul_laporan" class="form-label fw-semibold">Judul Laporan</label>
                    <input type="text" name="judul_laporan" id="judul_laporan" class="form-control @error('judul_laporan') is-invalid @enderror" value="{{ old('judul_laporan', $pengajuan->judul_laporan) }}" required>
                    @error('judul_laporan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Link Drive -->
                <div class="mb-4">
                    <label for="link_drive" class="form-label fw-semibold">Link Google Drive</label>
                    <input type="url" name="link_drive" id="link_drive" class="form-control @error('link_drive') is-invalid @enderror" 
                           placeholder="https://drive.google.com/..." value="{{ old('link_drive', $pengajuan->link_drive) }}" required>
                    <small class="text-muted d-block mt-2">Upload proposal / file pendukung ke Google Drive</small>
                    @error('link_drive')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-check-circle me-2"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('magang.pengajuan_judul.indexsiswa') }}" class="btn btn-secondary btn-lg">
                        <i class="bi bi-x-circle me-2"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
