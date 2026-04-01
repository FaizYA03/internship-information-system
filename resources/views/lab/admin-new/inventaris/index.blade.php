@extends('lab.layouts.unified', ['title' => 'Daftar Inventaris'])

@section('css')
<style>
    .filter-active-badge {
        animation: pulse 1.5s infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
    .result-info {
        background: linear-gradient(135deg, #EFF6FF, #F0FDF4);
        border-left: 4px solid #2563EB;
        border-radius: 0 10px 10px 0;
        padding: 10px 16px;
        font-size: 0.85rem;
    }
    .no-data-hint {
        background: #FFFBEB;
        border: 1px dashed #F59E0B;
        border-radius: 12px;
        padding: 24px;
        text-align: center;
    }
    .category-navbar {
        display: flex;
        gap: 10px;
        overflow-x: auto;
        padding-bottom: 15px;
        scrollbar-width: thin;
        scrollbar-color: #2563EB #f1f1f1;
    }
    .category-navbar::-webkit-scrollbar {
        height: 6px;
    }
    .category-navbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    .category-navbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    .category-item {
        white-space: nowrap;
        padding: 8px 20px;
        background: #fff;
        border: 1.5px solid #e2e8f0;
        border-radius: 30px;
        color: #64748b;
        font-weight: 600;
        font-size: 0.85rem;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
    .category-item:hover {
        border-color: #2563EB;
        color: #2563EB;
        background: #eff6ff;
        transform: translateY(-2px);
    }
    .category-item.active {
        background: #2563EB;
        border-color: #2563EB;
        color: #fff;
        box-shadow: 0 4px 12px rgba(37,99,235,0.25);
    }
</style>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1 text-dark">Daftar Inventaris Alat</h4>
                <p class="text-muted small mb-0">Kelola peralatan laboratorium, pantau kondisi, dan lokasi penyimpanan.</p>
            </div>
            <a href="{{ route('lab.admin_new.inventaris.create') }}" class="ui-btn ui-btn-primary">
                <i class="bi bi-plus-lg"></i> Tambah Alat
            </a>
        </div>
    </div>
</div>

{{-- Filter Card --}}
<div class="row mb-3">
    <div class="col-12">
        <x-ui.card class="border-0 shadow-sm">
            <form action="{{ route('lab.admin_new.inventaris.index') }}" method="GET" class="row g-3" id="filterForm">
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted">CARI ALAT</label>
                    <input type="text" name="search" class="form-control" placeholder="Nama, kode, atau kategori..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted">LABORATORIUM</label>
                    <select name="labor_id" class="form-select" id="filterLaborId">
                        <option value="">Semua Lab</option>
                        @foreach($laboratories as $lab)
                            <option value="{{ $lab->id }}" {{ request('labor_id') == $lab->id ? 'selected' : '' }}>
                                {{ $lab->nama_labor }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted">KONDISI</label>
                    <select name="kondisi" class="form-select">
                        <option value="">Semua Kondisi</option>
                        <option value="Sangat Baik" {{ request('kondisi') == 'Sangat Baik' ? 'selected' : '' }}>Sangat Baik</option>
                        <option value="Baik" {{ request('kondisi') == 'Baik' ? 'selected' : '' }}>Baik</option>
                        <option value="Rusak Ringan" {{ request('kondisi') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="Rusak Sedang" {{ request('kondisi') == 'Rusak Sedang' ? 'selected' : '' }}>Rusak Sedang</option>
                        <option value="Rusak Berat" {{ request('kondisi') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted">STATUS</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="Tersedia" {{ request('status') == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="Tidak Tersedia" {{ request('status') == 'Tidak Tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
                        <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="ui-btn ui-btn-primary flex-grow-1">
                        <i class="bi bi-funnel me-1"></i>Filter
                    </button>
                    <a href="{{ route('lab.admin_new.inventaris.index') }}" class="ui-btn ui-btn-secondary" title="Reset Filter">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>
            </form>
        </x-ui.card>
    </div>
</div>

{{-- Category Navbar --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="category-navbar">
            <a href="{{ request()->fullUrlWithQuery(['kategori' => null]) }}" 
               class="category-item {{ !request('kategori') ? 'active' : '' }}">
                <i class="bi bi-grid-fill me-1"></i> Semua Kategori
            </a>
            @foreach($categories as $cat)
                <a href="{{ request()->fullUrlWithQuery(['kategori' => $cat]) }}" 
                   class="category-item {{ request('kategori') == $cat ? 'active' : '' }}">
                    {{ $cat }}
                </a>
            @endforeach
        </div>
    </div>
</div>

{{-- Info Bar: hasil filter --}}
<div class="row mb-3">
    <div class="col-12">
        @if($hasFilter)
        <div class="result-info d-flex align-items-center justify-content-between">
            <div>
                <i class="bi bi-funnel-fill me-2 text-primary"></i>
                <strong>{{ $inventaris->total() }}</strong> dari <strong>{{ $totalInventaris }}</strong> alat ditemukan
                @if(request('labor_id'))
                    @php $selectedLab = $laboratories->find(request('labor_id')); @endphp
                    — Lab: <strong>{{ $selectedLab->nama_labor ?? 'ID '.request('labor_id') }}</strong>
                @endif
                @if(request('kondisi'))
                    — Kondisi: <strong>{{ request('kondisi') }}</strong>
                @endif
                @if(request('status'))
                    — Status: <strong>{{ request('status') }}</strong>
                @endif
                @if(request('kategori'))
                    — Kategori: <strong>{{ request('kategori') }}</strong>
                @endif
                @if(request('search'))
                    — Kata kunci: <strong>"{{ request('search') }}"</strong>
                @endif
            </div>
            <a href="{{ route('lab.admin_new.inventaris.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                <i class="bi bi-x me-1"></i>Reset
            </a>
        </div>
        @else
        <div class="text-muted small">
            <i class="bi bi-grid me-1"></i>Menampilkan <strong>{{ $inventaris->total() }}</strong> alat
        </div>
        @endif
    </div>
</div>

{{-- Card Grid --}}
<div class="row g-4">
    @forelse($inventaris as $item)
        <div class="col-md-6 col-lg-4">
            <x-ui.card :hover="true" class="h-100 border-0 shadow-sm overflow-hidden p-0">
                <div class="position-relative">
                    @if($item->gambar)
                        <img src="{{ asset('storage/' . $item->gambar) }}" class="w-100" style="height: 180px; object-fit: cover;" alt="{{ $item->nama_inventaris }}">
                    @else
                        <div class="w-100 bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                            <i class="bi bi-camera text-muted fs-1"></i>
                        </div>
                    @endif
                    <div class="position-absolute top-0 end-0 m-3">
                        @php
                            $statusVal = strtolower(str_replace(' ', '_', $item->status ?? ''));
                            $badgeVariant = $statusVal === 'tersedia' ? 'success' : ($statusVal === 'dipinjam' ? 'warning' : 'danger');
                        @endphp
                        <x-ui.badge variant="{{ $badgeVariant }}">
                            {{ strtoupper($item->status ?? 'N/A') }}
                        </x-ui.badge>
                    </div>
                </div>
                
                <div class="p-4">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <p class="text-primary small fw-bold mb-1">{{ $item->kode_inventaris ?? '-' }}</p>
                            <h5 class="fw-bold text-dark mb-0">{{ $item->nama_inventaris }}</h5>
                        </div>
                    </div>
                    
                    <p class="text-muted small mb-4">
                        <i class="bi bi-building me-1"></i>
                        @if($item->labor)
                            {{ $item->labor->nama_labor }}
                        @else
                            <span class="text-warning fst-italic">Lab belum ditentukan</span>
                        @endif
                        <span class="mx-2">|</span>
                        <i class="bi bi-geo-alt me-1"></i> {{ $item->lokasi ?? 'N/A' }}
                    </p>

                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="p-2 rounded-3 bg-light text-center" style="min-width: 60px;">
                            <small class="text-muted d-block small">JUMLAH</small>
                            <span class="fw-bold text-dark">{{ $item->jumlah }}</span>
                        </div>
                        <div class="flex-grow-1">
                            <small class="text-muted d-block small mb-1">KONDISI</small>
                            @php $kondisiColor = $item->getTingkatKerusakanColor(); @endphp
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-{{ $kondisiColor }}" style="width: {{ $item->kondisi == 'Sangat Baik' ? '100%' : ($item->kondisi == 'Baik' ? '80%' : ($item->kondisi == 'Rusak Ringan' ? '60%' : ($item->kondisi == 'Rusak Sedang' ? '40%' : '20%'))) }}"></div>
                            </div>
                            <small class="text-{{ $kondisiColor }} fw-bold" style="font-size: 0.65rem;">{{ strtoupper($item->kondisi ?? 'N/A') }}</small>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('lab.admin_new.inventaris.show', $item->id) }}" class="ui-btn ui-btn-primary flex-grow-1 text-center">Detail</a>
                        <a href="{{ route('lab.admin_new.inventaris.edit', $item->id) }}" class="ui-btn ui-btn-secondary"><i class="bi bi-pencil"></i></a>
                    </div>
                </div>
            </x-ui.card>
        </div>
    @empty
        <div class="col-12">
            @if($hasFilter && request('labor_id'))
                {{-- Pesan khusus ketika filter lab tidak menghasilkan data --}}
                <div class="no-data-hint">
                    <i class="bi bi-exclamation-circle text-warning fs-2 mb-3 d-block"></i>
                    <h5 class="fw-bold">Tidak ada alat di laboratorium ini</h5>
                    <p class="text-muted mb-3">
                        Kemungkinan data inventaris belum dikaitkan ke laboratorium tersebut.<br>
                        Silakan edit data inventaris dan pastikan kolom <strong>Laboratorium</strong> telah diisi.
                    </p>
                    <div class="d-flex gap-2 justify-content-center">
                        <a href="{{ route('lab.admin_new.inventaris.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-clockwise me-1"></i>Lihat Semua
                        </a>
                        <a href="{{ route('lab.admin_new.inventaris.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus me-1"></i>Tambah Alat
                        </a>
                    </div>
                </div>
            @else
                <x-ui.empty-state icon="bi-tools" title="Alat tidak ditemukan" description="Coba ubah filter atau tambah alat baru." />
            @endif
        </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $inventaris->links() }}
</div>
@endsection
