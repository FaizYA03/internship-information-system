@extends('siswa.layouts.main')

@php
    $role_prefix = Auth::check() && Auth::user()->role == 'guru' ? 'guru' : 'siswa';
@endphp

@section('css')
<style>
    :root {
        --status-ongoing: #10b981; /* Green */
        --status-upcoming: #3b82f6; /* Blue */
        --status-finished: #94a3b8; /* Gray */
        --primary-glow: rgba(59, 130, 246, 0.5);
        --ongoing-glow: rgba(16, 185, 129, 0.4);
    }

    .page-header {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        padding: 2.5rem 2rem;
        border-radius: 1.5rem;
        color: white;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .page-header::after {
        content: "";
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.03);
        border-radius: 50%;
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.25rem;
        margin-bottom: 2.5rem;
    }

    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        border: 1px solid #f1f5f9;
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        font-size: 1.25rem;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 800;
        color: #1e293b;
        line-height: 1;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .bg-light-blue { background: #eff6ff; color: #3b82f6; }
    .bg-light-green { background: #f0fdf4; color: #10b981; }
    .bg-light-purple { background: #faf5ff; color: #a855f7; }
    .bg-light-orange { background: #fff7ed; color: #f97316; }

    /* Schedule Cards & Accordion */
    .day-accordion-item {
        background: transparent !important;
        border: none !important;
        margin-bottom: 1.5rem;
    }

    .day-header {
        background: white !important;
        border-radius: 16px !important;
        padding: 0.75rem 1.25rem !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        border: 1px solid #f1f5f9 !important;
    }

    .day-button {
        background: transparent !important;
        box-shadow: none !important;
        padding: 0.75rem 0 !important;
        font-weight: 700;
        color: #1e293b !important;
    }

    .day-button::after {
        filter: grayscale(1) opacity(0.5);
    }

    .schedule-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
        padding-top: 1rem;
    }

    .schedule-card {
        background: white;
        border-radius: 16px;
        padding: 1.25rem;
        border: 1px solid #f1f5f9;
        position: relative;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
    }

    .schedule-card.border-ongoing { border-left: 6px solid var(--status-ongoing); }
    .schedule-card.border-upcoming { border-left: 6px solid var(--status-upcoming); }
    .schedule-card.border-finished { border-left: 6px solid var(--status-finished); }

    .schedule-card.ongoing-glow {
        box-shadow: 0 0 20px var(--ongoing-glow);
        animation: pulse-glow 2s infinite;
    }

    @keyframes pulse-glow {
        0% { box-shadow: 0 0 10px var(--ongoing-glow); }
        50% { box-shadow: 0 0 25px var(--ongoing-glow); }
        100% { box-shadow: 0 0 10px var(--ongoing-glow); }
    }

    .status-badge {
        font-size: 0.7rem;
        font-weight: 700;
        padding: 0.3rem 0.75rem;
        border-radius: 50px;
        text-transform: uppercase;
    }

    .badge-ongoing { background: #dcfce7; color: #166534; }
    .badge-upcoming { background: #dbeafe; color: #1e40af; }
    .badge-finished { background: #f1f5f9; color: #475569; }

    .my-class-badge {
        background: #4f46e5;
        color: white;
        font-size: 0.65rem;
        padding: 0.25rem 0.6rem;
        border-radius: 4px;
        font-weight: 600;
    }

    .practice-label {
        font-size: 0.75rem;
        color: #059669;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .schedule-time {
        font-family: 'JetBrains Mono', monospace;
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
    }

    .schedule-info-row {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        font-size: 0.9rem;
        color: #64748b;
    }

    .schedule-info-row i {
        margin-top: 3px;
        width: 16px;
        color: #94a3b8;
    }

    .lab-name {
        font-weight: 700;
        color: #1e293b;
    }

    .empty-state {
        background: white;
        border-radius: 16px;
        padding: 2.5rem !important;
        text-align: center;
        border: 1px dashed #ced4da;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .schedule-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 992px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 768px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 1rem; }
        .schedule-grid { grid-template-columns: 1fr; }
        .stat-card { padding: 1rem; }
        .stat-value { font-size: 1.5rem; }
    }

    @media (max-width: 480px) {
        .stats-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-lg-4 pb-5">
    <!-- Page Header -->
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h2 fw-bold mb-1">{{ Auth::user()->role == 'guru' ? 'Jadwal Mengajar Saya' : 'Jadwal Laboratorium' }}</h1>
            @if(Auth::user()->role == 'siswa')
                @php
                    $user = Auth::user();
                    $siswa = $user->siswa;
                    $nis = $user->nis_nip ?? ($siswa->nis ?? '-');
                    
                    // Priority: Relation > String Fields
                    $namaKelas = '-';
                    $jurusan = '';
                    
                    if ($siswa) {
                        if ($siswa->kelas instanceof \App\Models\Kelas) {
                            $namaKelas = $siswa->kelas->nama_kelas;
                            $jurusan = $siswa->kelas->jurusan;
                        } else {
                            $namaKelas = $siswa->kelas;
                            $jurusan = $siswa->jurusan;
                        }
                    }
                @endphp
                <p class="mb-0 opacity-75">
                    NIS: <span class="fw-bold">{{ $nis }}</span> | 
                    Kelas: <span class="fw-bold">{{ $namaKelas }}</span> 
                    @if($jurusan)
                        | Jurusan: <span class="fw-bold">{{ $jurusan }}</span>
                    @endif
                </p>
            @else
                <p class="mb-0 opacity-75">NIP: <span class="fw-bold">{{ Auth::user()->nis_nip ?? '-' }}</span></p>
            @endif
        </div>
        <div class="d-none d-md-block">
            <i class="bi bi-calendar-check display-4 opacity-25"></i>
        </div>
    </div>

    @if(isset($error))
        <div class="alert alert-warning rounded-4 border-0 shadow-sm p-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ $error }}
        </div>
    @else
        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon bg-light-blue">
                    <i class="bi bi-calendar-week"></i>
                </div>
                <div class="stat-value">{{ $stats['total_minggu'] }}</div>
                <div class="stat-label">Jadwal Minggu Ini</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon bg-light-green">
                    <i class="bi bi-calendar-event"></i>
                </div>
                <div class="stat-value">{{ $stats['hari_ini'] }}</div>
                <div class="stat-label">Jadwal Hari Ini</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon bg-light-purple">
                    <i class="bi bi-play-circle"></i>
                </div>
                <div class="stat-value">{{ $stats['sedang_berlangsung'] }}</div>
                <div class="stat-label">Sedang Berlangsung</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon bg-light-orange">
                    <i class="bi bi-building"></i>
                </div>
                <div class="stat-value">{{ $stats['lab_digunakan'] }}</div>
                <div class="stat-label">Lab Digunakan</div>
            </div>
        </div>

        <!-- View Toggle -->
        <div class="d-flex justify-content-end mb-3">
            <ul class="nav nav-pills bg-white p-1 rounded-pill shadow-sm" id="view-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active rounded-pill px-4" id="list-tab" data-bs-toggle="pill" data-bs-target="#list-view" type="button" role="tab" aria-controls="list-view" aria-selected="true"><i class="bi bi-list me-1"></i> List</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill px-4" id="card-tab" data-bs-toggle="pill" data-bs-target="#card-view" type="button" role="tab" aria-controls="card-view" aria-selected="false"><i class="bi bi-grid-fill me-1"></i> Card</button>
                </li>
            </ul>
        </div>

        <div class="tab-content" id="view-tabContent">
            <!-- List View Tab -->
            <div class="tab-pane fade show active" id="list-view" role="tabpanel" aria-labelledby="list-tab">
                <!-- Schedule Section -->
        <div class="card border-0 shadow-sm rounded-4 mt-2 mb-4 overflow-hidden">
            <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-dark">Daftar Jadwal</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="min-width: 800px;">
                        <thead style="background-color: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                            <tr>
                                <th class="ps-4 fw-semibold border-0 py-3 text-secondary text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Waktu</th>
                                <th class="fw-semibold border-0 py-3 text-secondary text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Mata Pelajaran</th>
                                <th class="fw-semibold border-0 py-3 text-secondary text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Laboratorium</th>
                                @if(Auth::user()->role == 'siswa')
                                <th class="fw-semibold border-0 py-3 text-secondary text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Guru</th>
                                @else
                                <th class="fw-semibold border-0 py-3 text-secondary text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Kelas</th>
                                @endif
                                <th class="fw-semibold border-0 py-3 text-secondary text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Status</th>
                                <th class="pe-4 border-0 py-3 text-end"></th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @if($stats['total_minggu'] == 0)
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="60" class="mb-3 opacity-50" alt="No data">
                                        <h5 class="fw-bold text-dark">Belum Ada Jadwal</h5>
                                        <p class="text-muted small">Belum ada jadwal yang dijadwalkan untuk Anda minggu ini.</p>
                                    </td>
                                </tr>
                            @else
                                @foreach($daysOrder as $day)
                                    @php
                                        $daySchedules = $jadwalGrouped->get($day) ?? collect();
                                        $isToday = ($todayIndo == $day);
                                    @endphp
                                    
                                    @if($daySchedules->count() > 0)
                                        <tr>
                                            <td colspan="6" class="bg-light ps-4 py-2 border-bottom-0" style="font-size: 0.85rem;">
                                                <div class="d-flex align-items-center">
                                                    <span class="fw-bold text-dark"><i class="bi bi-calendar-event me-2 text-primary"></i>{{ strtoupper($day) }}</span>
                                                    @if($isToday)
                                                        <span class="badge bg-primary ms-2 rounded-pill" style="font-size: 0.6rem">HARI INI</span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @foreach($daySchedules as $item)
                                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                                <td class="ps-4">
                                                    <span class="fw-bold text-dark fs-6">{{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }}</span>
                                                    <span class="text-muted small d-block">s/d {{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }}</span>
                                                </td>
                                                <td>
                                                    <span class="fw-bold text-dark d-block">{{ $item->mata_pelajaran }}</span>
                                                    <span class="badge {{ $item->status_class == 'ongoing' ? 'bg-success' : 'bg-primary' }} bg-opacity-10 {{ $item->status_class == 'ongoing' ? 'text-success' : 'text-primary' }} mt-1" style="font-size: 0.65rem">PRAKTIKUM</span>
                                                </td>
                                                <td>
                                                    <span class="text-dark fw-medium"><i class="bi bi-building me-1 opacity-50"></i>{{ $item->labor->nama_labor ?? '-' }}</span>
                                                </td>
                                                @if(Auth::user()->role == 'siswa')
                                                <td>
                                                    <span class="text-secondary"><i class="bi bi-person me-1 opacity-50"></i>{{ $item->guru->nama ?? '-' }}</span>
                                                </td>
                                                @else
                                                <td>
                                                    <span class="text-secondary"><i class="bi bi-people me-1 opacity-50"></i>{{ $item->kelas_relation->nama_kelas ?? $item->kelas }}</span>
                                                </td>
                                                @endif
                                                <td>
                                                    @php
                                                        $bgClass = $item->status_class == 'ongoing' ? '#10b981' : 
                                                                  ($item->status_class == 'upcoming' ? '#3b82f6' : '#94a3b8');
                                                        $textClass = $item->status_class == 'ongoing' ? 'text-success' : 
                                                                  ($item->status_class == 'upcoming' ? 'text-primary' : 'text-secondary');
                                                        $bgOpacity = $item->status_class == 'ongoing' ? 'rgba(16,185,129,0.1)' : 
                                                                  ($item->status_class == 'upcoming' ? 'rgba(59,130,246,0.1)' : 'rgba(148,163,184,0.1)');
                                                        $label = $item->status_class == 'ongoing' ? 'SEDANG BERLANGSUNG' : 
                                                                  ($item->status_class == 'upcoming' ? 'AKAN DATANG' : 'SELESAI');
                                                    @endphp
                                                    <span class="badge rounded-pill border-0 px-3 py-2 text-dark" style="background-color: {{ $bgOpacity }}; font-weight: 600; font-size: 0.7rem;">
                                                        <span class="d-inline-block rounded-circle me-1" style="width: 6px; height: 6px; background-color: {{ $bgClass }}; mb-1"></span>
                                                        <span style="color: {{ $bgClass }}">{{ $label }}</span>
                                                    </span>
                                                </td>
                                                <td class="pe-4 text-end">
                                                    <button class="btn btn-sm btn-light rounded-circle shadow-sm" onclick="showDetail('{{ $item->mata_pelajaran }}', '{{ $item->guru->nama ?? '-' }}', '{{ $item->kelas_relation->nama_kelas ?? $item->kelas }}', '{{ $item->labor->nama_labor ?? '-' }}', '{{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }}', '{{ $label }}')" title="Detail">
                                                        <i class="bi bi-three-dots"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
            </div>
            
            <!-- Card View Tab -->
            <div class="tab-pane fade" id="card-view" role="tabpanel" aria-labelledby="card-tab">
                @if($stats['total_minggu'] == 0)
                    <div class="text-center py-5 bg-white rounded-4 shadow-sm border-0">
                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80" class="mb-3 opacity-50" alt="No data">
                        <h5 class="fw-bold text-dark">Belum Ada Jadwal</h5>
                        <p class="text-muted small">Belum ada jadwal yang dijadwalkan untuk Anda minggu ini.</p>
                    </div>
                @else
                    @foreach($daysOrder as $day)
                        @php
                            $daySchedules = $jadwalGrouped->get($day) ?? collect();
                            $isToday = ($todayIndo == $day);
                        @endphp
                        
                        @if($daySchedules->count() > 0)
                            <div class="mb-4">
                                <h5 class="fw-bold text-dark mb-3 d-flex align-items-center">
                                    <i class="bi bi-calendar-event me-2 text-primary"></i>{{ strtoupper($day) }}
                                    @if($isToday)
                                        <span class="badge bg-primary ms-2 rounded-pill" style="font-size: 0.6rem">HARI INI</span>
                                    @endif
                                </h5>
                                <div class="row g-3">
                                    @foreach($daySchedules as $item)
                                        @php
                                            $bgClass = $item->status_class == 'ongoing' ? '#10b981' : 
                                                      ($item->status_class == 'upcoming' ? '#3b82f6' : '#94a3b8');
                                            $textClass = $item->status_class == 'ongoing' ? 'text-success' : 
                                                      ($item->status_class == 'upcoming' ? 'text-primary' : 'text-secondary');
                                            $bgOpacity = $item->status_class == 'ongoing' ? 'rgba(16,185,129,0.1)' : 
                                                      ($item->status_class == 'upcoming' ? 'rgba(59,130,246,0.1)' : 'rgba(148,163,184,0.1)');
                                            $label = $item->status_class == 'ongoing' ? 'SEDANG BERLANGSUNG' : 
                                                      ($item->status_class == 'upcoming' ? 'AKAN DATANG' : 'SELESAI');
                                        @endphp
                                        <div class="col-12 col-md-6 col-lg-4">
                                            <div class="card border-0 shadow-sm rounded-4 h-100 position-relative overflow-hidden" style="transition: transform 0.2s; cursor: pointer;" onclick="showDetail('{{ $item->mata_pelajaran }}', '{{ $item->guru->nama ?? '-' }}', '{{ $item->kelas_relation->nama_kelas ?? $item->kelas }}', '{{ $item->labor->nama_labor ?? '-' }}', '{{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }}', '{{ $label }}')">
                                                <div class="position-absolute top-0 start-0 w-100" style="height: 4px; background-color: {{ $bgClass }};"></div>
                                                <div class="card-body p-4">
                                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                                        <span class="badge rounded-pill border-0 px-2 py-1 text-dark" style="background-color: {{ $bgOpacity }}; font-weight: 600; font-size: 0.65rem;">
                                                            <span class="d-inline-block rounded-circle me-1" style="width: 5px; height: 5px; background-color: {{ $bgClass }}; mb-1"></span>
                                                            <span style="color: {{ $bgClass }}">{{ $label }}</span>
                                                        </span>
                                                        <span class="text-muted fw-bold" style="font-size: 0.8rem;">
                                                            <i class="bi bi-clock me-1"></i> {{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }}
                                                        </span>
                                                    </div>
                                                    <h5 class="fw-bold text-dark mb-1">{{ $item->mata_pelajaran }}</h5>
                                                    <p class="text-muted small mb-3"><i class="bi bi-building me-1 opacity-50"></i> {{ $item->labor->nama_labor ?? '-' }}</p>
                                                    
                                                    <div class="d-flex align-items-center mt-auto border-top pt-3">
                                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-primary fw-bold me-2" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                                            {{ strtoupper(substr($item->guru->nama ?? '?', 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <div class="small fw-semibold text-dark">{{ $item->guru->nama ?? '-' }}</div>
                                                            <div class="small text-muted" style="font-size: 0.7rem;">Guru Pengampu</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
    @endif
</div>
@endsection

@section('script')
<script>
    function showDetail(mapel, guru, kelas, labor, waktu, status) {
        Swal.fire({
            title: mapel,
            html: `
                <div class="text-start p-3 bg-light rounded text-dark mt-3">
                    <div class="mb-2"><i class="bi bi-clock me-2 text-primary"></i> <strong>Waktu:</strong> <br><span class="ms-4">${waktu}</span></div>
                    <div class="mb-2"><i class="bi bi-person me-2 text-primary"></i> <strong>Guru:</strong> <br><span class="ms-4">${guru}</span></div>
                    <div class="mb-2"><i class="bi bi-people me-2 text-primary"></i> <strong>Kelas:</strong> <br><span class="ms-4">${kelas}</span></div>
                    <div class="mb-2"><i class="bi bi-building me-2 text-primary"></i> <strong>Laboratorium:</strong> <br><span class="ms-4">${labor}</span></div>
                    <div class="mb-0"><i class="bi bi-info-circle me-2 text-primary"></i> <strong>Status:</strong> <br><span class="ms-4">${status}</span></div>
                </div>
            `,
            icon: 'info',
            confirmButtonText: 'Tutup',
            confirmButtonColor: '#3b82f6'
        });
    }
</script>
@endsection
