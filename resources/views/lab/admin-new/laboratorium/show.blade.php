@extends('lab.layouts.unified', ['title' => 'Detail Laboratorium'])

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-small">
        <li class="breadcrumb-item"><a href="{{ route('lab.admin_new.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('lab.admin_new.laboratorium.index') }}">Laboratorium</a></li>
        <li class="breadcrumb-item active">{{ $labor->nama_labor }}</li>
    </ol>
</nav>
@endsection

@section('css')
<style>
    .info-card {
        border-radius: 15px;
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    .equipment-card {
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        background: #fff;
    }
    .equipment-card:hover {
        border-color: #4361ee;
        box-shadow: 0 8px 20px rgba(67,97,238,0.12);
        transform: translateY(-4px);
    }
</style>
@endsection

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Lab Info Card -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card info-card">
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-8">
                        <h4 class="fw-bold mb-3">{{ $labor->nama_labor }}</h4>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                @php
                                    $warna = $labor->jenisData->warna ?? 'info';
                                @endphp
                                <small class="text-muted d-block">Jenis Laboratorium</small>
                                <span class="badge bg-{{ $warna }}">{{ $labor->jenis_labor ?? 'Lainnya' }}</span>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted d-block">Kapasitas</small>
                                <strong>{{ $labor->kapasitas ?? 30 }} Orang</strong>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted d-block">Status</small>
                                <span class="badge bg-{{ $labor->getCurrentStatus() == 'digunakan' ? 'warning' : 'success' }}">
                                    {{ $labor->getCurrentStatus() == 'digunakan' ? 'Sedang Digunakan' : 'Tersedia' }}
                                </span>
                            </div>
                        </div>
                        
                        @if($labor->penanggungJawabUser)
                            <div class="mb-2">
                                <small class="text-muted">Penanggung Jawab: </small>
                                <strong>{{ $labor->penanggungJawabUser->nama }}</strong>
                            </div>
                        @endif
                        
                        @if($labor->teknisiUser)
                            <div class="mb-2">
                                <small class="text-muted">Teknisi: </small>
                                <strong>{{ $labor->teknisiUser->nama }}</strong>
                            </div>
                        @endif
                        
                        @if($labor->deskripsi)
                            <div class="mt-3">
                                <small class="text-muted d-block">Deskripsi</small>
                                <p>{{ $labor->deskripsi }}</p>
                            </div>
                        @endif
                        
                        @if($labor->fasilitas)
                            <div class="mt-3">
                                <small class="text-muted d-block">Fasilitas</small>
                                <p>{{ $labor->fasilitas }}</p>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="{{ route('lab.admin_new.laboratorium.manual_usage', $labor->id) }}" class="btn btn-primary rounded-pill px-4 mb-2">
                            <i class="bi bi-plus-circle me-2"></i>Input Penggunaan Manual
                        </a>
                        <a href="{{ route('lab.admin_new.laboratorium.edit', $labor->id) }}" class="btn btn-outline-secondary rounded-pill px-4">
                            <i class="bi bi-pencil me-2"></i>Edit Info Lab
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Inventaris Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0">Daftar Inventaris ({{ $labor->inventaris->count() }})</h5>
            <a href="{{ route('lab.admin_new.inventaris.create') }}" class="btn btn-sm btn-success rounded-pill">
                <i class="bi bi-plus-circle me-1"></i>Tambah Alat
            </a>
        </div>
        
        <div class="row g-3">
            @forelse($labor->inventaris as $item)
                <div class="col-md-4 col-sm-6">
                    <div class="card equipment-card h-100" 
                         onclick="window.location='{{ route('lab.admin_new.inventaris.show', $item->id) }}'">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between mb-2">
                                <h6 class="fw-bold mb-0">{{ $item->nama_inventaris }}</h6>
                                <span class="badge {{ $item->getTingkatKerusakanBadgeClass() }}">
                                    {{ $item->kondisi ?? 'N/A' }}
                                </span>
                            </div>
                            <p class="text-muted small mb-2">
                                <span class="badge bg-secondary">{{ $item->jenis }}</span>
                                @if($item->kategori)
                                    <span class="badge bg-light text-dark">{{ $item->kategori }}</span>
                                @endif
                            </p>
                            <div class="d-flex justify-content-between">
                                <small class="text-muted">Jumlah: <strong>{{ $item->jumlah }}</strong></small>
                                <small class="text-muted">
                                    <span class="badge bg-{{ $item->status == 'tersedia' ? 'success' : 'warning' }}">
                                        {{ ucfirst($item->status ?? 'tersedia') }}
                                    </span>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="bi bi-info-circle me-2"></i>
                        Belum ada inventaris di laboratorium ini.
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Jadwal Penggunaan Section -->
<div class="row mb-4">
    <div class="col-12">
        <h5 class="fw-bold mb-3">Jadwal Penggunaan Mendatang</h5>
        
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal & Waktu</th>
                        <th>Kelas/Kegiatan</th>
                        <th>Penanggung Jawab</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($labor->jadwalPenggunaan as $jadwal)
                        <tr>
                            <td>
                                <strong>{{ \Carbon\Carbon::parse($jadwal->start)->format('d M Y') }}</strong><br>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($jadwal->start)->format('H:i') }} - 
                                    {{ \Carbon\Carbon::parse($jadwal->end)->format('H:i') }}
                                </small>
                            </td>
                            <td>{{ $jadwal->keterangan ?? 'N/A' }}</td>
                            <td>{{ $jadwal->penanggung_jawab ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-{{ $jadwal->status == 'digunakan' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($jadwal->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Tidak ada jadwal mendatang</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Jadwal Tetap (Mingguan) Section -->
<div class="row mb-4">
    <div class="col-12">
        <h5 class="fw-bold mb-1">Jadwal Mingguan (Rutin)</h5>
        <p class="text-muted small mb-3">Jadwal tetap berdasarkan kurikulum mata pelajaran.</p>
        
        <div class="table-responsive">
            <table class="table table-hover table-bordered shadow-sm" style="border-radius: 10px; overflow: hidden;">
                <thead class="table-primary text-white border-0">
                    <tr>
                        <th class="py-3">Hari</th>
                        <th class="py-3">Waktu</th>
                        <th class="py-3">Mata Pelajaran</th>
                        <th class="py-3">Kelas</th>
                        <th class="py-3">Guru</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse($labor->jadwalTetap()->orderByRaw("FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')")->orderBy('jam_mulai')->get() as $tetap)
                        <tr>
                            <td class="fw-bold text-primary">{{ $tetap->hari }}</td>
                            <td>{{ $tetap->jam_mulai }} - {{ $tetap->jam_selesai }}</td>
                            <td><strong class="text-dark">{{ $tetap->mata_pelajaran }}</strong></td>
                            <td><span class="badge bg-light text-dark border">{{ $tetap->kelas }}</span></td>
                            <td>{{ $tetap->guru->nama ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="bi bi-calendar-x fs-4 d-block mb-2"></i>
                                Belum ada jadwal mingguan rutin ditetapkan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Laporan Kerusakan Section -->
<div class="row">
    <div class="col-12">
        <h5 class="fw-bold mb-3">Laporan Kerusakan Terkait ({{ $laporanKerusakan->count() }})</h5>
        
        @forelse($laporanKerusakan as $laporan)
            <div class="card mb-3 border-start border-danger border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="fw-bold">{{ $laporan->inventaris->nama_inventaris ?? $laporan->nama_alat }}</h6>
                            <p class="text-muted small mb-2">
                                <i class="bi bi-person me-2"></i>{{ $laporan->user->nama ?? $laporan->nama_pelapor ?? 'Admin' }}
                                <i class="bi bi-calendar ms-3 me-2"></i>{{ \Carbon\Carbon::parse($laporan->tanggal_laporan)->format('d M Y') }}
                            </p>
                            <p class="mb-0">{{ $laporan->deskripsi_kerusakan }}</p>
                        </div>
                        <div class="text-end">
                            <span class="badge {{ $laporan->inventaris->getTingkatKerusakanBadgeClass() }} mb-2">
                                {{ $laporan->inventaris->kondisi }}
                            </span>
                            <br>
                            <span class="badge bg-{{ $laporan->status_perbaikan == 'selesai' ? 'success' : 'warning' }}">
                                {{ ucfirst(str_replace('_', ' ', $laporan->status_perbaikan ?? 'menunggu')) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-success text-center">
                <i class="bi bi-check-circle me-2"></i>
                Tidak ada laporan kerusakan untuk laboratorium ini.
            </div>
        @endforelse
    </div>
</div>

@endsection
