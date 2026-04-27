@extends('perpustakaan.layouts.main')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="page-title">Data Peminjaman Buku</h1>
            <p class="text-muted mb-4">Kelola dan pantau status peminjaman buku dari perpustakaan SMK Negeri 5 Padang</p>

            @if (Auth::check() && Auth::user()->role == 'admin_perpus')
            <!-- Filter & Export Section -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-3">
                    <form action="{{ route('perpustakaan.peminjaman.index') }}" method="GET" class="row align-items-end" id="filterForm">
                        <div class="col-md-3 mb-3 mb-md-0">
                            <label for="dari_tanggal" class="form-label form-label-sm">Dari Tanggal</label>
                            <input type="date" class="form-control" name="dari_tanggal" id="dari_tanggal" value="{{ request('dari_tanggal') }}">
                        </div>
                        <div class="col-md-3 mb-3 mb-md-0">
                            <label for="sampai_tanggal" class="form-label form-label-sm">Sampai Tanggal</label>
                            <input type="date" class="form-control" name="sampai_tanggal" id="sampai_tanggal" value="{{ request('sampai_tanggal') }}">
                        </div>
                        <div class="col-md-6 d-flex justify-content-md-end gap-2">
                            <button type="submit" class="btn btn-primary" style="background-color: var(--primary); border:none;">
                                <i class="bi bi-funnel"></i> Filter
                            </button>
                            @if(request('dari_tanggal') || request('sampai_tanggal'))
                                <a href="{{ route('perpustakaan.peminjaman.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Reset
                                </a>
                            @endif
                            <div class="dropdown">
                                <button class="btn btn-success dropdown-toggle" type="button" id="exportMenu" data-bs-toggle="dropdown" aria-expanded="false" style="background-color: #198754;">
                                    <i class="bi bi-download"></i> Export Data
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="exportMenu">
                                    <li>
                                        <a class="dropdown-item text-danger fw-bold" href="{{ route('perpustakaan.peminjaman.export.pdf', request()->all()) }}">
                                            <i class="bi bi-file-earmark-pdf"></i> Export PDF
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-success fw-bold" href="{{ route('perpustakaan.peminjaman.export.excel', request()->all()) }}">
                                            <i class="bi bi-file-earmark-excel"></i> Export Excel
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            <!-- View Toggle -->
            <div class="actions-row">

                <div class="toggle-view">
                    <button type="button" class="view-btn" id="gridViewBtn">
                        <i class="bi bi-grid-3x3-gap-fill"></i> Grid
                    </button>
                    <button type="button" class="view-btn active" id="tableViewBtn">
                        <i class="bi bi-table"></i> Tabel
                    </button>
                </div>
            </div>

            <!-- Grid View for Peminjaman -->
            <div class="book-grid d-none" id="gridView">
                @foreach($peminjaman as $p)
                <div class="book-card">
                    <div class="book-card-header">
                        <h3 class="book-title">{{ $p->nama }}</h3>
                        <p class="book-author">Buku: {{ $p->buku->judul }}</p>
                    </div>
                    <div class="book-card-body">
                        <div class="book-details">
                            <span class="book-detail-item">
                                <i class="bi bi-calendar"></i> Tanggal Pinjam: {{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d/m/Y') }}
                            </span>
                            <span class="book-detail-item">
                                <i class="bi bi-calendar-check"></i>
                                Tanggal Pengembalian:
                                @if(in_array($p->status, ['Dikembalikan', 'Terlambat']) && $p->tanggal_dikembalikan)
                                    <br><strong class="text-success">{{ \Carbon\Carbon::parse($p->tanggal_dikembalikan)->format('d/m/Y') }} (Aktual)</strong><br>
                                    <small class="text-muted">Target: {{ \Carbon\Carbon::parse($p->tanggal_kembali)->format('d/m/Y') }}</small>
                                    @if($p->denda > 0)
                                    <br><strong class="text-danger mt-1"><i class="bi bi-cash-coin"></i> Denda: Rp {{ number_format($p->denda, 0, ',', '.') }}</strong>
                                    @endif
                                @else
                                    {{ \Carbon\Carbon::parse($p->tanggal_kembali)->format('d/m/Y') }} <small class="text-muted">(Target)</small>
                                @endif
                            </span>
                            <span class="book-detail-item">
                                <i class="bi bi-check-circle"></i> Status:
                                @if ($p->status == 'Menunggu')
                                    <span class="status-badge status-pending">Menunggu</span>
                                @elseif ($p->status == 'Ditolak')
                                    <span class="status-badge status-rejected">Ditolak</span>
                                @elseif ($p->status == 'Disetujui')
                                    <span class="status-badge status-approved">Disetujui</span>
                                @elseif ($p->status == 'Dikembalikan')
                                    <span class="status-badge status-returned">Dikembalikan</span>
                                @elseif ($p->status == 'Terlambat')
                                    <span class="badge bg-danger">Terlambat</span>
                                @endif
                            </span>
                            @if($p->denda > 0)
                            <div class="mt-2 book-detail-item">
                                <i class="bi bi-cash-coin"></i> Denda: Rp {{ number_format($p->denda, 0, ',', '.') }}
                                @if($p->denda_dibayar)
                                    <span class="badge bg-success ms-1">Lunas</span>
                                @else
                                    <span class="badge bg-danger ms-1">Belum Lunas</span>
                                @endif
                            </div>
                            @endif
                        </div>
                        <div class="book-actions">
                            <a href="{{ route('perpustakaan.peminjaman.edit', $p->id) }}" class="btn-secondary-app">
                                <i class="bi bi-pencil-square"></i> Update
                            </a>
                            <form action="{{ route('perpustakaan.peminjaman.destroy', $p->id) }}" method="post" id="deleteForm{{ $p->id }}" class="d-inline">
                                @csrf
                                @method('delete')
                                <button type="button" onclick="Perpustakaan.confirmDelete('{{ $p->id }}')" class="btn-action btn-delete" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Table View for Peminjaman -->
            <div class="table-container" id="tableView">
                <div class="table-responsive">
                    <table class="table" id="data-table">
                        <thead>
                            <tr>
                                <th class="d-none d-md-table-cell">No</th>
                                <th>Nama</th>
                                <th>Buku</th>
                                <th class="d-none d-md-table-cell">Tanggal Pinjam</th>
                                <th class="d-none d-md-table-cell">Pengembalian</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($peminjaman as $index => $p)
                            <tr>
                                <td class="d-none d-md-table-cell" data-label="No">{{ $index + 1 }}</td>
                                <td data-label="Nama">{{ $p->nama }}</td>
                                <td data-label="Buku">{{ $p->buku->judul }}</td>
                                <td class="d-none d-md-table-cell" data-label="Tanggal Pinjam">{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d/m/Y') }}</td>
                                <td class="d-none d-md-table-cell" data-label="Pengembalian">
                                    @if(in_array($p->status, ['Dikembalikan', 'Terlambat']) && $p->tanggal_dikembalikan)
                                        <b class="text-success">{{ \Carbon\Carbon::parse($p->tanggal_dikembalikan)->format('d/m/Y') }} (Aktual)</b><br>
                                        <small class="text-muted">Target: {{ \Carbon\Carbon::parse($p->tanggal_kembali)->format('d/m/Y') }}</small>
                                        @if($p->denda > 0)
                                            <br>
                                            <strong class="{{ $p->denda_dibayar ? 'text-success' : 'text-danger' }}">
                                                Denda: Rp {{ number_format($p->denda, 0, ',', '.') }} 
                                                ({{ $p->denda_dibayar ? 'Lunas' : 'Belum Lunas' }})
                                            </strong>
                                        @endif
                                    @else
                                        {{ \Carbon\Carbon::parse($p->tanggal_kembali)->format('d/m/Y') }} <small class="text-muted">(Target)</small>
                                    @endif
                                </td>
                                <td data-label="Status">
                                    @if ($p->status == 'Menunggu')
                                    <span class="status-badge status-pending">Menunggu</span>
                                    @elseif ($p->status == 'Ditolak')
                                    <span class="status-badge status-rejected">Ditolak</span>
                                    @elseif ($p->status == 'Disetujui')
                                    <span class="status-badge status-approved">Disetujui</span>
                                    @elseif ($p->status == 'Dikembalikan')
                                    <span class="status-badge status-returned">Dikembalikan</span>
                                    @elseif ($p->status == 'Terlambat')
                                    <span class="badge bg-danger">Terlambat</span>
                                    @endif
                                </td>
                                <td data-label="Aksi">
                                    <div class="action-buttons">
                                        <a href="{{ route('perpustakaan.peminjaman.edit', $p->id) }}" class="btn-action btn-edit" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('perpustakaan.peminjaman.destroy', $p->id) }}" method="post" id="deleteForm{{ $p->id }}">
                                            @csrf
                                            @method('delete')
                                            <button type="button" onclick="Perpustakaan.confirmDelete('{{ $p->id }}')" class="btn-action btn-delete" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
