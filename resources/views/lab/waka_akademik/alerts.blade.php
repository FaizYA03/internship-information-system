@extends('lab.layouts.unified', ['title' => 'Sistem Alert & Peringatan'])

@section('content')

{{-- Header --}}
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="ui-card border-0" style="background: linear-gradient(135deg,#EF4444,#B91C1C); color:white;">
            <div class="ui-card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <div class="rounded-3 p-2" style="background:rgba(255,255,255,0.2);">
                                <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                            </div>
                            <div>
                                <p class="mb-0 opacity-75 small fw-bold">WAKA KURIKULUM</p>
                                <h4 class="fw-bold mb-0">Sistem Alert & Peringatan</h4>
                            </div>
                        </div>
                        <p class="mb-0 opacity-75 small">Deteksi otomatis permasalahan jadwal, lab idle, dan kerusakan yang membutuhkan perhatian segera.</p>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <a href="{{ route('lab.waka_akademik.dashboard') }}" class="btn btn-light btn-sm rounded-pill px-3">
                            <i class="bi bi-arrow-left me-1"></i>Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Alert Summary Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="ui-card border-0 text-center" style="border-top: 3px solid #EF4444 !important;">
            <div class="ui-card-body p-3">
                <h3 class="fw-bold text-danger mb-0">{{ count($bentrokGuru) }}</h3>
                <small class="text-muted">Bentrok Guru</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="ui-card border-0 text-center" style="border-top: 3px solid #F59E0B !important;">
            <div class="ui-card-body p-3">
                <h3 class="fw-bold text-warning mb-0">{{ count($bentrokLab) }}</h3>
                <small class="text-muted">Bentrok Lab</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="ui-card border-0 text-center" style="border-top: 3px solid #8B5CF6 !important;">
            <div class="ui-card-body p-3">
                <h3 class="fw-bold text-purple mb-0" style="color:#8B5CF6;">{{ count($guruOverload) }}</h3>
                <small class="text-muted">Guru Overload</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="ui-card border-0 text-center" style="border-top: 3px solid #06B6D4 !important;">
            <div class="ui-card-body p-3">
                <h3 class="fw-bold text-info mb-0">{{ count($kerusakanPending) }}</h3>
                <small class="text-muted">Kerusakan Pending</small>
            </div>
        </div>
    </div>
</div>

{{-- Guru Conflicts --}}
@if(count($bentrokGuru) > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="ui-card border-0">
            <div class="ui-card-body p-0">
                <div class="p-4 pb-0 d-flex align-items-center gap-2 mb-3">
                    <div class="rounded-circle p-2 bg-danger" style="width:34px;height:34px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-person-exclamation text-white" style="font-size:0.85rem;"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-danger">Bentrok Jadwal Guru</h6>
                        <small class="text-muted">Guru mengajar 2 kelas di waktu yang sama</small>
                    </div>
                    <span class="badge bg-danger rounded-pill ms-auto me-3">{{ count($bentrokGuru) }} kasus</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 ps-4">Nama Guru</th>
                                <th class="border-0">Hari</th>
                                <th class="border-0">Kelas A</th>
                                <th class="border-0">Kelas B</th>
                                <th class="border-0">Bentrok Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bentrokGuru as $b)
                            <tr class="table-danger-soft">
                                <td class="ps-4 fw-semibold">{{ $b->guru_nama }}</td>
                                <td><span class="badge bg-danger rounded-pill">{{ $b->hari }}</span></td>
                                <td><small>{{ $b->a_kelas }} ({{ $b->a_mulai }}–{{ $b->a_selesai }})</small></td>
                                <td><small>{{ $b->b_kelas }} ({{ $b->b_mulai }}–{{ $b->b_selesai }})</small></td>
                                <td>
                                    <span class="badge bg-danger-soft text-danger rounded-pill" style="background:#FEF2F2!important;">
                                        <i class="bi bi-x-circle me-1"></i>Tumpang Tindih
                                    </span>
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
@endif

{{-- Lab Conflicts --}}
@if(count($bentrokLab) > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="ui-card border-0">
            <div class="ui-card-body p-0">
                <div class="p-4 pb-0 d-flex align-items-center gap-2 mb-3">
                    <div class="rounded-circle p-2 bg-warning" style="width:34px;height:34px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-building-exclamation text-dark" style="font-size:0.85rem;"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-warning">Bentrok Penggunaan Lab</h6>
                        <small class="text-muted">Laboratorium dijadwalkan untuk 2 kelas di waktu yang sama</small>
                    </div>
                    <span class="badge bg-warning text-dark rounded-pill ms-auto me-3">{{ count($bentrokLab) }} kasus</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 ps-4">Laboratorium</th>
                                <th class="border-0">Hari</th>
                                <th class="border-0">Jam</th>
                                <th class="border-0">Kelas Bertabrakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bentrokLab as $b)
                            <tr>
                                <td class="ps-4 fw-semibold">{{ $b->lab_nama }}</td>
                                <td><span class="badge bg-warning text-dark rounded-pill">{{ $b->hari }}</span></td>
                                <td><small>{{ $b->jam_mulai }}–{{ $b->jam_selesai }}</small></td>
                                <td>
                                    <span class="badge bg-light text-dark me-1">{{ $b->a_kelas }}</span>
                                    <i class="bi bi-arrows-collapse text-danger"></i>
                                    <span class="badge bg-light text-dark ms-1">{{ $b->b_kelas }}</span>
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
@endif

{{-- Guru Overload --}}
@if(count($guruOverload) > 0)
<div class="row mb-4">
    <div class="col-md-6">
        <div class="ui-card border-0">
            <div class="ui-card-body p-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="rounded-circle p-2" style="background:#F3E8FF;width:34px;height:34px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-person-dash" style="color:#8B5CF6;font-size:0.85rem;"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0" style="color:#8B5CF6;">Guru Overload</h6>
                        <small class="text-muted">Memiliki lebih dari 4 jadwal per minggu</small>
                    </div>
                </div>
                @foreach($guruOverload as $g)
                <div class="d-flex justify-content-between align-items-center p-2 rounded-3 mb-2" style="background:#F9F5FF;">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle bg-purple text-white d-flex align-items-center justify-content-center" style="background:#8B5CF6!important;width:32px;height:32px;font-size:0.75rem;">
                            {{ strtoupper(substr($g->guru->nama ?? 'G', 0, 1)) }}
                        </div>
                        <span class="small fw-semibold">{{ $g->guru->nama ?? 'Guru #'.$g->guru_id }}</span>
                    </div>
                    <span class="badge rounded-pill" style="background:#8B5CF6;color:white;">{{ $g->total }} jadwal</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Lab Idle --}}
    @if(count($labIdle) > 0)
    <div class="col-md-6">
        <div class="ui-card border-0">
            <div class="ui-card-body p-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="rounded-circle p-2" style="background:#E0F2FE;width:34px;height:34px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-building-slash text-info" style="font-size:0.85rem;"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-info">Lab Tidak Terpakai</h6>
                        <small class="text-muted">Belum memiliki jadwal sama sekali</small>
                    </div>
                </div>
                @foreach($labIdle as $lab)
                <div class="d-flex justify-content-between align-items-center p-2 rounded-3 mb-2" style="background:#F0F9FF;">
                    <span class="small fw-semibold">{{ $lab->nama_labor }}</span>
                    <span class="text-muted small">{{ $lab->jenis_labor ?? '-' }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
@endif

{{-- Kerusakan Pending --}}
@if(count($kerusakanPending) > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="ui-card border-0">
            <div class="ui-card-body p-0">
                <div class="p-4 pb-0 d-flex align-items-center gap-2 mb-3">
                    <div class="rounded-circle p-2 bg-info" style="width:34px;height:34px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-tools text-white" style="font-size:0.85rem;"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-info">Kerusakan Belum Ditangani</h6>
                        <small class="text-muted">Laporan kerusakan yang masih menunggu tindakan</small>
                    </div>
                    <span class="badge bg-info rounded-pill ms-auto me-3">{{ count($kerusakanPending) }} laporan</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 ps-4">Nama Alat</th>
                                <th class="border-0">Tingkat Kerusakan</th>
                                <th class="border-0">Dilaporkan</th>
                                <th class="border-0 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kerusakanPending as $k)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-semibold small">{{ $k->nama_alat }}</div>
                                    <div class="text-muted" style="font-size:0.75rem;">{{ $k->deskripsi_kerusakan }}</div>
                                </td>
                                <td>
                                    @php
                                        $tingkat = $k->tingkat_kerusakan ?? 'Ringan';
                                        $tkColor  = $tingkat === 'Berat' ? 'danger' : ($tingkat === 'Sedang' ? 'warning' : 'info');
                                    @endphp
                                    <span class="badge bg-{{ $tkColor }} rounded-pill">{{ $tingkat }}</span>
                                </td>
                                <td><small>{{ $k->created_at->diffForHumans() }}</small></td>
                                <td class="text-center">
                                    <a href="{{ route('lab.admin_new.kerusakan.index') }}" class="btn btn-sm btn-outline-info rounded-pill px-3">
                                        <i class="bi bi-arrow-right me-1"></i>Lihat
                                    </a>
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
@endif

{{-- Jadwal Menggantung --}}
@if(count($jadwalMenggantung) > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="ui-card border-0" style="border-left: 4px solid #F59E0B !important;">
            <div class="ui-card-body p-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-hourglass-split text-warning fs-5"></i>
                    <h6 class="fw-bold mb-0 text-warning">Jadwal Menunggu Validasi > 3 Hari</h6>
                    <span class="badge bg-warning text-dark rounded-pill ms-auto">{{ count($jadwalMenggantung) }} jadwal</span>
                </div>
                @foreach($jadwalMenggantung as $j)
                <div class="d-flex justify-content-between align-items-center p-2 rounded-3 mb-2 bg-light">
                    <div>
                        <span class="fw-semibold small">{{ $j->mata_pelajaran }}</span>
                        <span class="text-muted small ms-2">— {{ $j->labor->nama_labor ?? '-' }} | {{ $j->hari }}</span>
                    </div>
                    <a href="{{ route('lab.waka_akademik.validasi_jadwal', ['status' => 'menunggu']) }}" class="btn btn-sm btn-warning rounded-pill px-3">Validasi</a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif

{{-- All clear --}}
@if(count($bentrokGuru) === 0 && count($bentrokLab) === 0 && count($guruOverload) === 0 && count($kerusakanPending) === 0 && count($jadwalMenggantung) === 0 && count($labIdle) === 0)
<div class="row">
    <div class="col-12 text-center py-5">
        <div class="rounded-circle d-inline-flex p-4 mb-3" style="background:#F0FDF4;">
            <i class="bi bi-shield-check fs-1 text-success"></i>
        </div>
        <h5 class="fw-bold text-success">Semua Bersih!</h5>
        <p class="text-muted">Tidak ada alert aktif saat ini. Sistem berjalan dengan baik.</p>
    </div>
</div>
@endif

@endsection
