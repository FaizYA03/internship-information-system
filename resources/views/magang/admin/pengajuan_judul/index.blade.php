@extends('magang.layouts.main')

@section('content')
<div class="container">
    <h3>Pengajuan Judul Siswa Bimbingan</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Nama</th>
                    <th>NIS</th>
                    <th>Perusahaan</th>
                    <th>Jurusan</th>
                    <th>Judul</th>
                    <th>Link Drive</th>
                    <th>Status</th>
                    <th>Review</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengajuan as $item)
                <tr>
                    <td>{{ $item->user->nama ?? '-' }}</td>
                    <td>{{ $item->user->nis_nip ?? '-' }}</td>
                    <td>{{ $item->wakilPerusahaan->nama_perusahaan ?? '-' }}</td>
                    <td>{{ $item->jurusan ?? '-' }}</td>
                    <td>{{ $item->judul_laporan ?? '-' }}</td>

                    {{-- LINK DRIVE --}}
                    <td>
                        @if($item->link_drive)
                            <a href="{{ $item->link_drive }}" target="_blank">Lihat</a>
                        @else
                            -
                        @endif
                    </td>

                    {{-- STATUS --}}
                    <td>
                        <span class="badge bg-{{ 
                            $item->status == 'accepted' ? 'success' : 
                            ($item->status == 'rejected' ? 'danger' : 'secondary') 
                        }}">
                            {{ ucfirst($item->status ?? 'pending') }}
                        </span>
                    </td>

                    {{-- REVIEW --}}
                    <td>
                        <form action="{{ route('admin.pengajuan-judul.review', $item->id) }}" method="POST">
                            @csrf

                            <select name="status" class="form-select mb-1" required>
                                <option value="pending" {{ $item->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="accepted" {{ $item->status == 'accepted' ? 'selected' : '' }}>Diterima</option>
                                <option value="rejected" {{ $item->status == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                            </select>

                            <textarea 
                                name="catatan" 
                                class="form-control mb-1" 
                                placeholder="Catatan..."
                            >{{ $item->catatan }}</textarea>

                            <button class="btn btn-sm btn-primary" type="submit">
                                Update
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">Belum ada pengajuan judul</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection