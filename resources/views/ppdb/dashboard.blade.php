@extends('ppdb.layouts.main')

@section('css')
<style>
    /* ── PPDB Landing Styles ── */
    .ppdb-hero {
        padding: 110px 0 80px;
        background: linear-gradient(135deg, #df4848 0%, #a42323 100%);
        color: #fff;
        position: relative;
        overflow: hidden;
    }
    .ppdb-hero::before {
        content: '';
        position: absolute;
        top: -20%; left: -10%;
        width: 600px; height: 600px;
        background: rgba(255,255,255,0.08);
        border-radius: 50%;
        filter: blur(2px);
    }
    .ppdb-hero::after {
        content: '';
        position: absolute;
        bottom: -30%; right: -10%;
        width: 500px; height: 500px;
        background: rgba(0,0,0,0.05);
        border-radius: 50%;
    }
    .ppdb-hero .hero-inner { position: relative; z-index: 2; }

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
        transform: translateX(-4px);
    }
    .hero-feature-list .feat-icon {
        width: 44px; height: 44px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .feat-icon.red    { background: rgba(223,72,72,0.3); color: #ffbaba; }
    .feat-icon.yellow { background: rgba(249,168,38,0.3);  color: #ffd06b; }
    .feat-icon.green  { background: rgba(40,167,69,0.3);  color: #72e48c; }
    
    .hero-feature-list .feat-title { font-weight: 600; font-size: .95rem; }
    .hero-feature-list .feat-sub   { font-size: .82rem; opacity: .9; }

    /* Section styling */
    .landing-section { padding: 80px 0; }
    .landing-section:nth-child(even) { background-color: #f8fafc; }
    
    .landing-title {
        font-size: 2rem;
        font-weight: 700;
        color: #a42323;
        margin-bottom: .5rem;
    }
    .landing-divider {
        width: 60px; height: 4px;
        background: linear-gradient(90deg, #df4848, #a42323);
        border-radius: 2px;
        margin-bottom: 1.2rem;
    }

    /* Info Cards Replacement */
    .info-box {
        display: flex;
        flex-direction: column;
        background: #fff;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,.04);
        margin-bottom: 24px;
        transition: all .3s ease;
        border-top: 5px solid #df4848;
        height: 100%;
    }
    .info-box:hover {
        transform: translateY(-8px);
        box-shadow: 0 10px 25px rgba(0,0,0,.08);
    }
    .info-box .ib-icon {
        width: 64px; height: 64px;
        background: rgba(223,72,72,0.1);
        color: #df4848;
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.8rem;
        margin-bottom: 20px;
    }
    .info-box h5 { font-weight: 700; color: #2c3e50; margin-bottom: 10px; font-size: 1.3rem; }
    .info-box p  { color: #6c757d; font-size: .95rem; margin: 0; line-height: 1.6; }

    /* Custom Timeline completely redesigned */
    .timeline-container { position: relative; padding: 20px 0; }
    .timeline-container::before {
        content: '';
        position: absolute;
        top: 0; left: 50%;
        width: 3px; height: 100%;
        background: rgba(223,72,72,0.2);
        transform: translateX(-50%);
    }
    .timeline-item { position: relative; margin-bottom: 50px; display: flex; align-items: center; }
    .timeline-item:nth-child(even) { flex-direction: row-reverse; }
    .timeline-item .ti-content {
        width: 45%;
        background: #fff;
        padding: 25px;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(0,0,0,.05);
        border: 1px solid rgba(0,0,0,.03);
        transition: all .3s ease;
    }
    .timeline-item:hover .ti-content {
        box-shadow: 0 10px 30px rgba(0,0,0,.08);
        transform: translateY(-5px);
    }
    .timeline-item .ti-dot {
        width: 24px; height: 24px;
        background: #df4848;
        border-radius: 50%;
        border: 4px solid #fff;
        box-shadow: 0 0 0 4px rgba(223,72,72,0.2);
        position: absolute;
        left: 50%; transform: translateX(-50%);
        z-index: 2;
    }
    .timeline-item .ti-date {
        color: #df4848; font-weight: 700; margin-bottom: 8px; font-size: 0.9rem; letter-spacing: 0.5px;
    }
    .timeline-item .ti-title { color: #2c3e50; font-weight: 700; font-size: 1.3rem; margin-bottom: 10px; }
    .timeline-item .ti-text { color: #6c757d; margin: 0; line-height: 1.6; }

    /* Counter */
    .counter-wrap {
        background: linear-gradient(135deg, #1f2937, #111827);
        border-radius: 20px;
        color: #fff;
        padding: 50px 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    .counter-item { text-align: center; }
    .counter-item h3 { font-size: 3.5rem; font-weight: 800; color: #df4848; margin-bottom: 10px; }
    .counter-item p { color: #9ca3af; font-size: 1.1rem; margin: 0; }

    /* Responsive Timeline */
    @media (max-width: 768px) {
        .timeline-container::before { left: 24px; }
        .timeline-item { flex-direction: column !important; align-items: flex-start; }
        .timeline-item .ti-content { width: 100%; padding-left: 60px; padding-right: 20px; }
        .timeline-item .ti-dot { left: 24px; transform: translateX(-50%); }
    }
</style>
@endsection

@section('content')
{{-- ═══════════════════ HERO ═══════════════════ --}}
<section class="ppdb-hero">
    <div class="container hero-inner">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0" data-aos="fade-right">
                <span class="badge bg-white text-dark mb-3 px-3 py-2 rounded-pill shadow-sm" style="font-size:.85rem; color:#df4848 !important;">
                    <i class="bi bi-clock-history me-1"></i> Tahun Ajaran 2025/2026
                </span>
                <h1 class="fw-bold mb-3" style="font-size:2.8rem; line-height:1.2;">Sistem PPDB<br>SMK Negeri 5 Padang</h1>
                <p class="mb-4" style="font-size:1.1rem; opacity:.95; line-height:1.7; max-width:550px;">Platform pendaftaran daring resmi untuk calon penerimaan peserta didik baru dan pendaftaran ulang yang dijamin mudah, cepat, dan transparan.</p>
                <div class="d-flex flex-wrap gap-3">
                    @if(Auth::check() && Auth::user()->role == 'admin_ppdb')
                        <a href="{{ route('admin.ppdb.daftar-ulang.index') }}" class="btn fw-bold px-4 py-3 rounded-pill" style="background:#fff; color:#a42323; border:none; box-shadow:0 4px 15px rgba(0,0,0,0.1);">
                            <i class="bi bi-people-fill me-2"></i>Kelola Daftar Ulang
                        </a>
                    @else
                        <a href="{{ route('daftar-ulang.create') }}" class="btn fw-bold px-4 py-3 rounded-pill" style="background:#fff; color:#a42323; border:none; box-shadow:0 4px 15px rgba(0,0,0,0.1);">
                            <i class="bi bi-pencil-square me-2"></i>Daftar Sekarang &rsaquo;
                        </a>
                    @endif
                    <a href="#informasi" class="btn btn-outline-light fw-semibold px-4 py-3 rounded-pill" style="border-width: 2px;">
                        <i class="bi bi-info-circle me-2"></i>Info Persyaratan
                    </a>
                </div>
            </div>
            <div class="col-lg-5 offset-lg-1" data-aos="fade-left" data-aos-delay="150">
                <ul class="hero-feature-list mb-0">
                    <li>
                        <div class="feat-icon red"><i class="bi bi-laptop"></i></div>
                        <div>
                            <div class="feat-title">Pendaftaran Online</div>
                            <div class="feat-sub">Pendaftaran peserta didik melalui aplikasi PPDB.</div>
                        </div>
                    </li>
                    <li>
                        <div class="feat-icon yellow"><i class="bi bi-journal-text"></i></div>
                        <div>
                            <div class="feat-title">Informasi Syarat & Jadwal</div>
                            <div class="feat-sub">Pemantauan syarat berkas dan batas jadwal.</div>
                        </div>
                    </li>
                    <li>
                        <div class="feat-icon green"><i class="bi bi-check2-circle"></i></div>
                        <div>
                            <div class="feat-title">Seleksi & Pengumuman</div>
                            <div class="feat-sub">Hasil pengumuman kelulusan transparan & seketika.</div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════ INFORMASI PERSYARATAN ═══════════════════ --}}
<section id="informasi" class="landing-section">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="landing-title">Informasi Pendaftaran</h2>
            <div class="landing-divider mx-auto"></div>
            <p class="text-muted" style="max-width:650px; margin:0 auto;">Temukan informasi terkini seputar panduan administrasi serta kelengkapan dokumen yang diperlukan.</p>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="info-box">
                    <div class="ib-icon"><i class="bi bi-folder2-open"></i></div>
                    <h5>Dokumen Dibutuhkan</h5>
                    <p>Persiapkan salinan digital Ijazah/SKL terbaru, Kartu Keluarga, Akta Kelahiran, serta file Nilai Rapor dalam format PDF (ukuran maks 2MB) untuk kelancaran pendaftaran.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="info-box" style="border-color:#f9a826;">
                    <div class="ib-icon" style="color:#f9a826; background:rgba(249,168,38,0.1);"><i class="bi bi-gear-fill"></i></div>
                    <h5>Kompetensi Keahlian</h5>
                    <p>Pelajari 8 jurusan (Kompetensi Keahlian) unggulan kami, mulai dari TKJ, RPL, Multimedia, Teknik Mesin, hingga Teknik Elektronika sebelum Anda melakukan pemilihan.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="info-box" style="border-color:#28a745;">
                    <div class="ib-icon" style="color:#28a745; background:rgba(40,167,69,0.1);"><i class="bi bi-envelope-paper"></i></div>
                    <h5>Notifikasi Kelulusan</h5>
                    <p>Anda akan menerima notifikasi otomatis dan e-mail kelulusan. Transparansi hasil seleksi dikirim langsung ke alamat email sewaktu pendaftaran akun berjalan.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════ ALUR PPDB TIMELINE ═══════════════════ --}}
<section class="landing-section" style="background:#f8fafc;">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="landing-title">Alur Pelaksanaan Kegiatan</h2>
            <div class="landing-divider mx-auto"></div>
            <p class="text-muted" style="max-width:650px; margin:0 auto;">Perhatikan tahapan-tahapan penting ini agar Anda tidak tertinggal agenda jadwal yang telah ditetapkan Panitia PPDB.</p>
        </div>

        <div class="timeline-container">
            <div class="timeline-item" data-aos="fade-up">
                <div class="ti-dot"></div>
                <div class="ti-content">
                    <div class="ti-date">23 JUNI – 27 JUNI 2025</div>
                    <h3 class="ti-title">Pendaftaran Online</h3>
                    <p class="ti-text">Pendaftaran calon siswa baru melalui website PPDB dengan mengunggah seluruh dokumen persyaratan form online ke server.</p>
                </div>
            </div>
            
            <div class="timeline-item" data-aos="fade-up">
                <div class="ti-dot"></div>
                <div class="ti-content">
                    <div class="ti-date">23 JUNI – 28 JUNI 2025</div>
                    <h3 class="ti-title">Tes Minat Bakat & Validasi</h3>
                    <p class="ti-text">Pelaksanaan tes minat bakat untuk memetakan penempatan jurusan, yang bersamaan dengan proses verifikasi data dan dokumen.</p>
                </div>
            </div>
            
            <div class="timeline-item" data-aos="fade-up">
                <div class="ti-dot"></div>
                <div class="ti-content">
                    <div class="ti-date">29 JUNI 2025</div>
                    <h3 class="ti-title">Pengumuman Kelulusan</h3>
                    <p class="ti-text">Hasil kelulusan final calon siswa baru dari setiap jurusan (kompetensi keahlian) yang disediakan dan dapat dicek langsung.</p>
                </div>
            </div>
            
            <div class="timeline-item" data-aos="fade-up">
                <div class="ti-dot"></div>
                <div class="ti-content">
                    <div class="ti-date">30 JUNI 2025</div>
                    <h3 class="ti-title">Proses Daftar Ulang</h3>
                    <p class="ti-text">Bagi para calon siswa yang telah dinyatakan Lulus, pendaftaran ulang merupakan tahap wajib terakhir untuk secara sah terdaftar di sekolah.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════ COUNTER ═══════════════════ --}}
<section class="landing-section">
    <div class="container" data-aos="zoom-in">
        <div class="counter-wrap row g-4 align-items-center">
            <div class="col-md-4 counter-item">
                <h3>8</h3>
                <p>Kompetensi Keahlian</p>
            </div>
            <div class="col-md-4 counter-item" style="border-left: 1px solid rgba(255,255,255,0.1); border-right: 1px solid rgba(255,255,255,0.1);">
                <h3>500+</h3>
                <p>Kuota Kuota Peserta Baru</p>
            </div>
            <div class="col-md-4 counter-item">
                <h3 style="color:#72e48c;">95%</h3>
                <p>Tingkat Lulusan Berprestasi</p>
            </div>
        </div>
    </div>
</section>
@endsection