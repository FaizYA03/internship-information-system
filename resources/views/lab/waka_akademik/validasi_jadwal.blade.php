@extends('lab.layouts.unified', ['title' => 'Validasi Jadwal Praktikum'])

@section('content')

{{-- Header --}}
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="ui-card border-0" style="background: linear-gradient(135deg,#10B981,#059669); color:white;">
            <div class="ui-card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <div class="rounded-3 p-2" style="background:rgba(255,255,255,0.2);">
                                <i class="bi bi-calendar-check-fill fs-4"></i>
                            </div>
                            <div>
                                <p class="mb-0 opacity-75 small fw-bold">WAKA KURIKULUM</p>
                                <h4 class="fw-bold mb-0">Validasi Jadwal Praktikum</h4>
                            </div>
                        </div>
                        <p class="mb-0 opacity-75 small">Review, setujui, atau tolak jadwal yang diajukan oleh admin laboratorium.</p>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <a href="{{ route('lab.waka_akademik.dashboard') }}" class="btn btn-light btn-sm rounded-pill px-3">
                            <i class="bi bi-arrow-left me-1"></i>Kembali ke Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Session alerts --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show rounded-3 border-0 mb-3" role="alert">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show rounded-3 border-0 mb-3" role="alert">
    <i class="bi bi-x-circle me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Status Tabs --}}
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="ui-card border-0">
            <div class="ui-card-body px-4 py-3">
                <div class="d-flex gap-2 flex-wrap">
                    @php
                        $tabs = [
                            'menunggu'  => ['label' => 'Menunggu',  'color' => 'warning', 'icon' => 'bi-hourglass-split'],
                            'draft'     => ['label' => 'Draft',     'color' => 'secondary','icon' => 'bi-file-earmark'],
                            'disetujui' => ['label' => 'Disetujui', 'color' => 'success', 'icon' => 'bi-check-circle'],
                            'ditolak'   => ['label' => 'Ditolak',   'color' => 'danger',  'icon' => 'bi-x-circle'],
                            'semua'     => ['label' => 'Semua',     'color' => 'primary', 'icon' => 'bi-list-ul'],
                        ];
                    @endphp
                    @foreach($tabs as $key => $tab)
                    <a href="{{ route('lab.waka_akademik.validasi_jadwal', ['status' => $key]) }}"
                       class="btn btn-{{ $status === $key ? $tab['color'] : 'outline-'.$tab['color'] }} btn-sm rounded-pill px-3">
                        <i class="bi {{ $tab['icon'] }} me-1"></i>
                        {{ $tab['label'] }}
                        <span class="badge {{ $status === $key ? 'bg-white text-'.$tab['color'] : 'bg-'.$tab['color'].' text-white' }} rounded-pill ms-1" style="font-size:0.7rem;">
                            {{ $counts[$key] ?? ($key === 'semua' ? array_sum($counts) : 0) }}
                        </span>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Jadwal Table --}}
<div class="row">
    <div class="col-12">
        <div class="ui-card border-0">
            <div class="ui-card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 ps-4">Mata Pelajaran / Kelas</th>
                                <th class="border-0">Laboratorium</th>
                                <th class="border-0">Guru</th>
                                <th class="border-0">Jadwal</th>
                                <th class="border-0 text-center">Status</th>
                                <th class="border-0 text-center pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jadwal as $j)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-semibold">{{ $j->mata_pelajaran }}</div>
                                    <div class="small text-muted">Kelas: {{ $j->kelas }}</div>
                                    @if($j->catatan_validasi)
                                    <div class="small text-danger mt-1"><i class="bi bi-chat-text me-1"></i>{{ $j->catatan_validasi }}</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-semibold small">{{ $j->labor->nama_labor ?? '-' }}</div>
                                </td>
                                <td>
                                    <div class="small">{{ $j->guru->nama ?? '-' }}</div>
                                </td>
                                <td>
                                    <div class="fw-semibold small">{{ $j->hari }}</div>
                                    <div class="small text-muted">{{ $j->jam_mulai }} – {{ $j->jam_selesai }}</div>
                                </td>
                                <td class="text-center">
                                    @php
                                        $statusBadge = [
                                            'draft'     => 'secondary',
                                            'menunggu'  => 'warning',
                                            'disetujui' => 'success',
                                            'ditolak'   => 'danger',
                                        ];
                                        $statusIcon = [
                                            'draft'     => 'bi-file-earmark',
                                            'menunggu'  => 'bi-hourglass-split',
                                            'disetujui' => 'bi-check-circle-fill',
                                            'ditolak'   => 'bi-x-circle-fill',
                                        ];
                                        $sv = $j->status_validasi ?? 'draft';
                                    @endphp
                                    <span class="badge bg-{{ $statusBadge[$sv] ?? 'secondary' }} rounded-pill px-3">
                                        <i class="bi {{ $statusIcon[$sv] ?? 'bi-question' }} me-1"></i>
                                        {{ ucfirst($sv) }}
                                    </span>
                                </td>
                                <td class="text-center pe-4">
                                    @if(in_array($sv, ['draft','menunggu','ditolak']))
                                    <form action="{{ route('lab.waka_akademik.validasi_jadwal.approve', $j->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm rounded-pill px-3 me-1" onclick="return confirm('Setujui jadwal ini?')">
                                            <i class="bi bi-check-lg me-1"></i>Setujui
                                        </button>
                                    </form>
                                    @endif
                                    @if(in_array($sv, ['draft','menunggu','disetujui']))
                                    <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $j->id }}">
                                        <i class="bi bi-x-lg me-1"></i>Tolak
                                    </button>
                                    @endif
                                </td>
                            </tr>

                            {{-- Reject Modal --}}
                            <div class="modal fade" id="rejectModal{{ $j->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 rounded-4 shadow-lg">
                                        <div class="modal-header border-0">
                                            <h6 class="modal-title fw-bold text-danger"><i class="bi bi-x-circle-fill me-2"></i>Tolak Jadwal</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('lab.waka_akademik.validasi_jadwal.reject', $j->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="bg-light rounded-3 p-3 mb-3">
                                                    <p class="small mb-1"><strong>{{ $j->mata_pelajaran }}</strong> – Kelas {{ $j->kelas }}</p>
                                                    <p class="small text-muted mb-0">{{ $j->hari }}, {{ $j->jam_mulai }}–{{ $j->jam_selesai }} | {{ $j->labor->nama_labor ?? '-' }}</p>
                                                </div>
                                                <label class="form-label small fw-semibold">Catatan Revisi <span class="text-muted fw-normal">(opsional)</span></label>
                                                <textarea name="catatan_validasi" class="form-control rounded-3" rows="3"
                                                    placeholder="Contoh: Waktu bentrok dengan kelas lain, mohon pindah ke jam 10.00..."></textarea>
                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger rounded-pill px-4">
                                                    <i class="bi bi-x-lg me-1"></i>Tolak Jadwal
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-calendar-x fs-1 d-block mb-2 opacity-50"></i>
                                    <p class="mb-0">Tidak ada jadwal dengan status <strong>{{ $status }}</strong>.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($jadwal->hasPages())
                <div class="p-4 border-top">
                    {{ $jadwal->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
