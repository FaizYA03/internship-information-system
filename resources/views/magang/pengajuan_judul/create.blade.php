@extends('magang.layouts.main')

@section('content')
<div class="container">
    <h3>Form Pengajuan Judul Laporan Akhir Magang</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('pengajuan-judul.store') }}" method="POST">
        @csrf

        <!-- Nama -->
        <div class="mb-3">
            <label>Nama Lengkap</label>
            <input type="text" class="form-control" value="{{ Auth::user()->nama }}" disabled>
        </div>

        <!-- NIS -->
        <div class="mb-3">
            <label>NIS/NISN</label>
            <input type="text" class="form-control" value="{{ Auth::user()->nis_nip ?? 'Belum diatur' }}" disabled>
        </div>

        <!-- Jurusan -->
        <div class="mb-3">
            <label for="jurusan">Jurusan</label>
            <select name="jurusan" class="form-control" required>
                <option value="">-- Pilih Jurusan --</option>
                <option value="Bisnis Konstruksi dan Properti">Bisnis Konstruksi dan Properti</option>
                <option value="Desain Pemodelan dan Informasi Bangunan">Desain Pemodelan dan Informasi Bangunan</option>
                <option value="Teknik Audio Video A">Teknik Audio Video A</option>
                <option value="Teknik Elektronika Industri">Teknik Elektronika Industri</option>
                <option value="Teknik Instalasi Tenaga Listrik A">Teknik Instalasi Tenaga Listrik A</option>
                <option value="Teknik Pemesinan A">Teknik Pemesinan A</option>
                <option value="Teknik Kendaraan Ringan A">Teknik Kendaraan Ringan A</option>
                <option value="Teknik Bodi Kendaraan Ringan">Teknik Bodi Kendaraan Ringan</option>
                <option value="Teknik Bisnis Sepeda Motor A">Teknik Bisnis Sepeda Motor A</option>
                <option value="Teknik Pendingin dan Tata Udara">Teknik Pendingin dan Tata Udara</option>
                <option value="Teknik Komputer Jaringan A">Teknik Komputer Jaringan A</option>
            </select>
        </div>

        <!-- Perusahaan -->
        <div class="mb-3">
            <label>Nama Perusahaan</label>
            <input type="text" class="form-control" value="{{ $namaPerusahaan ?? 'Belum terhubung' }}" disabled>
            <input type="hidden" name="wakil_perusahaan_id" value="{{ $wakilPerusahaanId }}">
        </div>

        <!-- Judul -->
        <div class="mb-3">
            <label for="judul_laporan">Judul Laporan</label>
            <input type="text" name="judul_laporan" class="form-control" required>
        </div>

        <!-- ✅ LINK DRIVE (WAJIB SESUAI DB) -->
        <div class="mb-3">
            <label for="link_drive">Link Google Drive</label>
            <input type="url" name="link_drive" class="form-control" placeholder="https://drive.google.com/..." required>
            <small class="text-muted">Upload proposal / file pendukung ke Google Drive</small>
        </div>

        <button type="submit" class="btn btn-primary">Kirim</button>
    </form>
</div>
@endsection