<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? 'Sistem Laboratorium' }} | SMK Negeri 5 Padang</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
    
    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/labor.css') }}">
    
    <!-- Custom CSS for this page -->
    @yield('css')
</head>

<body class="{{ session('sidebar_collapsed') ? 'sidebar-collapsed' : '' }}">
    @php
        $role_prefix = Auth::check() && Auth::user()->role == 'guru' ? 'guru' : 'siswa';
    @endphp
    @include('siswa.partials.navbar')
    @include('siswa.partials.sidebar')
    
    <div class="content-wrapper">
        @yield('content')
        @include('siswa.partials.footer')
    </div>
    
    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
    
    <!-- Main JS -->
    <script src="{{ asset('assets/js/labor.js') }}"></script>
    
    <!-- Flash Message Handler -->
    @if(session('status'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: "{{ session('title') }}",
                text: "{{ session('message') }}",
                icon: "{{ session('status') }}",
                confirmButtonColor: "#4ecdc4"
            });
        });
    </script>
    @endif
    
    <!-- Custom JS for this page -->
    @yield('script')
</body>
</html>