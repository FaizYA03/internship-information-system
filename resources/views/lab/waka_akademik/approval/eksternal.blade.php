@extends('lab.layouts.unified', ['title' => 'Otorisasi Peminjaman Eksternal'])

@section('content')

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('lab.waka_akademik.dashboard') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
        <i class="bi bi-arrow-left me-1"></i> Dashboard
    </a>
    <div>
        <h5 class="fw-bold mb-0">Otorisasi Peminjaman Pihak Luar</h5>
        <p class="small text-muted mb-0">Pengajuan yang telah mendapat rekomendasi Kepala Lab dan menunggu keputusan akhir Anda.</p>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success border-0 rounded-3 shadow-sm mb-4">
    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="alert alert-danger border-0 rounded-3 shadow-sm mb-4">
    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
</div>
@endif

<ul class="nav nav-pills mb-4" id="approvalTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active rounded-pill px-4 me-2" id="alat-tab" data-bs-toggle="pill" data-bs-target="#alat" type="button" role="tab">
            Peminjaman Alat 
            @if($requests->count() > 0) <span class="badge bg-danger ms-1">{{ $requests->count() }}</span> @endif
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link rounded-pill px-4 me-2" id="ruangan-tab" data-bs-toggle="pill" data-bs-target="#ruangan" type="button" role="tab">
            Peminjaman Ruangan
            @if($ruanganRequests->count() > 0) <span class="badge bg-danger ms-1">{{ $ruanganRequests->count() }}</span> @endif
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link rounded-pill px-4" id="riwayat-tab" data-bs-toggle="pill" data-bs-target="#riwayat" type="button" role="tab">
             Riwayat
        </button>
    </li>
</ul>

<div class="tab-content" id="approvalTabsContent">
    {{-- TAB ALAT --}}
    <div class="tab-pane fade show active" id="alat" role="tabpanel">
        @forelse($requests as $item)
        @php
            $priorityLabel = 'Normal';
            $priorityClass = 'success';
            $priorityBg    = '#ECFDF5';
            $priorityText  = '#065F46';
            $tujuan = strtolower($item->tujuan ?? $item->keperluan ?? '');
            if (str_contains($tujuan, 'urgent') || str_contains($tujuan, 'darurat') || str_contains($tujuan, 'segera')) {
                $priorityLabel = 'Urgent'; $priorityClass = 'danger'; $priorityBg = '#FEF2F2'; $priorityText = '#991B1B';
            } elseif (str_contains($tujuan, 'penting') || str_contains($tujuan, 'mendesak') || $item->jumlah >= 5) {
                $priorityLabel = 'Sedang'; $priorityClass = 'warning'; $priorityBg = '#FFFBEB'; $priorityText = '#92400E';
            }
        @endphp
        <div class="card border-0 rounded-4 shadow-sm mb-4">
            <div class="card-body p-4">

                <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-3">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">{{ $item->nama_peminjam }}</h6>
                        <p class="small text-muted mb-0">{{ $item->instansi }}</p>
                    </div>
                    <span class="badge rounded-pill px-3 py-2" style="background:{{ $priorityBg }};color:{{ $priorityText }};font-size:.78rem;">
                        <i class="bi bi-circle-fill me-1"></i>{{ $priorityLabel }}
                    </span>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-sm-6 col-md-3">
                        <p class="small text-muted mb-1 fw-medium">Alat Dipinjam</p>
                        <p class="fw-semibold small mb-0">{{ $item->inventaris->nama_inventaris ?? 'N/A' }}</p>
                        <p class="small text-muted mb-0">{{ $item->jumlah }} unit &bull; Stok: {{ $item->inventaris->jumlah ?? 0 }}</p>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <p class="small text-muted mb-1 fw-medium">Tanggal Pinjam</p>
                        <p class="fw-semibold small mb-0">{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->isoFormat('D MMM Y') }}</p>
                        <p class="small text-muted mb-0">s/d {{ \Carbon\Carbon::parse($item->tanggal_kembali)->isoFormat('D MMM Y') }}</p>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <p class="small text-muted mb-1 fw-medium">Keperluan</p>
                        <p class="small mb-0">{{ $item->tujuan ?? $item->keperluan ?? '—' }}</p>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <p class="small text-muted mb-1 fw-medium">Rekomendasi Kalab</p>
                        <span class="badge rounded-pill" style="background:#DCFCE7;color:#166534;">
                            <i class="bi bi-check-circle-fill me-1"></i>Direkomendasikan
                        </span>
                        <p class="small text-muted mb-0 mt-1">
                            {{ $item->rekomendasiBy->nama ?? 'Kepala Lab' }}
                            &bull; {{ $item->rekomendasi_kalab_at ? \Carbon\Carbon::parse($item->rekomendasi_kalab_at)->isoFormat('D MMM Y') : '—' }}
                        </p>
                    </div>
                </div>

                <div class="rounded-3 p-2 mb-3" style="background:#FFF7ED;border-left:3px solid #F97316;">
                    <p class="small mb-0 fw-medium" style="color:#9A3412;">
                        <i class="bi bi-info-circle me-1"></i>
                        <strong>Dampak jika ditolak:</strong> Pihak luar tidak dapat meminjam alat, dan kebutuhan mereka tidak terpenuhi.
                        @if($item->inventaris && $item->inventaris->jumlah >= $item->jumlah)
                            Stok tersedia cukup untuk memenuhi permintaan ini.
                        @else
                            <span class="text-danger">Stok tidak mencukupi!</span>
                        @endif
                    </p>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <form action="{{ route('lab.waka_akademik.approval.eksternal.approve', $item->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success rounded-pill px-4 btn-sm"
                            onclick="return confirm('Setujui peminjaman oleh {{ $item->nama_peminjam }}?')">
                            <i class="bi bi-check-circle me-1"></i> Setujui
                        </button>
                    </form>
                    <button type="button" class="btn btn-outline-danger rounded-pill px-4 btn-sm"
                        data-bs-toggle="modal" data-bs-target="#rejectModal{{ $item->id }}">
                        <i class="bi bi-x-circle me-1"></i> Tolak
                    </button>
                </div>
            </div>
        </div>

        <div class="modal fade" id="rejectModal{{ $item->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <form action="{{ route('lab.waka_akademik.approval.eksternal.reject', $item->id) }}" method="POST">
                    @csrf
                    <div class="modal-content border-0 rounded-4">
                        <div class="modal-header border-0">
                            <h5 class="fw-bold text-danger"><i class="bi bi-x-circle me-2"></i>Tolak Peminjaman</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p class="small text-muted">Tolak pengajuan dari <strong>{{ $item->nama_peminjam }}</strong> ({{ $item->instansi }})?</p>
                            <label class="form-label small fw-medium">Alasan Penolakan (Opsional)</label>
                            <textarea name="catatan" class="form-control rounded-3 small" rows="3" placeholder="Misal: Alat sedang digunakan untuk kebutuhan internal sekolah."></textarea>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-light rounded-pill px-4 btn-sm" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger rounded-pill px-4 btn-sm">Tolak Pengajuan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @empty
        <div class="card border-0 rounded-4 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                <h6 class="text-muted">Tidak ada pengajuan peminjaman alat eksternal</h6>
            </div>
        </div>
        @endforelse
    </div>

    {{-- TAB RUANGAN --}}
    <div class="tab-pane fade" id="ruangan" role="tabpanel">
        @forelse($ruanganRequests as $ruangan)
        @php
            $priorityLabel = 'Normal';
            $priorityBg    = '#ECFDF5';
            $priorityText  = '#065F46';
            $tujuan = strtolower($ruangan->tujuan ?? $ruangan->keperluan ?? '');
            if (str_contains($tujuan, 'urgent') || str_contains($tujuan, 'darurat')) {
                $priorityLabel = 'Urgent';  $priorityBg = '#FEF2F2'; $priorityText = '#991B1B';
            } elseif (str_contains($tujuan, 'penting')) {
                $priorityLabel = 'Sedang';  $priorityBg = '#FFFBEB'; $priorityText = '#92400E';
            }
        @endphp
        <div class="card border-0 rounded-4 shadow-sm mb-4">
            <div class="card-body p-4">

                <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-3">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">{{ $ruangan->nama }}</h6>
                        <p class="small text-muted mb-0">{{ $ruangan->instansi ?? 'Eksternal' }}</p>
                    </div>
                    <span class="badge rounded-pill px-3 py-2" style="background:{{ $priorityBg }};color:{{ $priorityText }};font-size:.78rem;">
                        <i class="bi bi-circle-fill me-1"></i>{{ $priorityLabel }}
                    </span>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-sm-6 col-md-3">
                        <p class="small text-muted mb-1 fw-medium">Ruangan Dipinjam</p>
                        <p class="fw-semibold small mb-0">{{ $ruangan->labor->nama_labor ?? 'N/A' }}</p>
                        <p class="small text-muted mb-0">Kapasitas: {{ $ruangan->labor->kapasitas ?? 0 }} Siswa</p>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <p class="small text-muted mb-1 fw-medium">Tanggal & Waktu</p>
                        <p class="fw-semibold small mb-0">{{ \Carbon\Carbon::parse($ruangan->tanggal)->isoFormat('D MMM Y') }} s/d {{ \Carbon\Carbon::parse($ruangan->tanggal_kembali ?? $ruangan->tanggal)->isoFormat('D MMM Y') }}</p>
                        <p class="small text-muted mb-0">{{ substr($ruangan->jam_pinjam, 0, 5) }} - {{ substr($ruangan->jam_kembali, 0, 5) }}</p>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <p class="small text-muted mb-1 fw-medium">Keperluan</p>
                        <p class="small mb-0">{{ $ruangan->tujuan ?? $ruangan->keperluan ?? '—' }}</p>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <p class="small text-muted mb-1 fw-medium">Direkomendasikan</p>
                        <span class="badge rounded-pill" style="background:#DCFCE7;color:#166534;">
                            <i class="bi bi-check-circle-fill me-1"></i>Ya
                        </span>
                    </div>
                </div>

                <div class="rounded-3 p-2 mb-3" style="background:#FFF7ED;border-left:3px solid #F97316;">
                    <p class="small mb-0 fw-medium" style="color:#9A3412;">
                        <i class="bi bi-info-circle me-1"></i>
                        <strong>Perhatian:</strong> Pastikan jadwal peminjaman ruangan ini tidak bentrok dengan jadwal reguler laboratorium.
                    </p>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <form action="{{ route('lab.waka_akademik.approval.ruangan_eksternal.approve', $ruangan->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success rounded-pill px-4 btn-sm"
                            onclick="return confirm('Setujui peminjaman ruangan oleh {{ $ruangan->nama }}?')">
                            <i class="bi bi-check-circle me-1"></i> Setujui
                        </button>
                    </form>
                    <button type="button" class="btn btn-outline-danger rounded-pill px-4 btn-sm"
                        data-bs-toggle="modal" data-bs-target="#rejectRuanganModal{{ $ruangan->id }}">
                        <i class="bi bi-x-circle me-1"></i> Tolak
                    </button>
                </div>
            </div>
        </div>

        <div class="modal fade" id="rejectRuanganModal{{ $ruangan->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <form action="{{ route('lab.waka_akademik.approval.ruangan_eksternal.reject', $ruangan->id) }}" method="POST">
                    @csrf
                    <div class="modal-content border-0 rounded-4">
                        <div class="modal-header border-0">
                            <h5 class="fw-bold text-danger"><i class="bi bi-x-circle me-2"></i>Tolak Peminjaman Ruangan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p class="small text-muted">Tolak pengajuan dari <strong>{{ $ruangan->nama }}</strong>?</p>
                            <label class="form-label small fw-medium">Alasan Penolakan (Opsional)</label>
                            <textarea name="catatan" class="form-control rounded-3 small" rows="3" placeholder="Misal: Ruangan sedang direnovasi atau digunakan."></textarea>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-light rounded-pill px-4 btn-sm" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger rounded-pill px-4 btn-sm">Tolak Pengajuan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @empty
        <div class="card border-0 rounded-4 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                <h6 class="text-muted">Tidak ada pengajuan peminjaman ruangan eksternal</h6>
            </div>
        </div>
        @endforelse
    </div>

    {{-- TAB RIWAYAT --}}
    <div class="tab-pane fade" id="riwayat" role="tabpanel">
        @if($riwayat->count() > 0 || $riwayatRuangan->count() > 0)
        <div class="row">
            @if($riwayat->count() > 0)
            <div class="col-12 mb-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-clock-history me-2"></i>Riwayat Persetujuan Alat (20 Terakhir)</h6>
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="small fw-semibold text-muted py-3 border-0 rounded-start-4 ps-4">Peminjam</th>
                                    <th class="small fw-semibold text-muted py-3 border-0">Instansi</th>
                                    <th class="small fw-semibold text-muted py-3 border-0">Alat/Lab</th>
                                    <th class="small fw-semibold text-muted py-3 border-0">Periode</th>
                                    <th class="small fw-semibold text-muted py-3 border-0 rounded-end-4 pe-4">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($riwayat as $r)
                                <tr>
                                    <td class="py-3 ps-4 fw-medium">{{ $r->nama_peminjam ?? '—' }}</td>
                                    <td class="py-3 text-muted small">{{ $r->instansi ?? '—' }}</td>
                                    <td class="py-3 small">{{ $r->inventaris->nama_inventaris ?? ($r->deskripsi ?? '—') }}</td>
                                    <td class="py-3 text-muted small">
                                        {{ $r->tanggal_pinjam ? \Carbon\Carbon::parse($r->tanggal_pinjam)->format('d M Y') : '—' }}
                                        @if($r->tanggal_kembali) – {{ \Carbon\Carbon::parse($r->tanggal_kembali)->format('d M Y') }} @endif
                                    </td>
                                    <td class="py-3 pe-4">
                                        @php 
                                            $st = strtolower($r->status ?? 'pending'); 
                                            $badgeClass = $st === 'approved' ? 'success' : ($st === 'rejected' ? 'danger' : 'secondary');
                                        @endphp
                                        <span class="badge bg-{{ $badgeClass }} bg-opacity-10 text-{{ $badgeClass }} border border-{{ $badgeClass }} rounded-pill px-3">
                                            {{ ucfirst($st === 'approved' ? 'Disetujui' : ($st === 'rejected' ? 'Ditolak' : ucfirst($st))) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            @if($riwayatRuangan->count() > 0)
            <div class="col-12 mb-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-clock-history me-2"></i>Riwayat Persetujuan Ruangan (20 Terakhir)</h6>
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="small fw-semibold text-muted py-3 border-0 rounded-start-4 ps-4">Peminjam</th>
                                    <th class="small fw-semibold text-muted py-3 border-0">Instansi</th>
                                    <th class="small fw-semibold text-muted py-3 border-0">Ruangan</th>
                                    <th class="small fw-semibold text-muted py-3 border-0">Periode</th>
                                    <th class="small fw-semibold text-muted py-3 border-0 rounded-end-4 pe-4">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($riwayatRuangan as $r)
                                <tr>
                                    <td class="py-3 ps-4 fw-medium">{{ collect(explode('-', $r->nama))->first() ?? '—' }}</td>
                                    <td class="py-3 text-muted small">{{ collect(explode('-', $r->nama))->last() ?? 'Eksternal' }}</td>
                                    <td class="py-3 small">{{ $r->labor->nama_labor ?? 'N/A' }}</td>
                                    <td class="py-3 text-muted small">
                                        {{ $r->tanggal ? \Carbon\Carbon::parse($r->tanggal)->format('d M Y') : '—' }}
                                        @if($r->tanggal_kembali && $r->tanggal_kembali !== $r->tanggal) – {{ \Carbon\Carbon::parse($r->tanggal_kembali)->format('d M Y') }} @endif
                                    </td>
                                    <td class="py-3 pe-4">
                                        @php 
                                            $st = strtolower($r->status ?? 'pending'); 
                                            $badgeClass = $st === 'approved' ? 'success' : ($st === 'rejected' ? 'danger' : 'secondary');
                                        @endphp
                                        <span class="badge bg-{{ $badgeClass }} bg-opacity-10 text-{{ $badgeClass }} border border-{{ $badgeClass }} rounded-pill px-3">
                                            {{ ucfirst($st === 'approved' ? 'Disetujui' : ($st === 'rejected' ? 'Ditolak' : ucfirst($st))) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
        @else
        <div class="card border-0 rounded-4 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-clock-history fs-1 text-muted d-block mb-3"></i>
                <h6 class="text-muted">Tidak ada riwayat persetujuan</h6>
            </div>
        </div>
        @endif
    </div>
</div>

@endsection
