@extends('lab.layouts.unified', ['title' => 'Strategic Dashboard — Kepala Sekolah'])

@section('content')
{{-- ============================================================
     HERO BANNER
     ============================================================ --}}
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="rounded-4 p-4 text-white" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 60%, #1e40af 100%);">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="rounded-3 p-2" style="background:rgba(255,255,255,.15)">
                            <i class="bi bi-shield-check fs-4"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0">Pusat Keputusan Strategis</h4>
                            <p class="mb-0 opacity-75 small">SMK Negeri 5 Padang — Sistem Laboratorium</p>
                        </div>
                    </div>
                    <p class="mb-0 opacity-75 small">{{ now()->isoFormat('dddd, D MMMM Y • HH:mm') }} WIB &nbsp;|&nbsp; Selamat datang, Bapak/Ibu Kepala Sekolah</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    @if($stats['approval_eksternal'] > 0 || $stats['approval_pengadaan'] > 0)
                    <span class="badge bg-danger rounded-pill px-3 py-2 fs-6">
                        <i class="bi bi-bell-fill me-1"></i>
                        {{ $stats['approval_eksternal'] + $stats['approval_pengadaan'] }} Perlu Keputusan
                    </span>
                    @else
                    <span class="badge rounded-pill px-3 py-2" style="background:rgba(255,255,255,.2)">
                        <i class="bi bi-check-circle me-1"></i> Tidak ada item tertunda
                    </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================
     7 KPI CARDS
     ============================================================ --}}
<div class="row g-3 mb-4">
    {{-- Total Laboratorium --}}
    <div class="col-6 col-md-4 col-xl-3">
        <div class="card border-0 rounded-4 h-100 shadow-sm">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="rounded-3 p-2" style="background:#EFF6FF;color:#2563EB;"><i class="bi bi-building fs-5"></i></div>
                    <span class="small text-muted fw-medium">Total Lab</span>
                </div>
                <h3 class="fw-bold mb-0 text-dark">{{ $stats['total_lab'] }}</h3>
                <p class="small text-muted mb-0">Laboratorium terdaftar</p>
            </div>
        </div>
    </div>
    {{-- Total Inventaris --}}
    <div class="col-6 col-md-4 col-xl-3">
        <div class="card border-0 rounded-4 h-100 shadow-sm">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="rounded-3 p-2" style="background:#F0FDF4;color:#16A34A;"><i class="bi bi-box-seam fs-5"></i></div>
                    <span class="small text-muted fw-medium">Total Inventaris</span>
                </div>
                <h3 class="fw-bold mb-0 text-dark">{{ number_format($stats['total_inventaris']) }}</h3>
                <p class="small text-muted mb-0">Unit alat & bahan</p>
            </div>
        </div>
    </div>
    {{-- Alat Rusak --}}
    <div class="col-6 col-md-4 col-xl-3">
        <div class="card border-0 rounded-4 h-100 shadow-sm">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="rounded-3 p-2" style="background:#FEF2F2;color:#DC2626;"><i class="bi bi-tools fs-5"></i></div>
                    <span class="small text-muted fw-medium">Alat Rusak</span>
                </div>
                <h3 class="fw-bold mb-0 {{ $stats['jumlah_rusak'] > 0 ? 'text-danger' : 'text-dark' }}">{{ $stats['jumlah_rusak'] }}</h3>
                <p class="small text-muted mb-0">Perlu perbaikan</p>
            </div>
        </div>
    </div>
    {{-- % Lab Aktif --}}
    <div class="col-6 col-md-4 col-xl-3">
        <div class="card border-0 rounded-4 h-100 shadow-sm">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="rounded-3 p-2" style="background:#ECFDF5;color:#059669;"><i class="bi bi-activity fs-5"></i></div>
                    <span class="small text-muted fw-medium">Lab Aktif</span>
                </div>
                <h3 class="fw-bold mb-0 text-success">{{ $stats['persen_aktif'] }}%</h3>
                <p class="small text-muted mb-0">Beroperasi normal</p>
            </div>
        </div>
    </div>
    {{-- Total Anggaran --}}
    <div class="col-6 col-md-4 col-xl-4">
        <div class="card border-0 rounded-4 h-100 shadow-sm">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="rounded-3 p-2" style="background:#FFFBEB;color:#D97706;"><i class="bi bi-cash-coin fs-5"></i></div>
                    <span class="small text-muted fw-medium">Total Anggaran Lab</span>
                </div>
                <h4 class="fw-bold mb-0 text-dark">Rp {{ number_format($stats['total_anggaran'], 0, ',', '.') }}</h4>
                <p class="small text-muted mb-0">Total pengadaan disetujui</p>
            </div>
        </div>
    </div>
    {{-- Anggaran Terpakai --}}
    <div class="col-6 col-md-4 col-xl-4">
        <div class="card border-0 rounded-4 h-100 shadow-sm">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="rounded-3 p-2" style="background:#FDF4FF;color:#9333EA;"><i class="bi bi-graph-up-arrow fs-5"></i></div>
                    <span class="small text-muted fw-medium">Anggaran Terpakai</span>
                </div>
                <h4 class="fw-bold mb-0 text-dark">Rp {{ number_format($stats['anggaran_terpakai'], 0, ',', '.') }}</h4>
                <p class="small text-muted mb-0">Tahun {{ now()->year }}</p>
            </div>
        </div>
    </div>
    {{-- Sisa Anggaran --}}
    <div class="col-12 col-md-4 col-xl-4">
        <div class="card border-0 rounded-4 h-100 shadow-sm">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="rounded-3 p-2" style="background:#F0F9FF;color:#0284C7;"><i class="bi bi-piggy-bank fs-5"></i></div>
                    <span class="small text-muted fw-medium">Sisa Anggaran</span>
                </div>
                <h4 class="fw-bold mb-0 {{ $stats['sisa_anggaran'] < 0 ? 'text-danger' : 'text-primary' }}">
                    Rp {{ number_format($stats['sisa_anggaran'], 0, ',', '.') }}
                </h4>
                <p class="small text-muted mb-0">Estimasi sisa</p>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================
     SMART QUICK ACTIONS (kondisional)
     ============================================================ --}}
@if($stats['approval_eksternal'] > 0 || $stats['approval_pengadaan'] > 0 || $stats['jumlah_rusak'] >= 5)
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card border-0 rounded-4 shadow-sm" style="border-left: 4px solid #2563EB !important; border-left-style: solid !important;">
            <div class="card-body p-3">
                <p class="fw-semibold text-dark mb-2 small"><i class="bi bi-lightning-fill text-warning me-1"></i> Aksi Cepat yang Memerlukan Perhatian</p>
                <div class="d-flex flex-wrap gap-2">
                    @if($stats['approval_eksternal'] > 0)
                    <a href="{{ route('lab.kepala_sekolah.approval.eksternal') }}" class="btn btn-sm btn-danger rounded-pill px-3">
                        <i class="bi bi-person-badge me-1"></i> Approve Peminjaman Eksternal
                        <span class="badge bg-white text-danger ms-1">{{ $stats['approval_eksternal'] }}</span>
                    </a>
                    @endif
                    @if($stats['approval_pengadaan'] > 0)
                    <a href="{{ route('lab.kepala_sekolah.approval.pengadaan.index') }}" class="btn btn-sm btn-warning rounded-pill px-3">
                        <i class="bi bi-box me-1"></i> Approve Pengadaan
                        <span class="badge bg-white text-warning ms-1">{{ $stats['approval_pengadaan'] }}</span>
                    </a>
                    @endif
                    @if($stats['jumlah_rusak'] >= 5)
                    <a href="{{ route('lab.admin_new.kerusakan.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                        <i class="bi bi-tools me-1"></i> Lihat Laporan Kerusakan
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ============================================================
     PERINGATAN SISTEM & RINGKASAN OPERASIONAL
     ============================================================ --}}
<div class="row g-4 mb-4">
    {{-- Peringatan Sistem --}}
    <div class="col-md-6">
        <div class="card border-0 rounded-4 shadow-sm h-100">
            <div class="card-header bg-white border-0 pt-3 pb-0 px-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-shield-exclamation text-danger me-2"></i>Peringatan Sistem</h6>
            </div>
            <div class="card-body p-3">
                @forelse($riskAlerts as $alert)
                <div class="d-flex align-items-start gap-2 mb-3 p-2 rounded-3"
                     style="background:{{ $alert['level'] === 'danger' ? '#FEF2F2' : ($alert['level'] === 'warning' ? '#FFFBEB' : '#EFF6FF') }}">
                    <i class="bi {{ $alert['icon'] }} mt-1
                       {{ $alert['level'] === 'danger' ? 'text-danger' : ($alert['level'] === 'warning' ? 'text-warning' : 'text-info') }}"></i>
                    <span class="small">{{ $alert['message'] }}</span>
                </div>
                @empty
                <div class="text-center py-4">
                    <i class="bi bi-check-circle fs-1 text-success d-block mb-2"></i>
                    <p class="text-muted small mb-0">Tidak ada peringatan sistem saat ini.</p>
                    <p class="text-muted small">Semua berjalan normal.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Ringkasan Operasional Lab --}}
    <div class="col-md-6">
        <div class="card border-0 rounded-4 shadow-sm h-100">
            <div class="card-header bg-white border-0 pt-3 pb-0 px-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-bar-chart-steps text-primary me-2"></i>Ringkasan Operasional Lab</h6>
            </div>
            <div class="card-body p-3">
                {{-- Lab Paling Aktif --}}
                <div class="mb-3">
                    <p class="small text-muted fw-medium mb-1">🏆 Lab Paling Aktif</p>
                    @if($labPalingAktif)
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-success rounded-pill">Aktif</span>
                        <span class="fw-semibold small">{{ $labPalingAktif->nama_labor }}</span>
                    </div>
                    @else
                    <span class="text-muted small">— Belum ada data —</span>
                    @endif
                </div>

                {{-- Lab Tidak Aktif --}}
                <div class="mb-3">
                    <p class="small text-muted fw-medium mb-1">⚠️ Lab Tidak Aktif (30 hari)</p>
                    @if($labTidakAktif->count() > 0)
                    <div class="d-flex flex-wrap gap-1">
                        @foreach($labTidakAktif->take(3) as $lab)
                        <span class="badge rounded-pill" style="background:#FEF3C7;color:#92400E;">{{ $lab->nama_labor }}</span>
                        @endforeach
                        @if($labTidakAktif->count() > 3)
                        <span class="badge bg-secondary rounded-pill">+{{ $labTidakAktif->count() - 3 }} lainnya</span>
                        @endif
                    </div>
                    @else
                    <span class="text-success small"><i class="bi bi-check-circle me-1"></i>Semua lab memiliki aktivitas</span>
                    @endif
                </div>

                {{-- Lab Kerusakan Terbanyak --}}
                <div>
                    <p class="small text-muted fw-medium mb-1">🔧 Lab Kerusakan Terbanyak</p>
                    @if($labKerusakanTerbanyak && $labKerusakanTerbanyak->rusak_count > 0)
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-danger rounded-pill">{{ $labKerusakanTerbanyak->rusak_count }} item</span>
                        <span class="fw-semibold small">{{ $labKerusakanTerbanyak->nama_labor }}</span>
                    </div>
                    @else
                    <span class="text-success small"><i class="bi bi-check-circle me-1"></i>Tidak ada inventaris rusak</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================
     GRAFIK
     ============================================================ --}}
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card border-0 rounded-4 shadow-sm h-100">
            <div class="card-header bg-white border-0 pt-3 pb-0 px-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-graph-up text-danger me-2"></i>Laporan Kerusakan per Bulan</h6>
            </div>
            <div class="card-body p-3">
                <canvas id="chartKerusakan" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 rounded-4 shadow-sm h-100">
            <div class="card-header bg-white border-0 pt-3 pb-0 px-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-cart-plus text-primary me-2"></i>Pengadaan per Bulan</h6>
            </div>
            <div class="card-body p-3">
                <canvas id="chartPengadaan" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================
     LOG AKTIVITAS TERBARU
     ============================================================ --}}
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card border-0 rounded-4 shadow-sm">
            <div class="card-header bg-white border-0 pt-3 pb-0 px-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0"><i class="bi bi-clock-history text-secondary me-2"></i>Log Aktivitas Penting</h6>
                <a href="{{ route('lab.kepala_sekolah.activity_log') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                    Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body p-3">
                @forelse($recentActivity as $log)
                @php
                    $badgeColor = match(true) {
                        str_contains($log->action, 'approved')  => ['bg' => '#DCFCE7', 'text' => '#166534', 'icon' => 'bi-check-circle-fill'],
                        str_contains($log->action, 'rejected')  => ['bg' => '#FEE2E2', 'text' => '#991B1B', 'icon' => 'bi-x-circle-fill'],
                        str_contains($log->action, 'eskalasi')  => ['bg' => '#FEF3C7', 'text' => '#92400E', 'icon' => 'bi-exclamation-circle-fill'],
                        str_contains($log->action, 'damage')    => ['bg' => '#FEF2F2', 'text' => '#DC2626', 'icon' => 'bi-tools'],
                        default                                 => ['bg' => '#EFF6FF', 'text' => '#1D4ED8', 'icon' => 'bi-info-circle-fill'],
                    };
                @endphp
                <div class="d-flex align-items-start gap-3 mb-3 pb-3 border-bottom">
                    <div class="rounded-circle p-2 flex-shrink-0" style="background:{{ $badgeColor['bg'] }};color:{{ $badgeColor['text'] }};width:36px;height:36px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi {{ $badgeColor['icon'] }} small"></i>
                    </div>
                    <div class="flex-grow-1">
                        <p class="small fw-semibold mb-0 text-dark">{{ $log->description ?? $log->action }}</p>
                        <p class="small text-muted mb-0">
                            {{ $log->user->nama ?? 'System' }}
                            &bull; {{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}
                        </p>
                    </div>
                    <span class="badge rounded-pill small flex-shrink-0" style="background:{{ $badgeColor['bg'] }};color:{{ $badgeColor['text'] }};">
                        {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                    </span>
                </div>
                @empty
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-journal-text fs-2 d-block mb-2"></i>
                    <p class="small mb-0">Belum ada log aktivitas yang tersedia.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const months = @json($chartMonths);
    const kerusakanData = @json($chartKerusakan);
    const pengadaanData = @json($chartPengadaan);

    // Chart Kerusakan
    new Chart(document.getElementById('chartKerusakan'), {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Laporan Kerusakan',
                data: kerusakanData,
                backgroundColor: 'rgba(220, 38, 38, 0.15)',
                borderColor: 'rgba(220, 38, 38, 0.9)',
                borderWidth: 2,
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: 'rgba(0,0,0,.04)' } },
                x: { grid: { display: false } }
            }
        }
    });

    // Chart Pengadaan
    new Chart(document.getElementById('chartPengadaan'), {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Pengadaan',
                data: pengadaanData,
                backgroundColor: 'rgba(37, 99, 235, 0.15)',
                borderColor: 'rgba(37, 99, 235, 0.9)',
                borderWidth: 2,
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: 'rgba(0,0,0,.04)' } },
                x: { grid: { display: false } }
            }
        }
    });
});
</script>
@endpush
