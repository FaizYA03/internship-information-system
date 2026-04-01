@extends('layouts.guest')

@section('css')
<style>
    /* ── Magang Landing Styles ── */
    .magang-hero {
        padding: 110px 0 80px;
        background: linear-gradient(135deg, #0ba360 0%, #3cba92 100%);
        color: #fff;
        position: relative;
        overflow: hidden;
    }
    .magang-hero::before {
        content: '';
        position: absolute;
        top: -20%; right: -10%;
        width: 600px; height: 600px;
        background: rgba(255,255,255,0.08);
        border-radius: 50%;
        filter: blur(2px);
    }
    .magang-hero::after {
        content: '';
        position: absolute;
        bottom: -30%; left: -10%;
        width: 500px; height: 500px;
        background: rgba(0,0,0,0.03);
        border-radius: 50%;
    }
    .magang-hero .hero-inner { position: relative; z-index: 2; }

    /* Feature pill cards in hero */
    .hero-feature-list { list-style: none; padding: 0; margin: 0; }
    .hero-feature-list li {
        display: flex;
        align-items: center;
        gap: 14px;
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(8px);
        border-radius: 12px;
        padding: 14px 20px;
        margin-bottom: 14px;
        transition: all .3s ease;
        border: 1px solid rgba(255,255,255,0.2);
    }
    .hero-feature-list li:hover {
        background: rgba(255,255,255,0.25);
        transform: translateY(-4px);
    }
    .hero-feature-list .feat-icon {
        width: 44px; height: 44px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .feat-icon.teal   { background: rgba(78,205,196,0.3); color: #20c997; }
    .feat-icon.amber  { background: rgba(249,168,38,0.3);  color: #ffd06b; }
    .feat-icon.blue   { background: rgba(13,110,253,0.3);  color: #6cb4ff; }
    
    .hero-feature-list .feat-title { font-weight: 600; font-size: .95rem; }
    .hero-feature-list .feat-sub   { font-size: .82rem; opacity: .9; }

    /* Section styling */
    .landing-section { padding: 80px 0; }
    .landing-section:nth-child(even) { background-color: #f8fafc; }
    
    .landing-title {
        font-size: 2rem;
        font-weight: 700;
        color: #0ba360;
        margin-bottom: .5rem;
    }
    .landing-divider {
        width: 60px; height: 4px;
        background: linear-gradient(90deg, #0ba360, #3cba92);
        border-radius: 2px;
        margin-bottom: 1.2rem;
    }

    /* Purpose boxes */
    .purpose-box {
        display: flex;
        background: #fff;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 15px rgba(0,0,0,.04);
        margin-bottom: 20px;
        transition: all .3s ease;
        border-left: 5px solid #0ba360;
    }
    .purpose-box:hover {
        transform: translateX(10px);
        box-shadow: 0 8px 25px rgba(0,0,0,.08);
    }
    .purpose-box .pb-icon {
        width: 60px; height: 60px;
        background: rgba(11,163,96,0.1);
        color: #0ba360;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.6rem;
        margin-right: 20px;
        flex-shrink: 0;
    }
    .purpose-box h5 { font-weight: 700; color: #2c3e50; margin-bottom: 6px; }
    .purpose-box p  { color: #6c757d; font-size: .95rem; margin: 0; line-height: 1.5; }

    /* Timeline Workflow */
    .workflow-timeline {
        position: relative;
        padding-left: 30px;
    }
    .workflow-timeline::before {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 2px; height: 100%;
        background: rgba(11,163,96,0.3);
    }
    .workflow-item {
        position: relative;
        padding-bottom: 30px;
    }
    .workflow-item::last-child { padding-bottom: 0; }
    .workflow-item::before {
        content: '';
        position: absolute;
        top: 0; left: -36px;
        width: 14px; height: 14px;
        border-radius: 50%;
        background: #0ba360;
        border: 3px solid #fff;
        box-shadow: 0 0 0 3px rgba(11,163,96,0.2);
    }
    .workflow-item h5 { color: #0ba360; font-weight: 700; font-size: 1.2rem; margin-bottom: 8px; }
    .workflow-item p  { color: #6c757d; font-size: .95rem; line-height: 1.6; }

    /* Company cards */
    .company-card {
        border-radius: 16px;
        border: none;
        box-shadow: 0 5px 20px rgba(0,0,0,.05);
        transition: all .4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        height: 100%;
        overflow: hidden;
        background: #fff;
    }
    .company-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0,0,0,.1);
    }
    .company-card .c-icon-wrap {
        padding: 30px 20px 20px;
        text-align: center;
        background: linear-gradient(to bottom, #f8fafc, #fff);
    }
    .company-card .c-icon {
        width: 80px; height: 80px;
        background: #fff;
        border-radius: 50%;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 2.5rem; color: #0ba360;
        box-shadow: 0 5px 15px rgba(0,0,0,.08);
        border: 2px solid rgba(11,163,96,0.1);
        margin-bottom: 15px;
        transition: transform .3s ease;
    }
    .company-card:hover .c-icon { transform: scale(1.1) rotate(5deg); }
    .company-card .card-body { padding: 0 25px 30px; }
    .company-info p {
        display: flex; margin-bottom: 10px; font-size: 0.9rem; color: #6c757d;
    }
    .company-info p i {
        color: #0ba360; width: 22px; font-size: 1rem; margin-top: 2px; flex-shrink: 0;
    }

    /* CTA Section */
    .cta-banner {
        background: linear-gradient(135deg, #0f2027, #2c5364);
        color: #fff;
        border-radius: 24px;
        padding: 60px 40px;
        text-align: center;
        position: relative;
        overflow: hidden;
        box-shadow: 0 15px 40px rgba(15, 32, 39, 0.2);
    }
    .cta-banner::before {
        content: '';
        position: absolute;
        top: -20%; right: -10%;
        width: 300px; height: 300px;
        background: rgba(11,163,96,0.2);
        border-radius: 50%;
        filter: blur(20px);
    }
    .cta-banner * { position: relative; z-index: 2; }
</style>
@endsection

@section('content')
{{-- ═══════════════════ HERO ═══════════════════ --}}
<section class="magang-hero">
    <div class="container hero-inner">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0" data-aos="fade-right">
                <span class="badge bg-white text-dark mb-3 px-3 py-2 rounded-pill shadow-sm" style="font-size:.85rem; color:#0ba360 !important;">
                    <i class="bi bi-briefcase-fill me-1"></i> Praktik Kerja Lapangan (PKL)
                </span>
                <h1 class="fw-bold mb-3" style="font-size:2.8rem; line-height:1.2;">Program Magang &<br>Praktik Industri</h1>
                <p class="mb-4" style="font-size:1.1rem; opacity:.95; line-height:1.7; max-width:550px;">Persiapkan diri Anda untuk pengalaman kerja nyata di dunia industri. Platform terpadu untuk informasi, pendaftaran, dan pelaporan program PKL/Magang siswa SMK Negeri 5 Padang.</p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('login', ['from' => 'magang']) }}" class="btn fw-bold px-4 py-3 rounded-pill" style="background:#fff; color:#0ba360; border:none; box-shadow:0 4px 15px rgba(0,0,0,0.1);">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Login Siswa
                    </a>
                    <a href="{{ asset('assets/files/Panduan_Magang_Siswa.pdf') }}" download class="btn btn-outline-light fw-semibold px-4 py-3 rounded-pill">
                        <i class="bi bi-download me-2"></i>Unduh Panduan
                    </a>
                </div>
            </div>
            <div class="col-lg-5 offset-lg-1" data-aos="fade-left" data-aos-delay="150">
                <ul class="hero-feature-list mb-0">
                    <li>
                        <div class="feat-icon teal"><i class="bi bi-building"></i></div>
                        <div>
                            <div class="feat-title">Kerjasama Industri</div>
                            <div class="feat-sub">Mitra dengan puluhan perusahaan terkemuka</div>
                        </div>
                    </li>
                    <li>
                        <div class="feat-icon amber"><i class="bi bi-graph-up-arrow"></i></div>
                        <div>
                            <div class="feat-title">Pengalaman Kerja Nyata</div>
                            <div class="feat-sub">Melatih skill dan etos kerja profesional</div>
                        </div>
                    </li>
                    <li>
                        <div class="feat-icon blue"><i class="bi bi-person-video3"></i></div>
                        <div>
                            <div class="feat-title">Pembimbingan Profesional</div>
                            <div class="feat-sub">Didampingi oleh mentor ahli di bidangnya</div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════ DETAILED EXPLANATION ═══════════════════ --}}
<section class="landing-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0" data-aos="fade-right">
                <h2 class="landing-title">Apa itu Program Magang?</h2>
                <div class="landing-divider"></div>
                <p class="text-muted mb-4" style="line-height:1.8; font-size:1.05rem;">
                    Program Praktik Kerja Lapangan (PKL) atau Magang merupakan bagian dari kurikulum wajib bagi siswa Sekolah Menengah Kejuruan (SMK). Program ini bertujuan untuk mendekatkan siswa dengan budaya kerja sesungguhnya di perusahaan, mengimplementasikan materi yang dipelajari di sekolah, serta membentuk karakter pekerja yang disiplin dan profesional.
                </p>
                
                <div class="purpose-box">
                    <div class="pb-icon"><i class="bi bi-mortarboard-fill"></i></div>
                    <div>
                        <h5>Mencapai Standar Kompetensi</h5>
                        <p>Mengasah hard-skill dan soft-skill agar sesuai dengan tuntutan dan standar industri saat ini.</p>
                    </div>
                </div>
                
                <div class="purpose-box">
                    <div class="pb-icon"><i class="bi bi-link-45deg"></i></div>
                    <div>
                        <h5>Link & Match Industri</h5>
                        <p>Mensinergikan pendidikan kejuruan dengan kebutuhan tenaga kerja nyata (industri/perusahaan).</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 offset-lg-1" data-aos="fade-left">
                <div class="bg-white p-5 rounded-4 shadow-sm border" style="border-radius: 20px !important;">
                    <h4 class="fw-bold mb-4" style="color:#2c3e50;">Alur Pelaksanaan <span style="color:#0ba360;">Magang</span></h4>
                    <div class="workflow-timeline">
                        <div class="workflow-item">
                            <h5>Pendaftaran Akun & Pemilihan Tempat</h5>
                            <p>Siswa mencari dan mendaftar lowongan magang ("openings") yang dibuka oleh perusahaan mitra secara online melalui portal sekolah.</p>
                        </div>
                        <div class="workflow-item">
                            <h5>Seleksi & Persetujuan</h5>
                            <p>Pihak perusahaan (Wakil Perusahaan) akan meninjau dan menerima/menolak ajuan siswa.</p>
                        </div>
                        <div class="workflow-item">
                            <h5>Pelaksanaan Magang & Pelaporan</h5>
                            <p>Siswa melaksanakan tugas magang dan wajib mengunggah jurnal progres/laporan kegiatan rutin ke dalam sistem.</p>
                        </div>
                        <div class="workflow-item">
                            <h5>Penilaian Akhir (Evaluasi)</h5>
                            <p>Guru pembimbing dan pembimbing industri memberikan nilai akhir ("Nilai Akhir Magang") yang menjadi syarat kelulusan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════ DAFTAR PERUSAHAAN MITRA ═══════════════════ --}}
<section id="mitra" class="landing-section" style="background:#f8fafc;">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="landing-title">Mitra Perusahaan Kami</h2>
            <div class="landing-divider mx-auto"></div>
            <p class="text-muted" style="max-width:650px; margin:0 auto;">Kami bekerja sama dengan berbagai industri terkemuka untuk memastikan siswa mendapat tempat magang (PKL) yang berkualitas dan relevan dengan kejuruannya.</p>
        </div>

        <div class="row justify-content-center g-4">
            @forelse($perusahaan as $item)
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                <div class="company-card">
                    <div class="c-icon-wrap">
                        <div class="c-icon">
                            <i class="bi bi-building"></i>
                        </div>
                        <h4 class="card-title fw-bold" style="color:#2c3e50; font-size:1.2rem;">{{ $item->nama_perusahaan }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="company-info">
                            <i class="bi bi-geo-alt-fill text-muted"></i>
                            <span>{{ $item->alamat }}</span>
                        </div>
                        <div class="company-info">
                            <i class="bi bi-telephone-fill text-muted"></i>
                            <span>{{ $item->no_perusahaan }}</span>
                        </div>
                        <div class="company-info mb-4">
                            <i class="bi bi-person-fill text-muted"></i>
                            <span>Pembimbing: <strong style="color:#2c3e50;">{{ $item->nama_pembimbing }}</strong></span>
                        </div>
                        <a href="{{ route('login', ['from' => 'magang']) }}" class="btn w-100 mt-2 fw-semibold" style="background:rgba(11,163,96,0.1); color:#0ba360; border-radius:8px;">
                            Daftar Lowongan <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5" data-aos="fade-up">
                <div class="d-inline-flex align-items-center justify-content-center bg-white rounded-circle mb-4 shadow-sm" style="width:100px; height:100px;">
                    <i class="bi bi-briefcase fs-1 text-muted"></i>
                </div>
                <h4 class="text-muted">Belum Ada Mitra</h4>
                <p class="text-muted mb-0">Saat ini belum ada data perusahaan mitra magang yang ditampilkan.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

{{-- ═══════════════════ CTA ═══════════════════ --}}
<section class="landing-section">
    <div class="container" data-aos="zoom-in" data-aos-delay="200">
        <div class="cta-banner">
            <h2 class="fw-bold mb-3" style="font-size:2.2rem;">Daftar Siswa & Portal Perusahaan</h2>
            <p class="mb-4" style="opacity:.9; max-width:700px; margin:0 auto; font-size:1.1rem; line-height:1.6;">Bagi siswa, login untuk mengunggah pengajuan magang dan mengisi log harian. Bagi mitra industri, login untuk meninjau ajuan dan memberikan penilaian magang.</p>
            
            <div class="d-flex flex-wrap justify-content-center gap-3 mt-4">
                <a href="{{ route('login', ['from' => 'magang']) }}" class="btn fw-bold px-4 py-3 rounded-pill" style="background:#0ba360; color:#fff; font-size:1.05rem; box-shadow:0 10px 20px rgba(11,163,96,0.3);">
                    <i class="bi bi-person-fill me-2"></i>Login Siswa/Guru
                </a>
                <a href="{{ route('login', ['from' => 'perusahaan']) }}" class="btn fw-bold px-4 py-3 rounded-pill" style="background:#fff; color:#2c3e50; font-size:1.05rem;">
                    <i class="bi bi-building me-2"></i>Portal Perusahaan
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
