@extends('siswa.layouts.main')

@php
    $role_prefix = Auth::check() && Auth::user()->role == 'guru' ? 'guru' : 'siswa';
@endphp

@section('css')
<style>
    :root {
        --primary-blue: #4361ee;
        --secondary-purple: #7209b7;
        --status-pending: #f59e0b;
        --status-approved: #10b981;
        --status-rejected: #ef4444;
        --status-completed: #3b82f6;
    }

    .page-header {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        padding: 2rem;
        border-radius: 1.5rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .nav-tabs-custom {
        border-bottom: 2px solid #e2e8f0;
        margin-bottom: 2rem;
        gap: 1.5rem;
    }

    .nav-tabs-custom .nav-link {
        border: none;
        color: #64748b;
        font-weight: 600;
        padding: 0.75rem 0.5rem;
        position: relative;
        transition: all 0.3s;
    }

    .nav-tabs-custom .nav-link:hover {
        color: var(--primary-blue);
    }

    .nav-tabs-custom .nav-link.active {
        color: var(--primary-blue);
        background: transparent;
    }

    .nav-tabs-custom .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 100%;
        height: 3px;
        background: var(--primary-blue);
        border-radius: 50px;
    }

    .card-history {
        background: white;
        border-radius: 1.25rem;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .table-custom {
        margin-bottom: 0;
    }

    .table-custom th {
        background: #f8fafc;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        padding: 1rem 1.5rem;
        border-top: none;
    }

    .table-custom td {
        padding: 1.25rem 1.5rem;
        vertical-align: middle;
        font-size: 0.9rem;
    }

    .item-name {
        font-weight: 700;
        color: #1e293b;
        display: block;
    }

    .item-subtext {
        font-size: 0.8rem;
        color: #64748b;
    }

    .status-badge {
        font-size: 0.75rem;
        font-weight: 700;
        padding: 0.4rem 0.8rem;
        border-radius: 50px;
        text-transform: uppercase;
    }

    .badge-pending { background: #fef3c7; color: #92400e; }
    .badge-approved { background: #dcfce7; color: #166534; }
    .badge-rejected { background: #fee2e2; color: #991b1b; }
    .badge-completed { background: #dbeafe; color: #1e40af; }

    .btn-action-cancel {
        color: #ef4444;
        background: #fee2e2;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.2s;
    }

    .btn-action-cancel:hover {
        background: #fecaca;
        transform: scale(1.05);
    }

    .empty-state {
        padding: 5rem 2rem;
        text-align: center;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h1 class="fw-bold mb-1">Riwayat Peminjaman</h1>
            <p class="mb-0 opacity-75">Pantau status peminjaman alat dan ruangan praktik Anda</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route($role_prefix . '.peminjaman.create') }}" class="btn btn-light rounded-pill px-4 fw-bold shadow-sm">
                <i class="bi bi-tools me-2"></i> Pinjam Alat
            </a>
            @if(Auth::check() && Auth::user()->role !== 'siswa')
            <a href="{{ route($role_prefix . '.peminjaman.ruangan.create') }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                <i class="bi bi-building me-2"></i> Pinjam Ruangan
            </a>
            @endif
        </div>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-tabs nav-tabs-custom" id="borrowTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="alat-tab" data-bs-toggle="tab" data-bs-target="#alat" type="button" role="tab" aria-controls="alat" aria-selected="true">
                <i class="bi bi-tools me-2"></i> Peminjaman Alat
            </button>
        </li>
        @if(Auth::check() && Auth::user()->role !== 'siswa')
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="ruangan-tab" data-bs-toggle="tab" data-bs-target="#ruangan" type="button" role="tab" aria-controls="ruangan" aria-selected="false">
                <i class="bi bi-door-open me-2"></i> Peminjaman Ruangan
            </button>
        </li>
        @endif
    </ul>

    <div class="tab-content" id="borrowTabsContent">
        <!-- Alat Tab -->
        <div class="tab-pane fade show active" id="alat" role="tabpanel" aria-labelledby="alat-tab">
            <div class="card-history">
                @if($peminjamanAlat->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th>Alat & Laboratorium</th>
                                    <th>Jumlah</th>
                                    <th>Jadwal Pinjam</th>
                                    <th>Batas Kembali</th>
                                    <th>Status</th>
                                    @if(Auth::user()->role !== 'siswa')
                                    <th>Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($peminjamanAlat as $item)
                                <tr>
                                    <td>
                                        <span class="item-name">{{ $item->inventaris->nama_inventaris ?? 'Alat tidak ditemukan' }}</span>
                                        <span class="item-subtext"><i class="bi bi-building me-1"></i>{{ $item->inventaris->labor->nama_labor ?? '-' }}</span>
                                    </td>
                                    <td><span class="fw-bold text-dark">{{ $item->jumlah }} Unit</span></td>
                                    <td>
                                        <div class="small fw-600 text-dark">
                                            <i class="bi bi-calendar3 me-1"></i> {{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}
                                        </div>
                                        @if($item->jam_pinjam)
                                            <div class="item-subtext small">
                                                <i class="bi bi-clock me-1"></i> {{ $item->jam_pinjam }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="small fw-600 text-dark">
                                            <i class="bi bi-calendar3 me-1"></i> {{ \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M Y') }}
                                        </div>
                                        @if($item->jam_kembali)
                                            <div class="item-subtext small">
                                                <i class="bi bi-clock me-1"></i> {{ $item->jam_kembali }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="status-badge badge-{{ $item->status }}">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                    @if(Auth::user()->role !== 'siswa')
                                    <td>
                                        @if($item->status == 'pending')
                                            <form action="{{ route($role_prefix . '.peminjaman.cancel', $item->id) }}" method="POST" class="d-inline confirm-cancel">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action-cancel">
                                                    <i class="bi bi-trash"></i> Batal
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80" class="mb-3 opacity-50" alt="Empty">
                        <h5 class="fw-bold">Belum Ada Riwayat Alat</h5>
                        <p class="text-muted small">Anda belum memiliki riwayat peminjaman alat praktik.</p>
                        <a href="{{ route($role_prefix . '.peminjaman.create') }}" class="btn btn-primary mt-3 px-4 rounded-pill">Pinjam Alat Sekarang</a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Ruangan Tab -->
        <div class="tab-pane fade" id="ruangan" role="tabpanel" aria-labelledby="ruangan-tab">
            <div class="card-history">
                @if($peminjamanRuangan->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th>Laboratorium</th>
                                    <th>Kelas & Mapel</th>
                                    <th>Jadwal Pemakaian</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($peminjamanRuangan as $item)
                                <tr>
                                    <td>
                                        <span class="item-name">{{ $item->labor->nama_labor ?? 'N/A' }}</span>
                                        <span class="item-subtext"><i class="bi bi-tag me-1"></i>{{ $item->labor->jenis_labor ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <span class="text-dark fw-600 d-block">{{ $item->kelas ?? '-' }}</span>
                                        <span class="item-subtext">{{ $item->mata_pelajaran ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <div class="small fw-600 text-dark">
                                            <i class="bi bi-calendar3 me-1"></i> {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                        </div>
                                        <div class="item-subtext small">
                                            <i class="bi bi-clock me-1"></i> {{ $item->jam_pinjam ?? $item->waktu }} - {{ $item->jam_kembali ?? 'Selesai' }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="status-badge badge-{{ $item->status }}">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($item->status == 'pending')
                                            <form action="{{ route($role_prefix . '.peminjaman.ruangan.cancel', $item->id) }}" method="POST" class="d-inline confirm-cancel">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action-cancel">
                                                    <i class="bi bi-trash"></i> Batal
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <img src="https://cdn-icons-png.flaticon.com/512/1053/1053181.png" width="80" class="mb-3 opacity-50" alt="Empty">
                        <h5 class="fw-bold">Belum Ada Riwayat Ruangan</h5>
                        <p class="text-muted small">Anda belum memiliki riwayat peminjaman ruangan laboratorium.</p>
                        @if(Auth::check() && Auth::user()->role !== 'siswa')
                        <a href="{{ route($role_prefix . '.peminjaman.ruangan.create') }}" class="btn btn-primary mt-3 px-4 rounded-pill">Pinjam Ruangan Sekarang</a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).on('submit', '.confirm-cancel', function(e) {
        e.preventDefault();
        const form = this;
        Swal.fire({
            title: 'Batalkan Peminjaman?',
            text: "Permohonan yang dibatalkan tidak dapat dikembalikan.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Batalkan!',
            cancelButtonText: 'Tutup'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>
@endsection
