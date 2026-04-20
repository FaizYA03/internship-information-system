<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistem Informasi Magang SMK Negeri 5 Padang">
    <meta name="keywords" content="Magang, SMK, Padang, Siswa, Perusahaan">
    <meta name="author" content="SMK Negeri 5 Padang">
    
    <title>{{ $title ?? 'Dashboard' }} - Sistem Informasi Magang SMK</title>
    <link rel="icon" href="{{ asset('assets/images/logo.png') }}" type="image/x-icon">
    
    <!-- Styles -->
     <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/magang.css') }}" rel="stylesheet">
    
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
</head>

<body class="{{ session('sidebar_collapsed') ? 'sidebar-collapsed' : '' }}">
    @include('magang.partials.navbar')

    @include('magang.partials.sidebar')

    <div class="content-wrapper">
        @yield('content')
    </div>

    @include('magang.partials.footer')

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

    @yield('script')
    @stack('script')
</body>

</html>