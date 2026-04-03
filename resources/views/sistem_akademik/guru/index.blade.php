@extends('sistem_akademik.layouts.main')

@section('content')
<div class="container mt-3 mb-3">
    <h1>Data Guru</h1>
    <div class="card p-3">
        <a href="{{ route('sistem_akademik.guru.create') }}"
            class="btn btn-primary mb-3"
            style="width:12%;">Tambah Guru</a>

        <table class="table table-bordered" id="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>NIP</th>
                    <th>Jurusan</th>
                    <th>Tanggal Lahir</th>
                    <th>Alamat</th>
                    <th>No HP</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($gurus as $index => $guru)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $guru->user->nama }}</td>
                    <td>{{ $guru->nip }}</td>
                    <td>{{ $guru->jurusan ? $guru->jurusan->nama_jurusan : '-' }}</td>
                    <td>{{ $guru->tanggal_lahir }}</td>
                    <td>{{ $guru->alamat }}</td>
                    <td>{{ $guru->no_hp }}</td>
                    <td>
                        <span class="badge bg-{{ $guru->status == 'Aktif' ? 'success' : 'secondary' }}">{{ $guru->status }}</span>
                    </td>
                    <td>
                        <a href="{{ route('sistem_akademik.guru.edit', $guru->id) }}"
                            class="btn btn-warning">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <form action="{{ route('sistem_akademik.guru.destroy', $guru->id) }}"
                            method="POST"
                            id="deleteForm{{ $guru->id }}"
                            style="display:inline">
                            @csrf @method('DELETE')
                            <a href="javascript:void(0)" onclick="confirmDelete('{{ $guru->id }}')" class="btn btn-danger">
                                <i class="bi bi-trash"></i>
                            </a>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
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
            text: "Data guru akan dihapus secara permanen!",
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