@extends('sistem_akademik.layouts.main', ['title' => 'Data Kelas'])

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold"><i class="bi bi-building text-primary me-2"></i> Data Kelas</h5>
        <a href="{{ route('sistem_akademik.kelas.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i> Tambah Kelas
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="data-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="8%" class="text-center">ID Kelas</th>
                        <th width="15%">Kode</th>
                        <th width="20%">Jurusan</th>
                        <th>Tahun Ajaran</th>
                        <th>Wali Kelas</th>
                        <th>Guru BK</th>
                        <th>Ruangan</th>
                        <th width="12%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kelas as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center">
                            <span style="background:#f0fdf4;color:#15803d;border:1px solid #bbf7d0;border-radius:6px;padding:4px 10px;font-weight:700;font-size:0.85rem;font-family:monospace;">
                                {{ $item->id_kelas ?? '-' }}
                            </span>
                        </td>
                        <td><span class="badge bg-primary px-3 py-2 rounded-pill">{{ $item->nama_kelas }}</span></td>
                        <td>{{ $item->jurusan }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $item->tahun_ajaran }}</span></td>
                        <td>{{ optional($item->waliKelas)->nama ?? '-' }}</td>
                        <td>{{ optional($item->guruBK)->nama ?? '-' }}</td>
                        <td>{{ $item->ruangan ?? '-' }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('sistem_akademik.kelas.edit', $item->id) }}" class="btn btn-sm btn-outline-warning shadow-sm" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('sistem_akademik.kelas.destroy', $item->id) }}" method="post" id="deleteForm{{ $item->id }}" class="d-inline">
                                    @csrf
                                    @method('delete')
                                    <button type="button" onclick="confirmDelete('{{ $item->id }}')" class="btn btn-sm btn-outline-danger shadow-sm" title="Hapus">
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

        @if($kelas->count() == 0)
        <div class="text-center py-5">
            <i class="bi bi-building text-muted" style="font-size: 3rem;"></i>
            <p class="mt-3 text-muted">Belum ada data kelas</p>
            <a href="{{ route('sistem_akademik.kelas.create') }}" class="btn btn-primary btn-sm mt-2">
                <i class="bi bi-plus-circle"></i> Tambah Kelas Sekarang
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
                    } // disable ordering on Aksi column
                ]
            });
        }
    });

    function confirmDelete(id) {
        Swal.fire({
            title: "Apakah anda yakin?",
            text: "Data kelas akan dihapus secara permanen!",
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