<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Profil Kepala Sekolah - SMKN 5 Padang</title>

    <!-- Bootstrap CSS (sesuaikan dengan yang kamu pakai) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        .navbar {
            background-color: #ff9022;
            box-shadow: 0 2px 4px rgba(0,64,128,0.2);
        }
        .navbar-brand span {
            font-size: 1.3rem;
        }
        .nav-link, .dropdown-menu a {
            color: #fff;
        }
        .nav-link:hover, .dropdown-menu a:hover {
            color: #ffd700;
        }
        .dropdown-menu {
            background-color: #ff9022;
        }
        
        /* Profil Kepala Sekolah Section */
        #profil-kepsek {
            padding: 60px 15px;
            background-color: #f8f9fa;
            min-height: 80vh;
        }
        #profil-kepsek .kepsek-photo {
            max-width: 250px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            margin-bottom: 20px;
        }
        #profil-kepsek h2 {
            color: #003366;
            margin-bottom: 15px;
            font-weight: 700;
        }
        #profil-kepsek .info {
            font-size: 1.1rem;
            line-height: 1.6;
            color: #333;
        }
        #profil-kepsek .info strong {
            color: #10375e;
        }
        footer.footer {
    background-color: #ff9022; /* warna biru gelap sama dengan navbar */
    color: #ffffff;
    padding: 40px 15px;
    font-size: 0.9rem;
    line-height: 1.5;
    border-top: 3px solid #ff9022; /* garis atas footer yang kontras */
}

footer.footer .footer-logo img {
    max-height: 60px;
    margin-bottom: 15px;
}

footer.footer .footer-about p {
    color: #cfd8dc; /* abu-abu terang agar mudah dibaca */
}

footer.footer .social-links a {
    color: #cfd8dc;
    font-size: 1.4rem;
    margin-right: 15px;
    transition: color 0.3s ease;
}

footer.footer .social-links a:hover {
    color: #ffd700; /* kuning emas saat hover */
}

footer.footer .footer-heading {
    font-weight: 700;
    color: #ffd700;
    margin-bottom: 20px;
    font-size: 1.1rem;
}

footer.footer .footer-links {
    list-style: none;
    padding-left: 0;
}

footer.footer .footer-links li {
    margin-bottom: 10px;
}

footer.footer .footer-links li a {
    color: #cfd8dc;
    text-decoration: none;
    transition: color 0.3s ease;
}

footer.footer .footer-links li a:hover {
    color: #ffd700;
}

/* Responsive untuk mobile */
@media (max-width: 767px) {
    footer.footer .footer-col {
        margin-bottom: 30px;
    }
}
    </style>
</head>
<body>

<!-- Navbar yang sudah disamakan -->
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
            <div class="brand-logo-container">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Logo SMK" style="height:40px;">
            </div>
            <span class="ms-2 text-white fw-bold">SMKN 5 Padang</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <i class="bi bi-list text-white fs-3"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard') }}">
                        <i class="bi bi-house-door me-1"></i> Beranda
                    </a>
                </li>

                <!-- Profil Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-building me-1"></i> Profil
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ url('/') }}#tentang">Tentang SMK 5 Padang</a></li>
                        <li><a class="dropdown-item" href="{{ url('/') }}#visi-misi">Visi & Misi</a></li>
                        <li><a class="dropdown-item" href="{{ url('/') }}#sejarah">Sejarah</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ url('/') }}#struktur-organisasi">Struktur Organisasi</a></li>
                        <li><a class="dropdown-item" href="{{ url('/') }}#guru">Profil Guru</a></li>
                    </ul>
                </li>

                <!-- Akademik Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-journal-text me-1"></i> Akademik
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('sistem_akademik.dashboard') }}">Dashboard Akademik</a></li>
                        <li><a class="dropdown-item" href="{{ route('sistem_akademik.mataPelajaran.index') }}">Mata Pelajaran</a></li>
                        <li><a class="dropdown-item" href="{{ url('/') }}#kompetensi-keahlian">Kompetensi Keahlian</a></li>
                    </ul>
                </li>

                <!-- Layanan Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-gear me-1"></i> Layanan
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('perpustakaan.buku.index') }}">Perpustakaan</a></li>
                        <li><a class="dropdown-item" href="{{ Auth::check() && Auth::user()->role == 'siswa' ? route('siswa.labor.index') : route('lab.dashboard') }}">Laboratorium</a></li>
                        <li><a class="dropdown-item" href="{{ route('magang.dashboard') }}">Program Magang</a></li>
                        <li><a class="dropdown-item" href="{{ route('ppdb.index') }}">PPDB</a></li>
                    </ul>
                </li>

                <!-- Kontak Link -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}#kontak">
                        <i class="bi bi-telephone me-1"></i> Kontak
                    </a>
                </li>

                <!-- Authentication Menu -->
                @if(Auth::check())
                    <li class="nav-item dropdown ms-lg-2">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu">
                            @if(Auth::user()->role == 'super_admin')
                            <li>
                                <a class="dropdown-item text-start" href="{{ route('admin.manage.index') }}">
                                    <i class="bi bi-speedometer2 me-1"></i> Dashboard
                                </a>
                            </li>
                            @endif

                            @if(Auth::user()->role == 'wakil_perusahaan')
                            <li>
                                <a class="dropdown-item text-start" href="{{ route('magang.wakil_perusahaan.dashboard') }}">
                                    <i class="bi bi-speedometer2 me-1"></i> Dashboard Mitra
                                </a>
                            </li>
                            @endif

                            <li>
                                <a class="dropdown-item text-start" href="javascript:void(0)" onclick="logout()">
                                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item ms-lg-2">
                        <a class="login-btn nav-link d-inline-block" href="{{ route('login') }}">
                            <i class="bi bi-person-circle me-1"></i> Login
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>

<!-- Profil Kepala Sekolah -->
<section id="profil-kepsek" class="container">
    <div class="row align-items-center">
        <div class="col-md-4 text-center">
            <img src="{{ asset('assets/images/kepsek.jpg') }}" alt="Foto Kepala Sekolah" class="kepsek-photo img-fluid" />
        </div>
        <div class="col-md-8">
            <h2>Rizka Fauzi Yosfi, S.Pd., S.T., M.Kom</h2>
            <p class="info">
                <strong>Jabatan:</strong> Kepala Sekolah SMKN 5 Padang<br>
                <strong>Alamat:</strong> Jl. Prof. Hamka No. 25, Padang, Sumatera Barat<br>
                <strong>Telepon:</strong> (0751) 123456<br>
                <strong>Email:</strong> rizka.fauzi@smkn5padang.sch.id<br>
            </p>
            <h4>Profil Singkat</h4>
            <p class="info">
                Rizka Fauzi Yosfi, S.Pd., S.T., M.Kom. adalah Kepala Sekolah SMKN 5 Padang sejak tahun 2020. Beliau memiliki pengalaman lebih dari 15 tahun di bidang pendidikan vokasi dan berkomitmen untuk meningkatkan kualitas pendidikan serta membentuk siswa yang kompeten dan berkarakter.
            </p>
            <h4>Visi Kepala Sekolah</h4>
            <p class="info">
                Menjadikan SMKN 5 Padang sebagai lembaga pendidikan kejuruan terdepan yang menghasilkan lulusan berkualitas, siap kerja, dan berjiwa entrepreneur.
            </p>
            <h4>Misi Kepala Sekolah</h4>
            <ul class="info">
                <li>Meningkatkan kualitas pengajaran dan pembelajaran berbasis kompetensi.</li>
                <li>Membangun kerjasama dengan dunia industri untuk meningkatkan peluang kerja siswa.</li>
                <li>Menciptakan lingkungan sekolah yang kondusif dan mendukung pengembangan karakter siswa.</li>
            </ul>
            <div class="mt-4">
                <a href="{{ route('dashboard') }}" class="btn btn-primary">
                    <i class="bi bi-arrow-left"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Bootstrap JS (required for dropdown & navbar toggler) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Logout script -->
<script>
    function logout() {
        event.preventDefault();
        document.getElementById('logoutForm').submit();
    }
</script>

<!-- Hidden logout form -->
<form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
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
                    <li><a href="{{ route('perpustakaan.buku.index') }}"><i class="bi bi-chevron-right"></i> Perpustakaan</a></li>
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
                    <li><a href="{{ route('sistem_akademik.mataPelajaran.index') }}"><i class="bi bi-chevron-right"></i> Mata Pelajaran</a></li>
                    <li><a href="{{ route('perpustakaan.buku.index') }}"><i class="bi bi-chevron-right"></i> Buku Perpustakaan</a></li>
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

</body>
</html>
