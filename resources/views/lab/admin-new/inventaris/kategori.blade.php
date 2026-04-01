@extends('lab.layouts.unified', ['title' => 'Manajemen Kategori'])

@section('css')
<style>
    .category-card {
        border: none;
        border-radius: 16px;
        transition: all 0.3s ease;
        overflow: hidden;
    }
    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.08);
    }
    .btn-add {
        background: linear-gradient(135deg, #2563EB, #1D4ED8);
        border: none;
        padding: 10px 24px;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.2s;
    }
    .btn-add:hover {
        box-shadow: 0 4px 12px rgba(37,99,235,0.3);
        transform: scale(1.02);
    }
    .stats-card {
        background: white;
        padding: 24px;
        border-radius: 14px;
        border: 1px solid #F1F5F9;
    }
    .icon-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .table-modern thead th {
        background: #F8FAFC;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        color: #64748B;
        border: none;
        padding: 16px;
    }
    .table-modern tbody td {
        padding: 16px;
        vertical-align: middle;
        border-bottom: 1px solid #F1F5F9;
    }
    .search-control {
        border-radius: 12px;
        padding: 10px 16px;
        border: 1.5px solid #E2E8F0;
        transition: all 0.2s;
    }
    .search-control:focus {
        border-color: #2563EB;
        box-shadow: 0 0 0 4px rgba(37,99,235,0.1);
    }
</style>
@endsection

@section('content')
<div class="row g-4 mb-4">
    <div class="col-12 col-md-4">
        <div class="stats-card">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-box bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-tags"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0">{{ $categories->count() }}</h5>
                    <p class="text-muted small mb-0">Total Kategori</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h5 class="fw-bold mb-1">Daftar Kategori Inventaris</h5>
                <p class="text-muted small mb-0">Kelola kategori untuk klasifikasi alat dan bahan laboratorium.</p>
            </div>
            <button class="btn btn-primary btn-add" data-bs-toggle="modal" data-bs-target="#addKategoriModal">
                <i class="bi bi-plus-lg me-2"></i>Tambah Kategori
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th width="80">ID</th>
                        <th>NAMA KATEGORI</th>
                        <th>DESKRIPSI</th>
                        <th width="150" class="text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $cat)
                        <tr>
                            <td class="text-muted fw-medium">#{{ $cat->id }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $cat->nama }}</div>
                            </td>
                            <td class="text-muted small">
                                {{ $cat->deskripsi ?? 'Tidak ada deskripsi' }}
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-sm btn-light border" onclick="editKategori({{ $cat->id }}, '{{ $cat->nama }}', '{{ $cat->deskripsi }}')" title="Edit">
                                        <i class="bi bi-pencil-square text-primary"></i>
                                    </button>
                                    <form action="{{ route('lab.admin_new.master_data.kategori.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini? Data alat dengan kategori ini mungkin perlu diperbarui.')">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="from_kategori_page" value="1">
                                        <button type="submit" class="btn btn-sm btn-light border" title="Hapus">
                                            <i class="bi bi-trash text-danger"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="text-muted mb-3">
                                    <i class="bi bi-tags fs-1 d-block mb-3 opacity-25"></i>
                                    Belum ada kategori yang ditambahkan.
                                </div>
                                <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addKategoriModal">
                                    <i class="bi bi-plus-lg"></i> Tambah Kategori Pertama
                                </button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="addKategoriModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold">Tambah Kategori Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('lab.admin_new.master_data.kategori.store') }}" method="POST">
                @csrf
                <input type="hidden" name="from_kategori_page" value="1">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control rounded-3" placeholder="Contoh: Alat Ukur, Bahan Kimia..." required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold small">Deskripsi</label>
                        <textarea name="deskripsi" rows="3" class="form-control rounded-3" placeholder="Tuliskan keterangan kategori jika diperlukan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4">Simpan Kategori</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editKategoriModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold">Edit Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editKategoriForm" action="" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="from_kategori_page" value="1">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="editKategoriNama" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold small">Deskripsi</label>
                        <textarea name="deskripsi" id="editKategoriDeskripsi" rows="3" class="form-control rounded-3"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    function editKategori(id, nama, deskripsi) {
        document.getElementById('editKategoriNama').value = nama;
        document.getElementById('editKategoriDeskripsi').value = deskripsi;
        document.getElementById('editKategoriForm').action = `/lab/admin-new/master-data/kategori/${id}`;
        new bootstrap.Modal(document.getElementById('editKategoriModal')).show();
    }
</script>
@endsection
