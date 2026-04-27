@extends('perpustakaan.layouts.main')

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="row align-items-center mb-5">
        <div class="col-md-8">
            <h1 class="page-title mb-1 fw-bold text-dark">
                <i class="bi bi-robot text-primary me-2"></i> {{ $title }}
            </h1>
            <p class="text-muted mb-0">Strategic Decision Support System (SDSS) - Insight & Analytics</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <div class="text-muted small">Periode Data: <span class="fw-bold">{{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</span></div>
        </div>
    </div>

    <!-- AI & EARLY WARNING SECTION -->
    <div class="row mb-4 g-3">
        <!-- AI Insights -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #f8faff 0%, #eef3ff 100%);">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-primary mb-4"><i class="bi bi-lightbulb-fill text-warning me-2"></i> Executive Insights Otomatis</h5>
                    <div class="row g-3">
                        @foreach($aiInsights as $insight)
                        <div class="col-md-6">
                            <div class="d-flex align-items-start bg-white p-3 rounded-3 shadow-sm h-100">
                                <div class="fs-1 me-3"><i class="bi {{ $insight['icon'] }}"></i></div>
                                <div>
                                    <h6 class="fw-bold mb-1">{{ $insight['title'] }}</h6>
                                    <p class="mb-0 small text-muted">{!! $insight['desc'] !!}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Early Warning System -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #fff5f5 0%, #ffecec 100%); border-left: 5px solid #dc3545 !important;">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-danger mb-4"><i class="bi bi-shield-exclamation me-2"></i> Early Warning System</h5>
                    @if(count($earlyWarnings) > 0)
                        <ul class="list-group list-group-flush rounded-3">
                            @foreach($earlyWarnings as $warning)
                            <li class="list-group-item bg-white text-dark small border-danger mb-2 rounded shadow-sm">
                                <i class="bi bi-exclamation-circle text-danger me-2"></i> {{ $warning }}
                            </li>
                            @endforeach
                        </ul>
                        <div class="mt-3">
                            <a href="{{ route('kepsek.ews.evaluasi') }}" class="btn btn-sm btn-outline-danger w-100 fw-bold">
                                <i class="bi bi-eye"></i> Lihat Rekomendasi Tindakan
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4 text-success">
                            <i class="bi bi-check-circle fs-1 mb-2"></i>
                            <p class="mb-0 fw-bold">Kondisi Ideal</p>
                            <small>Tidak ada peringatan kritis saat ini.</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- METRICS -->
    <div class="row g-4 mb-5">
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100 analytics-card">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-circle me-3">
                        <i class="bi bi-book fs-3"></i>
                    </div>
                    <div>
                        <h3 class="mb-0 fw-bold">{{ number_format($totalBuku, 0, ',', '.') }}</h3>
                        <span class="text-muted small text-uppercase fw-semibold">Total Koleksi</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100 analytics-card">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 text-warning p-3 rounded-circle me-3">
                        <i class="bi bi-arrow-left-right fs-3"></i>
                    </div>
                    <div>
                        <h3 class="mb-0 fw-bold">{{ number_format($dipinjam, 0, ',', '.') }}</h3>
                        <span class="text-muted small text-uppercase fw-semibold">Aktif Dipinjam</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100 analytics-card">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-info bg-opacity-10 text-info p-3 rounded-circle me-3">
                        <i class="bi bi-people fs-3"></i>
                    </div>
                    <div>
                        <h3 class="mb-0 fw-bold">{{ number_format($anggotaAktif, 0, ',', '.') }}</h3>
                        <span class="text-muted small text-uppercase fw-semibold">Peminjam Aktif</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100 analytics-card border-left-danger">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-danger bg-opacity-10 text-danger p-3 rounded-circle me-3">
                        <i class="bi bi-clock-history fs-3"></i>
                    </div>
                    <div>
                        <h3 class="mb-0 fw-bold">{{ $jumlahTerlambat }}</h3>
                        <span class="text-muted small text-uppercase fw-semibold">Kasus Terlambat</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CHARTS & GRAFIK -->
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Tren Kunjungan & Peminjaman ({{ date('Y') }})</h5>
                </div>
                <div class="card-body">
                    <canvas id="peminjamanChart" height="100"></canvas>
                </div>
            </div>
        </div>
        
        <!-- QUICK ACTIONS / SMART APPROVAL ENTRY -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <h5 class="fw-bold mb-0">Smart Action Center</h5>
                </div>
                <div class="card-body d-grid gap-3 align-content-start">
                    <a href="{{ route('perpustakaan.pengadaan.index') }}?status=Menunggu Persetujuan" class="btn btn-primary d-flex justify-content-between align-items-center p-3 shadow-sm rounded-3 w-100" style="background: linear-gradient(145deg, #4e54c8, #8f94fb); border:none;">
                        <div class="text-start">
                            <span class="d-block fw-bold fs-5">Persetujuan Pengadaan</span>
                            <small class="text-white-50">Tinjau draft pembelian buku baru</small>
                        </div>
                        <i class="bi bi-cart-check fs-1 text-white opacity-50"></i>
                    </a>
                    
                    <a href="{{ route('perpustakaan.policy.index') }}" class="btn btn-dark d-flex justify-content-between align-items-center p-3 shadow-sm rounded-3 w-100">
                        <div class="text-start">
                            <span class="d-block fw-bold fs-5">Policy & Budget Center</span>
                            <small class="text-white-50">Atur budget dan kebijakan perpus</small>
                        </div>
                        <i class="bi bi-sliders fs-1 text-white opacity-50"></i>
                    </a>

                    <a href="{{ route('kepsek.laporan') }}" class="btn btn-outline-secondary d-flex justify-content-between align-items-center p-3 rounded-3 w-100 text-start">
                        <div>
                            <span class="d-block fw-bold">Pelaporan Lanjutan</span>
                        </div>
                        <i class="bi bi-file-bar-graph fs-3"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('peminjamanChart').getContext('2d');
        const chartData = {
            labels: {!! json_encode($grafikBulan) !!},
            datasets: [{
                label: 'Jumlah Peminjam',
                data: {!! json_encode($grafikData) !!},
                backgroundColor: 'rgba(78, 205, 196, 0.2)',
                borderColor: '#4ecdc4',
                borderWidth: 2,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#4ecdc4',
                pointHoverBackgroundColor: '#4ecdc4',
                pointHoverBorderColor: '#fff',
                fill: true,
                tension: 0.4
            }]
        };

        new Chart(ctx, {
            type: 'line',
            data: chartData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
