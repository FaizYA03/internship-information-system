<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistem Informasi Akademik SMK Negeri 5 Padang">
    <meta name="keywords" content="Akademik, SMK, Padang, Siswa, Guru, Kelas">
    <meta name="author" content="SMK Negeri 5 Padang">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Dashboard' }} - Sistem Informasi Akademik SMK</title>
    <link rel="icon" href="{{ asset('assets/images/logo.png') }}" type="image/x-icon">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="{{ asset('assets/css/sistem-akademik.css') }}" rel="stylesheet">

    @yield('css')

    <script>
        // Configure SweetAlert2 globally
        window.addEventListener('DOMContentLoaded', (event) => {
            if (typeof Swal !== 'undefined') {
                Swal.mixin({
                    confirmButtonColor: '#4ecdc4',
                    cancelButtonColor: '#6c757d'
                });
            }
        });
    </script>


    <style>
        /* Add to your existing styles */
        .swal2-container {
            z-index: 99999 !important;
            /* Much higher than any other element */
        }

        .swal-custom-popup {
            z-index: 99999 !important;
            position: relative;
        }

        /* Override SweetAlert backdrop styles */
        .swal2-backdrop-show {
            z-index: 99990 !important;
        }
    </style>



<body class="{{ session('sidebar_collapsed') ? 'sidebar-collapsed' : '' }}">
    @include('sistem_akademik.partials.navbar')
    @include('sistem_akademik.partials.sidebar')

    <div class="content-wrapper">
        @yield('content')
    </div>

    @include('sistem_akademik.partials.footer')

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Display flash messages
        @if(session('status'))
        Swal.fire({
            title: '{{ session('title') }}',
            text: '{{ session('message') }}',
            icon: '{{ session('status') }}',
            confirmButtonColor: '#4ecdc4'
        });
        @endif

        // Handle sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const body = document.body;

            // Check if sidebar state is saved in localStorage
            const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';

            // Apply initial state without transitions
            if (sidebarCollapsed) {
                // Add classes immediately without transition on page load
                document.documentElement.style.setProperty('--suppress-transitions', 'none');
                sidebar.classList.add('collapsed');
                body.classList.add('sidebar-collapsed');

                // Re-enable transitions after initial state is applied
                setTimeout(() => {
                    document.documentElement.style.setProperty('--suppress-transitions', 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)');
                }, 50);
            }

            // Toggle sidebar on button click
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Toggle classes
                    sidebar.classList.toggle('collapsed');
                    body.classList.toggle('sidebar-collapsed');

                    // On mobile, also toggle the active class
                    if (window.innerWidth <= 768) {
                        sidebar.classList.toggle('active');
                    }

                    // Save state to localStorage
                    localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
                });
            }

            // Add CSS for smoother transitions
            const style = document.createElement('style');
            style.textContent = `
                :root {
                    --suppress-transitions: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                }
                
                .navbar-custom, .sidebar, body, .footer, .toggle-container {
                    transition: var(--suppress-transitions) !important;
                }
            `;
            document.head.appendChild(style);

            // Handle window resize to adapt layout
            window.addEventListener('resize', function() {
                if (window.innerWidth <= 768) {
                    if (sidebar.classList.contains('active')) {
                        document.body.style.overflow = 'hidden';
                    } else {
                        document.body.style.overflow = '';
                    }
                } else {
                    document.body.style.overflow = '';
                }
            });

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (window.innerWidth <= 768 &&
                    !sidebar.contains(event.target) &&
                    !sidebarToggle.contains(event.target) &&
                    sidebar.classList.contains('active')) {
                    sidebar.classList.remove('active');
                    document.body.style.overflow = '';
                }
            });

            // Initialize DataTables with consistent styling
            if ($.fn.DataTable) {
                $('table.table').each(function() {
                    if (!$.fn.DataTable.isDataTable(this)) {
                        $(this).DataTable({
                            responsive: true,
                            language: {
                                search: "Cari:",
                                lengthMenu: "Tampilkan _MENU_ data",
                                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                                infoEmpty: "Tidak ada data yang ditampilkan",
                                infoFiltered: "(difilter dari _MAX_ total data)",
                                paginate: {
                                    first: "Pertama",
                                    last: "Terakhir",
                                    next: "Selanjutnya",
                                    previous: "Sebelumnya"
                                },
                            }
                        });
                    }
                });
            }
        });

        // Confirm delete function
        function confirmDelete(id) {
            Swal.fire({
                title: "Apakah anda yakin?",
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#4ecdc4",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm' + id).submit();
                }
            });
        }
    </script>

    <script>
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

    @yield('script')
</body>

</html>