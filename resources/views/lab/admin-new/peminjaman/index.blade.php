@extends('layouts.modern', ['title' => 'Kelola Peminjaman'])

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-bold tracking-tight text-foreground">Kelola Peminjaman</h2>
        <p class="text-muted-foreground">Verifikasi dan monitor peminjaman alat serta ruangan laboratorium.</p>
    </div>
    <x-ui.dropdown-menu align="end">
        <x-slot name="trigger">
            <x-ui.button>
                <i data-lucide="plus-circle" class="mr-2 h-4 w-4"></i> Catat Peminjaman
            </x-ui.button>
        </x-slot>
        
        <!-- Internal -->
        <div class="px-2 py-1.5 text-xs font-semibold text-muted-foreground border-b mb-1">
            Internal (Siswa/Guru)
        </div>
        <a href="{{ route('lab.admin_new.manual_input.alat_siswa') }}" class="relative flex cursor-default select-none items-center rounded-sm px-2 py-1.5 text-sm outline-none transition-colors hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground">
            <i data-lucide="user" class="mr-2 h-4 w-4"></i> Alat - Siswa
        </a>
        <a href="{{ route('lab.admin_new.manual_input.alat_guru') }}" class="relative flex cursor-default select-none items-center rounded-sm px-2 py-1.5 text-sm outline-none transition-colors hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground">
            <i data-lucide="briefcase" class="mr-2 h-4 w-4"></i> Alat - Guru
        </a>
        <a href="{{ route('lab.admin_new.manual_input.ruangan_guru') }}" class="relative flex cursor-default select-none items-center rounded-sm px-2 py-1.5 text-sm outline-none transition-colors hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground">
            <i data-lucide="door-open" class="mr-2 h-4 w-4"></i> Ruangan - Guru
        </a>
        
        <x-ui.dropdown-menu.separator />
        
        <!-- External -->
        <div class="px-2 py-1.5 text-xs font-semibold text-muted-foreground border-b mb-1">
            Eksternal (Orang Luar)
        </div>
        <a href="{{ route('lab.admin_new.manual_input.alat_eksternal') }}" class="relative flex cursor-default select-none items-center rounded-sm px-2 py-1.5 text-sm outline-none transition-colors hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground">
            <i data-lucide="wrench" class="mr-2 h-4 w-4"></i> Alat - Eksternal
        </a>
        <a href="{{ route('lab.admin_new.manual_input.ruangan_eksternal') }}" class="relative flex cursor-default select-none items-center rounded-sm px-2 py-1.5 text-sm outline-none transition-colors hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground">
            <i data-lucide="building" class="mr-2 h-4 w-4"></i> Ruangan - Eksternal
        </a>
    </x-ui.dropdown-menu>
</div>

<x-ui.tabs defaultTab="alat">
    <div class="mb-4 overflow-x-auto pb-2">
        <x-ui.tabs-list class="w-max sm:w-auto">
            <x-ui.tabs-trigger value="alat">
                <i data-lucide="tool" class="w-4 h-4 mr-2"></i> Peminjaman Alat
                <x-ui.badge variant="secondary" class="ml-2">{{ $peminjaman->count() }}</x-ui.badge>
            </x-ui.tabs-trigger>
            <x-ui.tabs-trigger value="ruangan">
                <i data-lucide="building" class="w-4 h-4 mr-2"></i> Peminjaman Ruangan
                <x-ui.badge variant="secondary" class="ml-2">{{ $peminjamanRuangan->count() }}</x-ui.badge>
            </x-ui.tabs-trigger>
            <x-ui.tabs-trigger value="eksternal">
                <i data-lucide="external-link" class="w-4 h-4 mr-2"></i> Alat Eksternal
                <x-ui.badge variant="secondary" class="ml-2">{{ $peminjamanEksternal->count() }}</x-ui.badge>
            </x-ui.tabs-trigger>
            <x-ui.tabs-trigger value="ruangan_eksternal">
                <i data-lucide="building-2" class="w-4 h-4 mr-2"></i> Ruangan Eksternal
                <x-ui.badge variant="secondary" class="ml-2">{{ $peminjamanRuanganEksternal->count() }}</x-ui.badge>
            </x-ui.tabs-trigger>
        </x-ui.tabs-list>
    </div>

    <!-- Peminjaman Alat Tab -->
    <x-ui.tabs-content value="alat">
        <x-ui.card>
            <div class="overflow-x-auto">
                <x-ui.table>
                    <x-ui.table-header>
                        <x-ui.table-row>
                            <x-ui.table-head>Peminjam</x-ui.table-head>
                            <x-ui.table-head>Alat</x-ui.table-head>
                            <x-ui.table-head>Jumlah</x-ui.table-head>
                            <x-ui.table-head>Peminjaman</x-ui.table-head>
                            <x-ui.table-head>Pengembalian</x-ui.table-head>
                            <x-ui.table-head>Status</x-ui.table-head>
                            <x-ui.table-head class="text-center">Aksi</x-ui.table-head>
                        </x-ui.table-row>
                    </x-ui.table-header>
                    <x-ui.table-body>
                        @forelse($peminjaman as $item)
                        <x-ui.table-row>
                            <x-ui.table-cell>
                                <div class="font-medium text-foreground">{{ $item->user->nama ?? 'N/A' }}</div>
                                @php
                                    $role    = $item->user->role ?? '';
                                    $jurusan = null;
                                    $kelas   = null;
                                    if ($role === 'siswa') {
                                        $jurusan = $item->user->siswa->jurusan ?? null;
                                        $kelas   = $item->user->siswa->kelas ?? null;
                                    } elseif ($role === 'guru') {
                                        $jurusan = $item->user->guru->jurusan?->nama_jurusan ?? null;
                                    }
                                @endphp
                                <x-ui.badge variant="secondary" class="text-[0.65rem] mt-1">{{ ucfirst($role) }}</x-ui.badge>
                                @if($jurusan)
                                    <div class="text-[0.7rem] text-muted-foreground mt-1 flex items-center"><i data-lucide="graduation-cap" class="mr-1 w-3 h-3"></i>{{ $jurusan }}</div>
                                @endif
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                <div class="font-medium text-foreground">{{ $item->inventaris->nama_inventaris ?? 'N/A' }}</div>
                                <div class="text-xs text-muted-foreground">{{ $item->inventaris->labor->nama_labor ?? '' }}</div>
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                <span class="font-medium">{{ $item->jumlah }}</span> <span class="text-muted-foreground text-xs">Unit</span>
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                <div class="text-foreground text-sm flex items-center"><i data-lucide="calendar" class="w-3 h-3 mr-1"></i> {{ $item->tanggal_pinjam }}</div>
                                <div class="text-muted-foreground text-xs font-mono flex items-center mt-1"><i data-lucide="clock" class="w-3 h-3 mr-1"></i> {{ $item->jam_pinjam }}</div>
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                @if($item->status == 'returned')
                                    <div class="text-foreground text-sm flex items-center"><i data-lucide="calendar-check" class="w-3 h-3 mr-1"></i> {{ $item->tanggal_kembali }}</div>
                                    <div class="text-muted-foreground text-xs font-mono flex items-center mt-1"><i data-lucide="clock" class="w-3 h-3 mr-1"></i> {{ $item->jam_kembali ?? '-' }}</div>
                                @else
                                    <div class="text-xs italic text-muted-foreground">Belum dikembalikan</div>
                                @endif
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                @php
                                    $statusVariant = 'secondary';
                                    if($item->status == 'pending') $statusVariant = 'warning';
                                    if($item->status == 'approved') $statusVariant = 'info';
                                    if($item->status == 'returned') $statusVariant = 'success';
                                    if($item->status == 'rejected') $statusVariant = 'destructive';
                                @endphp
                                <x-ui.badge variant="{{ $statusVariant }}">
                                    {{ $item->status == 'pending' ? 'MENUNGGU VERIFIKASI' : ($item->status == 'approved' ? 'SEDANG DIPINJAM' : ($item->status == 'returned' ? 'SUDAH KEMBALI' : 'DITOLAK')) }}
                                </x-ui.badge>
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                <div class="flex items-center justify-center gap-2">
                                    @if($item->status == 'pending')
                                        <form action="{{ route('lab.admin_new.peminjaman.internal.approve', $item->id) }}" method="POST">
                                            @csrf
                                            <x-ui.button type="submit" size="icon" variant="default" class="h-8 w-8 bg-blue-600 hover:bg-blue-700" title="Setujui">
                                                <i data-lucide="check" class="h-4 w-4"></i>
                                            </x-ui.button>
                                        </form>
                                        <x-ui.button variant="destructive" size="icon" class="h-8 w-8 pointer-events-auto" onclick="window.dispatchEvent(new CustomEvent('open-dialog', { detail: 'reject-modal-{{ $item->id }}' }))" title="Tolak">
                                            <i data-lucide="x" class="h-4 w-4"></i>
                                        </x-ui.button>
                                    @elseif($item->status == 'approved')
                                        <x-ui.button variant="secondary" size="icon" class="h-8 w-8 bg-cyan-100 hover:bg-cyan-200 text-cyan-700 pointer-events-auto" onclick="window.dispatchEvent(new CustomEvent('open-dialog', { detail: 'return-modal-{{ $item->id }}' }))" title="Terima Kembali">
                                            <i data-lucide="corner-down-left" class="h-4 w-4"></i>
                                        </x-ui.button>
                                    @endif
                                    
                                    <x-ui.button as="a" href="{{ route('lab.admin_new.peminjaman.alat.edit', $item->id) }}" variant="outline" size="icon" class="h-8 w-8 border-amber-200 bg-amber-50 hover:bg-amber-100 text-amber-600" title="Edit">
                                        <i data-lucide="pencil" class="h-4 w-4"></i>
                                    </x-ui.button>
                                    
                                    <form action="{{ route('lab.admin_new.peminjaman.alat.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <x-ui.button type="submit" variant="destructive" size="icon" class="h-8 w-8" title="Hapus">
                                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                                        </x-ui.button>
                                    </form>
                                </div>
                            </x-ui.table-cell>
                        </x-ui.table-row>
                        @empty
                        <x-ui.table-row>
                            <x-ui.table-cell colspan="7" class="h-24 text-center">
                                <span class="text-sm text-muted-foreground">Tidak ada peminjaman alat internal.</span>
                            </x-ui.table-cell>
                        </x-ui.table-row>
                        @endforelse
                    </x-ui.table-body>
                </x-ui.table>
            </div>
        </x-ui.card>
    </x-ui.tabs-content>

    <!-- Peminjaman Ruangan Tab -->
    <x-ui.tabs-content value="ruangan">
        <x-ui.card>
            <div class="overflow-x-auto">
                <x-ui.table>
                    <x-ui.table-header>
                        <x-ui.table-row>
                            <x-ui.table-head>Peminjam</x-ui.table-head>
                            <x-ui.table-head>Ruangan</x-ui.table-head>
                            <x-ui.table-head>Keperluan</x-ui.table-head>
                            <x-ui.table-head>Waktu</x-ui.table-head>
                            <x-ui.table-head>Status</x-ui.table-head>
                            <x-ui.table-head class="text-center">Aksi</x-ui.table-head>
                        </x-ui.table-row>
                    </x-ui.table-header>
                    <x-ui.table-body>
                        @forelse($peminjamanRuangan as $item)
                        <x-ui.table-row>
                            <x-ui.table-cell>
                                <div class="font-medium text-foreground">{{ $item->user->nama ?? $item->nama ?? 'N/A' }}</div>
                                @php
                                    $roleR    = $item->user->role ?? '';
                                    if ($roleR === 'siswa') {
                                        $jurusanR = $item->user->siswa->jurusan ?? null;
                                    } elseif ($roleR === 'guru') {
                                        $jurusanR = $item->user->guru->jurusan?->nama_jurusan ?? null;
                                    }
                                @endphp
                                <x-ui.badge variant="secondary" class="text-[0.65rem] mt-1">{{ ucfirst($roleR ?: 'Eksternal') }}</x-ui.badge>
                                @if(!empty($jurusanR))
                                    <div class="text-[0.7rem] text-muted-foreground mt-1 flex items-center"><i data-lucide="graduation-cap" class="mr-1 w-3 h-3"></i>{{ $jurusanR }}</div>
                                @endif
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                <div class="font-medium text-foreground">{{ $item->labor->nama_labor ?? 'N/A' }}</div>
                                <div class="text-xs text-muted-foreground">Kapasitas: {{ $item->labor->kapasitas ?? 30 }} Siswa</div>
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                <span class="text-sm">{{ $item->keperluan ?? '-' }}</span>
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                <div class="text-foreground text-sm flex items-center"><i data-lucide="calendar" class="w-3 h-3 mr-1"></i> {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</div>
                                <div class="text-muted-foreground text-xs font-mono flex items-center mt-1"><i data-lucide="clock" class="w-3 h-3 mr-1"></i> {{ $item->waktu ?? 'N/A' }}</div>
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                @php
                                    $statusVariant = 'secondary';
                                    if($item->status == 'pending') $statusVariant = 'warning';
                                    if($item->status == 'approved') $statusVariant = 'success';
                                    if($item->status == 'completed') $statusVariant = 'secondary';
                                    if($item->status == 'rejected') $statusVariant = 'destructive';
                                @endphp
                                <x-ui.badge variant="{{ $statusVariant }}">
                                    {{ strtoupper($item->status) }}
                                </x-ui.badge>
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                <div class="flex items-center justify-center gap-2">
                                    @if($item->status == 'pending')
                                        <form action="{{ route('lab.admin_new.peminjaman.ruangan.approve', $item->id) }}" method="POST">
                                            @csrf
                                            <x-ui.button type="submit" size="icon" variant="default" class="h-8 w-8 bg-blue-600 hover:bg-blue-700" title="Setujui">
                                                <i data-lucide="check" class="h-4 w-4"></i>
                                            </x-ui.button>
                                        </form>
                                        <x-ui.button variant="destructive" size="icon" class="h-8 w-8 pointer-events-auto" onclick="window.dispatchEvent(new CustomEvent('open-dialog', { detail: 'reject-ruangan-{{ $item->id }}' }))" title="Tolak">
                                            <i data-lucide="x" class="h-4 w-4"></i>
                                        </x-ui.button>
                                    @endif
                                    
                                    <x-ui.button as="a" href="{{ route('lab.admin_new.peminjaman.ruangan.edit', $item->id) }}" variant="outline" size="icon" class="h-8 w-8 border-amber-200 bg-amber-50 hover:bg-amber-100 text-amber-600" title="Edit">
                                        <i data-lucide="pencil" class="h-4 w-4"></i>
                                    </x-ui.button>
                                    
                                    <form action="{{ route('lab.admin_new.peminjaman.ruangan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <x-ui.button type="submit" variant="destructive" size="icon" class="h-8 w-8" title="Hapus">
                                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                                        </x-ui.button>
                                    </form>
                                </div>
                            </x-ui.table-cell>
                        </x-ui.table-row>
                        @empty
                        <x-ui.table-row>
                            <x-ui.table-cell colspan="6" class="h-24 text-center">
                                <span class="text-sm text-muted-foreground">Tidak ada peminjaman ruangan.</span>
                            </x-ui.table-cell>
                        </x-ui.table-row>
                        @endforelse
                    </x-ui.table-body>
                </x-ui.table>
            </div>
        </x-ui.card>
    </x-ui.tabs-content>

    <!-- Eksternal Alat -->
    <x-ui.tabs-content value="eksternal">
        <x-ui.card>
            <div class="overflow-x-auto">
                <x-ui.table>
                    <x-ui.table-header>
                        <x-ui.table-row>
                            <x-ui.table-head>Peminjam</x-ui.table-head>
                            <x-ui.table-head>Barang</x-ui.table-head>
                            <x-ui.table-head>Tanggal Pinjam</x-ui.table-head>
                            <x-ui.table-head>Status</x-ui.table-head>
                            <x-ui.table-head class="text-center">Aksi</x-ui.table-head>
                        </x-ui.table-row>
                    </x-ui.table-header>
                    <x-ui.table-body>
                        @forelse($peminjamanEksternal as $p)
                        <x-ui.table-row>
                            <x-ui.table-cell>
                                <div class="font-medium text-foreground">{{ $p->nama_peminjam }}</div>
                                <div class="text-xs text-muted-foreground">{{ $p->instansi }}</div>
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                <div class="font-medium text-foreground">{{ $p->inventaris->nama_inventaris ?? 'N/A' }}</div>
                                <span class="text-sm font-medium">{{ $p->jumlah }}</span> <span class="text-xs text-muted-foreground">Unit</span>
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                <div class="text-foreground text-sm flex items-center"><i data-lucide="calendar" class="w-3 h-3 mr-1"></i> {{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d M Y') }}</div>
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                @php
                                    $extStatus = 'secondary';
                                    if($p->status == 'pending') $extStatus = 'warning';
                                    if($p->status == 'recommended') $extStatus = 'info';
                                    if($p->status == 'approved' || $p->status == 'aktif') $extStatus = 'success';
                                    if($p->status == 'selesai') $extStatus = 'secondary';
                                    if($p->status == 'rejected') $extStatus = 'destructive';
                                @endphp
                                <x-ui.badge variant="{{ $extStatus }}">{{ strtoupper($p->status == 'aktif' ? 'SEDANG DIPINJAM' : $p->status) }}</x-ui.badge>
                            </x-ui.table-cell>
                            <x-ui.table-cell class="text-center">
                                <x-ui.button as="a" href="{{ route('lab.admin_new.eksternal.index') }}" variant="outline" size="icon" class="rounded-full shadow-sm">
                                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                </x-ui.button>
                            </x-ui.table-cell>
                        </x-ui.table-row>
                        @empty
                        <x-ui.table-row>
                            <x-ui.table-cell colspan="5" class="h-24 text-center">
                                <span class="text-sm text-muted-foreground">Tidak ada peminjaman alat eksternal.</span>
                            </x-ui.table-cell>
                        </x-ui.table-row>
                        @endforelse
                    </x-ui.table-body>
                </x-ui.table>
            </div>
        </x-ui.card>
    </x-ui.tabs-content>

    <!-- Eksternal Ruangan -->
    <x-ui.tabs-content value="ruangan_eksternal">
        <x-ui.card>
            <div class="overflow-x-auto">
                <x-ui.table>
                    <x-ui.table-header>
                        <x-ui.table-row>
                            <x-ui.table-head>Peminjam</x-ui.table-head>
                            <x-ui.table-head>Ruangan</x-ui.table-head>
                            <x-ui.table-head>Keperluan</x-ui.table-head>
                            <x-ui.table-head>Waktu</x-ui.table-head>
                            <x-ui.table-head>Status</x-ui.table-head>
                            <x-ui.table-head class="text-center">Aksi</x-ui.table-head>
                        </x-ui.table-row>
                    </x-ui.table-header>
                    <x-ui.table-body>
                        @forelse($peminjamanRuanganEksternal as $item)
                        <x-ui.table-row>
                            <x-ui.table-cell>
                                <div class="font-medium text-foreground">{{ $item->nama ?? 'N/A' }}</div>
                                <x-ui.badge variant="secondary" class="text-[0.65rem] mt-1">Eksternal</x-ui.badge>
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                <div class="font-medium text-foreground">{{ $item->labor->nama_labor ?? 'N/A' }}</div>
                                <div class="text-xs text-muted-foreground">Kapasitas: {{ $item->labor->kapasitas ?? 30 }} Siswa</div>
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                <span class="text-sm">{{ $item->keperluan ?? '-' }}</span>
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                <div class="text-foreground text-sm flex items-center"><i data-lucide="calendar" class="w-3 h-3 mr-1"></i> {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</div>
                                <div class="text-muted-foreground text-xs font-mono flex items-center mt-1"><i data-lucide="clock" class="w-3 h-3 mr-1"></i> {{ $item->waktu ?? 'N/A' }}</div>
                            </x-ui.table-cell>
                            <x-ui.table-cell>
                                @php
                                    $statusVariant = 'secondary';
                                    if($item->status == 'pending') $statusVariant = 'warning';
                                    if($item->status == 'approved') $statusVariant = 'success';
                                    if($item->status == 'completed') $statusVariant = 'secondary';
                                    if($item->status == 'rejected') $statusVariant = 'destructive';
                                @endphp
                                <x-ui.badge variant="{{ $statusVariant }}">
                                    {{ strtoupper($item->status) }}
                                </x-ui.badge>
                            </x-ui.table-cell>
                            <x-ui.table-cell class="text-center">
                                <div class="flex items-center justify-center gap-2">
                                    @if($item->status == 'pending')
                                        <form action="{{ route('lab.admin_new.peminjaman.ruangan.approve', $item->id) }}" method="POST">
                                            @csrf
                                            <x-ui.button type="submit" size="icon" variant="default" class="h-8 w-8 bg-blue-600 hover:bg-blue-700" title="Setujui">
                                                <i data-lucide="check" class="h-4 w-4"></i>
                                            </x-ui.button>
                                        </form>
                                        <x-ui.button variant="destructive" size="icon" class="h-8 w-8 pointer-events-auto" onclick="window.dispatchEvent(new CustomEvent('open-dialog', { detail: 'reject-ruex-modal-{{ $item->id }}' }))" title="Tolak">
                                            <i data-lucide="x" class="h-4 w-4"></i>
                                        </x-ui.button>
                                    @endif
                                    
                                    <x-ui.button as="a" href="{{ route('lab.admin_new.peminjaman.ruangan.edit', $item->id) }}" variant="outline" size="icon" class="h-8 w-8 border-amber-200 bg-amber-50 hover:bg-amber-100 text-amber-600" title="Edit">
                                        <i data-lucide="pencil" class="h-4 w-4"></i>
                                    </x-ui.button>
                                    
                                    <form action="{{ route('lab.admin_new.peminjaman.ruangan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <x-ui.button type="submit" variant="destructive" size="icon" class="h-8 w-8" title="Hapus">
                                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                                        </x-ui.button>
                                    </form>
                                </div>
                            </x-ui.table-cell>
                        </x-ui.table-row>
                        @empty
                        <x-ui.table-row>
                            <x-ui.table-cell colspan="6" class="h-24 text-center">
                                <span class="text-sm text-muted-foreground">Tidak ada peminjaman ruangan eksternal.</span>
                            </x-ui.table-cell>
                        </x-ui.table-row>
                        @endforelse
                    </x-ui.table-body>
                </x-ui.table>
            </div>
        </x-ui.card>
    </x-ui.tabs-content>
</x-ui.tabs>

<!-- Render Dialogs outside Tabs Content logic -->
@foreach($peminjaman as $item)
    @if($item->status == 'pending')
        <x-ui.dialog id="reject-modal-{{ $item->id }}" title="Tolak Peminjaman" description="Pastikan untuk mengisi alasan penolakan agar peminjam dapat memahaminya.">
            <form action="{{ route('lab.admin_new.peminjaman.internal.reject', $item->id) }}" method="POST" class="space-y-4">
                @csrf
                <div class="space-y-2">
                    <label class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Alasan Penolakan</label>
                    <textarea name="reason" class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" rows="4" placeholder="Contoh: Stok tidak mencukupi atau alat sedang dalam perbaikan..." required></textarea>
                </div>
                <div class="flex justify-end gap-3 mt-4">
                    <x-ui.button type="button" variant="outline" onclick="window.dispatchEvent(new CustomEvent('close-dialog', { detail: 'reject-modal-{{ $item->id }}' }))">Batal</x-ui.button>
                    <x-ui.button type="submit" variant="destructive">Tolak Sekarang</x-ui.button>
                </div>
            </form>
        </x-ui.dialog>
    @elseif($item->status == 'approved')
        <x-ui.dialog id="return-modal-{{ $item->id }}" title="Proses Pengembalian" description="Silakan isi kondisi akhir barang setelah dikembalikan.">
            <form action="{{ route('lab.admin_new.peminjaman.internal.return', $item->id) }}" method="POST" class="space-y-4">
                @csrf
                <div class="space-y-2">
                    <label class="text-sm font-medium leading-none">Kondisi Akhir Alat</label>
                    <select name="kondisi_akhir" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" required>
                        <option value="Sangat Baik">Sangat Baik</option>
                        <option value="Baik" selected>Baik</option>
                        <option value="Rusak Ringan">Rusak Ringan</option>
                        <option value="Rusak Sedang">Rusak Sedang</option>
                        <option value="Rusak Berat">Rusak Berat</option>
                        <option value="Hilang">Hilang</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium leading-none">Catatan Tambahan (Opsional)</label>
                    <textarea name="catatan" class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" rows="3" placeholder="Contoh: Barang sudah dibersihkan atau ada sedikit baret..."></textarea>
                </div>
                <div class="flex justify-end gap-3 mt-4">
                    <x-ui.button type="button" variant="outline" onclick="window.dispatchEvent(new CustomEvent('close-dialog', { detail: 'return-modal-{{ $item->id }}' }))">Batal</x-ui.button>
                    <x-ui.button type="submit" variant="default" class="bg-blue-600 hover:bg-blue-700">Selesaikan Pengembalian</x-ui.button>
                </div>
            </form>
        </x-ui.dialog>
    @endif
@endforeach

@foreach($peminjamanRuangan as $item)
    @if($item->status == 'pending')
        <x-ui.dialog id="reject-ruangan-{{ $item->id }}" title="Tolak Peminjaman Ruangan" description="Pastikan untuk mengisi alasan penolakan agar peminjam dapat memahaminya.">
            <form action="{{ route('lab.admin_new.peminjaman.ruangan.reject', $item->id) }}" method="POST" class="space-y-4">
                @csrf
                <div class="space-y-2">
                    <label class="text-sm font-medium leading-none">Alasan Penolakan</label>
                    <textarea name="reason" class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" rows="4" placeholder="Contoh: Ruangan digunakan untuk rapat jurusan..." required></textarea>
                </div>
                <div class="flex justify-end gap-3 mt-4">
                    <x-ui.button type="button" variant="outline" onclick="window.dispatchEvent(new CustomEvent('close-dialog', { detail: 'reject-ruangan-{{ $item->id }}' }))">Batal</x-ui.button>
                    <x-ui.button type="submit" variant="destructive">Tolak Sekarang</x-ui.button>
                </div>
            </form>
        </x-ui.dialog>
    @endif
@endforeach

@foreach($peminjamanRuanganEksternal as $item)
    @if($item->status == 'pending')
        <x-ui.dialog id="reject-ruex-modal-{{ $item->id }}" title="Tolak Peminjaman Ruangan Eksternal" description="Masukan alasan penolakan ruangan.">
            <form action="{{ route('lab.admin_new.peminjaman.ruangan.reject', $item->id) }}" method="POST" class="space-y-4">
                @csrf
                <div class="space-y-2">
                    <label class="text-sm font-medium leading-none">Alasan Penolakan</label>
                    <textarea name="reason" class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" rows="4" placeholder="Contoh: Ruangan sudah digunakan atau sedang perbaikan..." required></textarea>
                </div>
                <div class="flex justify-end gap-3 mt-4">
                    <x-ui.button type="button" variant="outline" onclick="window.dispatchEvent(new CustomEvent('close-dialog', { detail: 'reject-ruex-modal-{{ $item->id }}' }))">Batal</x-ui.button>
                    <x-ui.button type="submit" variant="destructive">Tolak Sekarang</x-ui.button>
                </div>
            </form>
        </x-ui.dialog>
    @endif
@endforeach

@endsection
