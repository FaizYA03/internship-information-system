@extends('perpustakaan.layouts.main')

@section('css')
<style>
    .dashboard-header {
        background: linear-gradient(135deg, #1a2a3a, #2c3e50);
        padding: 2.5rem 2rem;
        border-radius: 12px;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(26, 42, 58, 0.15);
    }
    
    .dashboard-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .dashboard-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 0;
    }
    
    .stats-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(26, 42, 58, 0.05);
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        border: 1px solid rgba(0,0,0,0.05);
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(26, 42, 58, 0.1);
    }
    
    .stats-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }
    
    .icon-primary { background: rgba(78, 205, 196, 0.15); color: #4ecdc4; }
    .icon-info { background: rgba(52, 152, 219, 0.15); color: #3498db; }
    .icon-warning { background: rgba(241, 196, 15, 0.15); color: #f1c40f; }
    .icon-success { background: rgba(46, 204, 113, 0.15); color: #2ecc71; }
    .icon-danger { background: rgba(231, 76, 60, 0.15); color: #e74c3c; }
    
    .stats-value {
        font-size: 2.5rem;
        font-weight: 800;
        color: #1a2a3a;
        margin-bottom: 0.25rem;
        line-height: 1;
    }
    
    .stats-label {
        font-size: 1rem;
        font-weight: 500;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="row mb-2">
        <div class="col-12">
            <div class="dashboard-header d-md-flex align-items-center justify-content-between" data-aos="fade-up">
                <div>
                    <h1 class="dashboard-title">{{ $title }}</h1>
                    <p class="dashboard-subtitle">Ringkasan Data dan Aktivitas Perpustakaan SMK Negeri 5 Padang</p>
                </div>
                
                <div class="d-flex flex-column align-items-md-end gap-2 mt-3 mt-md-0">
                    @if($dipinjam > 0)
                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fs-6"><i class="bi bi-bell-fill me-1"></i> {{ $dipinjam }} buku belum dikembalikan</span>
                    @endif
                    @if($keterlambatan->count() > 0)
                    <span class="badge bg-danger px-3 py-2 rounded-pill fs-6"><i class="bi bi-exclamation-circle-fill me-1"></i> {{ $keterlambatan->count() }} peminjaman terlambat!</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Baris Pertama: Total Utama -->
    <div class="row g-4 mb-4">
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
            <div class="stats-card">
                <div class="stats-icon icon-primary">
                    <i class="bi bi-book"></i>
                </div>
                <div class="stats-value">{{ $totalBuku }}</div>
                <div class="stats-label">Total Buku</div>
            </div>
        </div>
        
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
            <div class="stats-card">
                <div class="stats-icon icon-info">
                    <i class="bi bi-tags"></i>
                </div>
                <div class="stats-value">{{ $totalKategori }}</div>
                <div class="stats-label">Total Kategori</div>
            </div>
        </div>
        
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
            <div class="stats-card">
                <div class="stats-icon icon-warning">
                    <i class="bi bi-journal-check"></i>
                </div>
                <div class="stats-value">{{ $totalPeminjaman }}</div>
                <div class="stats-label">Total Peminjaman</div>
            </div>
        </div>
    </div>

    <!-- Baris Kedua: Status Peminjaman -->
    <div class="row g-4">
        <div class="col-md-6" data-aos="fade-up" data-aos-delay="400">
            <div class="stats-card">
                <div class="stats-icon icon-warning">
                    <i class="bi bi-journal-arrow-down"></i>
                </div>
                <div class="stats-value">{{ $dipinjam }}</div>
                <div class="stats-label">
                    Buku Sedang Dipinjam 
                    <span class="badge bg-warning text-dark ms-2">{{ $persentaseDipinjam }}%</span>
                </div>
                <div class="progress mt-3" style="height: 6px;">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $persentaseDipinjam }}%"></div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6" data-aos="fade-up" data-aos-delay="500">
            <div class="stats-card">
                <div class="stats-icon icon-success">
                    <i class="bi bi-journal-check"></i>
                </div>
                <div class="stats-value">{{ $dikembalikan }}</div>
                <div class="stats-label">
                    Buku Telah Dikembalikan
                    <span class="badge bg-success ms-2">{{ $persentaseKembali }}%</span>
                </div>
                <div class="progress mt-3" style="height: 6px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $persentaseKembali }}%"></div>
                </div>
            </div>
        </div>
    </div>

    @if($keterlambatan->count() > 0)
    <!-- Section Keterlambatan -->
    <div class="row mt-4" data-aos="fade-up" data-aos-delay="600">
        <div class="col-12">
            <div class="alert alert-danger" style="border-radius: 12px; border-left: 5px solid #c0392b;">
                <h4 class="alert-heading fw-bold mb-3"><i class="bi bi-exclamation-triangle-fill me-2"></i> Peringatan Keterlambatan Pengembalian Buku!</h4>
                <p>Terdapat <strong>{{ $keterlambatan->count() }} peminjaman</strong> yang telah melewati batas waktu pengembalian. Berikut detailnya:</p>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered mt-2 text-dark bg-white" style="border-color: #f5b7b1;">
                        <thead style="background-color: #fadbd8;">
                            <tr>
                                <th>Peminjam</th>
                                <th>Buku</th>
                                <th>Tanggal Kembali</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($keterlambatan as $telat)
                            <tr>
                                <td>{{ $telat->nama }}</td>
                                <td>{{ $telat->buku->judul ?? '-' }}</td>
                                <td class="text-danger fw-bold">{{ \Carbon\Carbon::parse($telat->tanggal_kembali)->format('d/m/Y') }}</td>
                                <td><span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Terlambat</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row mt-4 g-4" data-aos="fade-up" data-aos-delay="700">
        <!-- Grafik Peminjaman -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 12px; height: 100%;">
                <div class="card-header bg-white p-4 border-bottom">
                    <h5 class="fw-bold mb-0"><i class="bi bi-bar-chart-fill text-primary me-2"></i>Grafik Peminjaman Bulanan (Tahun {{ \Carbon\Carbon::now()->year }})</h5>
                </div>
                <div class="card-body p-4 d-flex align-items-center justify-content-center">
                    <canvas id="peminjamanChart" style="max-height: 250px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Buku Terpopuler -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm" style="border-radius: 12px; height: 100%;">
                <div class="card-header bg-white p-4 border-bottom">
                    <h5 class="fw-bold mb-0"><i class="bi bi-star-fill text-warning me-2"></i>Top 5 Buku Terpopuler</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($bukuPopuler as $populer)
                        <li class="list-group-item d-flex justify-content-between align-items-center p-4">
                            <div class="d-flex align-items-center">
                                <div class="rounded me-3 d-flex align-items-center justify-content-center bg-light text-muted" style="width: 40px; height: 55px;">
                                    <i class="bi bi-book fs-3"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ Str::limit($populer->nama_buku ?? 'Tidak diketahui', 25) }}</h6>
                                    <small class="text-muted">Buku Perpustakaan</small>
                                </div>
                            </div>
                            <span class="badge bg-primary rounded-pill fs-6">{{ $populer->total }}x</span>
                        </li>
                        @empty
                        <li class="list-group-item text-center py-5 text-muted">
                            Belum ada riwayat peminjaman buku
                        </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Aktivitas Terbaru -->
    <div class="row mt-4" data-aos="fade-up" data-aos-delay="800">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-header bg-white p-4 border-bottom">
                    <h5 class="fw-bold mb-0"><i class="bi bi-activity text-success me-2"></i>Aktivitas Peminjaman Terbaru</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($recentActivity as $item)
                        <li class="list-group-item p-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded-circle p-3 d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                    @if($item->status == 'Dipinjam' || $item->status == 'Disetujui' || $item->status == 'Menunggu')
                                        <i class="bi bi-arrow-up-right text-warning fs-5"></i>
                                    @elseif($item->status == 'Dikembalikan')
                                        <i class="bi bi-check-circle text-success fs-5"></i>
                                    @else
                                        <i class="bi bi-dash-circle text-secondary fs-5"></i>
                                    @endif
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-bold text-dark">
                                        {{ $item->nama }}
                                        <span class="fw-normal text-muted"> 
                                            @if($item->status == 'Dikembalikan')
                                                baru saja <strong class="text-success">mengembalikan</strong>
                                            @elseif($item->status == 'Ditolak')
                                                <strong class="text-danger">batal meminjam</strong>
                                            @else
                                                baru saja <strong class="text-warning">meminjam</strong>
                                            @endif
                                        </span> 
                                        buku "{{ $item->buku->judul ?? 'Tidak diketahui' }}"
                                    </h6>
                                    <small class="text-muted"><i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($item->updated_at)->diffForHumans() }} ({{ \Carbon\Carbon::parse($item->updated_at)->format('d M Y, H:i') }})</small>
                                </div>
                            </div>
                        </li>
                        @empty
                        <li class="list-group-item text-center py-5 text-muted">
                            Belum ada aktivitas terekam.
                        </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Evaluasi Khusus Waka Kurikulum -->
    @if(auth()->user()->role == 'waka')
    <div class="row mt-5" data-aos="fade-up">
        <div class="col-12">
            <h4 class="fw-bold mb-4 text-primary"><i class="bi bi-graph-up-arrow me-2"></i>Evaluasi & Insight Pembelajaran</h4>
        </div>
        
        <!-- Insight Box -->
        <div class="col-12 mb-4">
            <div class="card border-0 shadow-sm bg-primary text-white" style="border-radius: 12px; background: linear-gradient(135deg, #3498db, #2980b9) !important;">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-1 text-center d-none d-md-block">
                            <i class="bi bi-lightbulb-fill fs-1 opacity-75"></i>
                        </div>
                        <div class="col-md-11">
                            <h5 class="fw-bold mb-2">Kesimpulan Evaluasi Hari Ini</h5>
                            <p class="mb-0 fs-5">
                                <i class="bi bi-check2-circle me-1"></i> {{ $insightBuku }} <br>
                                <i class="bi bi-check2-circle me-1"></i> {{ $insightKategori }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kategori Terpopuler -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm" style="border-radius: 12px; height: 100%;">
                <div class="card-header bg-white p-4 border-bottom">
                    <h5 class="fw-bold mb-0"><i class="bi bi-grid-fill text-info me-2"></i>Top 5 Kategori Paling Aktif</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($kategoriPopuler as $kat)
                        <li class="list-group-item d-flex justify-content-between align-items-center p-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-info-subtle text-info rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <i class="bi bi-bookmark-fill"></i>
                                </div>
                                <span class="fw-bold">{{ $kat->nama_kategori }}</span>
                            </div>
                            <span class="badge bg-info rounded-pill px-3">{{ $kat->total }} Transaksi</span>
                        </li>
                        @empty
                        <li class="list-group-item text-center py-5 text-muted">Belum ada data kategori.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <!-- Ulangi Buku Terpopuler di sini untuk layout yang lebih lengkap bagi Waka? Or just leave the one above. 
             Actually, let's keep it clean. Above we already have Buku Populer. -->
    </div>
    @endif
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('peminjamanChart').getContext('2d');
        const peminjamanChart = new Chart(ctx, {
            type: 'bar', // Bisa juga 'line'
            data: {
                labels: {!! json_encode($grafikBulan) !!},
                datasets: [{
                    label: 'Jumlah Transaksi',
                    data: {!! json_encode($grafikData) !!},
                    backgroundColor: 'rgba(78, 205, 196, 0.4)',
                    borderColor: 'rgba(78, 205, 196, 1)',
                    borderWidth: 2,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0 // Tidak ada angka desimal
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false // Sembunyikan legenda atas jika tak perlua
                    }
                }
            }
        });
    });
</script>
@endsection
