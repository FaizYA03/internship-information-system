<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Meta tags tambahan untuk SEO -->
    <meta name="description" content="SMK Negeri 5 Padang - Lembaga pendidikan kejuruan unggulan yang mempersiapkan siswa untuk menjadi tenaga kerja profesional dan kompetitif di bidangnya.">
    <meta name="keywords" content="SMK Negeri 5 Padang, SMK, Padang, Pendidikan, Kejuruan, Teknik, Sekolah">
    <meta name="author" content="SMK Negeri 5 Padang">
    <meta name="robots" content="index, follow">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $title }} - SMK Padang">
    <meta property="og:description" content="SMK Negeri 5 Padang - Lembaga pendidikan kejuruan unggulan.">
    <meta property="og:image" content="{{ asset('assets/images/logo.png') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ $title }} - SMK Padang">
    <meta property="twitter:description" content="SMK Negeri 5 Padang - Lembaga pendidikan kejuruan unggulan.">
    <meta property="twitter:image" content="{{ asset('assets/images/logo.png') }}">

    <title>{{ $title }} - SMK Negeri 5 Padang</title>
    <link rel="icon" href="{{ asset('assets/images/logo.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">

    <!-- AOS CSS for scroll animations -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>

<body>
    <!-- Modern Navbar - Include from separate file -->
    @include('home.sections.navbar')

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content" data-aos="fade-right" data-aos-delay="100">
                <h1 class="hero-title">Selamat Datang di SMK Negeri 5 Padang</h1>
                <p class="hero-subtitle">Platform terpadu untuk mengelola kegiatan akademik, perpustakaan, laboratorium, dan program magang. Temukan informasi lengkap tentang sekolah dan tingkatkan pengalaman belajar Anda bersama kami.</p>
                <div class="hero-buttons">
                    <a href="{{ route('daftar-ulang.create') }}" class="btn btn-secondary">
                        <i class="bi bi-pen me-2"></i> Daftar Ulang
                    </a>
                    <a href="{{ route('magang.wakil_perusahaan.register') }}" class="btn btn-outline-light">
                        <i class="bi bi-building me-2"></i> Daftar Mitra Magang
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="tentang" class="about-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-right" data-aos-delay="100">
                    <div class="about-img">
                        <img src="{{ asset('assets/images/banner.jpg') }}" alt="SMK Padang" class="img-fluid" loading="lazy">
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left" data-aos-delay="200">
                    <div class="about-content">
                        <h2>Tentang SMK 5 Padang</h2>
                        <p>SMK Padang adalah lembaga pendidikan kejuruan unggulan yang mempersiapkan siswa untuk menjadi tenaga kerja profesional dan kompetitif di bidangnya. Dengan fasilitas modern dan tenaga pengajar berkualitas, kami berkomitmen memberikan pendidikan yang berkualitas.</p>
                        <p>Kami menawarkan berbagai jurusan kejuruan yang relevan dengan kebutuhan industri saat ini, didukung dengan laboratorium lengkap dan program magang yang terintegrasi dengan dunia industri.</p>

                        <div class="about-features">
                            <div class="about-feature-item" data-aos="fade-up" data-aos-delay="300">
                                <div class="about-feature-icon">
                                    <i class="bi bi-check-circle-fill"></i>
                                </div>
                                <div class="about-feature-text">Laboratorium Komputer, Jaringan, dan Multimedia Modern</div>
                            </div>
                            <div class="about-feature-item" data-aos="fade-up" data-aos-delay="400">
                                <div class="about-feature-icon">
                                    <i class="bi bi-check-circle-fill"></i>
                                </div>
                                <div class="about-feature-text">Program Magang dengan Perusahaan Partner</div>
                            </div>
                            <div class="about-feature-item" data-aos="fade-up" data-aos-delay="500">
                                <div class="about-feature-icon">
                                    <i class="bi bi-check-circle-fill"></i>
                                </div>
                                <div class="about-feature-text">Perpustakaan Digital dan Fisik Lengkap</div>
                            </div>
                            <div class="about-feature-item" data-aos="fade-up" data-aos-delay="600">
                                <div class="about-feature-icon">
                                    <i class="bi bi-check-circle-fill"></i>
                                </div>
                                <div class="about-feature-text">Sistem Informasi Akademik Terintegrasi</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Visi & Misi Section -->
    <section id="visi-misi">
        @include('home.sections.vision-mission')
    </section>


    <!-- Struktur Organisasi Section -->
    <section id="struktur-organisasi">
        @include('home.sections.organization')
    </section>

    <!-- Profil Guru Section -->
    <section id="guru">
        @include('home.sections.teacher-profiles')
    </section>

    <!-- Features/Services Section -->
    <section class="features-section">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Layanan Kami</h2>
                <p>Akses berbagai fitur lengkap untuk mendukung kegiatan akademik dan pengembangan siswa</p>
            </div>

            <div class="row g-4">
                <!-- PPDB Card -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="card feature-card">
                        <div class="card-body d-flex flex-column">
                            <div class="feature-icon">
                                <i class="fa-solid fa-school"></i>
                            </div>
                            <h3>Informasi PPDB</h3>
                            <p>Akses informasi penerimaan siswa baru, syarat pendaftaran, dan jadwal penting secara lengkap.</p>
                            <a href="{{ route('ppdb.index') }}" class="btn-feature mt-auto">
                                Kunjungi <i class="fa-solid fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Sistem Akademik Card -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="card feature-card">
                        <div class="card-body d-flex flex-column">
                            <div class="feature-icon">
                                <i class="fa-solid fa-medal"></i>
                            </div>
                            <h3>Sistem Akademik</h3>
                            <p>Kelola nilai, jadwal mata pelajaran, dan informasi akademik lainnya dengan mudah dan efisien.</p>
                            <a href="{{ route('sistem_akademik.dashboard') }}" class="btn-feature mt-auto">
                                Kunjungi <i class="fa-solid fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Perpustakaan Card -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="card feature-card">
                        <div class="card-body d-flex flex-column">
                            <div class="feature-icon">
                                <i class="fa-solid fa-book"></i>
                            </div>
                            <h3>Perpustakaan</h3>
                            <p>Akses koleksi buku digital, peminjaman buku, dan layanan perpustakaan lainnya dengan praktis.</p>
                            @if(Auth::check() && (Auth::user()->role == 'kepsek' || Auth::user()->role == 'kepala_sekolah'))
                            <a href="{{ route('kepsek.dashboard') }}" class="btn-feature mt-auto">
                            @else
                            <a href="{{ route('perpustakaan.buku.index') }}" class="btn-feature mt-auto">
                            @endif
                                Kunjungi <i class="fa-solid fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Laboratorium Card -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="card feature-card">
                        <div class="card-body d-flex flex-column">
                            <div class="feature-icon">
                                <i class="fa-solid fa-flask-vial"></i>
                            </div>
                            <h3>Laboratorium</h3>
                            <p>Kelola jadwal penggunaan laboratorium, inventaris, dan pelaporan kerusakan alat dengan sistem terintegrasi.</p>
                            <a href="{{ route('lab.dashboard') }}" class="btn-feature mt-auto">
                                Kunjungi <i class="fa-solid fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Magang Card -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
                    <div class="card feature-card">
                        <div class="card-body d-flex flex-column">
                            <div class="feature-icon">
                                <i class="fa-solid fa-briefcase"></i>
                            </div>
                            <h3>Program Magang</h3>
                            <p>Dapatkan informasi tentang program magang, perusahaan partner, dan pendaftaran magang untuk siswa.</p>
                            <a href="{{ route('magang.dashboard') }}" class="btn-feature mt-auto">
                                Kunjungi <i class="fa-solid fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Login Card -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="600">
                    <div class="card feature-card">
                        <div class="card-body d-flex flex-column">
                            <div class="feature-icon">
                                <i class="fa-solid fa-user-lock"></i>
                            </div>
                            <h3>Area Pengguna</h3>
                            <p>Login untuk mengakses fitur lengkap dan personalisasi pengalaman Anda di sistem informasi SMK.</p>
                            <a href="{{ route('login') }}" class="btn-feature mt-auto">
                                Login <i class="fa-solid fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sejarah Section -->
    <section id="sejarah">
        @include('home.sections.history')
    </section>

    <!-- Kompetensi Keahlian Section -->
    <section id="kompetensi-keahlian">
        @include('home.sections.majors')
    </section>

    <!-- Footer -->
    <footer id="kontak" class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 footer-col">
                    <div class="footer-logo">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="Logo SMK">
                    </div>
                    <div class="footer-about">
                        <p>SMK 5 Padang adalah lembaga pendidikan kejuruan unggulan yang mempersiapkan siswa untuk menjadi tenaga kerja profesional dan kompetitif di bidangnya.</p>
                    </div>
                    <div class="social-links">
                        <a href="#"><i class="bi bi-facebook"></i></a>
                        <a href="#"><i class="bi bi-twitter-x"></i></a>
                        <a href="#"><i class="bi bi-instagram"></i></a>
                        <a href="#"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 footer-col">
                    <h4 class="footer-heading">Navigasi</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('dashboard') }}"><i class="bi bi-chevron-right"></i> Beranda</a></li>
                        <li><a href="{{ route('ppdb.index') }}"><i class="bi bi-chevron-right"></i> PPDB</a></li>
                        <li><a href="{{ route('sistem_akademik.dashboard') }}"><i class="bi bi-chevron-right"></i> Akademik</a></li>
                        <li>
                            @if(Auth::check() && (Auth::user()->role == 'kepsek' || Auth::user()->role == 'kepala_sekolah'))
                            <a href="{{ route('kepsek.dashboard') }}"><i class="bi bi-chevron-right"></i> Perpustakaan</a>
                            @else
                            <a href="{{ route('perpustakaan.buku.index') }}"><i class="bi bi-chevron-right"></i> Perpustakaan</a>
                            @endif
                        </li>
                        <li><a href="{{ route('lab.dashboard') }}"><i class="bi bi-chevron-right"></i> Laboratorium</a></li>
                        <li><a href="{{ route('magang.dashboard') }}"><i class="bi bi-chevron-right"></i> Magang</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 footer-col">
                    <h4 class="footer-heading">Layanan</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('lab.jadwal') }}"><i class="bi bi-chevron-right"></i> Jadwal Laboratorium</a></li>
                        <li><a href="{{ route('inv.index') }}"><i class="bi bi-chevron-right"></i> Inventaris Lab</a></li>
                        <li><a href="{{ route('inv.laporan') }}"><i class="bi bi-chevron-right"></i> Laporan Kerusakan</a></li>
                        <li><a href="{{ route('sistem_akademik.mata_pelajaran.index') }}"><i class="bi bi-chevron-right"></i> Mata Pelajaran</a></li>
                        <li>
                            @if(Auth::check() && (Auth::user()->role == 'kepsek' || Auth::user()->role == 'kepala_sekolah'))
                            <a href="{{ route('kepsek.dashboard') }}"><i class="bi bi-chevron-right"></i> Buku Perpustakaan</a>
                            @else
                            <a href="{{ route('perpustakaan.buku.index') }}"><i class="bi bi-chevron-right"></i> Buku Perpustakaan</a>
                            @endif
                        </li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 footer-col">
                    <h4 class="footer-heading">Kontak Kami</h4>
                    <ul class="footer-contact footer-links">
                        <li>
                            <i class="bi bi-geo-alt-fill"></i>
                            <span>Jalan Beringin No. 4 RT. 02 RW. 02 Kelurahan Lolong Belanti,
                                Kecamatan Padang Utara, Padang, Sumatera Barat, Indonesia</span>
                        </li>
                        <li>
                            <i class="bi bi-envelope-fill"></i>
                            <span>info@smkpadang.sch.id</span>
                        </li>
                        <li>
                            <i class="bi bi-telephone-fill"></i>
                            <span>(0751) 7053201</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; 2025 SMK 5 Kota Padang</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous" defer></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js" defer></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.8/dist/sweetalert2.all.min.js" defer></script>
    <script src="{{ asset('assets/js/custom.js') }}" defer></script>
    <!-- AOS Script for scroll animations -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        // Initialize AOS
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 450,
                easing: 'ease-in-out',
                once: true,
                mirror: false,
                offset: 100
            });

            // Sweet Alert notifications
            @if(session('status'))
                Swal.fire({
                    title: '{{ session('title') }}',
                    text: '{{ session('message') }}',
                    icon: '{{ session('status') }}',
                    confirmButtonColor: '#004080'
                });
            @endif
        });

        // Sticky navbar effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.padding = '0.3rem 1rem';
                navbar.style.boxShadow = '0 4px 10px rgba(0,0,0,0.1)';
            } else {
                navbar.style.padding = '0.5rem 1rem';
                navbar.style.boxShadow = '0 4px 6px rgba(0,0,0,0.1)';
            }
        });

        // Logout confirmation
        function logout(e) {
            Swal.fire({
                title: "Apakah anda yakin?",
                text: "Logout dari akun",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: '#004080',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Logout!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logoutForm').submit();
                }
            });
        }

        // Delete confirmation
        function confirmDelete(e) {
            Swal.fire({
                title: "Apakah anda yakin?",
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: '#004080',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm' + e).submit();
                }
            });
        }
    </script>
</body>
</html>
