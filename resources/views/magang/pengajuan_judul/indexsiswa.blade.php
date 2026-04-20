@extends('magang.layouts.main')

@section('content')
<div class="container">
    <h3>Daftar Pengajuan Judul Laporan Akhir Magang</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(Auth::user()->role == 'siswa' && $pengajuanJuduls->isEmpty())
        <div class="mb-3 text-end">
            <a href="{{ route('magang.pengajuan_judul.create') }}" class="btn btn-primary">
                <i class="bi bi-pencil-square"></i> Ajukan Judul
            </a>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Nama</th>
                    <th>NIS/NISN</th>
                    <th>Jurusan</th>
                    <th>Perusahaan</th>
                    <th>Judul</th>
                    <th>Link Drive</th>
                    <th>Catatan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pengajuanJuduls as $pengajuan)
                    <tr>
                        <td>{{ $pengajuan->user->nama ?? '-' }}</td>
                        <td>{{ $pengajuan->user->nis_nip ?? '-' }}</td>
                        <td>{{ $pengajuan->jurusan }}</td>
                        <td>{{ $pengajuan->wakilPerusahaan->nama_perusahaan ?? '-' }}</td>
                        <td>{{ $pengajuan->judul_laporan ?? '-' }}</td>

                        <!-- ✅ LINK DRIVE -->
                        <td>
                            @if($pengajuan->link_drive)
                                <a href="{{ $pengajuan->link_drive }}" target="_blank">Lihat</a>
                            @else
                                -
                            @endif
                        </td>

                        <!-- ✅ CATATAN -->
                        <td>{{ $pengajuan->catatan ?? '-' }}</td>

                        <!-- ✅ STATUS FIX -->
                        <td>
                            <span class="badge bg-
                                @if($pengajuan->status == 'accepted') success
                                @elseif($pengajuan->status == 'rejected') danger
                                @else secondary
                                @endif
                            ">
                                {{ ucfirst($pengajuan->status) }}
                            </span>
                        </td>

                        <!-- ✅ AKSI -->
                        <td>
                            @if(Auth::user()->role == 'admin_magang')
                                @if($pengajuan->status == 'pending')
                                    
                                    <form action="{{ route('admin.pengajuan-judul.review', $pengajuan->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="status" value="accepted">
                                        <button type="submit" class="btn btn-success btn-sm">Terima</button>
                                    </form>

                                    <form action="{{ route('admin.pengajuan-judul.review', $pengajuan->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                                    </form>

                                @else
                                    <em>Sudah direview</em>
                                @endif
                            @else
                                <em>{{ ucfirst($pengajuan->status) }}</em>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">Belum ada pengajuan judul.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection