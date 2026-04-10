@extends('lab.layouts.unified', ['title' => 'Rekomendasi Peminjaman Eksternal'])

@section('breadcrumb')
<p class="breadcrumb-small mb-0">Dashboard › Rekomendasi Eksternal</p>
@endsection

@section('css')
<style>
    .pinjam-card { border-radius: 14px; border: 1.5px solid #E2E8F0; background: white; transition: all 0.2s; }
    .pinjam-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.08); }
    .pinjam-card.pending-card { border-color: #FCD34D; background: #FFFDF0; }
    .status-badge { font-size: 0.7rem; padding: 4px 10px; border-radius: 20px; font-weight: 600; }
    .s-pending      { background:#FEF3C7; color:#B45309; }
    .s-recommended  { background:#DCFCE7; color:#15803D; }
    .s-rejected     { background:#FFE4E6; color:#BE123C; }
    .s-approved     { background:#E0F2FE; color:#0369A1; }
    .info-pill { background:#F8FAFC; border-radius:8px; padding:8px 12px; font-size:0.8rem; margin-bottom:6px; }
    .section-title { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; color: #94A3B8; font-weight: 700; margin-bottom: 12px; }
</style>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-0">Rekomendasi Peminjaman Eksternal</h4>
        <small class="text-muted">Berikan rekomendasi untuk permohonan peminjaman dari pihak luar</small>
    </div>
    @if($pending->count() > 0)
    <span class="badge bg-warning text-dark" style="font-size:0.8rem; padding:8px 14px;">
        <i class="bi bi-hourglass-split me-1"></i>{{ $pending->count() }} menunggu rekomendasi
    </span>
    @endif
</div>

{{-- ── Alur Persetujuan ── --}}
<div class="mb-4 p-3 rounded-3 d-flex align-items-center gap-3 flex-wrap" style="background:#F0F9FF; border: 1px solid #BAE6FD;">
    <div class="text-center px-3">
        <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-white border border-2 border-info mb-1" style="width:36px;height:36px;">
            <i class="bi bi-person-fill text-info" style="font-size:1rem;"></i>
        </div>
        <div style="font-size:0.72rem; font-weight:600; color:#0369A1;">Pemohon</div>
    </div>
    <i class="bi bi-arrow-right text-muted"></i>
    <div class="text-center px-3">
        <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-info text-white mb-1" style="width:36px;height:36px;">
            <i class="bi bi-person-badge-fill" style="font-size:1rem;"></i>
        </div>
        <div style="font-size:0.72rem; font-weight:600; color:#0369A1;">Admin Lab</div>
        <small style="font-size:0.65rem; color:#64748B;">Input Permohonan</small>
    </div>
    <i class="bi bi-arrow-right text-muted"></i>
    <div class="text-center px-3">
        <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-warning text-white mb-1" style="width:36px;height:36px;">
            <i class="bi bi-star-fill" style="font-size:1rem;"></i>
        </div>
        <div style="font-size:0.72rem; font-weight:700; color:#D97706;">Anda (Kepala Lab)</div>
        <small style="font-size:0.65rem; color:#64748B;">Rekomendasi</small>
    </div>
    <i class="bi bi-arrow-right text-muted"></i>
    <div class="text-center px-3">
        <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-white border border-2 border-secondary mb-1" style="width:36px;height:36px;">
            <i class="bi bi-building-check text-secondary" style="font-size:1rem;"></i>
        </div>
        <div style="font-size:0.72rem; font-weight:600; color:#475569;">Kepala Sekolah</div>
        <small style="font-size:0.65rem; color:#64748B;">Persetujuan Akhir</small>
    </div>
</div>

{{-- ── Menunggu Rekomendasi ── --}}
@if($pending->isEmpty())
    <div class="text-center py-4 mb-4 rounded-3" style="background:#F0FDF4; border: 1px dashed #86EFAC;">
        <i class="bi bi-check-circle-fill text-success" style="font-size:2.5rem;"></i>
        <p class="mt-2 mb-0 text-success fw-semibold">Tidak ada permohonan yang perlu direkomendasi saat ini!</p>
    </div>
@else
<div class="section-title"><i class="bi bi-hourglass me-2"></i>Menunggu Rekomendasi Anda ({{ $pending->count() }})</div>
<ul class="nav nav-pills mb-4" id="approvalTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active rounded-pill px-4 me-2" id="alat-tab" data-bs-toggle="pill" data-bs-target="#alat" type="button" role="tab">
            Peminjaman Alat 
            @if($pending->count() > 0) <span class="badge bg-warning text-dark ms-1">{{ $pending->count() }}</span> @endif
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link rounded-pill px-4" id="ruangan-tab" data-bs-toggle="pill" data-bs-target="#ruangan" type="button" role="tab">
            Peminjaman Ruangan
            @if($pendingRuangan->count() > 0) <span class="badge bg-warning text-dark ms-1">{{ $pendingRuangan->count() }}</span> @endif
        </button>
    </li>
</ul>

<div class="tab-content" id="approvalTabsContent">
    {{-- TAB ALAT --}}
    <div class="tab-pane fade show active" id="alat" role="tabpanel">
        <div class="row g-3 mb-4">
            @foreach($pending as $p)
            <div class="col-12 col-md-6">
                <div class="pinjam-card pending-card p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h6 class="fw-bold mb-1">{{ $p->nama_peminjam ?? 'N/A' }}</h6>
                            <small class="text-muted">{{ $p->instansi ?? '' }}</small>
                        </div>
                        <span class="status-badge s-pending"><i class="bi bi-hourglass-split me-1"></i>Menunggu</span>
                    </div>

                    <div class="info-pill"><i class="bi bi-box-seam me-2 text-muted"></i><strong>Alat/Lab:</strong> {{ $p->inventaris->nama_inventaris ?? ($p->deskripsi ?? '—') }}</div>
                    <div class="info-pill"><i class="bi bi-calendar-event me-2 text-muted"></i><strong>Tgl Pinjam:</strong> {{ $p->tanggal_pinjam ? \Carbon\Carbon::parse($p->tanggal_pinjam)->isoFormat('D MMMM Y') : '—' }}</div>
                    <div class="info-pill"><i class="bi bi-calendar-check me-2 text-muted"></i><strong>Tgl Kembali:</strong> {{ $p->tanggal_kembali ? \Carbon\Carbon::parse($p->tanggal_kembali)->isoFormat('D MMMM Y') : '—' }}</div>
                    @if($p->keperluan ?? $p->alasan)
                    <div class="info-pill"><i class="bi bi-chat-text me-2 text-muted"></i><strong>Keperluan:</strong> {{ $p->keperluan ?? $p->alasan }}</div>
                    @endif

                    <div class="d-flex gap-2 mt-3">
                        {{-- Rekomendasikan --}}
                        <button type="button" class="btn btn-success btn-sm flex-grow-1"
                            data-bs-toggle="modal" data-bs-target="#modalRekomen{{ $p->id }}">
                            <i class="bi bi-patch-check-fill me-1"></i> Rekomendasikan
                        </button>

                        {{-- Tolak --}}
                        <button type="button" class="btn btn-outline-danger btn-sm flex-grow-1"
                            data-bs-toggle="modal" data-bs-target="#modalTolak{{ $p->id }}">
                            <i class="bi bi-x-circle me-1"></i> Tolak
                        </button>
                    </div>
                </div>

                {{-- Modal Rekomendasikan --}}
                <div class="modal fade" id="modalRekomen{{ $p->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content border-0 shadow">
                            <div class="modal-header border-0">
                                <h6 class="modal-title fw-bold text-success"><i class="bi bi-patch-check-fill me-2"></i>Berikan Rekomendasi</h6>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('lab.kepala_lab.approval.eksternal.recommend', $p->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="action" value="recommend">
                                <div class="modal-body">
                                    <div class="p-3 mb-3 rounded-3" style="background:#F0FDF4;">
                                        <p class="mb-1 small"><strong>Peminjam:</strong> {{ $p->nama_peminjam }}</p>
                                        <p class="mb-1 small"><strong>Alat/Lab:</strong> {{ $p->inventaris->nama_inventaris ?? ($p->deskripsi ?? '—') }}</p>
                                        <p class="mb-0 small"><strong>Periode:</strong> {{ $p->tanggal_pinjam ? \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d M Y') : '—' }} – {{ $p->tanggal_kembali ? \Carbon\Carbon::parse($p->tanggal_kembali)->format('d M Y') : '—' }}</p>
                                    </div>
                                    <label class="form-label small fw-600">Catatan Rekomendasi (opsional)</label>
                                    <textarea name="catatan" class="form-control form-control-sm" rows="3" placeholder="Tambahkan catatan jika perlu…"></textarea>
                                    <p class="mt-2 text-muted small"><i class="bi bi-info-circle me-1"></i>Setelah Anda merekomendasikan, permohonan akan diteruskan ke Kepala Sekolah untuk persetujuan akhir.</p>
                                </div>
                                <div class="modal-footer border-0 pt-0">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-success btn-sm px-4">
                                        <i class="bi bi-patch-check-fill me-1"></i> Rekomendasikan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Modal Tolak --}}
                <div class="modal fade" id="modalTolak{{ $p->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content border-0 shadow">
                            <div class="modal-header border-0">
                                <h6 class="modal-title fw-bold text-danger"><i class="bi bi-x-circle-fill me-2"></i>Tolak Permohonan</h6>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('lab.kepala_lab.approval.eksternal.recommend', $p->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="action" value="reject">
                                <div class="modal-body">
                                    <div class="p-3 mb-3 rounded-3" style="background:#FFF5F5;">
                                        <p class="mb-1 small"><strong>Peminjam:</strong> {{ $p->nama_peminjam }}</p>
                                        <p class="mb-0 small"><strong>Alat/Lab:</strong> {{ $p->inventaris->nama_inventaris ?? ($p->deskripsi ?? '—') }}</p>
                                    </div>
                                    <label class="form-label small fw-600">Alasan Penolakan <span class="text-muted fw-400">(opsional)</span></label>
                                    <textarea name="catatan" class="form-control form-control-sm" rows="3" placeholder="Tulis alasan penolakan…"></textarea>
                                </div>
                                <div class="modal-footer border-0 pt-0">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-danger btn-sm px-4">Tolak Permohonan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            @if($pending->isEmpty())
                <div class="col-12 text-center py-4 rounded-3" style="background:#F0FDF4; border: 1px dashed #86EFAC;">
                    <i class="bi bi-check-circle-fill text-success" style="font-size:2.5rem;"></i>
                    <p class="mt-2 mb-0 text-success fw-semibold">Tidak ada permohonan alat yang perlu direkomendasi!</p>
                </div>
            @endif
        </div>
    </div>

    {{-- TAB RUANGAN --}}
    <div class="tab-pane fade" id="ruangan" role="tabpanel">
        <div class="row g-3 mb-4">
            @foreach($pendingRuangan as $r)
            <div class="col-12 col-md-6">
                <div class="pinjam-card pending-card p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h6 class="fw-bold mb-1">{{ collect(explode('-', $r->nama))->first() ?? 'N/A' }}</h6>
                            <small class="text-muted">{{ collect(explode('-', $r->nama))->last() ?? '' }}</small>
                        </div>
                        <span class="status-badge s-pending"><i class="bi bi-hourglass-split me-1"></i>Menunggu</span>
                    </div>

                    <div class="info-pill"><i class="bi bi-building me-2 text-muted"></i><strong>Ruangan:</strong> {{ $r->labor->nama_labor ?? 'N/A' }}</div>
                    <div class="info-pill"><i class="bi bi-calendar-event me-2 text-muted"></i><strong>Tgl Pinjam:</strong> {{ $r->tanggal ? \Carbon\Carbon::parse($r->tanggal)->isoFormat('D MMMM Y') : '—' }}</div>
                    <div class="info-pill"><i class="bi bi-clock me-2 text-muted"></i><strong>Waktu:</strong> {{ substr($r->jam_pinjam,0,5) }} - {{ substr($r->jam_kembali,0,5) }}</div>
                    @if($r->keperluan)
                    <div class="info-pill"><i class="bi bi-chat-text me-2 text-muted"></i><strong>Keperluan:</strong> {{ $r->keperluan }}</div>
                    @endif

                    <div class="d-flex gap-2 mt-3">
                        <button type="button" class="btn btn-success btn-sm flex-grow-1"
                            data-bs-toggle="modal" data-bs-target="#modalRekomenRuangan{{ $r->id }}">
                            <i class="bi bi-patch-check-fill me-1"></i> Rekomendasikan
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-sm flex-grow-1"
                            data-bs-toggle="modal" data-bs-target="#modalTolakRuangan{{ $r->id }}">
                            <i class="bi bi-x-circle me-1"></i> Tolak
                        </button>
                    </div>
                </div>

                {{-- Modal Rekomendasikan Ruangan --}}
                <div class="modal fade" id="modalRekomenRuangan{{ $r->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content border-0 shadow">
                            <div class="modal-header border-0">
                                <h6 class="modal-title fw-bold text-success"><i class="bi bi-patch-check-fill me-2"></i>Berikan Rekomendasi Ruangan</h6>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('lab.kepala_lab.approval.ruangan_eksternal.recommend', $r->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="action" value="recommend">
                                <div class="modal-body">
                                    <div class="p-3 mb-3 rounded-3" style="background:#F0FDF4;">
                                        <p class="mb-1 small"><strong>Peminjam:</strong> {{ $r->nama }}</p>
                                        <p class="mb-1 small"><strong>Ruangan:</strong> {{ $r->labor->nama_labor ?? 'N/A' }}</p>
                                    </div>
                                    <label class="form-label small fw-600">Catatan Rekomendasi (opsional)</label>
                                    <textarea name="catatan" class="form-control form-control-sm" rows="3" placeholder="Tambahkan catatan jika perlu…"></textarea>
                                </div>
                                <div class="modal-footer border-0 pt-0">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-success btn-sm px-4">
                                        <i class="bi bi-patch-check-fill me-1"></i> Rekomendasikan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Modal Tolak Ruangan --}}
                <div class="modal fade" id="modalTolakRuangan{{ $r->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content border-0 shadow">
                            <div class="modal-header border-0">
                                <h6 class="modal-title fw-bold text-danger"><i class="bi bi-x-circle-fill me-2"></i>Tolak Permohonan Ruangan</h6>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('lab.kepala_lab.approval.ruangan_eksternal.recommend', $r->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="action" value="reject">
                                <div class="modal-body">
                                    <div class="p-3 mb-3 rounded-3" style="background:#FFF5F5;">
                                        <p class="mb-1 small"><strong>Peminjam:</strong> {{ $r->nama }}</p>
                                        <p class="mb-0 small"><strong>Ruangan:</strong> {{ $r->labor->nama_labor ?? 'N/A' }}</p>
                                    </div>
                                    <label class="form-label small fw-600">Alasan Penolakan <span class="text-muted fw-400">(opsional)</span></label>
                                    <textarea name="catatan" class="form-control form-control-sm" rows="3" placeholder="Tulis alasan penolakan…"></textarea>
                                </div>
                                <div class="modal-footer border-0 pt-0">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-danger btn-sm px-4">Tolak Permohonan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            @if($pendingRuangan->isEmpty())
                <div class="col-12 text-center py-4 rounded-3" style="background:#F0FDF4; border: 1px dashed #86EFAC;">
                    <i class="bi bi-check-circle-fill text-success" style="font-size:2.5rem;"></i>
                    <p class="mt-2 mb-0 text-success fw-semibold">Tidak ada permohonan ruangan yang perlu direkomendasi!</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endif

{{-- ── Riwayat Rekomendasi ── --}}
@if($riwayat->count() > 0 || $riwayatRuangan->count() > 0)
<div class="row">
    @if($riwayat->count() > 0)
    <div class="col-12 mb-4">
        <div class="section-title"><i class="bi bi-clock-history me-2"></i>Riwayat Rekomendasi Alat (20 Terakhir)</div>
        <x-ui.card>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead style="background:#F8FAFC;">
                        <tr>
                            <th class="small fw-600 text-muted py-2 border-0">Peminjam</th>
                            <th class="small fw-600 text-muted py-2 border-0">Instansi</th>
                            <th class="small fw-600 text-muted py-2 border-0">Alat</th>
                            <th class="small fw-600 text-muted py-2 border-0">Periode</th>
                            <th class="small fw-600 text-muted py-2 border-0">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($riwayat as $r)
                        <tr style="font-size:0.84rem;">
                            <td class="py-2 fw-semibold">{{ $r->nama_peminjam ?? '—' }}</td>
                            <td class="py-2 text-muted">{{ $r->instansi ?? '—' }}</td>
                            <td class="py-2">{{ $r->inventaris->nama_inventaris ?? ($r->deskripsi ?? '—') }}</td>
                            <td class="py-2 text-muted">
                                {{ $r->tanggal_pinjam ? \Carbon\Carbon::parse($r->tanggal_pinjam)->format('d M Y') : '—' }}
                                @if($r->tanggal_kembali) – {{ \Carbon\Carbon::parse($r->tanggal_kembali)->format('d M Y') }} @endif
                            </td>
                            <td class="py-2">
                                @php $st = strtolower($r->status ?? 'pending'); @endphp
                                <span class="status-badge s-{{ $st }}">{{ ucfirst($st === 'recommended' ? 'Direkomendasikan' : ($st === 'approved' ? 'Disetujui KS' : ucfirst($st))) }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-ui.card>
    </div>
    @endif

    @if($riwayatRuangan->count() > 0)
    <div class="col-12 mb-4">
        <div class="section-title"><i class="bi bi-clock-history me-2"></i>Riwayat Rekomendasi Ruangan (20 Terakhir)</div>
        <x-ui.card>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead style="background:#F8FAFC;">
                        <tr>
                            <th class="small fw-600 text-muted py-2 border-0">Peminjam</th>
                            <th class="small fw-600 text-muted py-2 border-0">Instansi</th>
                            <th class="small fw-600 text-muted py-2 border-0">Ruangan</th>
                            <th class="small fw-600 text-muted py-2 border-0">Periode</th>
                            <th class="small fw-600 text-muted py-2 border-0">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($riwayatRuangan as $r)
                        <tr style="font-size:0.84rem;">
                            <td class="py-2 fw-semibold">{{ collect(explode('-', $r->nama))->first() ?? '—' }}</td>
                            <td class="py-2 text-muted">{{ collect(explode('-', $r->nama))->last() ?? 'Eksternal' }}</td>
                            <td class="py-2">{{ $r->labor->nama_labor ?? 'N/A' }}</td>
                            <td class="py-2 text-muted">
                                {{ $r->tanggal ? \Carbon\Carbon::parse($r->tanggal)->format('d M Y') : '—' }}
                                @if($r->tanggal_kembali && $r->tanggal_kembali !== $r->tanggal) – {{ \Carbon\Carbon::parse($r->tanggal_kembali)->format('d M Y') }} @endif
                            </td>
                            <td class="py-2">
                                @php $st = strtolower($r->status ?? 'pending'); @endphp
                                <span class="status-badge s-{{ $st }}">{{ ucfirst($st === 'recommended' ? 'Direkomendasikan' : ($st === 'approved' ? 'Disetujui KS' : ucfirst($st))) }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-ui.card>
    </div>
    @endif
</div>
@endif

@endsection
