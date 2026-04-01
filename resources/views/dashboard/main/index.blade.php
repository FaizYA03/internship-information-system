@extends('layouts.guest')

@section('css')
<style>
    /* ── Laboratorium Landing Styles ── */
    .lab-hero {
        padding: 110px 0 80px;
        background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
        color: #fff;
        position: relative;
        overflow: hidden;
    }
    .lab-hero::before {
        content: '';
        position: absolute;
        top: -30%; left: -10%;
        width: 600px; height: 600px;
        background: rgba(78,205,196,0.1);
        border-radius: 50%;
        filter: blur(40px);
    }
    .lab-hero::after {
        content: '';
        position: absolute;
        bottom: -20%; right: -10%;
        width: 400px; height: 400px;
        background: rgba(255,144,34,0.1);
        border-radius: 50%;
        filter: blur(40px);
    }
    .lab-hero .hero-inner { position: relative; z-index: 2; }

    /* Feature pill cards in hero */
    .hero-feature-list { list-style: none; padding: 0; margin: 0; }
    .hero-feature-list li {
        display: flex;
        align-items: center;
        gap: 14px;
        background: rgba(255,255,255,0.08);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        padding: 14px 20px;
        margin-bottom: 14px;
        transition: all .3s ease;
        border: 1px solid rgba(255,255,255,0.05);
    }
    .hero-feature-list li:hover {
        background: rgba(255,255,255,0.15);
        transform: translateX(-6px);
        border-color: rgba(78,205,196,0.3);
    }
    .hero-feature-list .feat-icon {
        width: 44px; height: 44px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .feat-icon.teal   { background: rgba(78,205,196,0.2); color: #4ecdc4; }
    .feat-icon.blue   { background: rgba(13,110,253,0.25); color: #6cb4ff; }
    .feat-icon.purple { background: rgba(111,66,193,0.2);  color: #b184f5; }
    
    .hero-feature-list .feat-title { font-weight: 600; font-size: .95rem; }
    .hero-feature-list .feat-sub   { font-size: .82rem; opacity: .8; }

    /* Section styling */
    .landing-section { padding: 80px 0; }
    .landing-section:nth-child(even) { background-color: #f8fafc; }
    
    .landing-title {
        font-size: 2rem;
        font-weight: 700;
        color: #203a43;
        margin-bottom: .5rem;
    }
    .landing-divider {
        width: 60px; height: 4px;
        background: linear-gradient(90deg, #4ecdc4, #556270);
        border-radius: 2px;
        margin-bottom: 1.2rem;
    }

    /* Info boxes */
    .info-box {
        background: #fff;
        border-radius: 16px;
        padding: 30px 24px;
        box-shadow: 0 4px 20px rgba(0,0,0,.05);
        height: 100%;
        transition: all .35s ease;
        border: 1px solid rgba(0,0,0,.03);
        position: relative;
        overflow: hidden;
    }
    .info-box::before {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 4px;
        background: var(--gradient);
        opacity: 0;
        transition: opacity .3s ease;
    }
    .info-box:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 30px rgba(0,0,0,.08);
    }
    .info-box:hover::before { opacity: 1; }
    
    .info-box.gradient-1 { --gradient: linear-gradient(90deg, #4ecdc4, #556270); }
    .info-box.gradient-2 { --gradient: linear-gradient(90deg, #ff9022, #ff6b35); }
    .info-box.gradient-3 { --gradient: linear-gradient(90deg, #1e3c72, #2a5298); }

    .info-box .ib-icon {
        width: 60px; height: 60px;
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.6rem;
        margin-bottom: 20px;
    }
    .info-box h5 { font-weight: 700; color: #203a43; margin-bottom: 10px; }
    .info-box p  { color: #6c757d; font-size: .95rem; margin: 0; line-height: 1.6; }

    /* Lab cards */
    .lab-card {
        border-radius: 16px;
        overflow: hidden;
        border: none;
        box-shadow: 0 5px 20px rgba(0,0,0,.06);
        transition: all .4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .lab-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 35px rgba(0,0,0,.1);
    }
    .lab-card .card-img-wrapper {
        position: relative;
        height: 220px;
        overflow: hidden;
    }
    .lab-card .card-img-wrapper img {
        width: 100%; height: 100%;
        object-fit: cover;
        transition: transform .5s ease;
    }
    .lab-card:hover .card-img-wrapper img {
        transform: scale(1.08);
    }
    .lab-card .lab-badge {
        position: absolute;
        top: 15px; right: 15px;
        background: rgba(32, 58, 67, 0.85);
        backdrop-filter: blur(4px);
        color: #fff;
        padding: 6px 12px;
        border-radius: 30px;
        font-size: 0.8rem;
        font-weight: 600;
        z-index: 2;
    }
    .lab-card .card-body { padding: 25px; }
    .lab-card .card-title {
        font-weight: 700; color: #203a43; font-size: 1.25rem;
        margin-bottom: 15px;
    }
    
    .lab-stat-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 15px;
        background: #f8fafc;
        border-radius: 10px;
        margin-bottom: 15px;
    }
    .lab-stat-item { text-align: center; }
    .lab-stat-val { display: block; font-weight: 700; color: #4ecdc4; font-size: 1.2rem; }
    .lab-stat-lbl { font-size: 0.75rem; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; }

    /* CTA Section */
    .cta-banner {
        background: linear-gradient(135deg, #203a43, #2c5364);
        color: #fff;
        border-radius: 24px;
        padding: 60px 40px;
        text-align: center;
        position: relative;
        overflow: hidden;
        box-shadow: 0 15px 40px rgba(32, 58, 67, 0.2);
    }
    .cta-banner::before {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M54.627 0l.83.83-54.627 54.627-.83-.83L54.627 0zM29.627 40l.83.83-29.627 29.627-.83-.83L29.627 40z' fill='%234ecdc4' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
    }
    .cta-banner * { position: relative; z-index: 2; }
</style>
@endsection

@section('content')
{{-- ═══════════════════ HERO ═══════════════════ --}}
<section class="lab-hero">
    <div class="container hero-inner">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0" data-aos="fade-right">
                <span class="badge" style="background:rgba(78,205,196,0.2); color:#4ecdc4; font-size:.85rem; border:1px solid rgba(78,205,196,0.3); padding:8px 15px; border-radius:30px; margin-bottom:15px;">
                    <i class="bi bi-flask me-1"></i> Praktikum & Penelitian
                </span>
                <h1 class="fw-bold mb-3" style="font-size:2.8rem; line-height:1.2;">Layanan Laboratorium<br>Sekolah</h1>
                <p class="mb-4" style="font-size:1.1rem; opacity:.9; line-height:1.7; max-width:550px;">Fasilitas laboratorium lengkap dan modern. Mendukung penuh kegiatan praktikum, penelitian, dan pengembangan kompetensi kejuruan siswa di lingkungan sekolah.</p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('login', ['from' => 'laboratorium']) }}" class="btn fw-bold px-4 py-3 rounded-pill" style="background:#4ecdc4; color:#0f2027; border:none; box-shadow:0 4px 15px rgba(78,205,196,0.3);">
                        <i class="bi bi-lock-fill me-2"></i>Login Sistem Lab
                    </a>
                    <a href="#daftar-lab" class="btn btn-outline-light fw-semibold px-4 py-3 rounded-pill">
                        <i class="bi bi-grid-fill me-2"></i>Lihat Ruang Praktik
                    </a>
                </div>
            </div>
            <div class="col-lg-5 offset-lg-1" data-aos="fade-left" data-aos-delay="150">
                <ul class="hero-feature-list mb-0">
                    <li>
                        <div class="feat-icon teal"><i class="bi bi-droplet-half"></i></div>
                        <div>
                            <div class="feat-title">Laboratorium IPA</div>
                            <div class="feat-sub">Peralatan standar untuk praktik sains</div>
                        </div>
                    </li>
                    <li>
                        <div class="feat-icon blue"><i class="bi bi-pc-display"></i></div>
                        <div>
                            <div class="feat-title">Laboratorium Komputer</div>
                            <div class="feat-sub">Fasilitas jaringan & komputer spesifikasi tinggi</div>
                        </div>
                    </li>
                    <li>
                        <div class="feat-icon purple"><i class="bi bi-tools"></i></div>
                        <div>
                            <div class="feat-title">Laboratorium Bengkel/Teknik</div>
                            <div class="feat-sub">Pusat pelatihan kompetensi kejuruan mesin</div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════ INFORMASI LAYANAN ═══════════════════ --}}
<section class="landing-section">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="landing-title">Fasilitas & Sistem Integrasi</h2>
            <div class="landing-divider mx-auto"></div>
            <p class="text-muted" style="max-width:650px; margin:0 auto;">Kami mengembangkan sistem informasi khusus untuk mempermudah pengelolaan seluruh kegiatan di lingkungan laboratorium sekolah.</p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="info-box gradient-1">
                    <div class="ib-icon" style="background:rgba(78,205,196,0.1); color:#4ecdc4;"><i class="bi bi-calendar-check"></i></div>
                    <h5>Manajemen Jadwal Pintar</h5>
                    <p>Melihat ketersediaan lab secara real-time. Sistem mencegah bentrok jadwal penggunaan ruangan antar kelas maupun antar guru mata pelajaran.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="info-box gradient-2">
                    <div class="ib-icon" style="background:rgba(255,144,34,0.1); color:#ff9022;"><i class="bi bi-box-seam"></i></div>
                    <h5>Tracking Inventaris Alat</h5>
                    <p>Pencatatan ribuan aset laboratorium secara detail, memonitor stok bahan habis pakai, dan melacak riwayat peminjaman barang per siswa.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="info-box gradient-3">
                    <div class="ib-icon" style="background:rgba(30,60,114,0.1); color:#1e3c72;"><i class="bi bi-tools"></i></div>
                    <h5>Pelaporan Kerusakan (Eskalasi)</h5>
                    <p>Fitur lapor kerusakan alat langsung ke kepala lab. Mempercepat proses perbaikan inventaris untuk menjaga kelancaran praktikum.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════ DAFTAR LABORATORIUM (DYNAMIC DATA) ═══════════════════ --}}
<section id="daftar-lab" class="landing-section">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-end mb-5">
            <div data-aos="fade-right">
                <h2 class="landing-title">Daftar Ruang Laboratorium</h2>
                <div class="landing-divider"></div>
                <p class="text-muted mb-0">Eksplorasi ruang praktik di SMK Negeri 5 Padang.</p>
            </div>
            <div data-aos="fade-left" class="mt-3 mt-md-0">
                <span class="badge bg-light text-dark shadow-sm px-3 py-2" style="font-size:1rem; border:1px solid #e9ecef;">
                    Total: <span style="color:#4ecdc4; font-weight:700;">{{ count($laboratoriums) }}</span> Lab Terdaftar
                </span>
            </div>
        </div>

        <div class="row g-4">
            @forelse($laboratoriums as $lab)
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                <div class="card lab-card h-100">
                    <div class="card-img-wrapper">
                        <div class="lab-badge">
                            <i class="bi bi-building me-1"></i> Lab
                        </div>
                        @if($lab->foto)
                            <img src="{{ asset('storage/' . $lab->foto) }}" alt="{{ $lab->labor }}">
                        @else
                            <div class="h-100 w-100 bg-light d-flex align-items-center justify-content-center">
                                <i class="bi bi-pc-display-horizontal text-secondary" style="font-size: 4rem; opacity: 0.2;"></i>
                            </div>
                        @endif
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h4 class="card-title">{{ $lab->labor }}</h4>
                        
                        <div class="mb-4">
                            <p class="text-muted small mb-2"><i class="bi bi-person-badge me-2" style="color:#4ecdc4;"></i>Penanggung Jawab:</p>
                            <p class="fw-semibold mb-0" style="color:#2c3e50;">{{ $lab->penanggung_jawab ?: 'Belum ditentukan' }}</p>
                        </div>
                        
                        <div class="lab-stat-row mt-auto">
                            <div class="lab-stat-item w-100">
                                <span class="lab-stat-val">{{ $lab->inventaris_count }}</span>
                                <span class="lab-stat-lbl">Total Inventaris Alat/Barang</span>
                            </div>
                        </div>

                        <a href="{{ route('login', ['from' => 'laboratorium']) }}" class="btn btn-outline-secondary w-100 mt-3 rounded-pill fw-semibold" style="font-size:0.9rem;">
                            Selengkapnya <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5" data-aos="fade-up">
                <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-4" style="width:100px; height:100px;">
                    <i class="bi bi-inbox fs-1 text-muted"></i>
                </div>
                <h4 class="text-muted">Laboratorium Kosong</h4>
                <p class="text-muted mb-0">Belum ada data ruang laboratorium yang didaftarkan ke sistem.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

{{-- ═══════════════════ CTA ═══════════════════ --}}
<section class="landing-section pt-0">
    <div class="container" data-aos="zoom-in" data-aos-delay="200">
        <div class="cta-banner">
            <h2 class="fw-bold mb-3" style="font-size:2.2rem;">Sistem Informasi Laboratorium<br>Terintegrasi</h2>
            <p class="mb-4" style="opacity:.9; max-width:650px; margin:0 auto; font-size:1.1rem; line-height:1.6;">Akses jadwal penggunaan lab secara lengkap, lakukan peminjaman alat, serta laporkan kerusakan perangkat komputer/mesin melalui akun Anda.</p>
            <a href="{{ route('login', ['from' => 'laboratorium']) }}" class="btn fw-bold px-5 py-3 rounded-pill mt-2" style="background:#fff; color:#203a43; font-size:1.05rem; box-shadow:0 10px 20px rgba(0,0,0,0.1);">
                <i class="bi bi-person-circle me-2"></i>Login ke Dashboard
            </a>
        </div>
    </div>
</section>
@endsection