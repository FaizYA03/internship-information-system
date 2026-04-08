@extends('sistem_akademik.layouts.main', ['title' => 'Data Ruangan'])

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold"><i class="bi bi-door-open text-primary me-2"></i> Data Ruangan</h5>
        @if(Auth::check() && (Auth::user()->role == 'super_admin' || Auth::user()->role == 'admin_sa' || Auth::user()->role == 'waka_akademik'))
        <a href="{{ route('sistem_akademik.ruangans.create') }}" class="btn btn-primary btn-sm">
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
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            @php
                                $badgeClass = match($ruangan->jenis_ruangan) {
                                    'Laboratorium' => 'bg-info text-dark',
                                    'Kelas' => 'bg-success',
                                    'Bengkel' => 'bg-warning text-dark',
                                    'Perpustakaan' => 'bg-primary',
                                    default => 'bg-secondary'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }} px-3 py-2 rounded-pill font-monospace" style="font-size: 0.85rem;">
                                {{ $ruangan->jenis_ruangan }}
                            </span>
                        </td>
                        <td class="fw-medium text-dark"><i class="bi bi-geo-alt me-2 text-muted"></i>{{ $ruangan->nama_ruangan }}</td>
                        @if(Auth::check() && (Auth::user()->role == 'super_admin' || Auth::user()->role == 'admin_sa' || Auth::user()->role == 'waka_akademik'))
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('sistem_akademik.ruangans.edit', $ruangan->id) }}" class="btn btn-sm btn-outline-warning shadow-sm" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <form action="{{ route('sistem_akademik.ruangans.destroy', $ruangan->id) }}" method="post" id="deleteForm{{ $ruangan->id }}" class="d-inline">
                                    @csrf
                                    @method('delete')
                                    <button type="button" onclick="confirmDelete('{{ $ruangan->id }}')" class="btn btn-sm btn-outline-danger shadow-sm" title="Hapus">
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

        @if($ruangans->count() == 0)
        <div class="text-center py-5">
            <i class="bi bi-door-open text-muted" style="font-size: 3rem;"></i>
            <p class="mt-3 text-muted">Belum ada data ruangan yang didaftarkan</p>
            @if(Auth::check() && (Auth::user()->role == 'super_admin' || Auth::user()->role == 'admin_sa' || Auth::user()->role == 'waka_akademik'))
            <a href="{{ route('sistem_akademik.ruangans.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle"></i> Tambah Master Ruangan Baru
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
