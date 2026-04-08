@extends('lab.layouts.unified', ['title' => 'Monitoring & Decision Center'])

@section('content')

{{-- Hero Banner --}}
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="ui-card border-0" style="background: linear-gradient(135deg, #4F46E5 0%, #6D28D9 50%, #7C3AED 100%) !important; color: white;">
            <div class="ui-card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <div class="rounded-3 p-2" style="background:rgba(255,255,255,0.2);">
                                <i class="bi bi-graph-up-arrow fs-4"></i>
                            </div>
                            <div>
                                <p class="mb-0 opacity-75 small text-uppercase fw-bold letter-spacing">Waka Kurikulum</p>
                                <h3 class="fw-bold mb-0">Monitoring & Decision Center</h3>
                            </div>
                        </div>
                        <p class="mb-0 opacity-75">Pantau, analisis, dan ambil keputusan berbasis data penggunaan laboratorium SMK Negeri 5 Padang.</p>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <div class="badge px-3 py-2 rounded-pill" style="background:rgba(255,255,255,0.2); font-size:0.85rem;">
                            <i class="bi bi-calendar3 me-1"></i>
                            {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
                        </div>
                        @if($alertCount > 0)
                        <div class="mt-2">
                            <a href="{{ route('lab.waka_akademik.alerts') }}" class="badge bg-danger px-3 py-2 rounded-pill text-decoration-none" style="font-size:0.85rem;">
                                <i class="bi bi-exclamation-triangle me-1"></i>{{ $alertCount }} Alert Aktif
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- KPI Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-lg">
        <div class="ui-card border-0 h-100 kpi-card">
            <div class="ui-card-body p-3 text-center">
                <div class="kpi-icon mx-auto mb-2" style="background: linear-gradient(135deg,#6366F1,#4F46E5);">
                    <i class="bi bi-building text-white fs-5"></i>
                </div>
                <p class="text-muted small mb-1">Total Laboratorium</p>
                <h2 class="fw-bold mb-0 text-dark">{{ $totalLab }}</h2>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg">
        <div class="ui-card border-0 h-100 kpi-card">
            <div class="ui-card-body p-3 text-center">
                <div class="kpi-icon mx-auto mb-2" style="background: linear-gradient(135deg,#10B981,#059669);">
                    <i class="bi bi-calendar-check text-white fs-5"></i>
                </div>
                <p class="text-muted small mb-1">Jadwal Aktif</p>
                <h2 class="fw-bold mb-0 text-dark">{{ $jadwalAktif }}</h2>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg">
        <div class="ui-card border-0 h-100 kpi-card">
            <div class="ui-card-body p-3 text-center">
                <div class="kpi-icon mx-auto mb-2" style="background: linear-gradient(135deg,#F59E0B,#D97706);">
                    <i class="bi bi-clock-history text-white fs-5"></i>
                </div>
                <p class="text-muted small mb-1">Jadwal Hari Ini</p>
                <h2 class="fw-bold mb-0 text-dark">{{ $jadwalHariIni }}</h2>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg">
        <div class="ui-card border-0 h-100 kpi-card">
            <div class="ui-card-body p-3 text-center">
                <div class="kpi-icon mx-auto mb-2" style="background: linear-gradient(135deg,#EC4899,#DB2777);">
                    <i class="bi bi-person-video3 text-white fs-5"></i>
                </div>
                <p class="text-muted small mb-1">Guru Mengajar</p>
                <h2 class="fw-bold mb-0 text-dark">{{ $guruMengajar }}</h2>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg">
        <div class="ui-card border-0 h-100 kpi-card">
            <div class="ui-card-body p-3 text-center">
                <div class="kpi-icon mx-auto mb-2" style="background: linear-gradient(135deg,#06B6D4,#0891B2);">
                    <i class="bi bi-speedometer2 text-white fs-5"></i>
                </div>
                <p class="text-muted small mb-1">Utilisasi Lab</p>
                <h2 class="fw-bold mb-0 text-dark">{{ $utiliasasiPersen }}%</h2>
            </div>
        </div>
    </div>
</div>

@if(count($bentrokGuru) > 0 || count($bentrokLab) > 0 || $alertBentrokGuru > 0 || $alertKerusakanBelumDitangani > 0)
{{-- Alert Strip --}}
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="ui-card border-0" style="background: linear-gradient(135deg, #FEF2F2, #FFF1F0); border-left: 4px solid #EF4444 !important;">
            <div class="ui-card-body p-3">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle p-1" style="background:#EF4444;">
                            <i class="bi bi-exclamation-triangle-fill text-white" style="font-size:0.8rem;"></i>
                        </div>
                        <span class="fw-bold text-danger">Perhatian Diperlukan!</span>
                    </div>
                    @if($alertBentrokGuru > 0)
                    <span class="badge bg-danger rounded-pill">{{ $alertBentrokGuru }} Bentrok Guru</span>
                    @endif
                    @if(count($bentrokLab) > 0)
                    <span class="badge bg-warning text-dark rounded-pill">{{ count($bentrokLab) }} Bentrok Lab</span>
                    @endif
                    @if($alertKerusakanBelumDitangani > 0)
                    <span class="badge bg-orange rounded-pill" style="background:#F97316!important; color:white;">{{ $alertKerusakanBelumDitangani }} Kerusakan Pending</span>
                    @endif
                    <a href="{{ route('lab.waka_akademik.alerts') }}" class="ms-auto btn btn-danger btn-sm rounded-pill px-3">
                        Lihat Semua Alert <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Charts Row --}}
<div class="row g-4 mb-4">
    <div class="col-md-8">
        <div class="ui-card border-0 h-100">
            <div class="ui-card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="fw-bold mb-0">Penggunaan Lab per Hari</h6>
                        <small class="text-muted">Distribusi jadwal praktikum mingguan</small>
                    </div>
                    <span class="badge bg-primary-soft text-primary rounded-pill px-3"><i class="bi bi-bar-chart-fill me-1"></i>Mingguan</span>
                </div>
                <canvas id="weeklyChart" height="100"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="ui-card border-0 h-100">
            <div class="ui-card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="fw-bold mb-0">Lab Terpakai</h6>
                        <small class="text-muted">Top 5 laboratorium</small>
                    </div>
                </div>
                <canvas id="labDonutChart" height="180"></canvas>
                <div class="mt-3">
                    @foreach($labUsage as $usage)
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <small class="text-truncate" style="max-width:150px;">{{ $usage->labor->nama_labor ?? 'Lab #'.$usage->labor_id }}</small>
                        <span class="badge bg-primary-soft text-primary rounded-pill">{{ $usage->total }} jadwal</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Validasi Jadwal & Quick Actions --}}
<div class="row g-4 mb-4">
    <div class="col-md-8">
        <div class="ui-card border-0">
            <div class="ui-card-body p-0">
                <div class="d-flex justify-content-between align-items-center p-4 pb-3">
                    <div>
                        <h6 class="fw-bold mb-0">Jadwal Menunggu Validasi</h6>
                        <small class="text-muted">{{ $totalPending }} jadwal perlu keputusan Anda</small>
                    </div>
                    <a href="{{ route('lab.waka_akademik.validasi_jadwal') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Lihat Semua</a>
                </div>

                @if($pendingValidasi->isEmpty())
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-check-circle fs-2 text-success d-block mb-2"></i>
                    <small>Semua jadwal sudah divalidasi.</small>
                </div>
                @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 ps-4 small">Mata Pelajaran</th>
                                <th class="border-0 small">Lab</th>
                                <th class="border-0 small">Hari / Jam</th>
                                <th class="border-0 small text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingValidasi as $j)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-semibold small">{{ $j->mata_pelajaran }}</div>
                                    <div class="text-muted" style="font-size:0.75rem;">{{ $j->kelas }}</div>
                                </td>
                                <td><small>{{ $j->labor->nama_labor ?? '-' }}</small></td>
                                <td>
                                    <small class="fw-semibold">{{ $j->hari }}</small>
                                    <div style="font-size:0.75rem;" class="text-muted">{{ $j->jam_mulai }} – {{ $j->jam_selesai }}</div>
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('lab.waka_akademik.validasi_jadwal.approve', $j->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm rounded-pill px-2 py-0" title="Setujui">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-danger btn-sm rounded-pill px-2 py-0" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $j->id }}" title="Tolak">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </td>
                            </tr>

                            {{-- Reject Modal --}}
                            <div class="modal fade" id="rejectModal{{ $j->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 rounded-4">
                                        <div class="modal-header border-0 pb-0">
                                            <h6 class="modal-title fw-bold">Tolak Jadwal</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('lab.waka_akademik.validasi_jadwal.reject', $j->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body pt-2">
                                                <p class="small text-muted">Jadwal: <strong>{{ $j->mata_pelajaran }}</strong> – {{ $j->hari }}, {{ $j->jam_mulai }}–{{ $j->jam_selesai }}</p>
                                                <label class="form-label small fw-semibold">Catatan Revisi (opsional)</label>
                                                <textarea name="catatan_validasi" class="form-control rounded-3" rows="3" placeholder="Tuliskan alasan penolakan atau saran revisi..."></textarea>
                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                <button type="button" class="btn btn-light btn-sm rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3">Tolak Jadwal</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        {{-- Conditional Quick Actions --}}
        @php
            $quickActions = [];
            // Only show Validasi if there are pending items
            if($totalPending > 0) {
                $quickActions[] = [
                    'route'  => 'lab.waka_akademik.validasi_jadwal',
                    'label'  => 'Validasi Jadwal',
                    'icon'   => 'bi-calendar-check',
                    'color'  => 'danger',
                    'badge'  => $totalPending,
                    'desc'   => $totalPending.' jadwal menunggu persetujuan',
                ];
            }
            // Only show Kerusakan if there are unhandled reports
            if($alertKerusakanBelumDitangani > 0) {
                $quickActions[] = [
                    'route'  => 'lab.admin_new.kerusakan.index',
                    'label'  => 'Tindak Kerusakan',
                    'icon'   => 'bi-tools',
                    'color'  => 'warning',
                    'badge'  => $alertKerusakanBelumDitangani,
                    'desc'   => $alertKerusakanBelumDitangani.' laporan belum ditangani',
                ];
            }
            // Monitoring Lab always useful as contextual shortcut
            if(count($labIdle) > 0) {
                $quickActions[] = [
                    'route'  => 'lab.waka_akademik.monitoring_lab',
                    'label'  => 'Cek Lab Idle',
                    'icon'   => 'bi-building-slash',
                    'color'  => 'info',
                    'badge'  => count($labIdle),
                    'desc'   => count($labIdle).' lab tanpa jadwal',
                ];
            }
            // Export always available
            $quickActions[] = [
                'route'  => 'lab.waka_akademik.export_laporan',
                'label'  => 'Export Laporan',
                'icon'   => 'bi-download',
                'color'  => 'success',
                'badge'  => null,
                'desc'   => 'Unduh data jadwal (CSV)',
            ];
        @endphp

        @if(count($quickActions) > 0)
        <div class="ui-card border-0 mb-3">
            <div class="ui-card-body p-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="fs-5">⚡</span>
                    <h6 class="fw-bold mb-0">Aksi Cepat</h6>
                    <small class="text-muted ms-1">— berdasarkan kondisi terkini</small>
                </div>
                <div class="d-flex flex-column gap-2">
                    @foreach($quickActions as $action)
                    <a href="{{ route($action['route']) }}"
                       class="btn btn-outline-{{ $action['color'] }} rounded-3 text-start d-flex align-items-center gap-2 py-2 px-3 action-btn">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:32px;height:32px;background:var(--bs-{{ $action['color'] }}-bg-subtle, #f8f9fa);">
                            <i class="bi {{ $action['icon'] }} text-{{ $action['color'] }}" style="font-size:0.9rem;"></i>
                        </div>
                        <div class="flex-grow-1 min-width-0">
                            <div class="fw-semibold small lh-1">{{ $action['label'] }}</div>
                            <div class="text-muted" style="font-size:0.7rem;">{{ $action['desc'] }}</div>
                        </div>
                        @if($action['badge'])
                        <span class="badge bg-{{ $action['color'] }} rounded-pill ms-auto flex-shrink-0">{{ $action['badge'] }}</span>
                        @else
                        <i class="bi bi-chevron-right text-muted ms-auto flex-shrink-0" style="font-size:0.75rem;"></i>
                        @endif
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- Analysis Insights --}}
        @if(count($guruOverload) > 0 || count($labIdle) > 0)
        <div class="ui-card border-0" style="background: linear-gradient(135deg,#FFFBEB,#FEF3C7); border-left: 3px solid #F59E0B!important;">
            <div class="ui-card-body p-4">
                <h6 class="fw-bold mb-3 text-warning"><i class="bi bi-lightbulb-fill me-1"></i> Insight Analisis</h6>
                @if(count($guruOverload) > 0)
                <p class="small mb-2">
                    <i class="bi bi-person-exclamation text-warning me-1"></i>
                    <strong>{{ count($guruOverload) }} guru</strong> memiliki jadwal melebihi batas wajar.
                </p>
                @endif
                @if(count($labIdle) > 0)
                <p class="small mb-2">
                    <i class="bi bi-building-slash text-danger me-1"></i>
                    <strong>{{ count($labIdle) }} lab</strong> belum memiliki jadwal sama sekali.
                </p>
                @endif
                <a href="{{ route('lab.waka_akademik.alerts') }}" class="btn btn-warning btn-sm rounded-pill px-3 w-100 mt-2">
                    Lihat Detail
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Recent Activity --}}
<div class="row">
    <div class="col-12">
        <div class="ui-card border-0">
            <div class="ui-card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Aktivitas Terbaru</h6>
                    <a href="{{ route('lab.waka_akademik.monitoring') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">Lihat Log Lengkap</a>
                </div>
                <div class="row row-cols-1 g-2">
                    @forelse($logs as $log)
                    <div class="col">
                        <div class="d-flex align-items-start gap-3 p-2 rounded-3 hover-bg-light transition-all">
                            @php
                                $iconClass = 'bi-activity text-secondary';
                                if(in_array($log->action, ['created','approved'])) $iconClass = 'bi-check-circle-fill text-success';
                                if(in_array($log->action, ['deleted','rejected'])) $iconClass = 'bi-x-circle-fill text-danger';
                                if($log->action == 'updated') $iconClass = 'bi-pencil-fill text-info';
                            @endphp
                            <i class="bi {{ $iconClass }} fs-5 mt-1 flex-shrink-0"></i>
                            <div class="flex-grow-1 min-width-0">
                                <p class="mb-0 small fw-semibold">{{ $log->user->nama ?? 'Sistem' }}</p>
                                <p class="mb-0 small text-muted text-truncate">{{ $log->description }}</p>
                            </div>
                            <small class="text-muted text-nowrap">{{ $log->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    @empty
                    <div class="col text-center py-3 text-muted"><small>Belum ada aktivitas.</small></div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('css')
<style>
    .kpi-card { transition: transform 0.2s, box-shadow 0.2s; }
    .kpi-card:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,0.08) !important; }
    .kpi-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; }
    .hover-bg-light:hover { background: #F8FAFC !important; }
    .transition-all { transition: all 0.2s; }
    .letter-spacing { letter-spacing: 0.08em; }
</style>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Weekly Bar Chart
    const weeklyCtx = document.getElementById('weeklyChart');
    if (weeklyCtx) {
        new Chart(weeklyCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($hariList) !!},
                datasets: [{
                    label: 'Jumlah Jadwal',
                    data: {!! json_encode($weeklyData) !!},
                    backgroundColor: [
                        'rgba(79,70,229,0.8)', 'rgba(16,185,129,0.8)', 'rgba(245,158,11,0.8)',
                        'rgba(236,72,153,0.8)', 'rgba(6,182,212,0.8)', 'rgba(239,68,68,0.8)'
                    ],
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: true,
                plugins: { legend: { display: false }, tooltip: { callbacks: {
                    label: ctx => `${ctx.parsed.y} jadwal`
                }}},
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { drawBorder: false } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // Lab Donut Chart
    const donutCtx = document.getElementById('labDonutChart');
    if (donutCtx) {
        const labNames = {!! json_encode($labUsage->map(fn($u) => ($u->labor->nama_labor ?? 'Lab #'.$u->labor_id))) !!};
        const labCounts = {!! json_encode($labUsage->pluck('total')) !!};
        new Chart(donutCtx, {
            type: 'doughnut',
            data: {
                labels: labNames,
                datasets: [{
                    data: labCounts,
                    backgroundColor: ['#4F46E5','#10B981','#F59E0B','#EC4899','#06B6D4'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: ctx => `${ctx.label}: ${ctx.raw} jadwal` }}
                },
                cutout: '70%'
            }
        });
    }
});
</script>
@endsection
