@extends('sistem_akademik.layouts.main', ['title' => 'Mata Pelajaran Master'])

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold"><i class="bi bi-journal-bookmark text-primary me-2"></i> Mata Pelajaran Master</h5>
        @if(Auth::check() && (Auth::user()->role == 'super_admin' || Auth::user()->role == 'admin_sa' || Auth::user()->role == 'waka_akademik'))
        <a href="{{ route('sistem_akademik.mapels.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i> Tambah Baru
        </a>
        @endif
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="data-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Mata Pelajaran</th>
                        @if(Auth::check() && (Auth::user()->role == 'super_admin' || Auth::user()->role == 'admin_sa' || Auth::user()->role == 'waka_akademik'))
                        <th width="15%">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mapels as $index => $mapel)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="fw-medium text-dark"><i class="bi bi-bookmark text-primary me-2"></i>{{ $mapel->nama_mapel }}</td>
                        @if(Auth::check() && (Auth::user()->role == 'super_admin' || Auth::user()->role == 'admin_sa' || Auth::user()->role == 'waka_akademik'))
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('sistem_akademik.mapels.edit', $mapel->id) }}" class="btn btn-sm btn-outline-warning shadow-sm" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <form action="{{ route('sistem_akademik.mapels.destroy', $mapel->id) }}" method="post" id="deleteForm{{ $mapel->id }}" class="d-inline">
                                    @csrf
                                    @method('delete')
                                    <button type="button" onclick="confirmDelete('{{ $mapel->id }}')" class="btn btn-sm btn-outline-danger shadow-sm" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($mapels->count() == 0)
        <div class="text-center py-5">
            <i class="bi bi-journal-x text-muted" style="font-size: 3rem;"></i>
            <p class="mt-3 text-muted">Belum ada data mata pelajaran master.</p>
            @if(Auth::check() && (Auth::user()->role == 'super_admin' || Auth::user()->role == 'admin_sa' || Auth::user()->role == 'waka_akademik'))
            <a href="{{ route('sistem_akademik.mapels.create') }}" class="btn btn-primary btn-sm mt-3">
                <i class="bi bi-plus-circle"></i> Tambah Master Pelajaran
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
