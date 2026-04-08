@extends('sistem_akademik.layouts.main', ['title' => 'Data Course'])

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold"><i class="bi bi-journal-text text-primary me-2"></i> Data Course</h5>
        @if(in_array(Auth::user()->role, ['admin','super_admin','admin_sa']))
        <a href="{{ route('sistem_akademik.course.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i> Tambah Course
        </a>
        @endif
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="data-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Kelas</th>
                        <th>Mata Pelajaran</th>
                        <th>Guru</th>
                        <th>Hari</th>
                        <th>Jam Mulai</th>
                        <th>Jam Selesai</th>
                        <th>Ruangan</th>
                        <th width="12%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($courses as $index => $course)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>

                        {{-- Kelas (null-safe) --}}
                        <td>
                            @if($course->kelas)
                            <span class="badge bg-primary px-2 py-1 rounded-pill">{{ $course->kelas->nama_kelas ?? '-' }}</span><br>
                            <small class="text-muted"><i class="bi bi-diagram-3 me-1"></i>{{ $course->kelas->jurusan ?? '-' }}</small>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>

                        {{-- Mata Pelajaran --}}
                        <td class="fw-bold text-dark">{{ optional($course->mataPelajaran)->nama_mata_pelajaran ?? '-' }}</td>

                        {{-- Guru: berasal dari mataPelajaran -> guru --}}
                        <td>{{ optional(optional($course->mataPelajaran)->guru)->nama ?? optional(optional($course->mataPelajaran)->guru)->name ?? '-' }}</td>

                        {{-- Hari --}}
                        <td><span class="badge bg-light text-dark border"><i class="bi bi-calendar-event me-1"></i>{{ $course->hari ?? '-' }}</span></td>

                        {{-- Jam Mulai --}}
                        <td>
                            @if(!empty($course->jam_mulai))
                            <span class="badge bg-secondary"><i class="bi bi-clock me-1"></i>{{ date('H:i', strtotime($course->jam_mulai)) }}</span>
                            @else
                            -
                            @endif
                        </td>

                        {{-- Jam Selesai --}}
                        <td>
                            @if(!empty($course->jam_selesai))
                            <span class="badge bg-dark"><i class="bi bi-clock-history me-1"></i>{{ date('H:i', strtotime($course->jam_selesai)) }}</span>
                            @else
                            -
                            @endif
                        </td>

                        {{-- Ruangan --}}
                        <td>{{ $course->ruangan ?? '-' }}</td>

                        {{-- Aksi --}}
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('sistem_akademik.course.show', $course->id) }}" class="btn btn-sm btn-outline-info shadow-sm" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>

                                @if(in_array(Auth::user()->role, ['admin','super_admin','admin_sa']))
                                <a href="{{ route('sistem_akademik.course.edit', $course->id) }}" class="btn btn-sm btn-outline-warning shadow-sm" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <form action="{{ route('sistem_akademik.course.destroy', $course->id) }}" method="post" id="deleteForm{{ $course->id }}" class="d-inline">
                                    @csrf
                                    @method('delete')
                                    <button type="button" onclick="confirmDelete('{{ $course->id }}')" class="btn btn-sm btn-outline-danger shadow-sm" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($courses->count() == 0)
        <div class="text-center py-5">
            <i class="bi bi-journal-x text-muted" style="font-size: 3rem;"></i>
            <p class="mt-3 text-muted">Belum ada data jadwal (course).</p>
            @if(in_array(Auth::user()->role, ['admin','super_admin','admin_sa']))
            <a href="{{ route('sistem_akademik.course.create') }}" class="btn btn-primary btn-sm mt-3">
                <i class="bi bi-plus-circle"></i> Tambah Course
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