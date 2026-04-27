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
        font-size: 0.9rem;
    }

    .student-name {
        font-weight: 600;
        color: #1e293b;
    }

    .badge-status {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
    }
    
    .badge-status.Menunggu { background: #fef3c7; color: #92400e; }
    .badge-status.Disetujui.Admin { background: #dcfce7; color: #166534; }
    .badge-status.Disetujui { background: #d0f0fd; color: #0369a1; }
    .badge-status.Ditolak { background: #fee2e2; color: #991b1b; }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn-action {
        padding: 0.5rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2.25rem;
        height: 2.25rem;
    }

    .btn-edit {
        background: #e0f2fe;
        color: #0284c7;
    }

    .btn-edit:hover {
        background: #bae6fd;
        color: #0369a1;
    }

    .btn-delete {
        background: #fee2e2;
        color: #dc2626;
    }

    .btn-delete:hover {
        background: #fecaca;
        color: #b91b1b;
    }
</style>

<div class="admin-page">
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="admin-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1>{{ $header }}</h1>
                    <p>Kelola seluruh data magang siswa, tempat, dan pembimbing/supervisor secara komprehensif.</p>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="data-table-container">
            <div class="table-header">
                <h3>Daftar Peserta Magang</h3>
                <div class="table-controls">
                    <div class="control-group">
                        <span class="control-label">Cari:</span>
                        <input type="text" id="searchInput" class="control-input" placeholder="Cari nama/perusahaan...">
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="modern-table" id="tableData">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Data Siswa</th>
                            <th>Tempat Magang</th>
                            <th>Durasi</th>
                            <th>Pembimbing / Supervisor</th>
                            <th>Tugas Singkat</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $i => $item)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td>
                                <div class="student-name">{{ $item->nama }}</div>
                                <div class="text-muted" style="font-size: 0.8rem;">
                                    {{ $item->user->email ?? '-' }}<br>
                                    {{ $item->no_hp ?? '-' }}
                                </div>
                            </td>
                            <td>
                                <strong>{{ optional($item->perusahaan)->nama_perusahaan ?? 'Siswa Mengajukan Mandiri' }}</strong>
                                <div style="font-size: 0.8rem; color: #64748b;">
                                    Posisi: {{ optional($item->opening)->posisi ?? '-' }}
                                </div>
                            </td>
                            <td>
                                <div style="font-size: 0.85rem;">
                                    <strong>Mulai:</strong> <br>
                                    {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }} <br><br>
                                    <strong>Selesai:</strong> <br>
                                    {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y') }}
                                </div>
                            </td>
                            <td>
                                <div style="font-size: 0.85rem; margin-bottom: 0.5rem;">
                                    <span class="badge bg-light text-dark border"><i class="bi bi-person-badge"></i> Pembimbing Lapangan / Supervisor</span>
                                    <br>
                                    <strong>
                                        @if($item->mitraSupervisor)
                                            {{ $item->mitraSupervisor->nama_lengkap }}
                                        @elseif($item->wakilPerusahaan)
                                            {{ $item->wakilPerusahaan->name }} (Mitra)
                                        @else
                                            Belum ada Supervisor
                                        @endif
                                    </strong>
                                </div>
                                <div style="font-size: 0.85rem;">
                                    <span class="badge bg-light text-dark border"><i class="bi bi-person-workspace"></i> Guru Pembimbing</span>
                                    <br>
                                    <strong>{{ optional(optional($item->pembimbing)->guru)->nama ?? 'Belum ada Guru' }}</strong>
                                </div>
                            </td>
                            <td>
                                <div style="max-height: 100px; overflow-y: auto; font-size: 0.85rem;" class="text-wrap pe-2">
                                    {!! $item->tugas_singkat ? nl2br(e($item->tugas_singkat)) : '<em class="text-muted">Belum ada catatan tugas...</em>' !!}
                                </div>
                            </td>
                            <td>
                                <span class="badge-status {{ str_replace(' ', '', $item->status) }}">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('magang.magang.edit', $item->id) }}" class="btn-action btn-edit" title="Edit Data">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <form action="{{ route('magang.magang.destroy', $item->id) }}" method="POST" id="deleteForm{{ $item->id }}" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn-action btn-delete" onclick="confirmDelete({{ $item->id }})" title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">Belum ada pengajuan data magang dari siswa.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="table-header" style="border-top: 1px solid #e2e8f0; border-bottom: none;">
                <span style="font-size: 0.875rem; color: #64748b;">Menampilkan {{ count($applications) }} data siswa</span>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
// Search functionality
document.getElementById('searchInput').addEventListener('keyup', function() {
    let value = this.value.toLowerCase();
    document.querySelectorAll('#tableData tbody tr').forEach(row => {
        let text = row.innerText.toLowerCase();
        row.style.display = text.includes(value) ? '' : 'none';
    });
});
</script>
@endpush
