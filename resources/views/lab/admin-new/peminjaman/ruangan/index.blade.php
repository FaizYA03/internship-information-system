@extends('lab.layouts.unified', ['title' => 'Peminjaman Ruangan'])

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-small">
        <li class="breadcrumb-item"><a href="{{ route('lab.admin_new.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Peminjaman Ruangan</li>
    </ol>
</nav>
@endsection

@section('css')
<style>
    .borrow-card {
        border: none;
        border-radius: 15px;
        transition: all 0.3s;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 20px;
    }
    .borrow-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    .user-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        background: linear-gradient(45deg, #667eea, #764ba2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 20px;
    }
    .status-badge-large {
        padding: 8px 16px;
        font-size: 0.85rem;
        border-radius: 20px;
    }
    .filter-card {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 20px;
    }
</style>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1">Peminjaman Ruangan Laboratorium</h4>
                <p class="text-muted mb-0">Kelola persetujuan peminjaman ruangan laboratorium</p>
            </div>
            <a href="{{ route('lab.admin_new.manual_input.ruangan_guru') }}" class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-plus-circle me-2"></i>Input Manual
            </a>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Filter Card -->
<div class="filter-card">
    <div class="row g-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label fw-semibold">Filter Status</label>
            <select class="form-select" id="statusFilter">
                <option value="all">Semua Status</option>
                <option value="pending">Pending</option>
                <option value="approved">Disetujui</option>
                <option value="rejected">Ditolak</option>
                <option value="completed">Selesai</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold">Laboratorium</label>
            <select class="form-select" id="labFilter">
                <option value="all">Semua Lab</option>
                @foreach(\App\Models\Labor::orderBy('nama_labor')->get() as $lab)
                    <option value="{{ $lab->id }}">{{ $lab->nama_labor }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold">Tanggal</label>
            <input type="date" class="form-control" id="dateFilter">
        </div>
        <div class="col-md-3">
            <button class="btn btn-outline-secondary w-100" onclick="resetFilters()">
                <i class="bi bi-arrow-clockwise me-2"></i>Reset Filter
            </button>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="card border-0 bg-warning bg-opacity-10">
            <div class="card-body text-center">
                <h3 class="fw-bold text-warning mb-0">{{ $peminjaman->where('status', 'pending')->count() }}</h3>
                <small class="text-muted">Menunggu Verifikasi</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card border-0 bg-primary bg-opacity-10">
            <div class="card-body text-center">
                <h3 class="fw-bold text-primary mb-0">{{ $peminjaman->where('status', 'approved')->count() }}</h3>
                <small class="text-muted">Disetujui</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card border-0 bg-success bg-opacity-10">
            <div class="card-body text-center">
                <h3 class="fw-bold text-success mb-0">{{ $peminjaman->where('status', 'completed')->count() }}</h3>
                <small class="text-muted">Selesai</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card border-0 bg-danger bg-opacity-10">
            <div class="card-body text-center">
                <h3 class="fw-bold text-danger mb-0">{{ $peminjaman->where('status', 'rejected')->count() }}</h3>
                <small class="text-muted">Ditolak</small>
            </div>
        </div>
    </div>
</div>

<!-- Borrowing Cards Grid -->
<div class="row g-4" id="borrowingGrid">
    @forelse($peminjaman as $item)
        <div class="col-lg-6 col-md-12 borrow-item" 
             data-status="{{ $item->status }}" 
             data-lab="{{ $item->labor_id }}"
             data-date="{{ $item->tanggal }}">
            <div class="card borrow-card h-100">
                <div class="card-body p-4">
                    <!-- Header with User Info -->
                    <div class="d-flex align-items-start mb-3">
                        <div class="user-avatar me-3">
                            @if($item->user->foto)
                                <img src="{{ asset('storage/' . $item->user->foto) }}" alt="{{ $item->user->nama }}" class="user-avatar">
                            @else
                                {{ strtoupper(substr($item->user->nama ?? $item->nama ?? 'U', 0, 1)) }}
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-1">{{ $item->user->nama ?? $item->nama }}</h6>
                            <small class="text-muted">
                                <i class="bi bi-person-badge me-1"></i>{{ ucfirst($item->user->role ?? 'Guru') }}
                            </small>
                        </div>
                        <div>
                            @if($item->status == 'pending')
                                <span class="badge status-badge-large bg-warning text-dark">
                                    <i class="bi bi-hourglass-split me-1"></i>Menunggu
                                </span>
                            @elseif($item->status == 'approved')
                                <span class="badge status-badge-large bg-primary">
                                    <i class="bi bi-check-circle me-1"></i>Disetujui
                                </span>
                            @elseif($item->status == 'rejected')
                                <span class="badge status-badge-large bg-danger">
                                    <i class="bi bi-x-circle me-1"></i>Ditolak
                                </span>
                            @else
                                <span class="badge status-badge-large bg-success">
                                    <i class="bi bi-check-all me-1"></i>Selesai
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Borrowing Details -->
                    <div class="border-top border-bottom py-3 mb-3">
                        <div class="row g-3">
                            <div class="col-6">
                                <small class="text-muted d-block mb-1">
                                    <i class="bi bi-door-open me-1"></i>Ruangan
                                </small>
                                <strong>{{ $item->labor->nama_labor ?? 'N/A' }}</strong>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block mb-1">
                                    <i class="bi bi-calendar-event me-1"></i>Tanggal
                                </small>
                                <strong>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</strong>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block mb-1">
                                    <i class="bi bi-clock me-1"></i>Waktu
                                </small>
                                <strong>{{ $item->waktu }}</strong>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block mb-1">
                                    <i class="bi bi-info-circle me-1"></i>Keperluan
                                </small>
                                <strong class="text-truncate d-block" title="{{ $item->keperluan }}">
                                    {{ Str::limit($item->keperluan, 20) }}
                                </strong>
                            </div>
                        </div>
                    </div>

                    <!-- Keperluan Full -->
                    @if($item->keperluan)
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Keterangan Lengkap:</small>
                            <p class="mb-0 small">{{ $item->keperluan }}</p>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="d-flex gap-2">
                        @if($item->status == 'pending')
                            <form action="{{ route('lab.admin_new.peminjaman.ruangan.approve', $item->id) }}" method="POST" class="flex-fill">
                                @csrf
                                <button type="submit" class="btn btn-success w-100 rounded-pill">
                                    <i class="bi bi-check-lg me-1"></i>Setujui
                                </button>
                            </form>
                            <button type="button" class="btn btn-outline-danger flex-fill rounded-pill" 
                                    data-bs-toggle="modal" data-bs-target="#rejectModal{{ $item->id }}">
                                <i class="bi bi-x-lg me-1"></i>Tolak
                            </button>
                        @elseif($item->status == 'approved')
                            <div class="alert alert-info mb-0 w-100 small">
                                <i class="bi bi-info-circle me-2"></i>
                                Disetujui oleh {{ $item->approver->nama ?? 'Admin' }} pada {{ \Carbon\Carbon::parse($item->approved_at)->format('d M Y H:i') }}
                            </div>
                        @elseif($item->status == 'rejected')
                            <div class="alert alert-danger mb-0 w-100 small">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <strong>Alasan:</strong> {{ $item->alasan_penolakan ?? 'Tidak ada keterangan' }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Reject Modal -->
        @if($item->status == 'pending')
            <div class="modal fade" id="rejectModal{{ $item->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 rounded-4">
                        <form action="{{ route('lab.admin_new.peminjaman.ruangan.reject', $item->id) }}" method="POST">
                            @csrf
                            <div class="modal-header border-0">
                                <h5 class="modal-title fw-bold">
                                    <i class="bi bi-x-circle text-danger me-2"></i>Tolak Peminjaman
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    Peminjaman oleh <strong>{{ $item->user->nama ?? $item->nama }}</strong> akan ditolak.
                                </div>
                                <label class="form-label fw-semibold">Alasan Penolakan *</label>
                                <textarea name="reason" class="form-control" rows="3" 
                                          placeholder="Contoh: Ruangan sudah dibooking untuk kegiatan lain" 
                                          required></textarea>
                                <small class="text-muted">Peminjam akan menerima notifikasi penolakan beserta alasan.</small>
                            </div>
                            <div class="modal-footer border-0">
                                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-danger rounded-pill px-4">
                                    <i class="bi bi-x-lg me-2"></i>Tolak Peminjaman
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size: 4rem; color: #ddd;"></i>
                <h5 class="text-muted mt-3">Belum ada peminjaman ruangan</h5>
                <p class="text-muted">Peminjaman baru akan muncul di sini</p>
            </div>
        </div>
    @endforelse
</div>

@endsection

@section('scripts')
<script>
// Filter functionality
document.getElementById('statusFilter').addEventListener('change', filterBorrowings);
document.getElementById('labFilter').addEventListener('change', filterBorrowings);
document.getElementById('dateFilter').addEventListener('change', filterBorrowings);

function filterBorrowings() {
    const statusFilter = document.getElementById('statusFilter').value;
    const labFilter = document.getElementById('labFilter').value;
    const dateFilter = document.getElementById('dateFilter').value;
    
    const items = document.querySelectorAll('.borrow-item');
    
    items.forEach(item => {
        const status = item.dataset.status;
        const lab = item.dataset.lab;
        const date = item.dataset.date;
        
        let showItem = true;
        
        if (statusFilter !== 'all' && status !== statusFilter) {
            showItem = false;
        }
        
        if (labFilter !== 'all' && lab !== labFilter) {
            showItem = false;
        }
        
        if (dateFilter && date !== dateFilter) {
            showItem = false;
        }
        
        item.style.display = showItem ? 'block' : 'none';
    });
}

function resetFilters() {
    document.getElementById('statusFilter').value = 'all';
    document.getElementById('labFilter').value = 'all';
    document.getElementById('dateFilter').value = '';
    filterBorrowings();
}

// Auto-refresh every 30 seconds
setInterval(() => {
    location.reload();
}, 30000);
</script>
@endsection
