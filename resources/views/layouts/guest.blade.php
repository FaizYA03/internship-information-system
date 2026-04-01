<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Layanan' }} - SMK Negeri 5 Padang</title>
    <link rel="icon" href="{{ asset('assets/images/logo.png') }}" type="image/x-icon">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Custom CSS applied from main home -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        /* Shared guest landing styles */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-body, #f4f6f9);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .guest-header-hero {
            padding: 100px 0 60px;
            background: linear-gradient(135deg, var(--primary, #004080), var(--primary-dark, #002244));
            color: white;
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .guest-header-hero::before {
            content: '';
            position: absolute;
            top: 0; right: 0;
            width: 300px; height: 300px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            transform: translate(30%, -30%);
            pointer-events: none;
        }

        .guest-header-hero h1 {
            font-weight: 700;
            font-size: 2.8rem;
            margin-bottom: 1rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .guest-header-hero p {
            font-size: 1.15rem;
            opacity: 0.9;
            max-width: 800px;
            margin: 0 auto;
        }

        .guest-main-content {
            flex: 1;
            padding-bottom: 4rem;
        }

        /* Standardize cards */
        .service-card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
            background: #fff;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .service-icon {
            font-size: 2.5rem;
            color: var(--secondary, #4ecdc4);
            margin-bottom: 1rem;
        }

        .service-btn {
            background-color: var(--secondary, #4ecdc4);
            color: white;
            border: none;
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .service-btn:hover {
            background-color: var(--primary, #004080);
            color: white;
        }

        .service-btn i {
            margin-left: 0.5rem;
        }
    </style>
    @yield('css')
</head>

<body>
    <!-- Navbar from Home -->
    @include('home.sections.navbar')

    <!-- Main Content -->
    <main class="guest-main-content">
        @yield('content')
    </main>

    <!-- Footer from Home but static implementation here to prevent missing layout issues -->
    <footer id="kontak" class="footer mt-auto" style="background: #111; color: #fff; padding: 60px 0 20px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 footer-col mb-4">
                    <div class="footer-logo mb-3">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="Logo SMK" style="height: 60px;">
                    </div>
                    <div class="footer-about text-muted">
                        <p>SMK 5 Padang adalah lembaga pendidikan kejuruan unggulan yang mempersiapkan siswa untuk menjadi tenaga kerja profesional dan kompetitif di bidangnya.</p>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 footer-col mb-4">
                    <h5 class="text-white mb-3">Navigasi</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('dashboard') }}" class="text-muted text-decoration-none">Beranda</a></li>
                        <li class="mb-2"><a href="{{ route('ppdb.index') }}" class="text-muted text-decoration-none">PPDB</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 footer-col mb-4">
                    <h5 class="text-white mb-3">Layanan Publik</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('perpustakaan.buku.index') }}" class="text-muted text-decoration-none">Perpustakaan</a></li>
                        <li class="mb-2"><a href="{{ route('laboratorium.link') }}" class="text-muted text-decoration-none">Laboratorium</a></li>
                        <li class="mb-2"><a href="{{ route('magang.dashboard') }}" class="text-muted text-decoration-none">Magang</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 footer-col mb-4">
                    <h5 class="text-white mb-3">Kontak Kami</h5>
                    <ul class="list-unstyled text-muted">
                        <li class="mb-2"><i class="bi bi-geo-alt-fill me-2 text-primary"></i>Jalan Beringin No. 4, Padang</li>
                        <li class="mb-2"><i class="bi bi-envelope-fill me-2 text-primary"></i>info@smkpadang.sch.id</li>
                        <li class="mb-2"><i class="bi bi-telephone-fill me-2 text-primary"></i>(0751) 7053201</li>
                    </ul>
                </div>
            </div>

            <div class="text-center text-muted pt-4 mt-4 border-top border-secondary">
                <p>&copy; {{ date('Y') }} SMK 5 Kota Padang</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.8/dist/sweetalert2.all.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 450,
                once: true,
                offset: 100
            });
        });

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
        
        // Sweet Alert notifications
        @if(session('status'))
            Swal.fire({
                title: '{{ session('title') }}',
                text: '{{ session('message') }}',
                icon: '{{ session('status') }}',
                confirmButtonColor: '#004080'
            });
        @endif
    </script>
    @yield('script')
</body>
</html>
