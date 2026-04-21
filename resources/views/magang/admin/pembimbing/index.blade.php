@extends('magang.layouts.main')

@section('content')
<style>
    .admin-page {
        min-height: 100vh;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        padding: 2rem 0;
    }

    .admin-header {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        border-radius: 20px;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .admin-header h1 {
        font-size: 2.25rem;
        font-weight: 700;
        margin: 0;
    }

    .admin-header p {
        margin: 0.5rem 0 0;
        color: rgba(255,255,255,0.8);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stats-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        border: 1px solid rgba(99,102,241,0.1);
        transition: all 0.3s ease;
    }

    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }

    .stats-icon {
        width: 3.5rem;
        height: 3.5rem;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        font-size: 1.75rem;
    }

    .stats-icon svg {
        width: 32px;
        height: 32px;
        display: block;
    }

    .stats-number {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
    }

    .stats-label {
        font-size: 0.9rem;
        color: #64748b;
        margin: 0.25rem 0 0;
    }

    .data-table-container {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        border: 1px solid rgba(99,102,241,0.1);
    }

    .table-header {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 1.5rem;
    }

    .table-header h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
    }

    .table-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        margin-top: 1rem;
    }

    .control-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .control-label {
        font-size: 0.875rem;
        color: #64748b;
        font-weight: 500;
    }

    .control-input {
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        transition: border-color 0.2s ease;
    }

    .control-input:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
    }

    .modern-table {
        width: 100%;
        border-collapse: collapse;
    }

    .modern-table thead th {
        background: #f1f5f9;
        color: #475569;
        font-weight: 600;
        font-size: 0.875rem;
        text-align: left;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .modern-table tbody tr {
        transition: all 0.2s ease;
        border-bottom: 1px solid #f1f5f9;
    }

    .modern-table tbody tr:hover {
        background-color: #f8fafc;
    }

    .modern-table tbody td {
        padding: 1rem 1.5rem;
        vertical-align: middle;
        color: #374151;
    }

    .student-name {
        font-weight: 600;
        color: #1e293b;
    }

    .position-badge {
        background: #dbeafe;
        color: #1e40af;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .mentor-status {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .mentor-assigned {
        background: #dcfce7;
        color: #166534;
    }

    .mentor-unassigned {
        background: #fef3c7;
        color: #92400e;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
    }

    .btn-action {
        padding: 0.5rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2.75rem;
        height: 2.75rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .btn-action i {
        font-size: 1.1rem;
    }

    .btn-edit {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
    }

    .btn-edit:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
    }

    .btn-delete {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }

    .btn-delete:hover {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.4);
    }

    .table-footer {
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        padding: 1rem 1.5rem;
        color: #64748b;
        font-size: 0.875rem;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #64748b;
    }

    .empty-state-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-state h3 {
        font-size: 1.5rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }
</style>

<div class="admin-page">
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="admin-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1>Kelola Pembimbing Magang</h1>
                    <p>Kelola dan pantau data pembimbing siswa magang dengan mudah dan efisien</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="d-flex justify-content-md-end gap-2">
                        <a href="{{ url('/admin/pembimbing/create') }}" class="btn btn-light rounded-pill px-4 py-2">
                            <i class="fas fa-plus me-2"></i>Tambah Pembimbing
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
<!-- Statistics Cards -->
<div class="stats-grid">

    <!-- TOTAL SISWA -->
    <div class="stats-card">
        <div class="stats-icon bg-primary bg-opacity-10 text-primary">
            <i class="bi bi-people-fill"></i>
        </div>
        <div class="stats-number text-primary">{{ count($magang) }}</div>
        <div class="stats-label">Total Siswa Magang</div>
    </div>

    <!-- SUDAH DIBIMBING -->
    <div class="stats-card">
        <div class="stats-icon bg-success bg-opacity-10 text-success">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <div class="stats-number text-success">
            {{ $magang->where('pembimbing', '!=', null)->count() }}
        </div>
        <div class="stats-label">Sudah Dibimbing</div>
    </div>

    <!-- BELUM DIBIMBING -->
    <div class="stats-card">
        <div class="stats-icon bg-warning bg-opacity-10 text-warning">
            <i class="bi bi-exclamation-circle-fill"></i>
        </div>
        <div class="stats-number text-warning">
            {{ $magang->where('pembimbing', null)->count() }}
        </div>
        <div class="stats-label">Belum Dibimbing</div>
    </div>

    <!-- POSISI MAGANG -->
    <div class="stats-card">
        <div class="stats-icon bg-info bg-opacity-10 text-info">
            <i class="bi bi-briefcase-fill"></i>
        </div>
        <div class="stats-number text-info">
            {{ $magang->unique('opening_id')->count() }}
        </div>
        <div class="stats-label">Posisi Magang</div>
    </div>

</div>

        <!-- Data Table -->
        <div class="data-table-container">
            <div class="table-header">
                <h3>Data Pembimbing Siswa</h3>
                <div class="table-controls">
                    <div class="control-group">
                        <span class="control-label">Tampilkan:</span>
                        <select id="limitSelect" class="control-input">
                            <option value="10">10</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <span class="control-label">data</span>
                    </div>
                    <div class="control-group">
                        <span class="control-label">Cari:</span>
                        <input type="text" id="searchInput" class="control-input" placeholder="Cari nama siswa...">
                    </div>
                </div>
            </div>

            @if($magang->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Belum Ada Data Siswa Magang</h3>
                    <p>Data siswa magang akan muncul di sini setelah ada yang mendaftar.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="modern-table" id="tableData">
                        <thead>
                            <tr>
                                <th style="width: 60px;">No</th>
                                <th>Nama Siswa</th>
                                <th>Posisi Magang</th>
                                <th>Pembimbing</th>
                                <th style="width: 120px; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($magang as $i => $item)
                            <tr>
                                <td>{{ $i+1 }}</td>
                                <td>
                                    <div class="student-name">{{ $item->nama }}</div>
                                </td>
                                <td>
                                    @if(optional($item->opening)->posisi)
                                        <span class="position-badge">{{ optional($item->opening)->posisi }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if(optional(optional($item->pembimbing)->guru)->nama)
                                        <span class="mentor-status mentor-assigned">
                                            <i class="fas fa-check-circle"></i>
                                            {{ optional(optional($item->pembimbing)->guru)->nama }}
                                        </span>
                                    @else
                                        <span class="mentor-status mentor-unassigned">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            Belum ditentukan
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ url('/admin/pembimbing/'.$item->id.'/edit') }}" class="btn-action btn-edit" title="Edit Pembimbing">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <form id="deleteForm{{ $item->id }}" action="/admin/pembimbing/{{ $item->id }}/delete" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete('{{ $item->id }}')" class="btn-action btn-delete" title="Hapus">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <div class="table-footer">
                <span>Menampilkan {{ count($magang) }} data siswa magang</span>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
// Delete confirmation
function confirmDelete(id) {
    Swal.fire({
        title: "Yakin hapus pembimbing?",
        text: "Data pembimbing siswa ini akan dihapus permanen!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#ef4444",
        cancelButtonColor: "#6b7280",
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal"
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deleteForm' + id).submit();
        }
    });
}

// Search functionality
document.getElementById('searchInput').addEventListener('keyup', function() {
    let value = this.value.toLowerCase();
    document.querySelectorAll('#tableData tbody tr').forEach(row => {
        let nama = row.querySelector('.student-name').innerText.toLowerCase();
        row.style.display = nama.includes(value) ? '' : 'none';
    });
});

// Limit functionality
document.getElementById('limitSelect').addEventListener('change', function() {
    let limit = parseInt(this.value);
    let rows = document.querySelectorAll('#tableData tbody tr');

    rows.forEach((row, index) => {
        row.style.display = index < limit ? '' : 'none';
    });
});
</script>
@endpush