@extends('lab.layouts.unified', ['title' => 'Data Inventaris Laboratorium'])

@section('content')

{{-- ============================================================
     HEADER
     ============================================================ --}}
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('lab.kepala_sekolah.dashboard') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
        <i class="bi bi-arrow-left me-1"></i> Dashboard
    </a>
    <div>
        <h5 class="fw-bold mb-0">Data Inventaris Laboratorium</h5>
        <p class="small text-muted mb-0">Pantau seluruh alat dan bahan di semua laboratorium — tampilan read-only.</p>
    </div>
</div>

{{-- ============================================================
     SUMMARY MINI-CARDS
     ============================================================ --}}
<div class="row g-3 mb-4">
    <div class="col-4">
        <div class="card border-0 rounded-4 shadow-sm text-center py-3">
            <p class="small text-muted mb-1">Total Alat</p>
            <h4 class="fw-bold text-primary mb-0">{{ number_format($totalAlat) }}</h4>
        </div>
    </div>
    <div class="col-4">
        <div class="card border-0 rounded-4 shadow-sm text-center py-3">
            <p class="small text-muted mb-1">Total Bahan</p>
            <h4 class="fw-bold text-success mb-0">{{ number_format($totalBahan) }}</h4>
        </div>
    </div>
    <div class="col-4">
        <div class="card border-0 rounded-4 shadow-sm text-center py-3">
            <p class="small text-muted mb-1">Rusak</p>
            <h4 class="fw-bold {{ $totalRusak > 0 ? 'text-danger' : 'text-muted' }} mb-0">{{ $totalRusak }}</h4>
        </div>
    </div>
</div>

{{-- ============================================================
     FILTER JENIS (TAB-STYLE)
     ============================================================ --}}
<div class="d-flex flex-wrap gap-2 mb-3">
    @php $activeJenis = request('jenis', 'semua'); @endphp
    @foreach(['semua' => 'Semua', 'Alat' => '🔧 Alat', 'Bahan' => '🧪 Bahan'] as $val => $label)
    <a href="{{ request()->fullUrlWithQuery(['jenis' => $val, 'page' => null]) }}"
       class="btn btn-sm rounded-pill px-3 {{ $activeJenis === $val ? 'btn-primary' : 'btn-outline-secondary' }}">
        {{ $label }}
    </a>
    @endforeach
</div>

{{-- ============================================================
     FILTER FORM
     ============================================================ --}}
<div class="card border-0 rounded-4 shadow-sm mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('lab.kepala_sekolah.inventaris') }}" class="row g-2 align-items-end">
            {{-- Pertahankan filter jenis dari tab --}}
            @if(request('jenis') && request('jenis') !== 'semua')
            <input type="hidden" name="jenis" value="{{ request('jenis') }}">
            @endif

            {{-- CARI ALAT --}}
            <div class="col-12 col-md-4">
                <label class="form-label small fw-medium mb-1">
                    <i class="bi bi-search me-1 text-muted"></i>
                    {{ request('jenis') === 'Bahan' ? 'Cari Bahan' : 'Cari Alat' }}
                </label>
                <input type="text" name="search" class="form-control form-control-sm rounded-3"
                    placeholder="Nama, kode, atau kategori…"
                    value="{{ request('search') }}">
            </div>

            {{-- LABORATORIUM --}}
            <div class="col-6 col-md-3">
                <label class="form-label small fw-medium mb-1">Laboratorium</label>
                <select name="labor_id" class="form-select form-select-sm rounded-3">
                    <option value="semua" {{ request('labor_id', 'semua') === 'semua' ? 'selected' : '' }}>Semua Lab</option>
                    @foreach($laborList as $lab)
                    <option value="{{ $lab->id }}" {{ request('labor_id') == $lab->id ? 'selected' : '' }}>
                        {{ $lab->nama_labor }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- KONDISI --}}
            <div class="col-6 col-md-2">
                <label class="form-label small fw-medium mb-1">Kondisi</label>
                <select name="kondisi" class="form-select form-select-sm rounded-3">
                    <option value="semua" {{ request('kondisi', 'semua') === 'semua' ? 'selected' : '' }}>Semua Kondisi</option>
                    @foreach(['Sangat Baik', 'Baik', 'Rusak Ringan', 'Rusak Sedang', 'Rusak Berat'] as $k)
                    <option value="{{ $k }}" {{ request('kondisi') === $k ? 'selected' : '' }}>{{ $k }}</option>
                    @endforeach
                </select>
            </div>

            {{-- STATUS --}}
            <div class="col-6 col-md-2">
                <label class="form-label small fw-medium mb-1">Status</label>
                <select name="status" class="form-select form-select-sm rounded-3">
                    <option value="semua" {{ request('status', 'semua') === 'semua' ? 'selected' : '' }}>Semua Status</option>
                    @foreach(['tersedia', 'dipinjam', 'dalam_perbaikan', 'tidak_aktif'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                        {{ ucwords(str_replace('_', ' ', $s)) }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- TOMBOL --}}
            <div class="col-6 col-md-1 d-flex gap-1">
                <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3 w-100">
                    <i class="bi bi-funnel"></i>
                </button>
                @if(request()->anyFilled(['search','labor_id','kondisi','status']))
                <a href="{{ route('lab.kepala_sekolah.inventaris', request()->only('jenis')) }}"
                   class="btn btn-outline-secondary btn-sm rounded-pill px-2">
                    <i class="bi bi-x"></i>
                </a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- ============================================================
     HASIL & TABEL
     ============================================================ --}}
<div class="card border-0 rounded-4 shadow-sm">
    <div class="card-header bg-white border-0 pt-3 pb-0 px-3 d-flex justify-content-between align-items-center">
        <span class="small text-muted fw-medium">
            Menampilkan <strong>{{ $inventaris->total() }}</strong> item
            @if(request('search'))
                &nbsp;&bull; pencarian: <em>"{{ request('search') }}"</em>
            @endif
        </span>
        <span class="badge rounded-pill" style="background:#EFF6FF;color:#1D4ED8;">
            Halaman {{ $inventaris->currentPage() }} / {{ $inventaris->lastPage() }}
        </span>
    </div>

    <div class="card-body p-0">
        @if($inventaris->count())
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 small">
                <thead style="background:#F8FAFC;">
                    <tr>
                        <th class="border-0 ps-3 py-3 text-muted fw-medium">Nama & Kode</th>
                        <th class="border-0 py-3 text-muted fw-medium">Jenis</th>
                        <th class="border-0 py-3 text-muted fw-medium">Kategori</th>
                        <th class="border-0 py-3 text-muted fw-medium">Laboratorium</th>
                        <th class="border-0 py-3 text-muted fw-medium text-center">Jumlah</th>
                        <th class="border-0 py-3 text-muted fw-medium">Kondisi</th>
                        <th class="border-0 py-3 text-muted fw-medium">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inventaris as $item)
                    @php
                        // Kondisi badge
                        $kondisiStyle = match($item->kondisi) {
                            'Sangat Baik' => ['bg' => '#DCFCE7', 'text' => '#166534'],
                            'Baik'        => ['bg' => '#DBEAFE', 'text' => '#1E40AF'],
                            'Rusak Ringan'=> ['bg' => '#FEF9C3', 'text' => '#854D0E'],
                            'Rusak Sedang'=> ['bg' => '#FFEDD5', 'text' => '#9A3412'],
                            'Rusak Berat' => ['bg' => '#FEE2E2', 'text' => '#991B1B'],
                            default       => ['bg' => '#F3F4F6', 'text' => '#374151'],
                        };
                        // Status badge
                        $statusStyle = match($item->status) {
                            'tersedia'        => ['bg' => '#DCFCE7', 'text' => '#166534'],
                            'dipinjam'        => ['bg' => '#FEF3C7', 'text' => '#92400E'],
                            'dalam_perbaikan' => ['bg' => '#FEE2E2', 'text' => '#991B1B'],
                            default           => ['bg' => '#F3F4F6', 'text' => '#374151'],
                        };
                    @endphp
                    <tr>
                        <td class="ps-3 py-2">
                            <div class="fw-semibold text-dark">{{ $item->nama_inventaris }}</div>
                            <div class="text-muted" style="font-size:.72rem;">{{ $item->kode_inventaris }}</div>
                        </td>
                        <td>
                            <span class="badge rounded-pill px-2"
                                  style="background:{{ $item->jenis === 'Alat' ? '#EFF6FF' : '#F0FDF4' }};
                                         color:{{ $item->jenis === 'Alat' ? '#1D4ED8' : '#166534' }};">
                                {{ $item->jenis }}
                            </span>
                        </td>
                        <td class="text-muted">{{ $item->kategori ?? '—' }}</td>
                        <td>
                            @if($item->labor)
                            <span class="small">{{ $item->labor->nama_labor }}</span>
                            @else
                            <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-center fw-semibold">{{ $item->jumlah }}</td>
                        <td>
                            <span class="badge rounded-pill px-2"
                                  style="background:{{ $kondisiStyle['bg'] }};color:{{ $kondisiStyle['text'] }};">
                                {{ $item->kondisi ?? '—' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge rounded-pill px-2"
                                  style="background:{{ $statusStyle['bg'] }};color:{{ $statusStyle['text'] }};">
                                {{ ucwords(str_replace('_', ' ', $item->status ?? 'tersedia')) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5 text-muted">
            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
            <p class="mb-0">Tidak ada data inventaris yang ditemukan.</p>
            @if(request()->anyFilled(['search','labor_id','kondisi','status','jenis']))
            <a href="{{ route('lab.kepala_sekolah.inventaris') }}" class="btn btn-sm btn-outline-primary rounded-pill mt-3">
                <i class="bi bi-x-circle me-1"></i> Hapus semua filter
            </a>
            @endif
        </div>
        @endif
    </div>

    @if($inventaris->hasPages())
    <div class="card-footer bg-white border-0 px-3 pb-3 pt-0">
        {{ $inventaris->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

@endsection
