@extends('lab.layouts.unified', ['title' => 'Edit Peminjaman Alat'])

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-small">
        <li class="breadcrumb-item"><a href="{{ route('lab.admin_new.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('lab.admin_new.peminjaman.internal.index') }}">Peminjaman</a></li>
        <li class="breadcrumb-item active">Edit Peminjaman Alat</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="fw-bold mb-0">Edit Peminjaman Alat</h5>
                <p class="text-muted small mb-0">Perbarui data peminjaman inventaris</p>
            </div>
            <div class="card-body p-4">
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form action="{{ route('lab.admin_new.peminjaman.alat.update', $pinjam->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Peminjam</label>
                        <input type="text" class="form-control bg-light" value="{{ $pinjam->user->nama }}" readonly>
                        <small class="text-muted">Nama peminjam tidak bisa diubah</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Alat/Inventaris <span class="text-danger">*</span></label>
                        <select name="inventaris_id" class="form-select @error('inventaris_id') is-invalid @enderror" required>
                            @foreach($inventaris as $item)
                                <option value="{{ $item->id }}" {{ $pinjam->inventaris_id == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama_inventaris }} ({{ $item->labor->nama_labor ?? 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                        @error('inventaris_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jumlah <span class="text-danger">*</span></label>
                        <input type="number" name="jumlah" class="form-control @error('jumlah') is-invalid @enderror" 
                               value="{{ old('jumlah', $pinjam->jumlah) }}" min="1" required>
                        @error('jumlah')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal Pinjam <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_pinjam" class="form-control @error('tanggal_pinjam') is-invalid @enderror" 
                                   value="{{ old('tanggal_pinjam', $pinjam->tanggal_pinjam) }}" required>
                            @error('tanggal_pinjam')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal Kembali <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_kembali" class="form-control @error('tanggal_kembali') is-invalid @enderror" 
                                   value="{{ old('tanggal_kembali', $pinjam->tanggal_kembali) }}" required>
                            @error('tanggal_kembali')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jam Pinjam <span class="text-danger">*</span></label>
                            <input type="time" name="jam_pinjam" class="form-control @error('jam_pinjam') is-invalid @enderror" 
                                   value="{{ old('jam_pinjam', $pinjam->jam_pinjam) }}" required>
                            @error('jam_pinjam')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jam Kembali <span class="text-danger">*</span></label>
                            <input type="time" name="jam_kembali" class="form-control @error('jam_kembali') is-invalid @enderror" 
                                   value="{{ old('jam_kembali', $pinjam->jam_kembali) }}" required>
                            @error('jam_kembali')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keperluan <span class="text-danger">*</span></label>
                        <textarea name="keperluan" class="form-control @error('keperluan') is-invalid @enderror" 
                                  rows="3" required>{{ old('keperluan', $pinjam->keperluan) }}</textarea>
                        @error('keperluan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <option value="pending" {{ $pinjam->status == 'pending' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                            <option value="approved" {{ $pinjam->status == 'approved' ? 'selected' : '' }}>Sedang Dipinjam</option>
                            <option value="returned" {{ $pinjam->status == 'returned' ? 'selected' : '' }}>Sudah Kembali</option>
                            <option value="rejected" {{ $pinjam->status == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>

                    <div class="d-flex gap-2 justify-content-end mt-4">
                        <a href="{{ route('lab.admin_new.peminjaman.internal.index') }}" class="btn btn-light rounded-pill px-4">
                            <i class="bi bi-x-circle me-2"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                            <i class="bi bi-check-circle me-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
