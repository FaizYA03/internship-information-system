@extends('sistem_akademik.layouts.main', ['title' => 'Data Jurusan'])

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold"><i class="bi bi-diagram-3 text-primary me-2"></i> Data Jurusan</h5>
        <a href="{{ route('sistem_akademik.jurusan.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i> Tambah Jurusan
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="data-table">
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
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="fw-medium text-dark">{{ $item->nama_jurusan }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('sistem_akademik.jurusan.edit', $item->id) }}" class="btn btn-sm btn-outline-warning shadow-sm" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('sistem_akademik.jurusan.destroy', $item->id) }}" method="post" id="deleteForm{{ $item->id }}" class="d-inline">
                                    @csrf
                                    @method('delete')
                                    <button type="button" onclick="confirmDeleteJurusan('{{ $item->id }}')" class="btn btn-sm btn-outline-danger shadow-sm" title="Hapus">
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

        @if($jurusans->count() == 0)
        <div class="text-center py-5">
            <i class="bi bi-diagram-3 text-muted" style="font-size: 3rem;"></i>
            <p class="mt-3 text-muted">Belum ada data jurusan yang ditambahkan.</p>
            <a href="{{ route('sistem_akademik.jurusan.create') }}" class="btn btn-primary btn-sm mt-3">
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
