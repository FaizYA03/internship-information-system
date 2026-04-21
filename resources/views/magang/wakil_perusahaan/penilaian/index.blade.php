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
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#detailPenilaianModal{{ $penilaian->id }}">
                                    <i class="bi bi-eye me-1"></i> Lihat Detail
                                </button>
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editPenilaianModal{{ $penilaian->id }}">
                                    <i class="bi bi-pencil me-1"></i> Edit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detail Penilaian Modal -->
                <div class="modal fade" id="detailPenilaianModal{{ $penilaian->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content border-0 shadow-lg">
                            <div class="modal-header bg-primary text-white border-0">
                                <h5 class="modal-title fw-bold"><i class="bi bi-file-earmark-text me-2"></i>Detail Penilaian PKL</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-4">
                                <!-- Siswa Info Card -->
                                <div class="card border-0 shadow-sm mb-4 bg-primary bg-opacity-5">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <div class="bg-primary text-white rounded-circle p-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="bi bi-person-fill fs-4"></i>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <h6 class="text-dark fw-bold mb-1">{{ $penilaian->siswa->name ?? '-' }}</h6>
                                                <small class="text-muted">NIS: {{ $penilaian->siswa->nis ?? 'N/A' }}</small>
                                            </div>
                                            <div class="col-auto">
                                                @php
                                                    $totalA = $penilaian->hard_skill_1 + $penilaian->hard_skill_2 + $penilaian->hard_skill_3;
                                                    $rataA = $totalA / 3;
                                                    $totalB = $penilaian->kewirausahaan;
                                                    $totalC = $penilaian->soft_skill_1 + $penilaian->soft_skill_2 + $penilaian->soft_skill_3 + $penilaian->soft_skill_4 + $penilaian->soft_skill_5 + $penilaian->soft_skill_6;
                                                    $rataC = $totalC / 6;
                                                    $nilaiPKL = round(0.4 * $rataA + 0.4 * $rataC + 0.2 * $totalB, 2);
                                                    
                                                    if ($nilaiPKL >= 91) {
                                                        $badgeColor = 'success';
                                                        $keterangan = 'Sangat Baik';
                                                    } elseif ($nilaiPKL >= 81) {
                                                        $badgeColor = 'info';
                                                        $keterangan = 'Baik';
                                                    } elseif ($nilaiPKL >= 71) {
                                                        $badgeColor = 'warning';
                                                        $keterangan = 'Cukup';
                                                    } else {
                                                        $badgeColor = 'danger';
                                                        $keterangan = 'Kurang';
                                                    }
                                                @endphp
                                                <span class="badge bg-{{ $badgeColor }} rounded-pill fs-6">{{ $keterangan }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Nilai Utama -->
                                <div class="text-center mb-4 pb-4 border-bottom">
                                    <p class="text-muted mb-2">Nilai PKL Akhir</p>
                                    <div class="display-3 fw-bold text-primary">{{ $nilaiPKL }}</div>
                                    <div class="progress mt-3" style="height: 10px;">
                                        <div class="progress-bar bg-{{ $badgeColor }}" style="width: {{ min($nilaiPKL, 100) }}%"></div>
                                    </div>
                                </div>

                                <!-- Komposisi Nilai -->
                                <div class="mb-4">
                                    <h6 class="fw-bold text-dark mb-3">Komposisi Nilai</h6>
                                    <div class="row g-3">
                                        <!-- Hard Skill -->
                                        <div class="col-6">
                                            <div class="card border-0 shadow-sm bg-light">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <small class="text-muted fw-semibold">Hard Skill (40%)</small>
                                                        <i class="bi bi-hammer text-primary"></i>
                                                    </div>
                                                    <h4 class="text-primary fw-bold mb-2">{{ round($rataA, 2) }}</h4>
                                                    <small class="text-muted">Rata-rata dari 3 kompetensi</small>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Soft Skill -->
                                        <div class="col-6">
                                            <div class="card border-0 shadow-sm bg-light">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <small class="text-muted fw-semibold">Soft Skill (40%)</small>
                                                        <i class="bi bi-heart text-info"></i>
                                                    </div>
                                                    <h4 class="text-info fw-bold mb-2">{{ round($rataC, 2) }}</h4>
                                                    <small class="text-muted">Rata-rata dari 6 kompetensi</small>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Kewirausahaan -->
                                        <div class="col-12">
                                            <div class="card border-0 shadow-sm bg-light">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <small class="text-muted fw-semibold">Kewirausahaan / Entrepreneurship (20%)</small>
                                                        <i class="bi bi-lightbulb text-warning"></i>
                                                    </div>
                                                    <h4 class="text-warning fw-bold">{{ $totalB }}</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Detail Setiap Kompetensi -->
                                <div>
                                    <h6 class="fw-bold text-dark mb-3">Detail Kompetensi</h6>
                                    
                                    <!-- Hard Skills Detail -->
                                    <div class="mb-3">
                                        <small class="text-muted fw-semibold d-block mb-2">
                                            <i class="bi bi-hammer me-1"></i>Hard Skills
                                        </small>
                                        <div class="row g-2">
                                            <div class="col-4">
                                                <div class="bg-light p-3 rounded-2 text-center">
                                                    <small class="d-block text-muted mb-1">Kompetensi 1</small>
                                                    <strong class="text-primary d-block fs-5">{{ $penilaian->hard_skill_1 }}</strong>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="bg-light p-3 rounded-2 text-center">
                                                    <small class="d-block text-muted mb-1">Kompetensi 2</small>
                                                    <strong class="text-primary d-block fs-5">{{ $penilaian->hard_skill_2 }}</strong>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="bg-light p-3 rounded-2 text-center">
                                                    <small class="d-block text-muted mb-1">Kompetensi 3</small>
                                                    <strong class="text-primary d-block fs-5">{{ $penilaian->hard_skill_3 }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Soft Skills Detail -->
                                    <div class="mb-3">
                                        <small class="text-muted fw-semibold d-block mb-2">
                                            <i class="bi bi-heart me-1"></i>Soft Skills
                                        </small>
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <div class="bg-light p-2 rounded-2">
                                                    <small class="text-muted d-block mb-1">Etika Berkomunikasi</small>
                                                    <strong class="text-info d-block">{{ $penilaian->soft_skill_1 }}</strong>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="bg-light p-2 rounded-2">
                                                    <small class="text-muted d-block mb-1">Integritas</small>
                                                    <strong class="text-info d-block">{{ $penilaian->soft_skill_2 }}</strong>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="bg-light p-2 rounded-2">
                                                    <small class="text-muted d-block mb-1">Etos Kerja</small>
                                                    <strong class="text-info d-block">{{ $penilaian->soft_skill_3 }}</strong>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="bg-light p-2 rounded-2">
                                                    <small class="text-muted d-block mb-1">Kerja Mandiri/Tim</small>
                                                    <strong class="text-info d-block">{{ $penilaian->soft_skill_4 }}</strong>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="bg-light p-2 rounded-2">
                                                    <small class="text-muted d-block mb-1">Kepedulian Sosial</small>
                                                    <strong class="text-info d-block">{{ $penilaian->soft_skill_5 }}</strong>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="bg-light p-2 rounded-2">
                                                    <small class="text-muted d-block mb-1">Ketaatan Norma K3LH</small>
                                                    <strong class="text-info d-block">{{ $penilaian->soft_skill_6 }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Entrepreneurship Detail -->
                                    <div>
                                        <small class="text-muted fw-semibold d-block mb-2">
                                            <i class="bi bi-lightbulb me-1"></i>Kewirausahaan
                                        </small>
                                        <div class="bg-light p-3 rounded-2 text-center">
                                            <strong class="text-warning d-block fs-4">{{ $totalB }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer border-top p-4">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="bi bi-x-circle me-1"></i> Tutup
                                </button>
                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#editPenilaianModal{{ $penilaian->id }}">
                                    <i class="bi bi-pencil me-1"></i> Edit Penilaian
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="editPenilaianModal{{ $penilaian->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content border-0 shadow-lg">
                            <div class="modal-header bg-primary text-white border-0">
                                <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Penilaian PKL</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('magang.wakil_perusahaan.penilaian.update', $penilaian->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body p-4">
                                    <div class="row mb-3">
                                        <div class="col-12 mb-3">
                                            <label class="form-label"><strong>Siswa:</strong> {{ $penilaian->siswa->name }}</label>
                                        </div>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <label class="form-label text-primary fw-bold">Hard Skill 1</label>
                                            <input type="number" name="hard_skill_1" class="form-control" value="{{ $penilaian->hard_skill_1 }}" min="0" max="100" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label text-primary fw-bold">Hard Skill 2</label>
                                            <input type="number" name="hard_skill_2" class="form-control" value="{{ $penilaian->hard_skill_2 }}" min="0" max="100" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label text-primary fw-bold">Hard Skill 3</label>
                                            <input type="number" name="hard_skill_3" class="form-control" value="{{ $penilaian->hard_skill_3 }}" min="0" max="100" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label text-warning fw-bold">Kewirausahaan</label>
                                            <input type="number" name="kewirausahaan" class="form-control" value="{{ $penilaian->kewirausahaan }}" min="0" max="100" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label text-info fw-bold">Soft Skill 1</label>
                                            <input type="number" name="soft_skill_1" class="form-control" value="{{ $penilaian->soft_skill_1 }}" min="0" max="100" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label text-info fw-bold">Soft Skill 2</label>
                                            <input type="number" name="soft_skill_2" class="form-control" value="{{ $penilaian->soft_skill_2 }}" min="0" max="100" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label text-info fw-bold">Soft Skill 3</label>
                                            <input type="number" name="soft_skill_3" class="form-control" value="{{ $penilaian->soft_skill_3 }}" min="0" max="100" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label text-info fw-bold">Soft Skill 4</label>
                                            <input type="number" name="soft_skill_4" class="form-control" value="{{ $penilaian->soft_skill_4 }}" min="0" max="100" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label text-info fw-bold">Soft Skill 5</label>
                                            <input type="number" name="soft_skill_5" class="form-control" value="{{ $penilaian->soft_skill_5 }}" min="0" max="100" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label text-info fw-bold">Soft Skill 6</label>
                                            <input type="number" name="soft_skill_6" class="form-control" value="{{ $penilaian->soft_skill_6 }}" min="0" max="100" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer border-top p-4">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        <i class="bi bi-x-circle me-1"></i> Batal
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle me-1"></i> Simpan Perubahan
                                    </button>
                                </div>
                            </form>
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

    .modal-content {
        border-radius: 16px;
    }

    .form-control-sm, .form-select-sm {
        border-radius: 8px;
    }

    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
</style>

<!-- Create Modal -->
<div class="modal fade" id="createPenilaianModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title fw-bold" id="createModalLabel">
                    <i class="bi bi-plus-circle me-2"></i> Input Penilaian PKL Siswa
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('penilaian.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label for="siswa_id_create" class="form-label fw-bold">
                            <i class="bi bi-person-circle me-2"></i> Pilih Siswa
                        </label>
                        <select name="siswa_id" id="siswa_id_create" class="form-select form-select-lg" required>
                            <option value="">-- Pilih Siswa --</option>
                            @forelse($siswas as $siswa)
                                @php
                                    $sudahDinilai = \App\Models\Penilaian::where('siswa_id', $siswa->id)->exists();
                                @endphp
                                <option value="{{ $siswa->id }}" {{ $sudahDinilai ? 'disabled' : '' }}>
                                    {{ $siswa->name }} {{ $sudahDinilai ? '(Sudah Dinilai)' : '' }}
                                </option>
                            @empty
                                <option disabled>Tidak ada siswa magang aktif</option>
                            @endforelse
                        </select>
                    </div>

                    <div class="table-responsive">
    <table class="table table-borderless align-middle small no-datatable">
        <thead class="table-light">
            <tr>
                <th style="width: 20%">Kategori</th>
                <th style="width: 50%">Indikator</th>
                <th style="width: 30%">Nilai (0-100)</th>
            </tr>
        </thead>
        <tbody>

            {{-- HARD SKILLS --}}
            <tr>
                <td class="fw-bold text-primary align-middle" rowspan="3">
                    <i class="bi bi-hammer me-1"></i> Hard Skills
                </td>
                <td>Kompetensi Teknis 1</td>
                <td>
                    <input type="number" name="hard_skill_1"
                        class="form-control form-control-sm"
                        min="0" max="100" required>
                </td>
            </tr>
            <tr>
                <td>Kompetensi Teknis 2</td>
                <td>
                    <input type="number" name="hard_skill_2"
                        class="form-control form-control-sm"
                        min="0" max="100" required>
                </td>
            </tr>
            <tr>
                <td>Kompetensi Teknis 3</td>
                <td>
                    <input type="number" name="hard_skill_3"
                        class="form-control form-control-sm"
                        min="0" max="100" required>
                </td>
            </tr>

            {{-- KEWIRAUSAHAAN --}}
            <tr>
                <td class="fw-bold text-warning align-middle">
                    <i class="bi bi-lightbulb me-1"></i> Kewirausahaan
                </td>
                <td>Nilai Kewirausahaan</td>
                <td>
                    <input type="number" name="kewirausahaan"
                        class="form-control form-control-sm"
                        min="0" max="100" required>
                </td>
            </tr>

            {{-- SOFT SKILLS --}}
            <tr>
                <td class="fw-bold text-info align-middle" rowspan="6">
                    <i class="bi bi-heart me-1"></i> Soft Skills
                </td>
                <td>Etika berkomunikasi</td>
                <td><input type="number" name="soft_skill_1" class="form-control form-control-sm" min="0" max="100" required></td>
            </tr>
            <tr>
                <td>Integritas</td>
                <td><input type="number" name="soft_skill_2" class="form-control form-control-sm" min="0" max="100" required></td>
            </tr>
            <tr>
                <td>Etos kerja</td>
                <td><input type="number" name="soft_skill_3" class="form-control form-control-sm" min="0" max="100" required></td>
            </tr>
            <tr>
                <td>Kerja mandiri/tim</td>
                <td><input type="number" name="soft_skill_4" class="form-control form-control-sm" min="0" max="100" required></td>
            </tr>
            <tr>
                <td>Kepedulian sosial</td>
                <td><input type="number" name="soft_skill_5" class="form-control form-control-sm" min="0" max="100" required></td>
            </tr>
            <tr>
                <td>Ketaatan norma K3LH</td>
                <td><input type="number" name="soft_skill_6" class="form-control form-control-sm" min="0" max="100" required></td>
            </tr>

        </tbody>
    </table>
</div>
                </div>
                <div class="modal-footer border-top p-4">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i> Simpan Penilaian
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

