@extends('magang.layouts.main')

@section('content')
<style>
    .modern-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .stats-card {
        transition: all 0.3s ease;
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .modern-table {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    }

    .modern-table thead th {
        background: #edf2f7;
        color: #334155;
        font-weight: 600;
        border-bottom: 1px solid #e2e8f0;
        padding: 1rem;
    }

    .modern-table tbody tr {
        transition: all 0.2s ease;
        border-bottom: 1px solid #f1f5f9;
    }

    .modern-table tbody tr:hover {
        background-color: #f8fafc;
        transform: scale(1.01);
    }

    .modern-table tbody td {
        padding: 1rem;
        vertical-align: middle;
        border: none;
    }

    .nilai-badge {
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-weight: 600;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .badge-excellent {
        background: linear-gradient(135deg, #4ade80, #22c55e);
        color: white;
    }

    .badge-good {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
    }

    .badge-average {
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
        color: white;
    }

    .badge-poor {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }

    .btn-modern {
        border-radius: 25px;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }

    .empty-state {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        border-radius: 20px;
        padding: 3rem;
        text-align: center;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    .stats-icon {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<div class="container-fluid py-5" style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); min-height: 100vh;">
    <div class="container">
        <!-- Modern Header -->
        <div class="card modern-header mb-5 border-0">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center mb-3 mb-md-0">
                            <div class="bg-white bg-opacity-20 p-3 rounded-circle me-3">
                                <i class="fas fa-chart-line text-white fs-4"></i>
                            </div>
                            <div>
                                <h2 class="text-white mb-1 fw-bold">📊 Rekap Nilai Akhir PKL</h2>
                                <p class="text-white-50 mb-0">Pantau dan kelola penilaian siswa magang dengan mudah</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <div class="d-flex flex-column flex-md-row gap-2">
                            <a href="{{ route('magang.wakil_perusahaan.nilaiakhir.create') }}"
                               class="btn btn-light btn-modern fw-semibold">
                                <i class="fas fa-plus me-2"></i>Tambah Nilai Laporan
                            </a>
                            <a href="{{ route('magang.wakil_perusahaan.nilaiakhir.export') }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="btn btn-dark btn-modern fw-semibold">
                                <i class="fas fa-file-pdf me-2"></i>Rekap PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($penilaians->isEmpty())
            <!-- Empty State -->
            <div class="empty-state">
                <div class="mb-4">
                    <i class="fas fa-chart-bar text-muted" style="font-size: 4rem;"></i>
                </div>
                <h3 class="text-dark mb-3">Belum Ada Data Penilaian</h3>
                <p class="text-muted mb-4">Saat ini belum ada data penilaian siswa yang tersedia. Mulai dengan menambahkan nilai laporan pertama.</p>
                <a href="{{ route('magang.wakil_perusahaan.nilaiakhir.create') }}"
                   class="btn btn-primary btn-modern btn-lg">
                    <i class="fas fa-plus me-2"></i>Tambah Data Pertama
                </a>
            </div>
        @else
            <!-- Statistics Cards -->
            <div class="row mb-5">
                <div class="col-md-3 mb-4">
                    <div class="card stats-card h-100">
                        <div class="card-body text-center">
                            <div class="stats-icon bg-primary bg-opacity-10 text-primary mx-auto mb-3">
                                <i class="fas fa-users"></i>
                            </div>
                            <h4 class="text-primary fw-bold">{{ $penilaians->count() }}</h4>
                            <p class="text-muted mb-0">Total Siswa</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card stats-card h-100">
                        <div class="card-body text-center">
                            <div class="stats-icon bg-success bg-opacity-10 text-success mx-auto mb-3">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h4 class="text-success fw-bold">{{ number_format($penilaians->avg('nilai_akhir'), 1) }}</h4>
                            <p class="text-muted mb-0">Rata-rata Nilai</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card stats-card h-100">
                        <div class="card-body text-center">
                            <div class="stats-icon bg-warning bg-opacity-10 text-warning mx-auto mb-3">
                                <i class="fas fa-star"></i>
                            </div>
                            <h4 class="text-warning fw-bold">{{ $penilaians->max('nilai_akhir') }}</h4>
                            <p class="text-muted mb-0">Nilai Tertinggi</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card stats-card h-100">
                        <div class="card-body text-center">
                            <div class="stats-icon bg-info bg-opacity-10 text-info mx-auto mb-3">
                                <i class="fas fa-building"></i>
                            </div>
                            <h4 class="text-info fw-bold">{{ $penilaians->unique('wakil_perusahaan_id')->count() }}</h4>
                            <p class="text-muted mb-0">Perusahaan</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="card modern-table border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-user me-2"></i>Nama Siswa</th>
                                    <th><i class="fas fa-building me-2"></i>Perusahaan</th>
                                    <th><i class="fas fa-calendar me-2"></i>Periode Magang</th>
                                    <th><i class="fas fa-user-tie me-2"></i>Pembimbing Lapangan</th>
                                    <th class="text-center"><i class="fas fa-trophy me-2"></i>Nilai Akhir (0-100)</th>
                                    <th class="text-center"><i class="fas fa-award me-2"></i>Keterangan</th>
                                    <th class="text-center"><i class="fas fa-cogs me-2"></i>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($penilaians as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div>
                                                <strong class="text-dark">{{ $item->siswa?->nama ?? 'Nama Tidak Tersedia' }}</strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <i class="fas fa-building text-muted me-2"></i>
                                        {{ $item->wakilPerusahaan?->nama_perusahaan ?? '-' }}
                                    </td>
                                    <td>
                                        <i class="fas fa-calendar text-muted me-2"></i>
                                        {{ $item->siswa?->magangreports?->tanggal_mulai ?? '-' }}
                                        <br>
                                        <small class="text-muted">s/d {{ $item->siswa?->magangreports?->tanggal_selesai ?? '-' }}</small>
                                    </td>
                                    <td>
                                        <i class="fas fa-user-tie text-muted me-2"></i>
                                        {{ $item->wakilPerusahaan?->nama ?? '-' }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary fs-6 px-3 py-2">{{ $item->nilai_akhir }}</span>
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $na = $item->nilai_akhir;
                                            $keterangan = $na >= 91 ? 'Sangat Baik' : ($na >= 81 ? 'Baik' : ($na >= 71 ? 'Cukup' : 'Kurang'));
                                            $badgeClass = match ($keterangan) {
                                                'Sangat Baik' => 'badge-excellent',
                                                'Baik' => 'badge-good',
                                                'Cukup' => 'badge-average',
                                                default => 'badge-poor',
                                            };
                                            $icon = match ($keterangan) {
                                                'Sangat Baik' => 'fas fa-star',
                                                'Baik' => 'fas fa-thumbs-up',
                                                'Cukup' => 'fas fa-check-circle',
                                                default => 'fas fa-exclamation-triangle',
                                            };
                                        @endphp
                                        <span class="nilai-badge {{ $badgeClass }}">
                                            <i class="{{ $icon }}"></i>
                                            {{ $keterangan }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <button class="btn btn-sm btn-info text-white rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#detailModal{{ $item->id }}">
                                                <i class="fas fa-eye"></i> Detail
                                            </button>
                                            <a href="{{ route('magang.wakil_perusahaan.nilaiakhir.edit', $item->id) }}" class="btn btn-sm btn-warning text-dark rounded-pill shadow-sm">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal Detail Penilaian -->
                                <div class="modal fade" id="detailModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content border-0 shadow" style="border-radius: 15px;">
                                            <div class="modal-header bg-primary bg-gradient text-white border-0" style="border-radius: 15px 15px 0 0;">
                                                <h5 class="modal-title fw-bold">Detail Penilaian PKL - {{ $item->siswa?->nama ?? 'Nama Tidak Tersedia' }}</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                @php
                                                    // Kalkulasi Nilai
                                                    $avgHardSkill = ($item->hard_skill_1 + $item->hard_skill_2 + $item->hard_skill_3) / 3;
                                                    $kewirausahaan = $item->kewirausahaan;
                                                    $avgSoftSkill = ($item->soft_skill_1 + $item->soft_skill_2 + $item->soft_skill_3 + $item->soft_skill_4 + $item->soft_skill_5 + $item->soft_skill_6) / 6;
                                                    $nilaiPKL = round(($avgHardSkill + $kewirausahaan + $avgSoftSkill) / 3, 2);
                                                    $nilaiLaporan = $item->nilai_laporan;
                                                @endphp

                                                <div class="row g-4">
                                                    <!-- Section: Nilai dari Mitra -->
                                                    <div class="col-md-6">
                                                        <div class="card h-100 border border-primary border-opacity-25 shadow-sm" style="border-radius: 12px;">
                                                            <div class="card-header bg-primary bg-opacity-10 text-primary fw-bold border-bottom-0">
                                                                <i class="fas fa-building me-2"></i>Nilai dari Perusahaan (Mitra)
                                                            </div>
                                                            <div class="card-body">
                                                                <ul class="list-group list-group-flush">
                                                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 border-0">
                                                                        <span>Rata-rata Kompetensi Teknis</span>
                                                                        <span class="badge bg-secondary rounded-pill">{{ number_format($avgHardSkill, 2) }}</span>
                                                                    </li>
                                                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 border-0">
                                                                        <span>Kewirausahaan</span>
                                                                        <span class="badge bg-secondary rounded-pill">{{ number_format($kewirausahaan, 2) }}</span>
                                                                    </li>
                                                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 border-0">
                                                                        <span>Rata-rata Soft Skill</span>
                                                                        <span class="badge bg-secondary rounded-pill">{{ number_format($avgSoftSkill, 2) }}</span>
                                                                    </li>
                                                                </ul>
                                                                <hr class="text-primary">
                                                                <div class="d-flex justify-content-between align-items-center fw-bold text-primary">
                                                                    <span>Nilai PKL (Gabungan)</span>
                                                                    <span>{{ number_format($nilaiPKL, 2) }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Section: Nilai dari Sekolah -->
                                                    <div class="col-md-6">
                                                        <div class="card h-100 border border-success border-opacity-25 shadow-sm" style="border-radius: 12px;">
                                                            <div class="card-header bg-success bg-opacity-10 text-success fw-bold border-bottom-0">
                                                                <i class="fas fa-school me-2"></i>Nilai dari Sekolah (Guru)
                                                            </div>
                                                            <div class="card-body d-flex flex-column justify-content-center">
                                                                <div class="d-flex justify-content-between align-items-center fw-bold fs-5 text-success">
                                                                    <span>Nilai Laporan Akhir</span>
                                                                    <span>{{ $nilaiLaporan ? number_format($nilaiLaporan, 2) : 'Belum Dinilai' }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Hasil Akhir -->
                                                <div class="mt-4 p-4 rounded bg-light border text-center">
                                                    <h5 class="fw-bold text-dark mb-1">Total Nilai Akhir PKL</h5>
                                                    <p class="small text-muted mb-3">Formula: (Nilai PKL × 70%) + (Nilai Laporan × 30%)</p>
                                                    
                                                    <div class="display-5 fw-bold text-primary mb-2">{{ number_format($item->nilai_akhir, 2) }}</div>
                                                    
                                                    <span class="nilai-badge {{ $badgeClass }} fs-5 px-4 py-2 mt-2">
                                                        <i class="{{ $icon }}"></i> {{ $keterangan }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-top-0">
                                                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
