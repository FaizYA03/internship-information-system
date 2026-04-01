@extends('sistem_akademik.layouts.main')

@section('content')
<div class="container-fluid animate-fade-in">
    <h1 class="page-title">Master Data Ruangan</h1>
    <p class="text-muted mb-4">Kelola data master ruangan (Kelas, Laboratorium, Lapangan, dll)</p>

    <div class="table-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0"><i class="bi bi-door-open me-2"></i>Daftar Master Ruangan</h5>
            @if(Auth::check() && (Auth::user()->role == 'super_admin' || Auth::user()->role == 'admin_sa' || Auth::user()->role == 'waka_akademik'))
            <a href="{{ route('sistem_akademik.ruangans.create') }}" class="btn-primary-app">
                <i class="bi bi-plus-circle"></i> Tambah Baru
            </a>
            @endif
        </div>

        <div class="table-responsive">
            <table class="table table-hover" id="data-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Kategori Ruangan</th>
                        <th>Nama / Label Ruangan</th>
                        @if(Auth::check() && (Auth::user()->role == 'super_admin' || Auth::user()->role == 'admin_sa' || Auth::user()->role == 'waka_akademik'))
                        <th width="15%">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ruangans as $index => $ruangan)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <span class="badge {{ $ruangan->jenis_ruangan == 'Laboratorium' ? 'bg-primary' : ($ruangan->jenis_ruangan == 'Kelas' ? 'bg-success' : 'bg-secondary') }}">
                                {{ $ruangan->jenis_ruangan }}
                            </span>
                        </td>
                        <td>{{ $ruangan->nama_ruangan }}</td>
                        @if(Auth::check() && (Auth::user()->role == 'super_admin' || Auth::user()->role == 'admin_sa' || Auth::user()->role == 'waka_akademik'))
                        <td>
                            <a href="{{ route('sistem_akademik.ruangans.edit', $ruangan->id) }}" class="btn-action btn-edit" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <form action="{{ route('sistem_akademik.ruangans.destroy', $ruangan->id) }}" method="post" id="deleteForm{{ $ruangan->id }}" class="d-inline">
                                @csrf
                                @method('delete')
                                <button type="button" onclick="confirmDelete('{{ $ruangan->id }}')" class="btn-action btn-delete" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($ruangans->count() == 0)
        <div class="empty-state">
            <i class="bi bi-door-open"></i>
            <p>Belum ada data ruangan yang didaftarkan</p>
            @if(Auth::check() && (Auth::user()->role == 'super_admin' || Auth::user()->role == 'admin_sa' || Auth::user()->role == 'waka_akademik'))
            <a href="{{ route('sistem_akademik.ruangans.create') }}" class="btn-primary-app">
                <i class="bi bi-plus-circle"></i> Tambah Baru
            </a>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        if (!$.fn.DataTable.isDataTable('#data-table')) {
            $('#data-table').DataTable({
                responsive: true,
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Tidak ada data yang ditampilkan",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    },
                }
            });
        }
    });

    function confirmDelete(id) {
        Swal.fire({
            title: "Apakah anda yakin?",
            text: "Data ruangan akan dihapus secara permanen!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#dc3545",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Ya, hapus!",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm' + id).submit();
            }
        });
    }
</script>
@endsection
