@extends('lab.layouts.unified', ['title' => 'Supervisi Laporan Kerusakan'])

@section('breadcrumb')
<p class="breadcrumb-small mb-0">Dashboard › Supervisi Kerusakan</p>
@endsection

@section('css')
<style>
    .readonly-badge { background: #EFF6FF; color: #1D4ED8; font-size: 0.68rem; border-radius: 20px; padding: 3px 10px; font-weight: 600; border: 1px solid #BFDBFE; }
    .eskalasi-card { border-radius: 14px; border: 2px solid #FCA5A5; background: #FFF5F5; transition: all 0.2s; }
    .eskalasi-card:hover { box-shadow: 0 4px 16px rgba(220,38,38,0.15); }
    .badge-eskalasi { background: #FEE2E2; color: #DC2626; }
    .status-badge { font-size: 0.7rem; padding: 4px 10px; border-radius: 20px; font-weight: 600; }
    .filter-bar { background: white; border: 1px solid #E2E8F0; border-radius: 12px; padding: 16px; margin-bottom: 20px; }
</style>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-0">Supervisi Laporan Kerusakan</h4>
        <small class="text-muted">Pantau laporan dan setujui/tolak eskalasi kerusakan · <span class="readonly-badge"><i class="bi bi-eye me-1"></i>View + Approve Eskalasi</span></small>
    </div>
    <span class="badge bg-light text-muted border" style="font-size:0.75rem; padding:6px 12px;">
        <i class="bi bi-tools me-1 text-danger"></i>
        Total: {{ $laporan->total() }} laporan
    </span>
</div>

{{-- ── Eskalasi Menunggu Persetujuan ── --}}
@if($eskalasi->count() > 0)
<div class="mb-4">
    <div class="d-flex align-items-center gap-2 mb-3">
        <h5 class="fw-bold mb-0 text-danger"><i class="bi bi-arrow-up-circle-fill me-2"></i>Eskalasi Menunggu Persetujuan Anda</h5>
        <span class="badge bg-danger">{{ $eskalasi->count() }}</span>
    </div>
    <div class="row g-3">
        @foreach($eskalasi as $e)
        <div class="col-12 col-md-6 col-xl-4">
            <div class="eskalasi-card p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <p class="fw-bold mb-0" style="font-size:0.9rem;">{{ $e->inventaris->nama_inventaris ?? 'Inventaris tidak ditemukan' }}</p>
                        <small class="text-muted">{{ $e->inventaris->kode_inventaris ?? '' }}</small>
                    </div>
                    <span class="status-badge badge-eskalasi"><i class="bi bi-arrow-up me-1"></i>Eskalasi</span>
                </div>

                <p class="text-muted mb-2" style="font-size:0.82rem; line-height:1.4;">
                    <strong>Deskripsi:</strong> {{ $e->deskripsi ?? $e->keterangan ?? '—' }}
                </p>
                <p class="text-muted mb-1" style="font-size:0.8rem;">
                    <i class="bi bi-person me-1"></i>Dilaporkan oleh: <strong>{{ $e->reporter_info }}</strong>
                </p>
                <p class="text-muted mb-3" style="font-size:0.8rem;">
                    <i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($e->created_at)->isoFormat('D MMM Y, HH:mm') }}
                </p>

                <div class="d-flex gap-2">
                    {{-- Setujui Eskalasi --}}
                    <form action="{{ route('lab.kepala_lab.supervisi.kerusakan.approve', $e->id) }}" method="POST" class="flex-grow-1">
                        @csrf
                        <button type="button" class="btn btn-success btn-sm w-100"
                            onclick="confirmAction(this.form, 'Setujui eskalasi kerusakan ini?', 'Eskalasi akan diproses lebih lanjut.')">
                            <i class="bi bi-check-lg me-1"></i> Setujui
                        </button>
                    </form>

                    {{-- Tolak Eskalasi --}}
                    <button type="button" class="btn btn-outline-danger btn-sm flex-grow-1"
                        data-bs-toggle="modal" data-bs-target="#rejectModal{{ $e->id }}">
                        <i class="bi bi-x-lg me-1"></i> Tolak
                    </button>
                </div>
            </div>

            {{-- Modal Tolak --}}
            <div class="modal fade" id="rejectModal{{ $e->id }}" tabindex="-1">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content border-0 shadow">
                        <div class="modal-header border-0">
                            <h6 class="modal-title fw-bold text-danger"><i class="bi bi-x-circle me-2"></i>Tolak Eskalasi</h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('lab.kepala_lab.supervisi.kerusakan.reject', $e->id) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <p class="text-muted small mb-3">Kerusakan: <strong>{{ $e->inventaris->nama_inventaris ?? '—' }}</strong></p>
                                <label class="form-label small fw-600">Catatan (opsional)</label>
                                <textarea name="catatan" class="form-control form-control-sm" rows="3" placeholder="Alasan penolakan…"></textarea>
                            </div>
                            <div class="modal-footer border-0 pt-0">
                                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-danger btn-sm">Tolak Eskalasi</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<hr class="my-4">
@endif

{{-- ── Semua Laporan (Read-only table) ── --}}
<h5 class="fw-bold mb-3">Semua Laporan Kerusakan</h5>

{{-- Filter --}}
<form method="GET" action="{{ route('lab.kepala_lab.supervisi.kerusakan') }}" class="filter-bar">
    <div class="row g-2 align-items-end">
        <div class="col-md-4">
            <label class="form-label small fw-600 text-muted mb-1">Filter Status</label>
            <select name="status" class="form-select form-select-sm">
                <option value="">Semua Status</option>
                @foreach(['dilaporkan', 'diproses', 'selesai', 'ditolak'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label small fw-600 text-muted mb-1">Hanya Eskalasi KaLab</label>
            <div class="form-check mt-1">
                <input class="form-check-input" type="checkbox" name="eskalasi" value="1" id="chkEsk" {{ request('eskalasi') ? 'checked' : '' }}>
                <label class="form-check-label small" for="chkEsk">Tampilkan eskalasi saja</label>
            </div>
        </div>
        <div class="col-md-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm px-3"><i class="bi bi-filter me-1"></i>Filter</button>
            <a href="{{ route('lab.kepala_lab.supervisi.kerusakan') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
        </div>
    </div>
</form>

@if($laporan->isEmpty())
    <div class="text-center py-4">
        <i class="bi bi-tools opacity-25" style="font-size:3rem;"></i>
        <p class="mt-2 text-muted small">Tidak ada laporan kerusakan ditemukan.</p>
    </div>
@else
<x-ui.card>
    <div class="table-responsive">
        <table class="table table-sm align-middle mb-0">
            <thead style="background:#F8FAFC;">
                <tr>
                    <th class="small fw-600 text-muted py-2 border-0">#</th>
                    <th class="small fw-600 text-muted py-2 border-0">Inventaris</th>
                    <th class="small fw-600 text-muted py-2 border-0">Dilaporkan Oleh</th>
                    <th class="small fw-600 text-muted py-2 border-0">Tingkat</th>
                    <th class="small fw-600 text-muted py-2 border-0">Status</th>
                    <th class="small fw-600 text-muted py-2 border-0">Eskalasi</th>
                    <th class="small fw-600 text-muted py-2 border-0">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($laporan as $i => $l)
                <tr style="font-size:0.84rem;" class="{{ $l->is_eskalasi && $l->eskalasi_ke == 'kepala_lab' && $l->eskalasi_status == 'menunggu' ? 'table-warning' : '' }}">
                    <td class="py-2 text-muted">{{ $laporan->firstItem() + $i }}</td>
                    <td class="py-2 fw-semibold">{{ $l->inventaris->nama_inventaris ?? 'N/A' }}</td>
                    <td class="py-2 text-muted">{{ $l->reporter_info }}</td>
                    <td class="py-2">
                        @php
                            $lvl = strtolower($l->tingkat_kerusakan ?? $l->level ?? 'ringan');
                            $lvlMap = ['ringan' => ['FEF3C7','B45309'], 'sedang' => ['FED7AA','C2410C'], 'berat' => ['FFE4E6','BE123C']];
                            $lvlC = $lvlMap[$lvl] ?? ['F1F5F9','475569'];
                        @endphp
                        <span class="status-badge" style="background:#{{ $lvlC[0] }}; color:#{{ $lvlC[1] }};">{{ ucfirst($lvl) }}</span>
                    </td>
                    <td class="py-2">
                        @php
                            $st = $l->status ?? 'dilaporkan';
                            $stMap = ['dilaporkan' => ['FEF3C7','B45309','Dilaporkan'], 'diproses' => ['E0F2FE','0369A1','Diproses'], 'selesai' => ['DCFCE7','15803D','Selesai'], 'ditolak' => ['F1F5F9','475569','Ditolak']];
                            $stC = $stMap[$st] ?? ['F1F5F9','475569', ucfirst($st)];
                        @endphp
                        <span class="status-badge" style="background:#{{ $stC[0] }}; color:#{{ $stC[1] }};">{{ $stC[2] }}</span>
                    </td>
                    <td class="py-2">
                        @if($l->is_eskalasi)
                            @if($l->eskalasi_ke == 'kepala_lab' && $l->eskalasi_status == 'menunggu')
                                <span class="status-badge" style="background:#FFE4E6;color:#BE123C;"><i class="bi bi-arrow-up me-1"></i>Perlu Tindakan</span>
                            @elseif($l->eskalasi_status == 'disetujui')
                                <span class="status-badge" style="background:#DCFCE7;color:#15803D;"><i class="bi bi-check me-1"></i>Disetujui</span>
                            @elseif($l->eskalasi_status == 'ditolak')
                                <span class="status-badge" style="background:#F1F5F9;color:#475569;">Ditolak</span>
                            @else
                                <span class="status-badge" style="background:#E0F2FE;color:#0369A1;">Dieskalasi</span>
                            @endif
                        @else
                            <span class="text-muted small">—</span>
                        @endif
                    </td>
                    <td class="py-2 text-muted">{{ \Carbon\Carbon::parse($l->created_at)->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $laporan->withQueryString()->links() }}</div>
</x-ui.card>
@endif

@endsection

@section('script')
<script>
function confirmAction(form, title, text) {
    Swal.fire({
        title: title, text: text,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#16A34A',
        cancelButtonColor: '#94A3B8',
        confirmButtonText: 'Ya, Setujui',
        cancelButtonText: 'Batal'
    }).then((result) => { if (result.isConfirmed) form.submit(); });
}
</script>
@endsection
