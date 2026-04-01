<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Laboratorium' }} - SMK Negeri 5 Padang</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/design-system.css') }}" rel="stylesheet">
    
    <style>
        :root {
            --header-height: 70px;
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 80px;
        }

        body {
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
            background-color: var(--background);
        }

        /* Sidebar Styles */
        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: var(--sidebar-bg);
            transition: all 0.3s ease;
            z-index: 1050;
            color: #94A3B8;
        }

        #sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar-header {
            height: var(--header-height);
            display: flex;
            align-items: center;
            padding: 0 24px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.1rem;
            white-space: nowrap;
            overflow: hidden;
        }

        .sidebar-menu {
            padding: 20px 12px;
            list-style: none;
            margin: 0;
        }

        .sidebar-item {
            margin-bottom: 4px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 10px;
            color: #94A3B8;
            text-decoration: none;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .sidebar-link:hover {
            background: rgba(255,255,255,0.05);
            color: white;
        }

        .sidebar-link.active {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }

        .sidebar-link i {
            font-size: 1.2rem;
            min-width: 24px;
            display: flex;
            justify-content: center;
        }

        .sidebar-text {
            transition: opacity 0.3s;
        }

        #sidebar.collapsed .sidebar-text {
            opacity: 0;
            pointer-events: none;
        }

        /* Main Content Styles */
        #main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        #main-content.expanded {
            margin-left: var(--sidebar-collapsed-width);
        }

        .header {
            height: var(--header-height);
            background: white;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .breadcrumb-small {
            font-size: 0.8rem;
            color: var(--neutral);
            margin-bottom: 0;
        }

        .page-title {
            font-weight: 700;
            font-size: 1.25rem;
            margin: 0;
            color: #1E293B;
        }

        .content-body {
            padding: 30px;
        }

        /* Responsive */
        @media (max-width: 991.98px) {
            #sidebar {
                left: calc(-1 * var(--sidebar-width));
            }
            #sidebar.show {
                left: 0;
            }
            #main-content {
                margin-left: 0 !important;
            }
        }

        /* Profile Dropdown */
        .profile-dropdown .dropdown-toggle {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 5px 15px;
            border-radius: 50px;
            background: #F1F5F9;
            border: none;
        }

        .profile-dropdown .dropdown-toggle:after {
            display: none;
        }

        .profile-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.8rem;
        }
    </style>
    @yield('css')
</head>
<body>

    @php
        $role = auth()->user()->role;

        // ============================================================
        // KEPALA LAB — Hanya Monitoring, Supervisi, Persetujuan, Rekomendasi
        // TIDAK BOLEH: tambah data, edit, hapus, input peminjaman
        // ============================================================
        if ($role == 'kepala_lab') {
            // Badge counts for kepala_lab — show pending items
            $badge_eskalasi   = \App\Models\Lab\LaporanKerusakan::where('is_eskalasi', true)
                ->where('eskalasi_ke', 'kepala_lab')
                ->where('eskalasi_status', 'menunggu')
                ->count();

            $badge_eksternal  = \App\Models\Lab\PinjamEksternal::where('status', 'pending')->count();

            $menuItems = [
                [
                    'name'   => 'Dashboard',
                    'icon'   => 'bi-speedometer2',
                    'route'  => 'lab.kepala_lab.dashboard',
                    'active' => request()->routeIs('lab.kepala_lab.dashboard'),
                    'badge'  => null,
                ],
                [
                    'name'   => 'Monitoring Lab',
                    'icon'   => 'bi-building-fill',
                    'route'  => 'lab.kepala_lab.monitoring.lab',
                    'active' => request()->routeIs('lab.kepala_lab.monitoring.lab'),
                    'badge'  => null,
                ],
                [
                    'name'   => 'Monitoring Jadwal',
                    'icon'   => 'bi-calendar-week-fill',
                    'route'  => 'lab.kepala_lab.monitoring.jadwal',
                    'active' => request()->routeIs('lab.kepala_lab.monitoring.jadwal'),
                    'badge'  => null,
                ],
                [
                    'name'   => 'Monitoring Inventaris',
                    'icon'   => 'bi-box-seam-fill',
                    'route'  => 'lab.kepala_lab.monitoring.inventaris',
                    'active' => request()->routeIs('lab.kepala_lab.monitoring.inventaris'),
                    'badge'  => null,
                ],
                [
                    'name'   => 'Monitoring Peminjaman',
                    'icon'   => 'bi-clipboard-data-fill',
                    'route'  => 'lab.kepala_lab.monitoring.peminjaman',
                    'active' => request()->routeIs('lab.kepala_lab.monitoring.peminjaman'),
                    'badge'  => null,
                ],
                [
                    'name'   => 'Laporan Kerusakan',
                    'icon'   => 'bi-exclamation-triangle-fill',
                    'route'  => 'lab.kepala_lab.supervisi.kerusakan',
                    'active' => request()->routeIs('lab.kepala_lab.supervisi.kerusakan'),
                    'badge'  => $badge_eskalasi > 0 ? $badge_eskalasi : null,
                ],
                [
                    'name'   => 'Perbaikan Selesai',
                    'icon'   => 'bi-check-all',
                    'route'  => 'lab.kepala_lab.supervisi.perbaikan_selesai',
                    'active' => request()->routeIs('lab.kepala_lab.supervisi.perbaikan_selesai'),
                    'badge'  => null,
                ],
                [
                    'name'   => 'Rekomendasi Eksternal',
                    'icon'   => 'bi-patch-check-fill',
                    'route'  => 'lab.kepala_lab.approval.eksternal',
                    'active' => request()->routeIs('lab.kepala_lab.approval.eksternal*'),
                    'badge'  => $badge_eksternal > 0 ? $badge_eksternal : null,
                ],
            ];
        } elseif ($role == 'kepala_sekolah') {
            // Badge counts for kepala_sekolah
            $badge_eksternal = \App\Models\Lab\PinjamEksternal::where('status', 'recommended')->count();
            $badge_pengadaan = \App\Models\Lab\Pengadaan::where('status', 'pending')->count();

            $menuItems = [
                [
                    'name'   => 'Dashboard',
                    'icon'   => 'bi-speedometer2',
                    'route'  => 'lab.kepala_sekolah.dashboard',
                    'active' => request()->routeIs('lab.kepala_sekolah.dashboard'),
                    'badge'  => null,
                ],
                [
                    'name'   => 'Peminjaman Eksternal',
                    'icon'   => 'bi-patch-check-fill',
                    'route'  => 'lab.kepala_sekolah.approval.eksternal',
                    'active' => request()->routeIs('lab.kepala_sekolah.approval.eksternal*'),
                    'badge'  => $badge_eksternal > 0 ? $badge_eksternal : null,
                ],
                [
                    'name'   => 'Pengadaan Fasilitas',
                    'icon'   => 'bi-box-seam-fill',
                    'route'  => 'lab.kepala_sekolah.approval.pengadaan.index',
                    'active' => request()->routeIs('lab.kepala_sekolah.approval.pengadaan*'),
                    'badge'  => $badge_pengadaan > 0 ? $badge_pengadaan : null,
                ],
                [
                    'name'   => 'Monitoring Laboratorium',
                    'icon'   => 'bi-building-fill',
                    'route'  => 'lab.admin_new.laboratorium.index',
                    'active' => request()->routeIs('lab.admin_new.laboratorium.*'),
                    'badge'  => null,
                ],
                [
                    'name'   => 'Aktivitas Lab',
                    'icon'   => 'bi-activity',
                    'route'  => 'lab.admin_new.activity_log.index',
                    'active' => request()->routeIs('lab.admin_new.activity_log.index'),
                    'badge'  => null,
                ],
            ];
        } elseif ($role == 'waka_akademik') {
            $menuItems = [
                [
                    'name'   => 'Dashboard',
                    'icon'   => 'bi-speedometer2',
                    'route'  => 'lab.waka_akademik.dashboard',
                    'active' => request()->routeIs('lab.waka_akademik.dashboard'),
                    'badge'  => null,
                ],
                [
                    'name'   => 'Monitoring Akademik',
                    'icon'   => 'bi-journal-check',
                    'route'  => 'lab.waka_akademik.monitoring',
                    'active' => request()->routeIs('lab.waka_akademik.monitoring'),
                    'badge'  => null,
                ],
                [
                    'name'   => 'Data Laboratorium',
                    'icon'   => 'bi-building-fill',
                    'route'  => 'lab.admin_new.laboratorium.index',
                    'active' => request()->routeIs('lab.admin_new.laboratorium.*'),
                    'badge'  => null,
                ],
                [
                    'name'   => 'Jadwal Praktikum',
                    'icon'   => 'bi-calendar-event-fill',
                    'route'  => 'lab.admin_new.jadwal.index',
                    'active' => request()->routeIs('lab.admin_new.jadwal.index'),
                    'badge'  => null,
                ],
                [
                    'name'   => 'Laporan Kerusakan',
                    'icon'   => 'bi-exclamation-triangle-fill',
                    'route'  => 'lab.admin_new.kerusakan.index',
                    'active' => request()->routeIs('lab.admin_new.kerusakan.*'),
                    'badge'  => null,
                ],
            ];
        } else {
            // ============================================================
            // MENU DEFAULT (admin_lab, siswa, kepala_sekolah, waka_akademik, dsb.)
            // ============================================================
            // Counts for Admin/Siswa
            $badge_kerusakan_aktif = \App\Models\Lab\LaporanKerusakan::where('status_perbaikan', '!=', 'selesai')
                ->where(function($q) use ($role) {
                    if ($role == 'siswa') $q->where('user_id', auth()->id());
                })
                ->count();

            $badge_peminjaman_aktif = 0;
            if ($role == 'admin_lab') {
                $badge_peminjaman_aktif = \App\Models\Lab\PinjamAlat::where('status', 'pending')->count() +
                                          \App\Models\PinjamLabor::where('status', 'pending')->count();
            }

            $menuItems = [
                [
                    'name'   => 'Dashboard',
                    'icon'   => 'bi-grid-fill',
                    'route'  => $role == 'siswa' ? 'siswa.dashboard' : (
                                $role == 'admin_lab' ? 'lab.admin_new.dashboard' : (
                                $role == 'kepala_sekolah' ? 'lab.kepala_sekolah.dashboard' : (
                                $role == 'waka_akademik' ? 'lab.waka_akademik.dashboard' : 'dashboard'
                    ))),
                    'active' => request()->routeIs('*.dashboard') || request()->routeIs('lab.admin_new.dashboard'),
                    'badge'  => null,
                ],
                [
                    'name'   => 'Laboratorium',
                    'icon'   => 'bi-building-fill',
                    'route'  => $role == 'siswa' ? 'siswa.labor.index' : 'lab.admin_new.laboratorium.index',
                    'active' => request()->routeIs('*.laboratorium.*') || request()->routeIs('*.labor.*'),
                    'badge'  => null,
                ],
                [
                    'name'   => 'Jadwal',
                    'icon'   => 'bi-calendar-event-fill',
                    'route'  => $role == 'siswa' ? 'siswa.jadwal.index' : 'lab.admin_new.jadwal.index',
                    'active' => request()->routeIs('*.jadwal.*'),
                    'badge'  => null,
                ],
                [
                    'name'   => 'Inventaris',
                    'icon'   => 'bi-box-seam-fill',
                    'route'  => $role == 'siswa' ? 'siswa.inventaris.index' : 'lab.admin_new.inventaris.index',
                    'active' => request()->routeIs('*.inventaris.index') || request()->routeIs('*.inventaris.show') || request()->routeIs('*.inventaris.edit') || request()->routeIs('*.inventaris.create'),
                    'badge'  => null,
                ],
                [
                    'name'   => 'Kategori',
                    'icon'   => 'bi-tags-fill',
                    'route'  => $role == 'admin_lab' ? 'lab.admin_new.inventaris.kategori.index' : '#',
                    'active' => request()->routeIs('*.inventaris.kategori.*'),
                    'hidden' => $role != 'admin_lab',
                    'badge'  => null,
                ],
                [
                    'name'   => 'Jenis Lab',
                    'icon'   => 'bi-grid-3x3-gap-fill',
                    'route'  => $role == 'admin_lab' ? 'lab.admin_new.jenis_lab.index' : '#',
                    'active' => request()->routeIs('*.jenis_lab.*'),
                    'hidden' => $role != 'admin_lab',
                    'badge'  => null,
                ],
                [
                    'name'   => 'Peminjaman',
                    'icon'   => 'bi-clipboard-check-fill',
                    'route'  => $role == 'admin_lab' ? 'lab.admin_new.peminjaman.internal.index' : '#',
                    'active' => request()->routeIs('*.peminjaman.*'),
                    'badge'  => $badge_peminjaman_aktif > 0 ? $badge_peminjaman_aktif : null,
                ],
                [
                    'name'   => 'Laporan Kerusakan',
                    'icon'   => 'bi-exclamation-triangle-fill',
                    'route'  => $role == 'siswa' ? 'siswa.laporan.index' : 'lab.admin_new.kerusakan.index',
                    'active' => request()->routeIs('*.kerusakan.index') || request()->routeIs('*.laporan.index'),
                    'badge'  => $badge_kerusakan_aktif > 0 ? $badge_kerusakan_aktif : null,
                ],
                [
                    'name'   => 'Perbaikan Selesai',
                    'icon'   => 'bi-patch-check-fill',
                    'route'  => $role == 'siswa' ? 'siswa.laporan.selesai' : 'lab.admin_new.kerusakan.selesai',
                    'active' => request()->routeIs('*.kerusakan.selesai') || request()->routeIs('*.laporan.selesai'),
                    'badge'  => null,
                ],
            ];
        }
    @endphp

    <!-- Sidebar -->
    <aside id="sidebar" class="role-{{ $role }}">
        <div class="sidebar-header">
            <a href="/" class="sidebar-brand">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" height="32">
                <div class="sidebar-text">
                    <span style="font-size:1rem; font-weight:700;">SMK Negeri 5</span>
                    @if($role == 'kepala_lab')
                        <span class="d-block mt-1" style="font-size:0.65rem; color: #93C5FD; font-weight:500; letter-spacing:0.5px; text-transform:uppercase;">Kepala Laboratorium</span>
                    @elseif($role == 'admin_lab')
                        <span class="d-block mt-1" style="font-size:0.65rem; color: #6EE7B7; font-weight:500; letter-spacing:0.5px; text-transform:uppercase;">Admin Lab</span>
                    @elseif($role == 'kepala_sekolah')
                        <span class="d-block mt-1" style="font-size:0.65rem; color: #FDE68A; font-weight:500; letter-spacing:0.5px; text-transform:uppercase;">Kepala Sekolah</span>
                    @elseif($role == 'waka_akademik')
                        <span class="d-block mt-1" style="font-size:0.65rem; color: #F9A8D4; font-weight:500; letter-spacing:0.5px; text-transform:uppercase;">Waka Akademik</span>
                    @elseif($role == 'siswa')
                        <span class="d-block mt-1" style="font-size:0.65rem; color: #A5B4FC; font-weight:500; letter-spacing:0.5px; text-transform:uppercase;">Siswa</span>
                    @endif
                </div>
            </a>
        </div>

        @if($role == 'kepala_lab')
        <div class="px-3 py-2 mx-3 mt-2 mb-1 rounded-3" style="background: rgba(37,99,235,0.15); border: 1px solid rgba(37,99,235,0.25);">
            <p class="mb-0 sidebar-text" style="font-size:0.7rem; color:#93C5FD; line-height:1.4;">
                <i class="bi bi-eye-fill me-1"></i>
                Mode <strong>Monitoring & Supervisi</strong>. Input data tidak tersedia.
            </p>
        </div>
        @endif
        
        <ul class="sidebar-menu">
            @foreach($menuItems as $item)
                @if($item['route'] != '#' && !($item['hidden'] ?? false))
                <li class="sidebar-item">
                    <a href="{{ route($item['route'], $item['params'] ?? []) }}" class="sidebar-link {{ $item['active'] ? 'active' : '' }}">
                        <i class="bi {{ $item['icon'] }}"></i>
                        <span class="sidebar-text">{{ $item['name'] }}</span>
                        @if(!empty($item['badge']))
                            <span class="badge bg-danger ms-auto" style="font-size:0.65rem;">{{ $item['badge'] }}</span>
                        @endif
                    </a>
                </li>
                @endif
            @endforeach
        </ul>
    </aside>

    <!-- Main Content -->
    <main id="main-content">
        <!-- Header -->
        <header class="header">
            <div class="d-flex align-items-center gap-3">
                <button id="toggleSidebar" class="btn btn-link text-dark p-0">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <div>
                    @yield('breadcrumb')
                    <h1 class="page-title">{{ $title ?? 'Dashboard' }}</h1>
                </div>
            </div>

            <div class="profile-dropdown dropdown">
                <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <div class="profile-avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="d-none d-md-block text-start">
                        <p class="mb-0 fw-semibold text-dark" style="font-size: 0.85rem; line-height: 1;">{{ auth()->user()->name }}</p>
                        <small class="text-muted" style="font-size: 0.75rem;">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</small>
                    </div>
                    <i class="bi bi-chevron-down ms-2 fs-7 text-muted"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
                    <li><a class="dropdown-item py-2" href="{{ route('sistem_akademik.profile') }}"><i class="bi bi-person me-2"></i> Profil Saya</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item py-2 text-danger"><i class="bi bi-box-arrow-right me-2"></i> Keluar</button>
                        </form>
                    </li>
                </ul>
            </div>
        </header>

        <!-- Page Content -->
        <div class="content-body">
            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="px-4 py-3 border-top bg-white text-center mt-auto">
            <p class="mb-0 text-muted" style="font-size: 0.8rem;">&copy; {{ date('Y') }} SMK Negeri 5 Padang - Sistem Layanan Laboratorium</p>
        </footer>
    </main>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            
            if (window.innerWidth > 991) {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
            } else {
                sidebar.classList.toggle('show');
            }
        });

        // Auto collapse on small screens
        window.addEventListener('resize', function() {
            if (window.innerWidth <= 991) {
                document.getElementById('sidebar').classList.remove('collapsed');
                document.getElementById('main-content').classList.remove('expanded');
            }
        });

        // Flash Message Handling
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: "{{ session('error') }}",
            });
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                text: "{{ $errors->first() }}",
            });
        @endif
    </script>
    @yield('script')
</body>
</html>
