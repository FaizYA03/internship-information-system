@extends('sistem_akademik.layouts.main', ['title' => 'Dashboard Akademik'])

@section('content')

@php
    $role = auth()->user()->role;
@endphp

<!-- Welcome Hero Section -->
<div class="card bg-primary text-white mb-4 border-0 shadow-sm" style="background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%) !important; overflow: hidden; position: relative;">
    <div style="position: absolute; right: -5%; top: -20%; opacity: 0.1;">
        <i class="bi bi-shield-check" style="font-size: 15rem;"></i>
    </div>
    <div class="card-body p-4 p-md-5 position-relative z-1">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="fw-bold mb-2">Selamat Datang, {{ explode(' ', Auth::user()->name)[0] }}!</h2>
                <p class="mb-0 text-white-50 fs-6">Kelola seluruh kegiatan akademik SMK Negeri 5 Padang secara terpusat, cepat, dan profesional.</p>
                <div class="mt-3">
                    <span class="badge bg-white text-primary"> {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }} </span>
                </div>
            </div>
            <div class="col-lg-4 d-none d-lg-flex justify-content-end">
                <img src="{{ asset('assets/images/dashboard-illustration.png') }}" alt="" class="img-fluid" style="max-height: 120px; filter: brightness(0) invert(1) opacity(0.8);">
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <!-- Stats Grid depending on role -->
        @if(in_array($role, ['super_admin','admin_sa']))
        <div class="row g-3 mb-4">
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm h-100" style="border-top: 4px solid #3B82F6 !important;">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="bg-primary bg-opacity-10 text-primary rounded p-3 d-flex align-items-center justify-content-center">
                            <i class="bi bi-people fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-0 small text-uppercase">Total Siswa</p>
                            <h4 class="mb-0 fw-bold">{{ App\Models\Siswa::count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm h-100" style="border-top: 4px solid #10B981 !important;">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="bg-success bg-opacity-10 text-success rounded p-3 d-flex align-items-center justify-content-center">
                            <i class="bi bi-person-workspace fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-0 small text-uppercase">Total Guru</p>
                            <h4 class="mb-0 fw-bold">{{ App\Models\User::where('role','guru')->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm h-100" style="border-top: 4px solid #F59E0B !important;">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="bg-warning bg-opacity-10 text-warning rounded p-3 d-flex align-items-center justify-content-center">
                            <i class="bi bi-journal-text fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-0 small text-uppercase">Total Course</p>
                            <h4 class="mb-0 fw-bold">{{ App\Models\Course::count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card border-0 shadow-sm h-100" style="border-top: 4px solid #8B5CF6 !important;">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="bg-info bg-opacity-10 text-info rounded p-3 d-flex align-items-center justify-content-center">
                            <i class="bi bi-building fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-0 small text-uppercase">Total Kelas</p>
                            <h4 class="mb-0 fw-bold">{{ App\Models\Kelas::count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @elseif($role == 'siswa' && Auth::user()->siswa)
        <div class="row g-3 mb-4">
            <div class="col-md-4 col-6">
                <div class="card border-0 shadow-sm h-100" style="border-top: 4px solid #3B82F6 !important;">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="bg-primary bg-opacity-10 text-primary rounded p-3 d-flex align-items-center justify-content-center">
                            <i class="bi bi-journal-text fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-0 small text-uppercase">Course Saya</p>
                            <h4 class="mb-0 fw-bold">{{ Auth::user()->siswa->courses()->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-6">
                <div class="card border-0 shadow-sm h-100" style="border-top: 4px solid #10B981 !important;">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="bg-success bg-opacity-10 text-success rounded p-3 d-flex align-items-center justify-content-center">
                            <i class="bi bi-journal-check fs-4"></i>
                        </div>
                        <div>
                            @php
                            $count = \App\Models\Peminatan::where('user_id', Auth::id())->count();
                            $filled = $count > 0 ? 1 : 0;
                            @endphp
                            <p class="text-muted mb-0 small text-uppercase">Status Peminatan</p>
                            <h4 class="mb-0 fw-bold">{{ $filled }}/1</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="card border-0 shadow-sm h-100" style="border-top: 4px solid #F59E0B !important;">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="bg-warning bg-opacity-10 text-warning rounded p-3 d-flex align-items-center justify-content-center">
                            <i class="bi bi-calendar-check fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-0 small text-uppercase">Jadwal Hari Ini</p>
                            <h4 class="mb-0 fw-bold">{{ Auth::user()->siswa->courses()->where('hari', \Carbon\Carbon::now()->locale('id')->isoFormat('dddd'))->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @elseif($role == 'siswa' && !Auth::user()->siswa)
        <div class="alert alert-warning mb-4 shadow-sm border-0 d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill me-3 fs-3 text-warning"></i>
            <div>
                <strong>Perhatian:</strong> Data profil siswa Anda terpantau belum lengkap. Segera lengkapi terlebih dahulu.
            </div>
        </div>
        @elseif($role == 'guru')
        <div class="row g-3 mb-4">
            <div class="col-md-4 col-6">
                <div class="card border-0 shadow-sm h-100" style="border-top: 4px solid #3B82F6 !important;">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="bg-primary bg-opacity-10 text-primary rounded p-3 d-flex align-items-center justify-content-center">
                            <i class="bi bi-journal-text fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-0 small text-uppercase">Course Saya</p>
                            <h4 class="mb-0 fw-bold">
                                {{ \App\Models\Course::whereHas('mataPelajaran', function($q) { $q->where('guru_id', Auth::id()); })->count() }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-6">
                <div class="card border-0 shadow-sm h-100" style="border-top: 4px solid #10B981 !important;">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="bg-success bg-opacity-10 text-success rounded p-3 d-flex align-items-center justify-content-center">
                            <i class="bi bi-people fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-0 small text-uppercase">Total Siswa</p>
                            <h4 class="mb-0 fw-bold">
                                {{ \App\Models\Siswa::whereHas('courses', function($q) { $q->whereHas('mataPelajaran', function($q2) { $q2->where('guru_id', Auth::id()); }); })->count() }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="card border-0 shadow-sm h-100" style="border-top: 4px solid #F59E0B !important;">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="bg-warning bg-opacity-10 text-warning rounded p-3 d-flex align-items-center justify-content-center">
                            <i class="bi bi-calendar-check fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-0 small text-uppercase">Jadwal Hari Ini</p>
                            <h4 class="mb-0 fw-bold">
                                {{ \App\Models\Course::whereHas('mataPelajaran', function($q) { $q->where('guru_id', Auth::id()); })->where('hari', \Carbon\Carbon::now()->locale('id')->isoFormat('dddd'))->count() }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Aksi Cepat Menu -->
        <h5 class="fw-bold mb-3">Aksi Cepat Menu</h5>
        <div class="row g-3 mb-4">
            @if(in_array($role, ['super_admin','admin_sa']))
            <div class="col-md-3 col-6">
                <a href="{{ route('sistem_akademik.course.create') }}" class="card text-decoration-none text-center border-0 shadow-sm h-100 action-card hover-lift">
                    <div class="card-body p-3">
                        <div class="text-primary mb-2"><i class="bi bi-plus-circle fs-3"></i></div>
                        <h6 class="text-dark fw-semibold mb-0" style="font-size:0.9rem;">Course Baru</h6>
                        <small class="text-muted" style="font-size:0.7rem;">Tambah Data</small>
                    </div>
                </a>
            </div>
            <div class="col-md-3 col-6">
                <a href="{{ route('sistem_akademik.siswa.create') }}" class="card text-decoration-none text-center border-0 shadow-sm h-100 action-card hover-lift">
                    <div class="card-body p-3">
                        <div class="text-success mb-2"><i class="bi bi-person-plus fs-3"></i></div>
                        <h6 class="text-dark fw-semibold mb-0" style="font-size:0.9rem;">Data Siswa</h6>
                        <small class="text-muted" style="font-size:0.7rem;">Siswa Baru</small>
                    </div>
                </a>
            </div>
            <div class="col-md-3 col-6">
                <a href="{{ route('sistem_akademik.kelas.create') }}" class="card text-decoration-none text-center border-0 shadow-sm h-100 action-card hover-lift">
                    <div class="card-body p-3">
                        <div class="text-warning mb-2"><i class="bi bi-building-add fs-3"></i></div>
                        <h6 class="text-dark fw-semibold mb-0" style="font-size:0.9rem;">Data Kelas</h6>
                        <small class="text-muted" style="font-size:0.7rem;">Kelas Baru</small>
                    </div>
                </a>
            </div>
            <div class="col-md-3 col-6">
                <a href="{{ route('sistem_akademik.berita.create') }}" class="card text-decoration-none text-center border-0 shadow-sm h-100 action-card hover-lift">
                    <div class="card-body p-3">
                        <div class="text-danger mb-2"><i class="bi bi-megaphone fs-3"></i></div>
                        <h6 class="text-dark fw-semibold mb-0" style="font-size:0.9rem;">Pengumuman</h6>
                        <small class="text-muted" style="font-size:0.7rem;">Buat Berita</small>
                    </div>
                </a>
            </div>
            @elseif($role == 'guru')
            <div class="col-md-4 col-6">
                <a href="{{ route('sistem_akademik.course.index') }}" class="card text-decoration-none text-center border-0 shadow-sm h-100 action-card hover-lift">
                    <div class="card-body p-3">
                        <div class="text-primary mb-2"><i class="bi bi-journal-text fs-3"></i></div>
                        <h6 class="text-dark fw-semibold mb-0" style="font-size:0.9rem;">Course</h6>
                        <small class="text-muted" style="font-size:0.7rem;">Lihat Course Anda</small>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-6">
                <a href="{{ route('sistem_akademik.profile') }}" class="card text-decoration-none text-center border-0 shadow-sm h-100 action-card hover-lift">
                    <div class="card-body p-3">
                        <div class="text-success mb-2"><i class="bi bi-person-circle fs-3"></i></div>
                        <h6 class="text-dark fw-semibold mb-0" style="font-size:0.9rem;">Profil Anda</h6>
                        <small class="text-muted" style="font-size:0.7rem;">Pengaturan Akun</small>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-12">
                <a href="{{ route('sistem_akademik.mata_pelajaran.index') }}" class="card text-decoration-none text-center border-0 shadow-sm h-100 action-card hover-lift">
                    <div class="card-body p-3">
                        <div class="text-warning mb-2"><i class="bi bi-book fs-3"></i></div>
                        <h6 class="text-dark fw-semibold mb-0" style="font-size:0.9rem;">Mapel</h6>
                        <small class="text-muted" style="font-size:0.7rem;">Lihat Mapel</small>
                    </div>
                </a>
            </div>
            @elseif($role == 'siswa')
            <div class="col-md-4 col-6">
                <a href="{{ route('sistem_akademik.course.index') }}" class="card text-decoration-none text-center border-0 shadow-sm h-100 action-card hover-lift">
                    <div class="card-body p-3">
                        <div class="text-primary mb-2"><i class="bi bi-journal-text fs-3"></i></div>
                        <h6 class="text-dark fw-semibold mb-0" style="font-size:0.9rem;">Lihat Course</h6>
                        <small class="text-muted" style="font-size:0.7rem;">Course Aktif</small>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-6">
                <a href="{{ route('sistem_akademik.peminatan.index') }}" class="card text-decoration-none text-center border-0 shadow-sm h-100 action-card hover-lift">
                    <div class="card-body p-3">
                        <div class="text-success mb-2"><i class="bi bi-cash-coin fs-3"></i></div>
                        <h6 class="text-dark fw-semibold mb-0" style="font-size:0.9rem;">Peminatan</h6>
                        <small class="text-muted" style="font-size:0.7rem;">Status Peminatan</small>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-12">
                <a href="{{ route('sistem_akademik.profile') }}" class="card text-decoration-none text-center border-0 shadow-sm h-100 action-card hover-lift">
                    <div class="card-body p-3">
                        <div class="text-warning mb-2"><i class="bi bi-person-circle fs-3"></i></div>
                        <h6 class="text-dark fw-semibold mb-0" style="font-size:0.9rem;">Profil Saya</h6>
                        <small class="text-muted" style="font-size:0.7rem;">Perbarui Data</small>
                    </div>
                </a>
            </div>
            @endif
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Announcements (Pengumuman) -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-megaphone me-2 text-danger"></i> Pengumuman Terbaru</h6>
                @if(in_array($role, ['super_admin','admin_sa']))
                    <a href="{{ route('sistem_akademik.berita.index') }}" class="btn btn-sm btn-light">Kelola Berita</a>
                @endif
            </div>
            <div class="card-body p-0">
                @if ($berita->isEmpty())
                <div class="p-4 text-center text-muted">
                    <i class="bi bi-inbox fs-1 mb-2 d-block"></i>
                    Belum ada pengumuman
                </div>
                @else
                <div class="list-group list-group-flush border-0">
                    @foreach ($berita->take(4) as $b)
                    <div class="list-group-item p-3 border-bottom border-light">
                        <div class="d-flex gap-3">
                            @if ($b->foto)
                            <img src="{{ asset('assets/berita/' . $b->foto) }}" alt="" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="bi bi-image text-muted fs-4"></i>
                            </div>
                            @endif
                            <div class="flex-grow-1">
                                <h6 class="mb-1 text-dark" style="font-size:0.9rem;">{{ Str::limit($b->judul, 50) }}</h6>
                                <p class="mb-1 text-muted" style="font-size:0.75rem;">{!! Str::limit(strip_tags($b->isi), 60) !!}</p>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <small class="text-muted" style="font-size: 0.7rem;"><i class="bi bi-clock me-1"></i> {{ optional($b->created_at)->format('d M Y') }}</small>
                                    <a href="{{ route('sistem_akademik.berita.show', $b->id) }}" class="text-primary text-decoration-none" style="font-size: 0.75rem;">Baca <i class="bi bi-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <!-- Pagination minimal -->
                <div class="p-2 border-top d-flex justify-content-center">
                    {!! $berita->appends(request()->query())->links() !!}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    .hover-lift {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important;
    }
    
    /* Modify pagination for sidebar widget */
    .pagination {
        margin-bottom: 0;
        font-size: 0.8rem;
    }
    .page-link {
        padding: 0.25rem 0.5rem;
    }
</style>
@endsection

@section('script')
<script>
    // Include minimal logic for handling if necessary
</script>
@endsection