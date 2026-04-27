@extends('perpustakaan.layouts.main')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="page-title">Sistem Pengadaan Buku</h1>
            <p class="text-muted mb-4">Kelola usulan, pembelian, dan penerimaan buku perpustakaan.</p>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-3">
                    <form action="{{ route('perpustakaan.pengadaan.index') }}" method="GET" class="row align-items-end" id="filterForm">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label for="status" class="form-label form-label-sm">Filter Status</label>
                            <select name="status" id="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">Semua Status</option>
                                <option value="Draft" {{ request('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                                <option value="Menunggu Persetujuan" {{ request('status') == 'Menunggu Persetujuan' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                                <option value="Disetujui" {{ request('status') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                                <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                                <option value="Diterima" {{ request('status') == 'Diterima' ? 'selected' : '' }}>Diterima & Dikatalogkan</option>
                            </select>
                        </div>
                        <div class="col-md-8 d-flex justify-content-md-end gap-2">
                            <div class="dropdown">
                                <button class="btn btn-success dropdown-toggle btn-sm" type="button" id="exportMenu" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-download"></i> Export Data
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="exportMenu">
                                    <li>
                                        <a class="dropdown-item text-danger fw-bold" href="{{ route('perpustakaan.pengadaan.export.pdf', request()->all()) }}">
                                            <i class="bi bi-file-earmark-pdf"></i> Export PDF
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-success fw-bold" href="{{ route('perpustakaan.pengadaan.export.excel', request()->all()) }}">
                                            <i class="bi bi-file-earmark-excel"></i> Export Excel
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @if(in_array(auth()->user()->role, ['admin_perpus', 'super_admin']))
            <div class="mb-3">
                <a href="{{ route('perpustakaan.pengadaan.create') }}" class="btn btn-primary" style="background-color: var(--primary); border:none;">
                    <i class="bi bi-plus-lg"></i> Buat Draft Pengadaan
                </a>
            </div>
            @endif

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
                                    <th class="px-4 py-3">ID</th>
                                    <th class="py-3">Judul Pengadaan</th>
                                    <th class="py-3">Vendor</th>
                                    <th class="py-3">Estimasi</th>
                                    <th class="py-3">Status</th>
                                    <th class="py-3">Tanggal Diterima</th>
                                    <th class="py-3 text-end px-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pengadaans as $p)
                                <tr>
                                    <td class="px-4 fw-bold">#{{ str_pad($p->id, 4, '0', STR_PAD_LEFT) }}</td>
                                    <td>
                                        <h6 class="mb-0">{{ $p->judul }}</h6>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($p->created_at)->format('d M Y') }}</small>
                                    </td>
                                    <td>{{ $p->vendor->nama ?? '-' }}</td>
                                    <td>Rp {{ number_format($p->total_estimasi, 0, ',', '.') }}</td>
                                    <td>
                                        @if($p->status == 'Draft')
                                            <span class="badge bg-secondary">Draft</span>
                                        @elseif($p->status == 'Menunggu Persetujuan')
                                            <span class="badge bg-info">Menunggu Persetujuan</span>
                                        @elseif($p->status == 'Disetujui')
                                            <span class="badge bg-success">Disetujui</span>
                                        @elseif($p->status == 'Ditolak')
                                            <span class="badge bg-danger">Ditolak</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Diterima</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $p->tanggal_diterima ? \Carbon\Carbon::parse($p->tanggal_diterima)->format('d M Y') : '-' }}
                                    </td>
                                    <td class="text-end px-4">
                                        <a href="{{ route('perpustakaan.pengadaan.show', $p->id) }}" class="btn btn-sm btn-outline-primary">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                        Tidak ada data pengadaan
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
@endsection
