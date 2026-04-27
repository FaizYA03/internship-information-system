@extends('magang.layouts.main')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="font-weight-bolder mb-0">Kelola Supervisor Instansi</h2>
                    <p class="text-muted mb-0">Daftarkan dan kelola pembimbing lapangan di perusahaan Anda.</p>
                </div>
                <button type="button" class="btn btn-primary bg-gradient-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="fas fa-plus me-2"></i> Tambah Supervisor
                </button>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show text-white" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card mb-4" style="border-radius: 12px; border: none; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                <div class="card-header pb-0 bg-white" style="border-bottom: 1px solid #f0f2f5;">
                    <h6>Daftar Supervisor Mitra</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-4">Nama & Jabatan</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">NIP/ID</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Departemen</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kontak HP/WA</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($supervisors as $spv)
                                <tr>
                                    <td class="px-4">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">{{ $spv->nama_lengkap }}</h6>
                                            <p class="text-xs text-secondary mb-0">{{ $spv->jabatan ?? '-' }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $spv->nip ?? '-' }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">
                                            <span class="badge bg-light text-dark">{{ $spv->departemen ?? '-' }}</span>
                                        </p>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="text-secondary text-xs font-weight-bold">{{ $spv->no_hp ?? '-' }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <button class="btn btn-link text-dark px-3 mb-0" data-bs-toggle="modal" data-bs-target="#editModal{{ $spv->id }}">
                                            <i class="fas fa-pencil-alt text-dark me-2" aria-hidden="true"></i>Edit
                                        </button>
                                        <button class="btn btn-link text-danger text-gradient px-3 mb-0" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $spv->id }}">
                                            <i class="far fa-trash-alt me-2"></i>Delete
                                        </button>
                                    </td>
                                </tr>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editModal{{ $spv->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $spv->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('magang.wakil_perusahaan.supervisors.update', $spv->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header bg-light">
                                                    <h5 class="modal-title" id="editModalLabel{{ $spv->id }}">Edit Data Supervisor</h5>
                                                    <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <div class="mb-3">
                                                        <label class="form-label form-label-sm fw-bold">Nama Lengkap *</label>
                                                        <input type="text" name="nama_lengkap" class="form-control" value="{{ $spv->nama_lengkap }}" required>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label form-label-sm fw-bold">NIP / ID Karyawan</label>
                                                            <input type="text" name="nip" class="form-control" value="{{ $spv->nip }}">
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label form-label-sm fw-bold">No. HP / WA</label>
                                                            <input type="text" name="no_hp" class="form-control" value="{{ $spv->no_hp }}">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label form-label-sm fw-bold">Jabatan</label>
                                                            <input type="text" name="jabatan" class="form-control" value="{{ $spv->jabatan }}" placeholder="Misal: Senior Developer">
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label form-label-sm fw-bold">Departemen</label>
                                                            <input type="text" name="departemen" class="form-control" value="{{ $spv->departemen }}" placeholder="Misal: IT Development">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal{{ $spv->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $spv->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('magang.wakil_perusahaan.supervisors.destroy', $spv->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title" id="deleteModalLabel{{ $spv->id }}">Hapus Supervisor</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <p>Apakah Anda yakin ingin menghapus data supervisor <strong>{{ $spv->nama_lengkap }}</strong>? Tindakan ini tidak dapat dibatalkan.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-danger">Ya, Hapus Permenen</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center justify-content-center text-muted">
                                            <i class="fas fa-users-slash fa-3x mb-3 text-secondary opacity-5"></i>
                                            <h5>Belum Ada Data Supervisor</h5>
                                            <p>Tambahkan supervisor dari perusahaan Anda untuk memudahkan pendelegasian siswa magang.</p>
                                        </div>
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

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('magang.wakil_perusahaan.supervisors.store') }}" method="POST">
                @csrf
                <div class="modal-header text-white" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark));">
                    <h5 class="modal-title" id="createModalLabel"><i class="fas fa-user-plus me-2"></i> Tambah Supervisor</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label form-label-sm fw-bold">Nama Lengkap *</label>
                        <input type="text" name="nama_lengkap" class="form-control" required placeholder="Masukkan nama lengkap dengan gelar">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label form-label-sm fw-bold">NIP / ID Karyawan</label>
                            <input type="text" name="nip" class="form-control" placeholder="ID jika ada">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label form-label-sm fw-bold">No. HP / WA</label>
                            <input type="text" name="no_hp" class="form-control" placeholder="Awali dgn 08/62">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label form-label-sm fw-bold">Jabatan</label>
                            <input type="text" name="jabatan" class="form-control" placeholder="Misal: Senior Developer">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label form-label-sm fw-bold">Departemen</label>
                            <input type="text" name="departemen" class="form-control" placeholder="Misal: IT Development">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border: none;">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
