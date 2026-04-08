@extends('lab.layouts.unified', ['title' => 'Monitoring Laboratorium'])

@section('content')

{{-- Header --}}
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="ui-card border-0" style="background: linear-gradient(135deg,#0EA5E9,#0284C7); color:white;">
            <div class="ui-card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <div class="rounded-3 p-2" style="background:rgba(255,255,255,0.2);">
                                <i class="bi bi-bar-chart-line-fill fs-4"></i>
                            </div>
                            <div>
                                <p class="mb-0 opacity-75 small fw-bold">WAKA KURIKULUM</p>
                                <h4 class="fw-bold mb-0">Monitoring Laboratorium</h4>
                            </div>
                        </div>
                        <p class="mb-0 opacity-75 small">Analisis utilisasi lab, ranking penggunaan, dan identifikasi lab idle.</p>
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

{{-- Summary Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="ui-card border-0 text-center">
            <div class="ui-card-body p-4">
                <div class="rounded-circle d-inline-flex p-3 mb-2" style="background:#EFF6FF;">
                    <i class="bi bi-building fs-3 text-primary"></i>
                </div>
                <h3 class="fw-bold">{{ count($labs) }}</h3>
                <p class="text-muted small mb-0">Total Laboratorium</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="ui-card border-0 text-center">
            <div class="ui-card-body p-4">
                <div class="rounded-circle d-inline-flex p-3 mb-2" style="background:#F0FDF4;">
                    <i class="bi bi-calendar-check fs-3 text-success"></i>
                </div>
                <h3 class="fw-bold">{{ $totalJadwal }}</h3>
                <p class="text-muted small mb-0">Total Jadwal Terdaftar</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="ui-card border-0 text-center">
            <div class="ui-card-body p-4">
                <div class="rounded-circle d-inline-flex p-3 mb-2" style="background:#FFF7ED;">
                    <i class="bi bi-building-slash fs-3 text-warning"></i>
                </div>
                <h3 class="fw-bold text-warning">{{ count($labIdle) }}</h3>
                <p class="text-muted small mb-0">Lab Idle (Tanpa Jadwal)</p>
            </div>
        </div>
    </div>
</div>

{{-- Lab Usage Ranking --}}
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="ui-card border-0">
            <div class="ui-card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h6 class="fw-bold mb-0">Ranking Penggunaan Laboratorium</h6>
                        <small class="text-muted">Berdasarkan jumlah jadwal terdaftar</small>
                    </div>
                </div>

                @if(count($labStats) > 0)
                <div class="row g-3">
                    @foreach($labStats as $index => $stat)
                    @php
                        $barColor = match(true) {
                            $stat['utilisasi'] >= 70 => '#10B981',
                            $stat['utilisasi'] >= 40 => '#F59E0B',
                            $stat['utilisasi'] >= 1  => '#6366F1',
                            default                  => '#E5E7EB',
                        };
                        $medal = match($index) {
                            0 => '🥇', 1 => '🥈', 2 => '🥉', default => '#'.($index+1)
                        };
                    @endphp
                    <div class="col-md-6">
                        <div class="p-3 rounded-3 border hover-bg-light transition-all">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span style="font-size:1.1rem;">{{ $medal }}</span>
                                    <div>
                                        <div class="fw-semibold small">{{ $stat['lab']->nama_labor }}</div>
                                        <div class="text-muted" style="font-size:0.75rem;">{{ $stat['lab']->jenis_labor ?? 'Umum' }}</div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold" style="color:{{ $barColor }};">{{ $stat['utilisasi'] }}%</div>
                                    <small class="text-muted">{{ $stat['total_jadwal'] }} jadwal</small>
                                </div>
                            </div>
                            <div class="progress rounded-pill" style="height:8px;">
                                <div class="progress-bar rounded-pill" style="width:{{ max($stat['utilisasi'], 2) }}%; background:{{ $barColor }};"></div>
                            </div>
                            {{-- Per-hari breakdown --}}
                            <div class="d-flex gap-1 mt-2 flex-wrap">
                                @foreach($stat['per_hari'] as $hari => $cnt)
                                <span class="badge rounded-pill {{ $cnt > 0 ? 'bg-primary-soft text-primary' : 'bg-light text-muted' }}" style="font-size:0.65rem;">
                                    {{ substr($hari, 0, 3) }}: {{ $cnt }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-building-slash fs-1 d-block mb-2 opacity-50"></i>
                    <p>Belum ada data jadwal laboratorium.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Lab Idle Section --}}
@if(count($labIdle) > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="ui-card border-0" style="border-left: 4px solid #EF4444 !important;">
            <div class="ui-card-body p-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-exclamation-circle-fill text-danger fs-5"></i>
                    <h6 class="fw-bold mb-0 text-danger">Laboratorium Idle — Belum Ada Jadwal</h6>
                </div>
                <div class="row g-3">
                    @foreach($labIdle as $lab)
                    <div class="col-md-4">
                        <div class="p-3 rounded-3 bg-danger-soft d-flex align-items-center gap-3">
                            <div class="rounded-circle p-2 bg-danger-soft text-danger" style="background:#FEF2F2!important;">
                                <i class="bi bi-building-slash"></i>
                            </div>
                            <div>
                                <div class="fw-semibold small">{{ $lab->nama_labor }}</div>
                                <div class="text-muted" style="font-size:0.75rem;">{{ $lab->jenis_labor ?? 'Tipe tidak diketahui' }} • Kapasitas: {{ $lab->kapasitas ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Per-Hari Chart --}}
<div class="row">
    <div class="col-12">
        <div class="ui-card border-0">
            <div class="ui-card-body p-4">
                <h6 class="fw-bold mb-3">Distribusi Jadwal per Hari</h6>
                <canvas id="labHariChart" height="80"></canvas>
            </div>
        </div>
    </div>
</div>

@endsection

@section('css')
<style>
    .hover-bg-light:hover { background-color: #F8FAFC !important; }
    .transition-all { transition: all 0.2s; }
    .bg-danger-soft { background-color: #FEF2F2 !important; }
</style>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const labNames = {!! json_encode(collect($labStats)->pluck('lab.nama_labor')) !!};
    const hariList = {!! json_encode($hariList) !!};
    const labData  = {!! json_encode(collect($labStats)->pluck('per_hari')) !!};

    const colors = ['#4F46E5','#10B981','#F59E0B','#EC4899','#06B6D4','#EF4444','#8B5CF6','#84CC16'];

    const datasets = labData.map((perHari, i) => ({
        label: labNames[i] || 'Lab',
        data: Object.values(perHari),
        backgroundColor: colors[i % colors.length] + 'CC',
        borderRadius: 6,
        borderSkipped: false
    }));

    new Chart(document.getElementById('labHariChart'), {
        type: 'bar',
        data: { labels: hariList, datasets },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, padding: 15 } } },
            scales: {
                x: { stacked: true, grid: { display: false } },
                y: { stacked: true, beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });
});
</script>
@endsection
