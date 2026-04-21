@extends('magang.layouts.main')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="fw-bold text-dark mb-2">
                    <i class="bi bi-bar-chart-fill me-2 text-primary"></i> Daftar Nilai PKL Siswa
                </h2>
                <p class="text-muted mb-0">Kelola dan pantau penilaian praktik kerja lapangan siswa</p>
            </div>
            <div class="col-md-4 text-end">
                <button type="button" class="btn btn-primary btn-lg shadow-sm" data-bs-toggle="modal" data-bs-target="#createPenilaianModal">
                    <i class="bi bi-plus-circle me-2"></i> Input Penilaian
                </button>
            </div>
        </div>
    </div>

    <!-- Success Alert -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($penilaians->isEmpty())
        <!-- Empty State -->
        <div class="card border-0 shadow-sm">
            <div class="card-body py-5 text-center">
                <i class="bi bi-inbox display-4 text-muted mb-3"></i>
                <h5 class="text-muted fw-semibold mb-2">Belum Ada Data Penilaian</h5>
                <p class="text-muted mb-4">Mulai dengan menginput penilaian siswa untuk melihat hasilnya di sini.</p>
                <a href="{{ route('magang.wakil_perusahaan.penilaian.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i> Input Penilaian Pertama
                </a>
            </div>
        </div>
    @else
        <!-- Stats Summary -->
        @php
            $totalSiswa = $penilaians->count();
            $nilaiTerbaik = 0;
            $nilaiTerendah = 100;
            
            foreach ($penilaians as $p) {
                $totalA = $p->hard_skill_1 + $p->hard_skill_2 + $p->hard_skill_3;
                $rataA = $totalA / 3;
                $totalB = $p->kewirausahaan;
                $totalC = $p->soft_skill_1 + $p->soft_skill_2 + $p->soft_skill_3 + $p->soft_skill_4 + $p->soft_skill_5 + $p->soft_skill_6;
                $rataC = $totalC / 6;
                // Rumus: rata-rata dari Hard Skill (40%), Soft Skill (40%), dan Kewirausahaan (20%)
                $nilaiPKL = round(0.4 * $rataA + 0.4 * $rataC + 0.2 * $totalB, 2);
                
                $nilaiTerbaik = max($nilaiTerbaik, $nilaiPKL);
                $nilaiTerendah = min($nilaiTerendah, $nilaiPKL);
            }
        @endphp

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-gradient-blue">
                    <div class="card-body text-white">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-white-50 mb-2">Total Siswa</p>
                                <h3 class="fw-bold mb-0">{{ $totalSiswa }}</h3>
                            </div>
                            <i class="bi bi-people-fill display-6 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-gradient-success">
                    <div class="card-body text-white">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-white-50 mb-2">Nilai Terbaik</p>
                                <h3 class="fw-bold mb-0">{{ $nilaiTerbaik }}</h3>
                            </div>
                            <i class="bi bi-graph-up-arrow display-6 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-gradient-warning">
                    <div class="card-body text-white">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-white-50 mb-2">Nilai Terendah</p>
                                <h3 class="fw-bold mb-0">{{ $nilaiTerendah }}</h3>
                            </div>
                            <i class="bi bi-exclamation-triangle-fill display-6 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Penilaian Cards -->
        <div class="row g-3">
            @foreach($penilaians as $index => $penilaian)
                @php
                    $totalA = $penilaian->hard_skill_1 + $penilaian->hard_skill_2 + $penilaian->hard_skill_3;
                    $rataA = $totalA / 3;
                    $totalB = $penilaian->kewirausahaan;
                    $totalC = $penilaian->soft_skill_1 + $penilaian->soft_skill_2 + $penilaian->soft_skill_3 + $penilaian->soft_skill_4 + $penilaian->soft_skill_5 + $penilaian->soft_skill_6;
                    $rataC = $totalC / 6;
                    // Rumus: rata-rata dari Hard Skill (40%), Soft Skill (40%), dan Kewirausahaan (20%)
                    $nilaiPKL = round(0.4 * $rataA + 0.4 * $rataC + 0.2 * $totalB, 2);

                    if ($nilaiPKL >= 91) {
                        $keterangan = 'Sangat Baik';
                        $badgeColor = 'success';
                        $borderColor = 'border-success';
                    } elseif ($nilaiPKL >= 81) {
                        $keterangan = 'Baik';
                        $badgeColor = 'info';
                        $borderColor = 'border-info';
                    } elseif ($nilaiPKL >= 71) {
                        $keterangan = 'Cukup';
                        $badgeColor = 'warning';
                        $borderColor = 'border-warning';
                    } else {
                        $keterangan = 'Kurang';
                        $badgeColor = 'danger';
                        $borderColor = 'border-danger';
                    }
                @endphp

                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100 overflow-hidden">
                        <!-- Card Header -->
                        <div class="card-header bg-primary bg-opacity-10 border-0 pb-0">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1 fw-bold text-dark">
                                        <i class="bi bi-person-circle me-2 text-primary"></i> {{ $penilaian->siswa->name ?? '-' }}
                                    </h6>
                                </div>
                                <span class="badge bg-{{ $badgeColor }} rounded-pill">{{ $keterangan }}</span>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="card-body">
                            <!-- Nilai Utama -->
                            <div class="text-center mb-4 pb-3 border-bottom">
                                <p class="text-muted small mb-2">Nilai PKL</p>
                                <div class="display-4 fw-bold text-primary">
                                    {{ $nilaiPKL }}
                                </div>
                                <div class="progress mt-3" style="height: 8px;">
                                    <div class="progress-bar bg-{{ $badgeColor }}" style="width: {{ min($nilaiPKL, 100) }}%"></div>
                                </div>
                            </div>

                            <!-- Sub Nilai -->
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <div class="bg-light p-3 rounded-2">
                                        <small class="d-block text-muted mb-1">Hard Skill</small>
                                        <strong class="text-primary">{{ round($rataA, 2) }}</strong>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-light p-3 rounded-2">
                                        <small class="d-block text-muted mb-1">Soft Skill</small>
                                        <strong class="text-info">{{ round($rataC, 2) }}</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-light p-3 rounded-2">
                                <small class="d-block text-muted mb-1">Kewirausahaan</small>
                                <strong class="text-warning">{{ $totalB }}</strong>
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="card-footer bg-white border-top-0">
                            <div class="d-grid gap-2">
                                <a href="{{ route('magang.wakil_perusahaan.penilaian.show', $penilaian->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye me-1"></i> Lihat Detail
                                </a>
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editPenilaianModal{{ $penilaian->id }}">
                                    <i class="bi bi-pencil me-1"></i> Edit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- Custom Styles --}}
<style>
    .bg-gradient-blue {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .bg-gradient-success {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .bg-gradient-warning {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    }

    .card {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1) !important;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        font-weight: 600;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
    }

    .display-4 {
        font-size: 2.5rem;
    }

    .rounded-2 {
        border-radius: 12px;
    }

    .rounded-3 {
        border-radius: 16px;
    }

    .text-white-50 {
        color: rgba(255, 255, 255, 0.7);
    }
</style>
@endsection
