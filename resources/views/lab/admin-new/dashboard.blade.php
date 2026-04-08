@extends('layouts.modern', ['title' => 'Admin Dashboard'])

@section('content')
<div class="flex flex-col gap-6">
    <!-- Hero Section -->
    <x-ui.card class="bg-gradient-to-br from-blue-600 to-blue-800 text-white border-0 shadow-lg">
        <x-ui.card-content class="p-6 sm:p-8">
            <div class="flex items-center justify-between">
                <div class="space-y-2">
                    <h2 class="text-2xl sm:text-3xl font-bold tracking-tight">Selamat Datang, Admin!</h2>
                    <p class="text-blue-100 max-w-2xl text-sm sm:text-base leading-relaxed">Kelola operasional harian laboratorium SMK Negeri 5 Padang dengan mudah dan profesional melalui akses terpusat.</p>
                </div>
                <div class="hidden md:block opacity-20">
                    <i data-lucide="shield-check" class="w-24 h-24"></i>
                </div>
            </div>
        </x-ui.card-content>
    </x-ui.card>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <x-ui.card class="hover:shadow-md transition-shadow group">
            <x-ui.card-content class="p-6 flex items-center gap-4">
                <div class="p-3 rounded-xl bg-blue-100 text-blue-600 group-hover:scale-110 transition-transform">
                    <i data-lucide="door-open" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-muted-foreground">Laboratorium</p>
                    <h3 class="text-2xl font-bold">{{ $stats['total_laboratorium'] }}</h3>
                </div>
            </x-ui.card-content>
        </x-ui.card>

        <x-ui.card class="hover:shadow-md transition-shadow group">
            <x-ui.card-content class="p-6 flex items-center gap-4">
                <div class="p-3 rounded-xl bg-amber-100 text-amber-600 group-hover:scale-110 transition-transform">
                    <i data-lucide="hourglass" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-muted-foreground">Pinjam Alat Pending</p>
                    <h3 class="text-2xl font-bold">{{ $stats['pinjam_pending'] }}</h3>
                </div>
            </x-ui.card-content>
        </x-ui.card>

        <x-ui.card class="hover:shadow-md transition-shadow group">
            <x-ui.card-content class="p-6 flex items-center gap-4">
                <div class="p-3 rounded-xl bg-red-100 text-red-600 group-hover:scale-110 transition-transform">
                    <i data-lucide="alert-triangle" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-muted-foreground">Inventaris Rusak</p>
                    <h3 class="text-2xl font-bold">{{ $stats['barang_rusak'] }}</h3>
                </div>
            </x-ui.card-content>
        </x-ui.card>

        <x-ui.card class="hover:shadow-md transition-shadow group">
            <x-ui.card-content class="p-6 flex items-center gap-4">
                <div class="p-3 rounded-xl bg-emerald-100 text-emerald-600 group-hover:scale-110 transition-transform">
                    <i data-lucide="check-circle" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-muted-foreground">Alat Tersedia</p>
                    <h3 class="text-2xl font-bold">{{ $stats['alat_tersedia'] }}</h3>
                </div>
            </x-ui.card-content>
        </x-ui.card>
    </div>

    <!-- Quick Actions and Info -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <x-ui.card>
                <x-ui.card-header>
                    <x-ui.card-title>Aksi Cepat Menu</x-ui.card-title>
                </x-ui.card-header>
                <x-ui.card-content>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <a href="{{ route('lab.admin_new.peminjaman.internal.index') }}" class="group block p-6 border rounded-xl text-center hover:border-primary hover:shadow-md transition-all">
                            <div class="mx-auto w-12 h-12 flex items-center justify-center rounded-full bg-blue-50 text-primary group-hover:scale-110 transition-transform mb-3">
                                <i data-lucide="clipboard-check" class="w-6 h-6"></i>
                            </div>
                            <h4 class="font-semibold text-foreground">Peminjaman</h4>
                            <p class="text-sm text-muted-foreground">Kelola Pinjaman</p>
                        </a>

                        <a href="{{ route('lab.admin_new.laboratorium.index') }}" class="group block p-6 border rounded-xl text-center hover:border-cyan-600 hover:shadow-md transition-all">
                            <div class="mx-auto w-12 h-12 flex items-center justify-center rounded-full bg-cyan-50 text-cyan-600 group-hover:scale-110 transition-transform mb-3">
                                <i data-lucide="building" class="w-6 h-6"></i>
                            </div>
                            <h4 class="font-semibold text-foreground">Laboratorium</h4>
                            <p class="text-sm text-muted-foreground">Detail Ruangan</p>
                        </a>

                        <a href="{{ route('lab.admin_new.kerusakan.index') }}" class="group block p-6 border rounded-xl text-center hover:border-red-600 hover:shadow-md transition-all">
                            <div class="mx-auto w-12 h-12 flex items-center justify-center rounded-full bg-red-50 text-red-600 group-hover:scale-110 transition-transform mb-3">
                                <i data-lucide="wrench" class="w-6 h-6"></i>
                            </div>
                            <h4 class="font-semibold text-foreground">Kerusakan</h4>
                            <p class="text-sm text-muted-foreground">Monitor Laporan</p>
                        </a>
                    </div>
                </x-ui.card-content>
            </x-ui.card>
        </div>

        <div class="lg:col-span-1">
            <x-ui.card class="h-full flex flex-col">
                <x-ui.card-header>
                    <x-ui.card-title>Info Lainnya</x-ui.card-title>
                </x-ui.card-header>
                <x-ui.card-content class="flex-1">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between pb-4 border-b">
                            <div class="flex items-center text-muted-foreground">
                                <i data-lucide="package" class="w-4 h-4 mr-2"></i>
                                <span>Total Inventaris</span>
                            </div>
                            <span class="font-semibold">{{ $stats['total_barang'] }}</span>
                        </div>
                        <div class="flex items-center justify-between pb-4 border-b">
                            <div class="flex items-center text-muted-foreground">
                                <i data-lucide="door-closed" class="w-4 h-4 mr-2"></i>
                                <span>Pinjaman Ruangan</span>
                            </div>
                            <x-ui.badge class="bg-amber-100 text-amber-700 hover:bg-amber-100 pointer-events-none border-transparent">
                                {{ $stats['pinjam_ruangan_pending'] }}
                            </x-ui.badge>
                        </div>
                        <div class="flex items-center justify-between pb-4 border-b">
                            <div class="flex items-center text-muted-foreground">
                                <i data-lucide="alert-circle" class="w-4 h-4 mr-2"></i>
                                <span>Kerusakan Aktif</span>
                            </div>
                            <x-ui.badge variant="destructive" class="pointer-events-none">
                                {{ $stats['kerusakan_aktif'] }}
                            </x-ui.badge>
                        </div>
                    </div>
                </x-ui.card-content>
                <x-ui.card-footer>
                    <x-ui.button as="a" href="{{ route('lab.admin_new.master_data.index') }}" variant="secondary" class="w-full">
                        <i data-lucide="settings" class="w-4 h-4 mr-2"></i>
                        Pengaturan Data Statis
                    </x-ui.button>
                </x-ui.card-footer>
            </x-ui.card>
        </div>
    </div>

    <!-- Manual Input Forms -->
    <div>
        <h3 class="text-xl font-bold mb-4 tracking-tight">Input Peminjaman Manual</h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <x-ui.button as="a" href="{{ route('lab.admin_new.manual_input.alat_siswa') }}" class="h-14 font-semibold text-base shadow-sm">
                <i data-lucide="user-plus" class="w-5 h-5 mr-3"></i>
                Pinjam Alat Siswa
            </x-ui.button>

            <x-ui.button as="a" href="{{ route('lab.admin_new.manual_input.alat_guru') }}" variant="secondary" class="h-14 font-semibold text-base shadow-sm border border-border">
                <i data-lucide="user-check" class="w-5 h-5 mr-3"></i>
                Pinjam Alat Guru
            </x-ui.button>

            <x-ui.button as="a" href="{{ route('lab.admin_new.manual_input.ruangan_guru') }}" variant="outline" class="h-14 font-semibold text-base border-amber-200 bg-amber-50 text-amber-700 hover:bg-amber-100 hover:text-amber-800 shadow-sm">
                <i data-lucide="door-open" class="w-5 h-5 mr-3"></i>
                Pinjam Ruangan
            </x-ui.button>
        </div>
    </div>
</div>
@endsection

