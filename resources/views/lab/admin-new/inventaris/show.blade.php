@extends('lab.layouts.unified', ['title' => 'Detail Inventaris'])

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('lab.admin_new.inventaris.index') }}" class="btn btn-light rounded-circle p-2">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h4 class="fw-bold mb-1 text-dark">Detail Peralatan</h4>
                <p class="text-muted small mb-0">{{ $inventaris->kode_inventaris }} - {{ $inventaris->nama_inventaris }}</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Main Info -->
    <div class="col-lg-8">
        <x-ui.card class="border-0 shadow-sm mb-4">
            <div class="row">
                <div class="col-md-5 mb-4 mb-md-0">
                    @if($inventaris->gambar)
                        <img src="{{ asset('storage/' . $inventaris->gambar) }}" class="img-fluid rounded-4 shadow-sm w-100" alt="{{ $inventaris->nama_inventaris }}">
                    @else
                        <div class="bg-light rounded-4 d-flex align-items-center justify-content-center w-100" style="height: 300px;">
                            <i class="bi bi-camera text-muted fs-1"></i>
                        </div>
                    @endif
                </div>
                <div class="col-md-7">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <x-ui.badge variant="{{ $inventaris->status == 'Tersedia' ? 'success' : 'warning' }}">{{ strtoupper($inventaris->status) }}</x-ui.badge>
                        <span class="text-muted small">Terdaftar: {{ \Carbon\Carbon::parse($inventaris->created_at)->format('d M Y') }}</span>
                    </div>
                    
                    <h3 class="fw-bold text-dark mb-4">{{ $inventaris->nama_inventaris }}</h3>
                    
                    <div class="row g-4 mb-4">
                        <div class="col-6">
                            <label class="text-muted small fw-bold text-uppercase d-block mb-1">Laboratorium</label>
                            <span class="text-dark fw-bold">{{ $inventaris->labor->nama_labor ?? 'N/A' }}</span>
                        </div>
                        <div class="col-6">
                            <label class="text-muted small fw-bold text-uppercase d-block mb-1">Kategori</label>
                            <span class="text-dark fw-bold">{{ $inventaris->kategori ?? 'Umum' }}</span>
                        </div>
                        <div class="col-6">
                            <label class="text-muted small fw-bold text-uppercase d-block mb-1">Jumlah Total</label>
                            <span class="text-dark fw-bold">{{ $inventaris->jumlah }} Unit</span>
                        </div>
                        <div class="col-6">
                            <label class="text-muted small fw-bold text-uppercase d-block mb-1">Kondisi Saat Ini</label>
                            @php $kondisiColor = $inventaris->getTingkatKerusakanColor(); @endphp
                            <span class="text-{{ $kondisiColor }} fw-bold">{{ strtoupper($inventaris->kondisi) }}</span>
                        </div>
                    </div>
                    
                    <div class="pt-4 border-top">
                        <a href="{{ route('lab.admin_new.inventaris.edit', $inventaris->id) }}" class="ui-btn ui-btn-primary px-4 me-2">
                            <i class="bi bi-pencil-square me-2"></i> Edit Data
                        </a>
                        <button class="ui-btn ui-btn-secondary px-4">
                            <i class="bi bi-file-earmark-bar-graph me-2"></i> Cetak Label
                        </button>
                    </div>
                </div>
            </div>
        </x-ui.card>

        <!-- Description & Specs -->
        <div class="row g-4">
            <div class="col-md-6">
                <x-ui.card class="border-0 shadow-sm h-100">
                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-info-circle me-2"></i> Deskripsi</h6>
                    <p class="text-muted small mb-0">{{ $inventaris->deskripsi ?? 'Tidak ada deskripsi tersedia.' }}</p>
                </x-ui.card>
            </div>
            <div class="col-md-6">
                <x-ui.card class="border-0 shadow-sm h-100">
                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-cpu me-2"></i> Spesifikasi</h6>
                    <p class="text-muted small mb-0">{{ $inventaris->spesifikasi ?? 'Tidak ada spesifikasi khusus.' }}</p>
                </x-ui.card>
            </div>
        </div>
    </div>

    <!-- Sidebar Info -->
    <div class="col-lg-4">
        <x-ui.card class="border-0 shadow-sm mb-4">
            <h6 class="fw-bold text-dark mb-4">Informasi Pengadaan</h6>
            <div class="mb-3">
                <label class="text-muted small d-block mb-1">Tahun Perolehan</label>
                <div class="text-dark fw-bold">{{ $inventaris->tahun_perolehan ?? '-' }}</div>
            </div>
            <div class="mb-3">
                <label class="text-muted small d-block mb-1">Sumber Dana</label>
                <div class="text-dark fw-bold">{{ $inventaris->sumber_dana ?? 'Bantuan Sekolah' }}</div>
            </div>
            <div class="mb-0">
                <label class="text-muted small d-block mb-1">Lokasi Penyimpanan</label>
                <div class="text-dark fw-bold">{{ $inventaris->lokasi ?? 'Sesuai Labor' }}</div>
            </div>
        </x-ui.card>

        <x-ui.card class="border-0 shadow-sm">
            <h6 class="fw-bold text-dark mb-4">Status Peminjaman</h6>
            @forelse($inventaris->peminjaman->where('status', 'dipinjam') as $pinjam)
                <div class="p-3 bg-light rounded-4 mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-dark fw-bold small">{{ $pinjam->user->name ?? 'User' }}</span>
                        <x-ui.badge variant="warning">DIPINJAM</x-ui.badge>
                    </div>
                    <div class="text-muted small"><i class="bi bi-clock me-1"></i> Sejak {{ \Carbon\Carbon::parse($pinjam->tanggal_pinjam)->format('d M Y') }}</div>
                </div>
            @empty
                <div class="text-center py-3">
                    <i class="bi bi-check-circle text-success fs-1 mb-2"></i>
                    <p class="text-muted small mb-0">Tersedia untuk dipinjam.</p>
                </div>
            @endforelse
        </x-ui.card>
    </div>
</div>
@endsection
