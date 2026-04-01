@extends('lab.layouts.unified', ['title' => 'Stok Bahan Habis Pakai'])

@section('css')
<style>
    .category-navbar {
        display: flex;
        gap: 10px;
        overflow-x: auto;
        padding-bottom: 15px;
        scrollbar-width: thin;
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
        padding: 8px 18px;
        background: #fff;
        border: 1.5px solid #e2e8f0;
        border-radius: 25px;
        color: #64748b;
        font-weight: 600;
        font-size: 0.8rem;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .category-item:hover {
        border-color: #2563EB;
        color: #2563EB;
        background: #eff6ff;
    }
    .category-item.active {
        background: #2563EB;
        border-color: #2563EB;
        color: #fff;
    }
    .bahan-card {
        transition: transform 0.2s;
    }
    .bahan-card:hover {
        transform: translateY(-5px);
    }
</style>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1 text-dark">Stok Bahan Habis Pakai</h4>
                <p class="text-muted small mb-0">Kelola ketersediaan bahan praktikum dan pendukung lainnya.</p>
            </div>
            <a href="{{ route('lab.admin_new.inventaris.create') }}?jenis=Bahan" class="ui-btn ui-btn-primary">
                <i class="bi bi-plus-lg"></i> Tambah Bahan
            </a>
        </div>
    </div>
</div>

{{-- Category Navbar --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="category-navbar">
            <a href="{{ request()->fullUrlWithQuery(['kategori' => null]) }}" 
               class="category-item {{ !request('kategori') ? 'active' : '' }}">
                Semua Kategori
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

<div class="row g-4">
    @forelse($bahan as $item)
        <div class="col-md-4">
            <x-ui.card class="border-0 shadow-sm bahan-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <small class="text-primary fw-bold text-uppercase" style="font-size: 0.65rem;">{{ $item->kategori }}</small>
                        <h6 class="fw-bold text-dark mb-0 mt-1">{{ $item->nama_inventaris }}</h6>
                    </div>
                    <x-ui.badge variant="{{ $item->jumlah <= $item->stok_minimum ? 'danger' : 'success' }}">
                        {{ $item->jumlah }} UNIT
                    </x-ui.badge>
                </div>
                <p class="text-muted small mb-3"><i class="bi bi-building me-1"></i> {{ $item->labor->nama_labor ?? 'N/A' }}</p>
                
                @if($item->jumlah <= $item->stok_minimum)
                    <div class="small text-danger fw-bold mb-3"><i class="bi bi-exclamation-circle me-1"></i> Perlu Re-stok!</div>
                @endif

                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-outline-primary flex-grow-1" onclick="updateStock({{ $item->id }}, 'add')">
                        <i class="bi bi-plus-circle me-1"></i> Tambah
                    </button>
                    <button class="btn btn-sm btn-outline-secondary flex-grow-1" onclick="updateStock({{ $item->id }}, 'reduce')">
                        <i class="bi bi-dash-circle me-1"></i> Kurangi
                    </button>
                </div>
            </x-ui.card>
        </div>
    @empty
        <div class="col-12">
            <x-ui.empty-state icon="bi-box-seam" title="Bahan tidak ditemukan" description="Coba ubah filter atau tambah bahan baru." />
        </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $bahan->links() }}
</div>

@endsection

@section('script')
<script>
    function updateStock(id, action) {
        const jumlah = prompt(`Masukkan jumlah yang ingin di${action === 'add' ? 'tambah' : 'kurangi'}:`, "1");
        if (jumlah && !isNaN(jumlah)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/lab/admin-new/bahan/${id}/stock`;
            
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            
            const method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'PATCH';
            
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = action;
            
            const jumlahInput = document.createElement('input');
            jumlahInput.type = 'hidden';
            jumlahInput.name = 'jumlah';
            jumlahInput.value = jumlah;
            
            form.appendChild(csrf);
            form.appendChild(method);
            form.appendChild(actionInput);
            form.appendChild(jumlahInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endsection
