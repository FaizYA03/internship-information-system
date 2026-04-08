<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Sistem Sekolah'))</title>
    <link rel="stylesheet" href="{{ mix('css/modern.css') }}">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen bg-background text-foreground font-sans antialiased">
    <div class="relative flex min-h-screen" x-data="{ sidebarOpen: false }">
        <!-- Sidebar Component -->
        <x-layout.sidebar />
        
        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col md:ml-64 transition-all duration-300 min-w-0">
            <!-- Header Component -->
            <x-layout.header />
            
            <!-- Main Content -->
            <main class="flex-1 p-4 md:p-6 lg:p-8 overflow-x-hidden">
                <div class="mx-auto max-w-7xl">
                    @yield('content')
                </div>
            </main>
        </div>
        
        <x-ui.toast />
    </div>
    
    <script src="{{ mix('js/modern.js') }}"></script>
    <script>
      lucide.createIcons();
    </script>
    @stack('scripts')
</body>
</html>
