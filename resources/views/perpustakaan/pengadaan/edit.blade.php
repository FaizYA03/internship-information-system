@extends('perpustakaan.layouts.main')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="page-title">Edit Draft Pengadaan</h1>
            <p class="text-muted mb-4">Perbarui informasi dasar pengadaan atau ubah vendor.</p>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('perpustakaan.pengadaan.update', $pengadaan->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Judul Pengadaan <span class="text-danger">*</span></label>
                            <input type="text" name="judul" class="form-control" value="{{ $pengadaan->judul }}" required>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Deskripsi Tambahan</label>
                            <textarea name="deskripsi" class="form-control" rows="3">{{ $pengadaan->deskripsi }}</textarea>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Pilih Vendor</label>
                            <select name="vendor_id" class="form-select">
                                <option value="">-- Belum Ditentukan --</option>
                                @foreach($vendors as $v)
                                    <option value="{{ $v->id }}" {{ $pengadaan->vendor_id == $v->id ? 'selected' : '' }}>{{ $v->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="alert alert-info">
                            📝 Daftar item buku tidak dapat diubah di halaman ini. Hapus draft dan buat ulang bila terjadi kesalahan fatal pada input item.
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('perpustakaan.pengadaan.show', $pengadaan->id) }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary" style="background-color: var(--primary); border:none;">
                                <i class="bi bi-save"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
