@extends('magang.layouts.main')

@section('css')
<style>
    /* Wrapper & Card */
    .modern-card {
        background: #ffffff;
        border-radius: 1rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        border: 1px solid #f3f4f6;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .modern-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .page-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }
    
    /* Primary Button */
    .btn-primary-modern {
        background-color: #4f46e5;
        color: #ffffff;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-weight: 500;
        font-size: 0.875rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
        border: none;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }
    
    .btn-primary-modern:hover {
        background-color: #4338ca;
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    /* Export Buttons */
    .btn-export {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }
    
    .btn-export-excel {
        background-color: #f0fdf4;
        color: #166534;
        border-color: #bbf7d0;
    }
    .btn-export-excel:hover {
        background-color: #dcfce7;
        color: #15803d;
    }

    .btn-export-pdf {
        background-color: #fef2f2;
        color: #991b1b;
        border-color: #fecaca;
    }
    .btn-export-pdf:hover {
        background-color: #fee2e2;
        color: #b91b1b;
    }

    /* Filter Form Removed */

    /* Action Icon Buttons */
    .btn-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2rem;
        height: 2rem;
        border-radius: 0.375rem;
        border: none;
        background: transparent;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    
    .btn-icon-edit {
        color: #3b82f6;
    }
    .btn-icon-edit:hover {
        background-color: #eff6ff;
        color: #2563eb;
    }
    
    .btn-icon-delete {
        color: #ef4444;
    }
    .btn-icon-delete:hover {
        background-color: #fef2f2;
        color: #dc2626;
    }

    /* DataTables Overrides */
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        outline: none;
        transition: border-color 0.2s;
        margin-left: 0.5rem;
    }
    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
    }
    
    .dataTables_wrapper .dataTables_length select {
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        padding: 0.25rem 2rem 0.25rem 0.75rem;
        font-size: 0.875rem;
        outline: none;
    }

    table.dataTable.no-footer {
        border-bottom: 1px solid #e5e7eb;
    }
    
    table.dataTable thead th, table.dataTable thead td {
        border-bottom: 1px solid #e5e7eb;
        background-color: #f9fafb;
        color: #6b7280;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0.75rem 1rem;
    }
    
    table.dataTable tbody th, table.dataTable tbody td {
        padding: 1rem;
        color: #374151;
        font-size: 0.875rem;
        vertical-align: middle;
        border-bottom: 1px solid #f3f4f6;
    }
    
    table.dataTable.row-border tbody th, table.dataTable.row-border tbody td, table.dataTable.display tbody th, table.dataTable.display tbody td {
        border-top: none;
    }
    
    table.dataTable tbody tr:hover {
        background-color: #f9fafb;
    }

    /* Modern Pagination */
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0.25rem 0.75rem;
        margin-left: 2px;
        border-radius: 0.375rem;
        border: 1px solid transparent;
        font-size: 0.875rem;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: #4f46e5;
        color: white !important;
        border-color: #4f46e5;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #f3f4f6;
        color: #111827 !important;
        border-color: #e5e7eb;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="modern-card">
        <div class="modern-header flex-wrap gap-3">
            <h4 class="page-title">Data Perusahaan Magang</h4>
            <div class="d-flex gap-2">
                <a href="{{ route('magang.perusahaan.export.excel', request()->all()) }}" class="btn-export btn-export-excel">
                    <i class="bi bi-file-earmark-excel"></i> Export Excel
                </a>
                <a href="{{ route('magang.perusahaan.export.pdf', request()->all()) }}" class="btn-export btn-export-pdf" target="_blank">
                    <i class="bi bi-file-earmark-pdf"></i> Export PDF
                </a>
                <a href="{{ route('magang.perusahaan.create') }}" class="btn-primary-modern">
                    <i class="bi bi-plus-lg"></i> Tambah Data
                </a>
            </div>
        </div>
        
        <!-- Filter Form Removed -->

        <div class="card-body px-0 pb-0">
            <div class="table-responsive">
                <table class="table" id="data-table">
                    <thead>
                        <tr>
                            <th style="width: 5%">No</th>
                            <th style="width: 25%">Nama Perusahaan</th>
                            <th style="width: 30%">Alamat</th>
                            <th style="width: 15%">Kontak</th>
                            <th style="width: 15%">Pembimbing</th>
                            <th style="width: 10%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
        @foreach ($wakils as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    <div style="font-weight: 600; color: #111827;">{{ $item->nama_perusahaan }}</div>
                </td>
                <td>
                    <div style="color: #4b5563; font-size: 0.85rem;">{{ $item->alamat }}</div>
                </td>
                <td>
                    <div style="display: flex; align-items: center; gap: 0.375rem; color: #4b5563;">
                        <i class="bi bi-telephone text-muted" style="font-size: 0.8rem;"></i> {{ $item->no_perusahaan }}
                    </div>
                </td>
                <td>
                    <div style="display: flex; align-items: center; gap: 0.375rem;">
                        <i class="bi bi-person text-muted" style="font-size: 0.8rem;"></i> {{ $item->nama }}
                    </div>
                </td>
                <td>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('magang.perusahaan.edit', $item->id) }}" class="btn-icon btn-icon-edit" title="Edit">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <form action="{{ route('magang.perusahaan.destroy', $item->id) }}" method="post" id="deleteForm{{ $item->id }}" style="margin:0;">
                            @csrf
                            @method('delete')
                            <button type="button" onclick="confirmDelete('{{ $item->id }}')" class="btn-icon btn-icon-delete" title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function(){
        // Check if DataTable is already initialized before initializing it
        if (!$.fn.DataTable.isDataTable('#data-table')) {
            $('#data-table').DataTable({
                responsive: true,
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    zeroRecords: "Tidak ada data yang ditemukan",
                    info: "Menampilkan halaman _PAGE_ dari _PAGES_",
                    infoEmpty: "Tidak ada data tersedia",
                    infoFiltered: "(difilter dari _MAX_ total records)",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                }
            });
        }
    });
    
    function confirmDelete(id) {
        if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            document.getElementById('deleteForm' + id).submit();
        }
    }
</script>
@endsection