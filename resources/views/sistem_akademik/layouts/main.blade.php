<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Sistem Akademik' }} - SMK Negeri 5 Padang</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Menggunakan design-system yang sama dengan lab -->
    <link href="{{ asset('assets/css/design-system.css') }}" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
    <style>
        :root {
            --header-height: 70px;
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 80px;
            --primary: #f97316; /* Ubah ke orange style sistem akademik atau pertahankan default? Di gambar adminlab warnanya biru standar. Kita ikuti dark-sidebar sistem baru dengan accent color default. 
                                  Wait, gambar refrensi adminlab temanya gelap dg primer biru. */
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
            background: var(--sidebar-bg); /* Dark sidebar */
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
        
        $menuItems = [
            [
                'name'   => 'Dashboard',
                'icon'   => 'bi-speedometer2',
                'route'  => 'sistem_akademik.dashboard',
                'active' => request()->routeIs('sistem_akademik.dashboard') || request()->routeIs('sistem_akademik.index'),
                'hidden' => false,
            ]
        ];

        if (in_array($role, ['super_admin', 'admin_sa'])) {
            $menuItems = array_merge($menuItems, [
                [
                    'name'   => 'Kelola Berita',
                    'icon'   => 'bi-newspaper',
                    'route'  => 'sistem_akademik.berita.index',
                    'active' => request()->routeIs('sistem_akademik.berita.*'),
                    'hidden' => false,
                ],
                [
                    'name'   => 'Kelola Guru',
                    'icon'   => 'bi-person-workspace',
                    'route'  => 'sistem_akademik.guru.index',
                    'active' => request()->routeIs('sistem_akademik.guru.*'),
                    'hidden' => false,
                ],
                [
                    'name'   => 'Kelola Siswa',
                    'icon'   => 'bi-people',
                    'route'  => 'sistem_akademik.siswa.index',
                    'active' => request()->routeIs('sistem_akademik.siswa.*'),
                    'hidden' => false,
                ],
                [
                    'name'   => 'Kelola Kelas',
                    'icon'   => 'bi-building',
                    'route'  => 'sistem_akademik.kelas.index',
                    'active' => request()->routeIs('sistem_akademik.kelas.*'),
                    'hidden' => false,
                ],
                [
                    'name'   => 'Kelola Jurusan',
                    'icon'   => 'bi-diagram-3',
                    'route'  => 'sistem_akademik.jurusan.index',
                    'active' => request()->routeIs('sistem_akademik.jurusan.*'),
                    'hidden' => false,
                ],
                [
                    'name'   => 'Data Mata Pelajaran',
                    'icon'   => 'bi-book',
                    'route'  => 'sistem_akademik.mapels.index',
                    'active' => request()->routeIs('sistem_akademik.mapels.*'),
                    'hidden' => false,
                ],
                [
                    'name'   => 'Data Ruangan',
                    'icon'   => 'bi-door-open',
                    'route'  => 'sistem_akademik.ruangans.index',
                    'active' => request()->routeIs('sistem_akademik.ruangans.*'),
                    'hidden' => false,
                ],
                [
                    'name'   => 'Pengampu Mapel',
                    'icon'   => 'bi-person-video3',
                    'route'  => 'sistem_akademik.mata_pelajaran.index',
                    'active' => request()->routeIs('sistem_akademik.mata_pelajaran.*'),
                    'hidden' => false,
                ],
                [
                    'name'   => 'Kelola Peminatan',
                    'icon'   => 'bi-cash-coin',
                    'route'  => 'sistem_akademik.peminatan.index',
                    'active' => request()->routeIs('sistem_akademik.peminatan.*'),
                    'hidden' => false,
                ],
                [
                    'name'   => 'Kelola Jadwal',
                    'icon'   => 'bi-calendar-event-fill',
                    'route'  => 'sistem_akademik.course.index',
                    'active' => request()->routeIs('sistem_akademik.course.*'),
                    'hidden' => false,
                ],
            ]);
        } elseif ($role == 'guru') {
            $menuItems = array_merge($menuItems, [
                [
                    'name'   => 'Course Saya',
                    'icon'   => 'bi-journal-text',
                    'route'  => 'sistem_akademik.course.index',
                    'active' => request()->routeIs('sistem_akademik.course.*'),
                    'hidden' => false,
                ],
                [
                    'name'   => 'Mata Pelajaran',
                    'icon'   => 'bi-book',
                    'route'  => 'sistem_akademik.mata_pelajaran.index',
                    'active' => request()->routeIs('sistem_akademik.mata_pelajaran.*'),
                    'hidden' => false,
                ],
                [
                    'name'   => 'Peminatan',
                    'icon'   => 'bi-journal-check',
                    'route'  => 'sistem_akademik.peminatan.index',
                    'active' => request()->routeIs('sistem_akademik.peminatan.*'),
                    'hidden' => false,
                ],
            ]);
        } elseif ($role == 'siswa') {
            $menuItems = array_merge($menuItems, [
                [
                    'name'   => 'Course Saya',
                    'icon'   => 'bi-journal-text',
                    'route'  => 'sistem_akademik.course.index',
                    'active' => request()->routeIs('sistem_akademik.course.*'),
                    'hidden' => false,
                ],
                [
                    'name'   => 'Peminatan Saya',
                    'icon'   => 'bi-cash-coin',
                    'route'  => 'sistem_akademik.peminatan.index',
                    'active' => request()->routeIs('sistem_akademik.peminatan.*'),
                    'hidden' => false,
                ],
            ]);
        }

        // Add Profile link for all roles
        $menuItems[] = [
            'name'   => 'Profil Saya',
            'icon'   => 'bi-person-circle',
            'route'  => 'sistem_akademik.profile',
            'active' => request()->routeIs('sistem_akademik.profile'),
            'hidden' => false,
        ];
    @endphp

    <!-- Sidebar -->
    <aside id="sidebar" class="role-{{ $role }}">
        <div class="sidebar-header">
            <a href="{{ route('sistem_akademik.dashboard') }}" class="sidebar-brand">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" height="32">
                <div class="sidebar-text">
                    <span style="font-size:1rem; font-weight:700;">Sistem Akademik</span>
                    @if(in_array($role, ['super_admin', 'admin_sa']))
                        <span class="d-block mt-1" style="font-size:0.65rem; color: #6EE7B7; font-weight:500; letter-spacing:0.5px; text-transform:uppercase;">Administrator</span>
                    @elseif($role == 'guru')
                        <span class="d-block mt-1" style="font-size:0.65rem; color: #93C5FD; font-weight:500; letter-spacing:0.5px; text-transform:uppercase;">Guru</span>
                    @elseif($role == 'siswa')
                        <span class="d-block mt-1" style="font-size:0.65rem; color: #A5B4FC; font-weight:500; letter-spacing:0.5px; text-transform:uppercase;">Siswa</span>
                    @endif
                </div>
            </a>
        </div>
        
        <ul class="sidebar-menu">
            @foreach($menuItems as $item)
                @if(!($item['hidden'] ?? false))
                <li class="sidebar-item">
                    <a href="{{ route($item['route'], $item['params'] ?? []) }}" class="sidebar-link {{ $item['active'] ? 'active' : '' }}">
                        <i class="bi {{ $item['icon'] }}"></i>
                        <span class="sidebar-text">{{ $item['name'] }}</span>
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
                    <h1 class="page-title">{{ $title ?? 'Sistem Akademik' }}</h1>
                </div>
            </div>

            <div class="profile-dropdown dropdown">
                <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <div class="profile-avatar" style="background:#f97316;">
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
                        <form id="logoutForm" action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="button" onclick="logout()" class="dropdown-item py-2 text-danger"><i class="bi bi-box-arrow-right me-2"></i> Keluar</button>
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
            <p class="mb-0 text-muted" style="font-size: 0.8rem;">&copy; {{ date('Y') }} SMK Negeri 5 Padang - Sistem Akademik</p>
        </footer>
    </main>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

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

        function logout() {
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: 'Logout dari akun',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Logout!'
            }).then(result => {
                if (result.isConfirmed) {
                    document.getElementById('logoutForm').submit();
                }
            });
        }

        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: 'Data tidak dapat dikembalikan',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!'
            }).then(result => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm' + id).submit();
                }
            });
        }

        // Flash Message Handling
        @if(session('success') || session('status') == 'success')
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: "{{ session('success') ?? session('message') }}",
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        @if(session('error') || session('status') == 'error')
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: "{{ session('error') ?? session('message') }}",
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