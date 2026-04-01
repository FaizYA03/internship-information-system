@extends('sistem_akademik.layouts.main')

@section('content')
<div class="container-fluid animate-fade-in">
    <h1 class="page-title">{{ $header }}</h1>
    <p class="text-muted mb-4">Kelola data jurusan SMK Negeri 5 Padang</p>

    <div class="table-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0"><i class="bi bi-diagram-3 me-2"></i>Daftar Jurusan</h5>
            <a href="{{ route('sistem_akademik.jurusan.create') }}" class="btn-primary-app">
                <i class="bi bi-plus-circle"></i> Tambah Jurusan
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover" id="data-table">
                <thead>
                    <tr>
                        <th width="10%">No</th>
                        <th>Nama Jurusan</th>
                        <th width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jurusans as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->nama_jurusan }}</td>
                        <td>
                            <a href="{{ route('sistem_akademik.jurusan.edit', $item->id) }}" class="btn-action btn-edit" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('sistem_akademik.jurusan.destroy', $item->id) }}" method="post" id="deleteForm{{ $item->id }}" class="d-inline">
                                @csrf
                                @method('delete')
                                <button type="button" onclick="confirmDeleteJurusan('{{ $item->id }}')" class="btn-action btn-delete" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($jurusans->count() == 0)
        <div class="empty-state">
            <i class="bi bi-diagram-3"></i>
            <p>Belum ada data jurusan</p>
            <a href="{{ route('sistem_akademik.jurusan.create') }}" class="btn-primary-app">
                <i class="bi bi-plus-circle"></i> Tambah Jurusan
            </a>
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
                },
                columnDefs: [{
                        orderable: false,
                        targets: [-1]
                    }
                ]
            });
        }
    });

    function confirmDeleteJurusan(id) {
        Swal.fire({
            title: "Apakah anda yakin?",
            text: "Data jurusan akan dihapus secara permanen!",
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
