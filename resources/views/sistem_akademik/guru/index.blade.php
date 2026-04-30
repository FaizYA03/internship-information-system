@extends('sistem_akademik.layouts.main', ['title' => 'Data Guru'])

@section('css')
<style>
    .filter-bar {
        background: #f8fafc; border: 1px solid #e2e8f0;
        border-radius: 12px; padding: 16px 20px; margin-bottom: 20px;
    }
    .filter-label {
        font-size: 0.75rem; font-weight: 600; text-transform: uppercase;
        letter-spacing: 0.5px; color: #64748b; margin-bottom: 4px;
    }
    .filter-bar .form-control, .filter-bar .form-select {
        border-radius: 8px; border: 1px solid #e2e8f0;
        font-size: 0.875rem; height: 38px;
    }
    .filter-bar .form-control:focus, .filter-bar .form-select:focus {
        border-color: #f97316; box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
    }
    .btn-filter {
        background: #f97316; color: white; border: none;
        border-radius: 8px; padding: 8px 20px;
        font-size: 0.875rem; font-weight: 600; transition: all 0.2s;
    }
    .btn-filter:hover { background: #ea580c; color: white; transform: translateY(-1px); }
    .btn-reset {
        background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0;
        border-radius: 8px; padding: 8px 16px;
        font-size: 0.875rem; font-weight: 600; transition: all 0.2s;
    }
    .btn-reset:hover { background: #e2e8f0; color: #334155; }
    .stats-badge {
        background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa;
        border-radius: 20px; padding: 3px 12px;
        font-size: 0.78rem; font-weight: 600;
    }
    .active-filter-tag {
        background: #fff7ed; border: 1px solid #fdba74; border-radius: 20px;
        padding: 3px 10px; font-size: 0.76rem; color: #c2410c;
        display: inline-flex; align-items: center; gap: 5px; margin: 2px;
    }
    .active-filter-tag a { color: #c2410c; text-decoration: none; font-weight: 700; line-height: 1; }
    .table thead th {
        background: #f8fafc; color: #475569; font-weight: 600;
        font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.4px;
        border-top: none;
    }
    .table tbody tr:hover { background: #fff7ed; }
    .badge-jurusan {
        background: #dcfce7; color: #15803d; border-radius: 6px;
        padding: 3px 8px; font-size: 0.72rem; font-weight: 600;
    }
    .guru-name { font-weight: 600; color: #1e293b; }
    .nip-text { font-family: monospace; font-size: 0.78rem; color: #64748b; }

    /* Bulk delete bar */
    #bulk-action-bar {
        display: none;
        background: #fff3cd;
        border: 1px solid #ffc107;
        border-radius: 10px;
        padding: 10px 16px;
        margin-bottom: 12px;
        align-items: center;
        gap: 12px;
        animation: slideDown 0.2s ease;
    }
    @keyframes slideDown { from { opacity:0; transform:translateY(-8px); } to { opacity:1; transform:translateY(0); } }
    #bulk-action-bar.show { display: flex !important; }
    .btn-bulk-delete {
        background: #dc3545; color: white; border: none;
        border-radius: 8px; padding: 6px 16px; font-size: 0.85rem;
        font-weight: 600; transition: all 0.2s;
    }
    .btn-bulk-delete:hover { background: #b91c1c; }
    .form-check-input:checked { background-color: #f97316; border-color: #f97316; }
    .row-selected td { background: #fff7ed !important; }
</style>
@endsection

@section('content')

{{-- Filter Bar --}}
<form method="GET" action="{{ route('sistem_akademik.guru.index') }}" id="filterForm">
<div class="filter-bar">
    <div class="row g-3 align-items-end">
        <div class="col-md-3">
            <div class="filter-label"><i class="bi bi-search me-1"></i>Cari Guru</div>
            <input type="text" name="search" class="form-control"
                   placeholder="Nama guru atau NIP..."
                   value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <div class="filter-label"><i class="bi bi-diagram-3 me-1"></i>Jurusan</div>
            <select name="jurusan_id" class="form-select">
                <option value="">Semua Jurusan</option>
                @foreach($jurusanList as $j)
                    <option value="{{ $j->id }}" {{ request('jurusan_id') == $j->id ? 'selected' : '' }}>
                        {{ $j->nama_jurusan }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <div class="filter-label"><i class="bi bi-person me-1"></i>Jenis Kelamin</div>
            <select name="jenis_kelamin" class="form-select">
                <option value="">Semua</option>
                <option value="Laki-laki" {{ request('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                <option value="Perempuan" {{ request('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
            </select>
        </div>
        <div class="col-md-2">
            <div class="filter-label"><i class="bi bi-check-circle me-1"></i>Status</div>
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="Nonaktif" {{ request('status') == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-filter flex-fill">
                <i class="bi bi-funnel-fill me-1"></i> Filter
            </button>
            <a href="{{ route('sistem_akademik.guru.index') }}" class="btn btn-reset">
                <i class="bi bi-x-lg"></i>
            </a>
        </div>
    </div>
</div>
</form>

{{-- Active Filters --}}
@if(request()->anyFilled(['search', 'jurusan_id', 'status', 'jenis_kelamin']))
<div class="mb-3 d-flex align-items-center gap-2 flex-wrap">
    <span class="text-muted" style="font-size:0.82rem;"><i class="bi bi-funnel me-1"></i>Filter aktif:</span>
    @if(request('search'))
        <span class="active-filter-tag">Cari: "{{ request('search') }}" <a href="{{ route('sistem_akademik.guru.index', request()->except('search')) }}">×</a></span>
    @endif
    @if(request('jurusan_id'))
        <span class="active-filter-tag">Jurusan: {{ $jurusanList->find(request('jurusan_id'))?->nama_jurusan }} <a href="{{ route('sistem_akademik.guru.index', request()->except('jurusan_id')) }}">×</a></span>
    @endif
    @if(request('jenis_kelamin'))
        <span class="active-filter-tag">{{ request('jenis_kelamin') }} <a href="{{ route('sistem_akademik.guru.index', request()->except('jenis_kelamin')) }}">×</a></span>
    @endif
    @if(request('status'))
        <span class="active-filter-tag">{{ request('status') }} <a href="{{ route('sistem_akademik.guru.index', request()->except('status')) }}">×</a></span>
    @endif
    <span class="stats-badge ms-1">{{ $gurus->count() }} hasil</span>
</div>
@endif

{{-- Bulk Action Bar --}}
<div id="bulk-action-bar">
    <i class="bi bi-check2-square text-warning fs-5"></i>
    <span id="selected-count" class="fw-semibold" style="font-size:0.9rem;color:#92400e;">0 guru dipilih</span>
    <button type="button" class="btn-bulk-delete ms-2" onclick="confirmBulkDelete()">
        <i class="bi bi-trash me-1"></i> Hapus yang Dipilih
    </button>
    <button type="button" class="btn btn-sm btn-light ms-1" onclick="clearSelection()">
        Batal Pilih
    </button>
</div>

{{-- Bulk Delete Form (hidden) --}}
<form id="bulkDeleteForm" action="{{ route('sistem_akademik.guru.bulk-delete') }}" method="POST">
    @csrf
    @method('DELETE')
    <div id="bulk-ids-container"></div>
</form>

{{-- Data Table --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">
            <i class="bi bi-person-workspace text-primary me-2"></i> Data Guru
            <span class="ms-2 stats-badge">{{ $gurus->count() }} guru</span>
        </h5>
        <div class="d-flex gap-2">
            <a href="{{ route('sistem_akademik.guru.template') }}" class="btn btn-outline-secondary btn-sm" title="Download Template Excel">
                <i class="bi bi-file-earmark-arrow-down me-1"></i> Template
            </a>
            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="bi bi-file-earmark-excel me-1"></i> Import Excel
            </button>
            <a href="{{ route('sistem_akademik.guru.export', request()->query()) }}"
               class="btn btn-outline-primary btn-sm" title="Export ke Excel">
                <i class="bi bi-download me-1"></i> Export Excel
            </a>
            <a href="{{ route('sistem_akademik.guru.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle me-1"></i> Tambah Guru
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="data-table">
                <thead>
                    <tr>
                        <th class="ps-4" style="width:40px;">
                            {{-- Select All --}}
                            <input class="form-check-input" type="checkbox" id="checkAll" title="Pilih Semua">
                        </th>
                        <th style="width:50px">No</th>
                        <th>Nama Guru</th>
                        <th>NIP</th>
                        <th>L/P</th>
                        <th>Agama</th>
                        <th>Jurusan</th>
                        <th>No HP</th>
                        <th>Status</th>
                        <th class="text-center" style="width:100px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($gurus as $index => $guru)
                    <tr id="row-{{ $guru->id }}">
                        <td class="ps-4">
                            <input class="form-check-input row-check" type="checkbox"
                                   value="{{ $guru->id }}" onchange="updateBulkBar()">
                        </td>
                        <td class="text-muted">{{ $index + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($guru->foto)
                                    <img src="{{ asset('storage/' . $guru->foto) }}"
                                         class="rounded-circle"
                                         style="width:36px;height:36px;object-fit:cover;border:2px solid #e2e8f0;flex-shrink:0;"
                                         alt="Foto">
                                @else
                                    <img src="{{ asset('assets/images/default_avatar.png') }}"
                                         class="rounded-circle"
                                         style="width:36px;height:36px;object-fit:cover;border:2px solid #e2e8f0;flex-shrink:0;"
                                         alt="Default">
                                @endif
                                <div>
                                    <div class="guru-name">{{ $guru->user->nama ?? '-' }}</div>
                                    <div style="font-size:0.75rem;color:#94a3b8;">{{ $guru->user->email ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="nip-text">{{ $guru->nip ?? '-' }}</span></td>
                        <td>
                            @if($guru->jenis_kelamin == 'Laki-laki')
                                <span style="background:#dbeafe;color:#1d4ed8;border-radius:6px;padding:3px 8px;font-size:0.75rem;font-weight:600;">
                                    <i class="bi bi-gender-male"></i> L
                                </span>
                            @elseif($guru->jenis_kelamin == 'Perempuan')
                                <span style="background:#fce7f3;color:#9d174d;border-radius:6px;padding:3px 8px;font-size:0.75rem;font-weight:600;">
                                    <i class="bi bi-gender-female"></i> P
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td style="font-size:0.8rem;">{{ $guru->agama ?? '-' }}</td>
                        <td><span class="badge-jurusan">{{ $guru->jurusan->nama_jurusan ?? '-' }}</span></td>
                        <td>{{ $guru->no_hp ?? '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $guru->status == 'Aktif' ? 'success' : 'secondary' }}">
                                {{ $guru->status ?? '-' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('sistem_akademik.guru.edit', $guru->id) }}"
                                   class="btn btn-warning btn-sm" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('sistem_akademik.guru.destroy', $guru->id) }}"
                                      method="POST" id="deleteForm{{ $guru->id }}">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="confirmDelete('{{ $guru->id }}')"
                                            class="btn btn-danger btn-sm" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-person-x fs-1 d-block mb-2" style="color:#cbd5e1;"></i>
                                <strong>Tidak ada data guru</strong>
                                @if(request()->anyFilled(['search', 'jurusan_id', 'status', 'jenis_kelamin']))
                                    <p class="mt-1 mb-0" style="font-size:0.85rem;">Coba ubah atau hapus filter yang aktif.</p>
                                    <a href="{{ route('sistem_akademik.guru.index') }}" class="btn btn-sm btn-reset mt-3">
                                        <i class="bi bi-x-circle me-1"></i> Hapus Semua Filter
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

{{-- Import Modal --}}
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header" style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                <h5 class="modal-title fw-bold" id="importModalLabel">
                    <i class="bi bi-file-earmark-excel text-success me-2"></i> Import Data Guru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('sistem_akademik.guru.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="alert alert-info py-2 mb-3" style="font-size:0.83rem;">
                        <i class="bi bi-info-circle me-1"></i>
                        <strong>Kolom yang diperlukan:</strong><br>
                        <code>nama_guru</code>, <code>nip</code>, <code>lp</code> (L/P),
                        <code>agama</code>, <code>jurusan</code>, <code>no_hp</code>, <code>status</code>
                    </div>
                    <div class="mb-3 p-3 rounded" style="background:#f8fafc;border:1px solid #e2e8f0;font-size:0.8rem;">
                        <p class="fw-bold mb-2 text-muted">Keterangan Format:</p>
                        <ul class="mb-0 ps-3" style="line-height:2;">
                            <li><code>lp</code> → <code>L</code> untuk Laki-laki, <code>P</code> untuk Perempuan</li>
                            <li><code>jurusan</code> → nama jurusan sesuai data di sistem</li>
                            <li><code>status</code> → <code>Aktif</code> atau <code>Nonaktif</code></li>
                            <li>Email default: <strong>namaguru@gmail.com</strong></li>
                            <li>Password default: <strong>guru</strong></li>
                        </ul>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih File Excel / CSV</label>
                        <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                        <div class="form-text">Format: .xlsx, .xls, .csv — Maks. 5MB</div>
                    </div>
                    <a href="{{ route('sistem_akademik.guru.template') }}"
                       class="btn btn-sm btn-outline-secondary w-100">
                        <i class="bi bi-download me-1"></i> Download Template CSV
                    </a>
                </div>
                <div class="modal-footer" style="background:#f8fafc;border-top:1px solid #e2e8f0;">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-upload me-1"></i> Import Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('script')
<script>
    $(document).ready(function() {
        if (!$.fn.DataTable.isDataTable('#data-table')) {
            $('#data-table').DataTable({
                responsive: true,
                pageLength: 25,
                lengthMenu: [10, 25, 50, 100],
                searching: false,
                language: {
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ guru",
                    infoEmpty: "Tidak ada data",
                    paginate: { first: "«", last: "»", next: "›", previous: "‹" }
                },
                columnDefs: [{ orderable: false, targets: [0, 1, 9] }]
            });
        }
    });

    // ——— Filter form behavior ———
    document.getElementById('filterForm').querySelectorAll('select').forEach(sel => {
        sel.addEventListener('change', () => document.getElementById('filterForm').submit());
    });
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        searchInput.addEventListener('keydown', e => {
            if (e.key === 'Enter') document.getElementById('filterForm').submit();
        });
    }

    // ——— Checkbox: Select All ———
    const checkAll = document.getElementById('checkAll');
    if (checkAll) {
        checkAll.addEventListener('change', function() {
            document.querySelectorAll('.row-check').forEach(cb => {
                cb.checked = this.checked;
                cb.closest('tr').classList.toggle('row-selected', this.checked);
            });
            updateBulkBar();
        });
    }

    function updateBulkBar() {
        const checked = document.querySelectorAll('.row-check:checked');
        const bar = document.getElementById('bulk-action-bar');
        const countEl = document.getElementById('selected-count');

        // Highlight rows
        document.querySelectorAll('.row-check').forEach(cb => {
            cb.closest('tr').classList.toggle('row-selected', cb.checked);
        });

        if (checked.length > 0) {
            bar.classList.add('show');
            countEl.textContent = checked.length + ' guru dipilih';
        } else {
            bar.classList.remove('show');
        }

        // Sync check-all state
        const allChecks = document.querySelectorAll('.row-check');
        if (checkAll) {
            checkAll.indeterminate = checked.length > 0 && checked.length < allChecks.length;
            checkAll.checked = checked.length === allChecks.length && allChecks.length > 0;
        }
    }

    function clearSelection() {
        document.querySelectorAll('.row-check').forEach(cb => {
            cb.checked = false;
            cb.closest('tr').classList.remove('row-selected');
        });
        if (checkAll) { checkAll.checked = false; checkAll.indeterminate = false; }
        document.getElementById('bulk-action-bar').classList.remove('show');
    }

    function confirmBulkDelete() {
        const checked = document.querySelectorAll('.row-check:checked');
        if (checked.length === 0) return;

        Swal.fire({
            title: 'Hapus ' + checked.length + ' Guru?',
            text: 'Data yang dihapus tidak dapat dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then(result => {
            if (result.isConfirmed) {
                const container = document.getElementById('bulk-ids-container');
                container.innerHTML = '';
                checked.forEach(cb => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ids[]';
                    input.value = cb.value;
                    container.appendChild(input);
                });
                document.getElementById('bulkDeleteForm').submit();
            }
        });
    }

    // ——— Single delete ———
    function confirmDelete(id) {
        Swal.fire({
            title: "Apakah anda yakin?",
            text: "Data guru akan dihapus secara permanen!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#dc3545",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Ya, hapus!",
            cancelButtonText: "Batal"
        }).then(result => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm' + id).submit();
            }
        });
    }
</script>
@endsection