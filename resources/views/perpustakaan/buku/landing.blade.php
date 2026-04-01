@extends('layouts.guest')

@section('css')
<style>
    /* ── Perpustakaan Landing Styles ── */
    .perpus-hero {
        padding: 110px 0 80px;
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #1e3c72 100%);
        color: #fff;
        position: relative;
        overflow: hidden;
    }
    .perpus-hero::before {
        content: '';
        position: absolute;
        top: -60%; right: -20%;
        width: 600px; height: 600px;
        background: rgba(249,168,38,0.08);
        border-radius: 50%;
    }
    .perpus-hero::after {
        content: '';
        position: absolute;
        bottom: -40%; left: -10%;
        width: 500px; height: 500px;
        background: rgba(255,255,255,0.04);
        border-radius: 50%;
    }
    .perpus-hero .hero-inner { position: relative; z-index: 2; }

    /* Feature pill cards in hero */
    .hero-feature-list { list-style: none; padding: 0; margin: 0; }
    .hero-feature-list li {
        display: flex;
        align-items: center;
        gap: 14px;
        background: rgba(255,255,255,0.12);
        backdrop-filter: blur(6px);
        border-radius: 12px;
        padding: 14px 20px;
        margin-bottom: 14px;
        transition: all .3s ease;
        border: 1px solid rgba(255,255,255,0.08);
    }
    .hero-feature-list li:hover {
        background: rgba(255,255,255,0.2);
        transform: translateX(6px);
    }
    .hero-feature-list .feat-icon {
        width: 44px; height: 44px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .feat-icon.green  { background: rgba(40,167,69,0.25); color: #5be87b; }
    .feat-icon.blue   { background: rgba(13,110,253,0.25); color: #6cb4ff; }
    .feat-icon.amber  { background: rgba(249,168,38,0.3);  color: #ffd06b; }
    .hero-feature-list .feat-title { font-weight: 600; font-size: .95rem; }
    .hero-feature-list .feat-sub   { font-size: .82rem; opacity: .8; }

    /* Section titles */
    .landing-section { padding: 70px 0; }
    .landing-section:nth-child(even) { background-color: #f8fafc; }
    .landing-title {
        font-size: 1.9rem;
        font-weight: 700;
        color: #1e3c72;
        margin-bottom: .5rem;
    }
    .landing-divider {
        width: 60px; height: 4px;
        background: linear-gradient(90deg, #f9a826, #ff6b35);
        border-radius: 2px;
        margin-bottom: 1.2rem;
    }

    /* Info boxes */
    .info-box {
        background: #fff;
        border-radius: 14px;
        padding: 28px 24px;
        box-shadow: 0 4px 20px rgba(0,0,0,.06);
        height: 100%;
        transition: all .35s ease;
        border: 1px solid rgba(0,0,0,.04);
    }
    .info-box:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 30px rgba(0,0,0,.1);
    }
    .info-box .ib-icon {
        width: 56px; height: 56px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 18px;
    }
    .ib-icon.bg-blue   { background: rgba(30,60,114,0.1); color: #1e3c72; }
    .ib-icon.bg-green  { background: rgba(40,167,69,0.1);  color: #28a745; }
    .ib-icon.bg-amber  { background: rgba(249,168,38,0.12); color: #f9a826; }
    .ib-icon.bg-purple { background: rgba(111,66,193,0.1);  color: #6f42c1; }
    .info-box h5 { font-weight: 700; color: #1e3c72; margin-bottom: 8px; }
    .info-box p  { color: #6c757d; font-size: .9rem; margin: 0; }

    /* Stats row */
    .stats-pill {
        text-align: center;
        padding: 30px 20px;
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 3px 12px rgba(0,0,0,.05);
        transition: all .3s ease;
    }
    .stats-pill:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,.08); }
    .stats-pill .sp-num {
        font-size: 2.4rem;
        font-weight: 800;
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .stats-pill .sp-label { color: #6c757d; font-weight: 500; font-size: .9rem; }

    /* Book grid */
    .book-card {
        border: none;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(0,0,0,.06);
        transition: all .35s ease;
        height: 100%;
    }
    .book-card:hover { transform: translateY(-6px); box-shadow: 0 12px 30px rgba(0,0,0,.12); }
    .book-card .card-img-top { height: 240px; object-fit: cover; }
    .book-card .card-body { padding: 18px; }
    .book-card .card-title {
        font-size: .95rem; font-weight: 700; color: #1e3c72;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    }

    /* Procedure steps */
    .step-card {
        display: flex; gap: 18px;
        padding: 22px;
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 3px 12px rgba(0,0,0,.05);
        margin-bottom: 18px;
        transition: all .3s ease;
        border-left: 4px solid #1e3c72;
    }
    .step-card:hover { transform: translateX(6px); box-shadow: 0 6px 20px rgba(0,0,0,.08); }
    .step-num {
        width: 44px; height: 44px;
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        color: #fff;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 1.1rem;
        flex-shrink: 0;
    }

    /* CTA banner */
    .cta-banner {
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        color: #fff;
        border-radius: 20px;
        padding: 50px 40px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .cta-banner::before {
        content: '';
        position: absolute;
        top: -50%; right: -20%;
        width: 400px; height: 400px;
        background: rgba(249,168,38,0.1);
        border-radius: 50%;
    }
    .cta-banner * { position: relative; z-index: 2; }
</style>
@endsection

@section('content')
{{-- ═══════════════════ HERO ═══════════════════ --}}
<section class="perpus-hero">
    <div class="container hero-inner">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-right">
                <span class="badge bg-warning text-dark mb-3 px-3 py-2 rounded-pill" style="font-size:.85rem;">
                    <i class="bi bi-book me-1"></i> Perpustakaan Digital & Fisik
                </span>
                <h1 class="fw-bold mb-3" style="font-size:2.6rem; line-height:1.15;">Koleksi & Layanan<br>Perpustakaan</h1>
                <p class="mb-4" style="font-size:1.1rem; opacity:.9; line-height:1.7;">Temukan ribuan koleksi buku pelajaran, fiksi, dan referensi. Nikmati layanan peminjaman yang mudah serta akses e-book kapan saja, di mana saja.</p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('login', ['from' => 'perpustakaan']) }}" class="btn btn-warning text-dark fw-bold px-4 py-2 rounded-pill">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Login & Akses
                    </a>
                    <a href="#koleksi" class="btn btn-outline-light fw-semibold px-4 py-2 rounded-pill">
                        <i class="bi bi-arrow-down-circle me-2"></i>Lihat Koleksi
                    </a>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left" data-aos-delay="150">
                <ul class="hero-feature-list">
                    <li>
                        <div class="feat-icon green"><i class="bi bi-journal-bookmark-fill"></i></div>
                        <div>
                            <div class="feat-title">Koleksi Buku Lengkap</div>
                            <div class="feat-sub">Ribuan buku pelajaran, fiksi, & referensi</div>
                        </div>
                    </li>
                    <li>
                        <div class="feat-icon blue"><i class="bi bi-arrow-left-right"></i></div>
                        <div>
                            <div class="feat-title">Layanan Peminjaman</div>
                            <div class="feat-sub">Peminjaman & pengembalian buku mudah</div>
                        </div>
                    </li>
                    <li>
                        <div class="feat-icon amber"><i class="bi bi-lamp-fill"></i></div>
                        <div>
                            <div class="feat-title">Ruang Baca Nyaman</div>
                            <div class="feat-sub">Ruang baca yang nyaman & kondusif</div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════ KEUNGGULAN ═══════════════════ --}}
<section class="landing-section">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="landing-title">Keunggulan Perpustakaan Kami</h2>
            <div class="landing-divider mx-auto"></div>
            <p class="text-muted" style="max-width:650px; margin:0 auto;">Fasilitas perpustakaan modern yang mendukung kebutuhan literasi seluruh warga sekolah.</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="info-box">
                    <div class="ib-icon bg-blue"><i class="bi bi-book-half"></i></div>
                    <h5>Koleksi Beragam</h5>
                    <p>Buku akademik, novel, ensiklopedia, majalah, serta koleksi digital (PDF/E-book) tersedia untuk mendukung proses belajar.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="info-box">
                    <div class="ib-icon bg-green"><i class="bi bi-qr-code-scan"></i></div>
                    <h5>Peminjaman Digital</h5>
                    <p>Sistem peminjaman terintegrasi berbasis QR Code untuk efisiensi dan transparansi proses transaksi.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="info-box">
                    <div class="ib-icon bg-amber"><i class="bi bi-wifi"></i></div>
                    <h5>Akses E-book 24/7</h5>
                    <p>Koleksi buku digital dapat diakses kapan saja dan di mana saja melalui akun siswa yang telah terdaftar.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="info-box">
                    <div class="ib-icon bg-purple"><i class="bi bi-people-fill"></i></div>
                    <h5>Ruang Diskusi</h5>
                    <p>Area diskusi kelompok dan pojok baca individual tersedia untuk mendukung berbagai gaya belajar siswa.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════ STATISTIK ═══════════════════ --}}
<section class="landing-section" style="background: #f0f4fa;">
    <div class="container">
        <div class="row g-4 justify-content-center" data-aos="fade-up">
            <div class="col-6 col-md-3">
                <div class="stats-pill">
                    <div class="sp-num">{{ $buku->count() }}+</div>
                    <div class="sp-label">Judul Buku</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stats-pill">
                    <div class="sp-num">{{ $buku->where('pdf_path', '!=', null)->count() }}</div>
                    <div class="sp-label">E-book Digital</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stats-pill">
                    <div class="sp-num">5+</div>
                    <div class="sp-label">Kategori Buku</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stats-pill">
                    <div class="sp-num">100+</div>
                    <div class="sp-label">Kursi Baca</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════ KATALOG BUKU ═══════════════════ --}}
<section id="koleksi" class="landing-section">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="landing-title">Sekilas Koleksi Kami</h2>
            <div class="landing-divider mx-auto"></div>
            <p class="text-muted" style="max-width:600px; margin:0 auto;">Beberapa koleksi buku terpopuler di perpustakaan SMK Negeri 5 Padang.</p>
        </div>

        <div class="row g-4 justify-content-center">
            @forelse($buku->take(8) as $item)
            <div class="col-xl-3 col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 60 }}">
                <div class="card book-card">
                    <div class="position-relative">
                        @if($item->cover_path)
                            <img src="{{ asset('storage/' . $item->cover_path) }}" class="card-img-top" alt="{{ $item->judul }}">
                        @else
                            <div class="d-flex align-items-center justify-content-center bg-light" style="height:240px;">
                                <i class="bi bi-book text-secondary" style="font-size:4rem; opacity:.25;"></i>
                            </div>
                        @endif
                        @if($item->pdf_path)
                        <span class="position-absolute top-0 end-0 m-2 badge rounded-pill" style="background:#1e3c72;">
                            <i class="bi bi-file-earmark-pdf me-1"></i>PDF
                        </span>
                        @endif
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $item->judul }}</h5>
                        <p class="text-muted small mb-3">{{ $item->pengarang }}</p>
                        <a href="{{ route('login', ['from' => 'perpustakaan']) }}" class="btn btn-sm mt-auto w-100 fw-semibold" style="background:rgba(30,60,114,.08); color:#1e3c72; border-radius:8px;">
                            Detail & Pinjam <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5" data-aos="fade-up">
                <i class="bi bi-journal-x" style="font-size:4rem; color:#ccc;"></i>
                <p class="text-muted fs-5 mt-3">Belum ada koleksi buku yang tersedia.</p>
            </div>
            @endforelse
        </div>

        @if($buku->count() > 8)
        <div class="text-center mt-5" data-aos="fade-up">
            <a href="{{ route('login', ['from' => 'perpustakaan']) }}" class="btn btn-outline-primary rounded-pill px-4 py-2 fw-semibold">
                Masuk untuk Lihat {{ $buku->count() }} Koleksi Lengkap <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
        @endif
    </div>
</section>

{{-- ═══════════════════ PROSEDUR PEMINJAMAN ═══════════════════ --}}
<section class="landing-section" style="background:#f8fafc;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5 mb-4 mb-lg-0" data-aos="fade-right">
                <h2 class="landing-title">Cara Meminjam Buku</h2>
                <div class="landing-divider"></div>
                <p class="text-muted" style="line-height:1.8;">Proses peminjaman buku di perpustakaan SMK Negeri 5 Padang sangat mudah. Ikuti langkah-langkah berikut untuk memulai.</p>
                <div class="p-3 rounded-3 mt-3" style="background:rgba(30,60,114,.05); border-left:4px solid #f9a826;">
                    <p class="mb-0 text-muted small"><i class="bi bi-info-circle-fill me-2" style="color:#1e3c72;"></i>Durasi peminjaman standar adalah <strong>7 hari kerja</strong>. Perpanjangan dapat dilakukan melalui sistem.</p>
                </div>
            </div>
            <div class="col-lg-7" data-aos="fade-left">
                <div class="step-card">
                    <div class="step-num">1</div>
                    <div>
                        <h6 class="fw-bold mb-1" style="color:#1e3c72;">Login ke Sistem</h6>
                        <p class="text-muted small mb-0">Masuk menggunakan akun siswa atau guru yang telah terdaftar di sistem informasi sekolah.</p>
                    </div>
                </div>
                <div class="step-card">
                    <div class="step-num">2</div>
                    <div>
                        <h6 class="fw-bold mb-1" style="color:#1e3c72;">Cari & Pilih Buku</h6>
                        <p class="text-muted small mb-0">Jelajahi katalog digital, gunakan pencarian atau filter kategori untuk menemukan buku yang diinginkan.</p>
                    </div>
                </div>
                <div class="step-card">
                    <div class="step-num">3</div>
                    <div>
                        <h6 class="fw-bold mb-1" style="color:#1e3c72;">Ajukan Peminjaman</h6>
                        <p class="text-muted small mb-0">Klik tombol "Pinjam" dan lengkapi form peminjaman. Petugas akan memproses permintaan Anda.</p>
                    </div>
                </div>
                <div class="step-card">
                    <div class="step-num">4</div>
                    <div>
                        <h6 class="fw-bold mb-1" style="color:#1e3c72;">Ambil di Perpustakaan</h6>
                        <p class="text-muted small mb-0">Setelah disetujui, datang ke perpustakaan untuk mengambil buku. Atau baca E-book langsung dari perangkat Anda.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════ TATA TERTIB ═══════════════════ --}}
<section class="landing-section">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="landing-title">Tata Tertib Perpustakaan</h2>
            <div class="landing-divider mx-auto"></div>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="info-box text-center">
                    <div class="ib-icon bg-blue mx-auto"><i class="bi bi-clock-history"></i></div>
                    <h5>Jam Operasional</h5>
                    <p>Senin – Jumat: 07.30 – 15.30 WIB<br>Sabtu: 08.00 – 12.00 WIB</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="info-box text-center">
                    <div class="ib-icon bg-amber mx-auto"><i class="bi bi-exclamation-triangle"></i></div>
                    <h5>Ketentuan Peminjaman</h5>
                    <p>Maksimal 2 buku per peminjaman. Keterlambatan pengembalian dikenakan sanksi denda.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="info-box text-center">
                    <div class="ib-icon bg-green mx-auto"><i class="bi bi-shield-check"></i></div>
                    <h5>Tanggung Jawab</h5>
                    <p>Peminjam bertanggung jawab atas kondisi buku. Kerusakan/kehilangan wajib diganti.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════ CTA ═══════════════════ --}}
<section class="landing-section" style="background:#f0f4fa;">
    <div class="container" data-aos="zoom-in">
        <div class="cta-banner">
            <h2 class="fw-bold mb-3" style="font-size:2rem;">Siap Menjelajahi Perpustakaan?</h2>
            <p class="mb-4" style="opacity:.9; max-width:600px; margin:0 auto;">Login sekarang untuk mengakses seluruh koleksi buku, melakukan peminjaman, dan membaca e-book kapan saja.</p>
            <a href="{{ route('login', ['from' => 'perpustakaan']) }}" class="btn btn-warning text-dark fw-bold px-5 py-3 rounded-pill" style="font-size:1.05rem;">
                <i class="bi bi-box-arrow-in-right me-2"></i>Login Sekarang
            </a>
        </div>
    </div>
</section>
@endsection
