@extends('perpustakaan.layouts.main')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="page-title">Manajemen Vendor</h1>
            <p class="text-muted mb-4">Kelola data pemasok buku perpustakaan.</p>

            <button type="button" class="btn btn-primary mb-4" style="background-color: var(--primary); border:none;" data-bs-toggle="modal" data-bs-target="#createVendorModal">
                <i class="bi bi-plus-lg"></i> Tambah Vendor
            </button>

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3">Nama Vendor</th>
                                    <th class="py-3">Alamat</th>
                                    <th class="py-3">Kontak Personal</th>
                                    <th class="py-3">Telepon / Email</th>
                                    <th class="py-3 text-end px-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($vendors as $v)
                                <tr>
                                    <td class="px-4 fw-bold">{{ $v->nama }}</td>
                                    <td>{{ $v->alamat ?? '-' }}</td>
                                    <td>{{ $v->kontak ?? '-' }}</td>
                                    <td>
                                        <small class="d-block">{{ $v->telepon ?? '-' }}</small>
                                        <small class="text-muted">{{ $v->email ?? '-' }}</small>
                                    </td>
                                    <td class="text-end px-4">
                                        <form action="{{ route('perpustakaan.vendor.destroy', $v->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus vendor ini?')">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-shop fs-1 d-block mb-3"></i>
                                        Tidak ada data vendor
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<!-- Modal Create Vendor -->
<div class="modal fade" id="createVendorModal" tabindex="-1" aria-labelledby="createVendorModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('perpustakaan.vendor.store') }}" method="POST">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title" id="createVendorModalLabel">Tambah Vendor Baru</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
                <label class="form-label fw-bold">Nama Vendor <span class="text-danger">*</span></label>
                <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Alamat</label>
                <textarea name="alamat" class="form-control" rows="2"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Kontak Personal (PIC)</label>
                <input type="text" name="kontak" class="form-control">
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">No. Telepon</label>
                    <input type="text" name="telepon" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Email</label>
                    <input type="email" name="email" class="form-control">
                </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary" style="background-color: var(--primary); border:none;">Simpan Vendor</button>
          </div>
      </form>
    </div>
  </div>
</div>
@endsection
