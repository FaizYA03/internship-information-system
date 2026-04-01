@extends('lab.layouts.unified', ['title' => 'Master Data'])

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-small">
        <li class="breadcrumb-item"><a href="{{ route('lab.admin_new.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Kelola Data Statis</li>
    </ol>
</nav>
@endsection

@section('css')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-4">
        <h4 class="fw-bold mb-4">Kelola Data Statis Laboratorium</h4>
        
        <!-- Tabs Navigation -->
        <ul class="nav nav-pills mb-4" id="masterDataTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="kategori-tab" data-bs-toggle="pill" data-bs-target="#kategori" type="button" role="tab">
                    <i class="bi bi-tags me-2"></i>Kategori Alat
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="jenis-lab-tab" data-bs-toggle="pill" data-bs-target="#jenis-lab" type="button" role="tab">
                    <i class="bi bi-building me-2"></i>Jenis Laboratorium
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="kondisi-tab" data-bs-toggle="pill" data-bs-target="#kondisi" type="button" role="tab">
                    <i class="bi bi-tools me-2"></i>Status Kondisi
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="sumber-tab" data-bs-toggle="pill" data-bs-target="#sumber" type="button" role="tab">
                    <i class="bi bi-box-seam me-2"></i>Sumber Aset
                </button>
            </li>
        </ul>

        <!-- Tabs Content -->
        <div class="tab-content" id="masterDataTabsContent">
            
            <!-- Kategori Alat Tab -->
            <div class="tab-pane fade show active" id="kategori" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Daftar Kategori Alat</h5>
                    <button class="btn btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#addKategoriModal">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Kategori
                    </button>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="50">#</th>
                                <th>Nama Kategori</th>
                                <th>Deskripsi</th>
                                <th width="150" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kategoriAlat as $index => $kat)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><strong>{{ $kat->nama }}</strong></td>
                                    <td>{{ $kat->deskripsi ?? '-' }}</td>
                                    <td class="text-center table-actions">
                                        <button class="btn btn-sm btn-warning" onclick="editKategori({{ $kat->id }}, '{{ $kat->nama }}', '{{ $kat->deskripsi }}')">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="{{ route('lab.admin_new.master_data.kategori.destroy', $kat->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus kategori ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Belum ada kategori alat</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Jenis Laboratorium Tab -->
            <div class="tab-pane fade" id="jenis-lab" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Daftar Jenis Laboratorium</h5>
                    <button class="btn btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#addJenisLabModal">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Jenis Lab
                    </button>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="50">#</th>
                                <th>Nama Jenis</th>
                                <th>Deskripsi</th>
                                <th width="150" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jenisLab as $index => $jenis)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><strong>{{ $jenis->nama }}</strong></td>
                                    <td>{{ $jenis->deskripsi ?? '-' }}</td>
                                    <td class="text-center table-actions">
                                        <button class="btn btn-sm btn-warning" onclick="editJenisLab({{ $jenis->id }}, '{{ $jenis->nama }}', '{{ $jenis->deskripsi }}')">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="{{ route('lab.admin_new.master_data.jenis_lab.destroy', $jenis->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus jenis lab ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Belum ada jenis laboratorium</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Status Kondisi Tab -->
            <div class="tab-pane fade" id="kondisi" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Daftar Status Kondisi</h5>
                    <button class="btn btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#addKondisiModal">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Status
                    </button>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="50">#</th>
                                <th>Nama Status</th>
                                <th>Deskripsi</th>
                                <th>Warna Badge</th>
                                <th width="150" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($statusKondisi as $index => $status)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><strong>{{ $status->nama }}</strong></td>
                                    <td>{{ $status->deskripsi ?? '-' }}</td>
                                    <td><span class="badge bg-{{ $status->warna }}">{{ $status->nama }}</span></td>
                                    <td class="text-center table-actions">
                                        <button class="btn btn-sm btn-warning" onclick="editKondisi({{ $status->id }}, '{{ $status->nama }}', '{{ $status->deskripsi }}', '{{ $status->warna }}')">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="{{ route('lab.admin_new.master_data.kondisi.destroy', $status->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus status ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Belum ada status kondisi</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Sumber Aset Tab -->
            <div class="tab-pane fade" id="sumber" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Daftar Sumber Aset</h5>
                    <button class="btn btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#addSumberModal">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Sumber Aset
                    </button>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="50">#</th>
                                <th>Nama Sumber</th>
                                <th>Deskripsi</th>
                                <th width="150" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sumberAset as $index => $sumber)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><strong>{{ $sumber->nama }}</strong></td>
                                    <td>{{ $sumber->deskripsi ?? '-' }}</td>
                                    <td class="text-center table-actions">
                                        <button class="btn btn-sm btn-warning" onclick="editSumber({{ $sumber->id }}, '{{ $sumber->nama }}', '{{ $sumber->deskripsi }}')">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="{{ route('lab.admin_new.master_data.sumber.destroy', $sumber->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus sumber aset ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Belum ada sumber aset</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals for Add/Edit -->
@include('lab.admin.master_data.modals')

@endsection

@section('script')
<script>
function editKategori(id, nama, deskripsi) {
    document.getElementById('editKategoriId').value = id;
    document.getElementById('editKategoriNama').value = nama;
    document.getElementById('editKategoriDeskripsi').value = deskripsi;
    document.getElementById('editKategoriForm').action = `/lab/admin-new/master-data/kategori/${id}`;
    new bootstrap.Modal(document.getElementById('editKategoriModal')).show();
}

function editJenisLab(id, nama, deskripsi) {
    document.getElementById('editJenisLabId').value = id;
    document.getElementById('editJenisLabNama').value = nama;
    document.getElementById('editJenisLabDeskripsi').value = deskripsi;
    document.getElementById('editJenisLabForm').action = `/lab/admin-new/master-data/jenis-lab/${id}`;
    new bootstrap.Modal(document.getElementById('editJenisLabModal')).show();
}

function editKondisi(id, nama, deskripsi, warna) {
    document.getElementById('editKondisiId').value = id;
    document.getElementById('editKondisiNama').value = nama;
    document.getElementById('editKondisiDeskripsi').value = deskripsi;
    document.getElementById('editKondisiWarna').value = warna;
    document.getElementById('editKondisiForm').action = `/lab/admin-new/master-data/kondisi/${id}`;
    new bootstrap.Modal(document.getElementById('editKondisiModal')).show();
}

function editSumber(id, nama, deskripsi) {
    document.getElementById('editSumberId').value = id;
    document.getElementById('editSumberNama').value = nama;
    document.getElementById('editSumberDeskripsi').value = deskripsi;
    document.getElementById('editSumberForm').action = `/lab/admin-new/master-data/sumber/${id}`;
    new bootstrap.Modal(document.getElementById('editSumberModal')).show();
}
</script>
@endsection
