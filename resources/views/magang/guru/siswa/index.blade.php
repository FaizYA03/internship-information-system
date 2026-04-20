@extends('magang.layouts.main')

@section('content')
<div class="container">
    <h3 class="mb-4">Siswa Bimbingan</h3>

    <div class="card">
        <div class="card-body">

            <table id="tableSiswa" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Perusahaan</th>
                        <th>Posisi</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $item)
                        <tr>
                            <td>{{ $item->siswa->user->nama ?? '-' }}</td>

                            <td>
                                {{ optional(optional($item->siswa->magangSiswa)->wakilPerusahaan)->nama_perusahaan ?? '-' }}
                            </td>

                            <td>
                                {{ optional(optional($item->siswa->magangSiswa)->opening)->posisi ?? '-' }}
                            </td>

                            <td>
                                <span class="badge bg-success">
                                    {{ $item->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">
                                Belum ada siswa bimbingan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        $('#tableSiswa').DataTable({
            responsive: true,
            autoWidth: false
        });
    });
</script>
@endsection