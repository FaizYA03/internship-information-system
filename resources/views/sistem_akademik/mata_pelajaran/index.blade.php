@extends('sistem_akademik.layouts.main')

@section('content')
<div class="container-fluid animate-fade-in">
    <h1 class="page-title">{{ $header }}</h1>
    <p class="text-muted mb-4">Kelola data mata pelajaran di SMK Negeri 5 Padang</p>

    <div class="table-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0"><i class="bi bi-book me-2"></i>Daftar Mata Pelajaran</h5>
            @if(Auth::check() && (Auth::user()->role == 'super_admin' || Auth::user()->role == 'admin_sa'))
            <a href="{{ route('sistem_akademik.mata_pelajaran.create') }}" class="btn-primary-app">
                <i class="bi bi-plus-circle"></i> Tambah Mata Pelajaran
            </a>
            @endif
        </div>

        <div class="table-responsive">
            <table class="table table-hover" id="data-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Mata Pelajaran</th>
                        <th>Nama Guru</th>
                        @if(Auth::check() && (Auth::user()->role == 'super_admin' || Auth::user()->role == 'admin_sa'))
                        <th width="15%">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mapels as $index => $mapel)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $mapel->nama_mata_pelajaran }}</td>
                        <td>{{ optional(optional($mapel->guru)->user)->name ?? optional($mapel->guru)->name ?? '-' }}</td>
                        @if(Auth::check() && (Auth::user()->role == 'super_admin' || Auth::user()->role == 'admin_sa'))
                        <td>
                            <a href="{{ route('sistem_akademik.mata_pelajaran.edit', $mapel->id) }}" class="btn-action btn-edit" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <form action="{{ route('sistem_akademik.mata_pelajaran.destroy', $mapel->id) }}" method="post" id="deleteForm{{ $mapel->id }}" class="d-inline">
                                @csrf
                                @method('delete')
                                <button type="button" onclick="confirmDelete('{{ $mapel->id }}')" class="btn-action btn-delete" title="Hapus">
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

        @if($mapels->count() == 0)
        <div class="empty-state">
            <i class="bi bi-book-x"></i>
            <p>Belum ada data mata pelajaran</p>
            @if(Auth::check() && (Auth::user()->role == 'super_admin' || Auth::user()->role == 'admin_sa'))
            <a href="{{ route('sistem_akademik.mata_pelajaran.create') }}" class="btn-primary-app">
                <i class="bi bi-plus-circle"></i> Tambah Mata Pelajaran
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
            text: "Data mata pelajaran akan dihapus secara permanen!",
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