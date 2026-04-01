@extends('lab.layouts.unified', ['title' => 'Edit Peminjaman Ruangan'])

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-small">
        <li class="breadcrumb-item"><a href="{{ route('lab.admin_new.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('lab.admin_new.peminjaman.internal.index') }}">Peminjaman</a></li>
        <li class="breadcrumb-item active">Edit Peminjaman Ruangan</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="fw-bold mb-0">Edit Peminjaman Ruangan</h5>
                <p class="text-muted small mb-0">Perbarui data reservasi laboratorium</p>
            </div>
            <div class="card-body p-4">
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form action="{{ route('lab.admin_new.peminjaman.ruangan.update', $pinjam->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Peminjam</label>
                        <input type="text" class="form-control bg-light" value="{{ $pinjam->user->nama ?? $pinjam->nama }}" readonly>
                        <small class="text-muted">Nama peminjam tidak bisa diubah dari sini</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Laboratorium <span class="text-danger">*</span></label>
                        <select name="labor_id" class="form-select @error('labor_id') is-invalid @enderror" required>
                            @foreach($laboratories as $lab)
                                <option value="{{ $lab->id }}" {{ $pinjam->labor_id == $lab->id ? 'selected' : '' }}>
                                    {{ $lab->nama_labor }}
                                </option>
                            @endforeach
                        </select>
                        @error('labor_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" 
                               value="{{ old('tanggal', $pinjam->tanggal) }}" required>
                        @error('tanggal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Waktu (Jam Mulai - Jam Selesai) <span class="text-danger">*</span></label>
                        <input type="text" name="waktu" class="form-control @error('waktu') is-invalid @enderror" 
                               value="{{ old('waktu', $pinjam->waktu) }}" placeholder="Contoh: 08:00 - 10:00" required>
                        @error('waktu')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
                            <option value="pending" {{ $pinjam->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ $pinjam->status == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ $pinjam->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
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
