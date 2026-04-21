@extends('magang.layouts.main')

@section('content')
<div class="container">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
        <div>
            <h3 class="mb-1 fw-bold">Pengajuan Judul Laporan Akhir Magang</h3>
            <p class="text-muted mb-0">Pantau status pengajuan, catatan bimbingan, dan akses link drive secara cepat.</p>
        </div>

        @if(Auth::user()->role == 'siswa' && $pengajuanJuduls->isEmpty())
            <a href="{{ route('magang.pengajuan_judul.create') }}" class="btn btn-primary btn-lg shadow-sm">
                <i class="bi bi-pencil-square me-2"></i> Ajukan Judul
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="card-body p-3">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-3 gap-3">
                <div>
                    <span class="badge bg-primary bg-opacity-10 text-primary me-2">Total Pengajuan</span>
                    <span class="fs-5 fw-semibold">{{ $pengajuanJuduls->count() }}</span>
                </div>
                <div class="text-muted small">
                    {{ $pengajuanJuduls->isEmpty() ? 'Tidak ada pengajuan saat ini' : 'Tampilkan pengajuan terbaru' }}
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-primary text-white">
                        <tr class="text-uppercase small">
                            <th class="border-0">Nama</th>
                            <th class="border-0">NIS/NISN</th>
                            <th class="border-0">Jurusan</th>
                            <th class="border-0">Perusahaan</th>
                            <th class="border-0">Judul</th>
                            <th class="border-0">Link Drive</th>
                            <th class="border-0">Catatan</th>
                            <th class="border-0">Status</th>
                            <th class="border-0">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pengajuanJuduls as $pengajuan)
                            <tr class="bg-white">
                                <td class="fw-semibold text-secondary">{{ $pengajuan->user->nama ?? '-' }}</td>
                                <td class="text-muted">{{ $pengajuan->user->nis_nip ?? '-' }}</td>
                                <td>{{ $pengajuan->jurusan }}</td>
                                <td>{{ $pengajuan->wakilPerusahaan->nama_perusahaan ?? '-' }}</td>
                                <td>{{ $pengajuan->judul_laporan ?? '-' }}</td>
                                <td>
                                    @if($pengajuan->link_drive)
                                        <a href="{{ $pengajuan->link_drive }}" target="_blank" class="text-decoration-none text-primary">
                                            <i class="bi bi-link-45deg me-1"></i> Buka
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-truncate" style="max-width: 220px;">{{ $pengajuan->catatan ?? '-' }}</td>
                                <td>
                                    @php
                                        $badge = 'secondary';
                                        if ($pengajuan->status == 'accepted') $badge = 'success';
                                        elseif ($pengajuan->status == 'rejected') $badge = 'danger';
                                        elseif ($pengajuan->status == 'pending') $badge = 'warning text-dark';
                                    @endphp
                                    <span class="badge rounded-pill bg-{{ $badge }}">
                                        {{ ucfirst($pengajuan->status) }}
                                    </span>
                                </td>
                                <td class="text-nowrap">
                                    @if(Auth::user()->role == 'admin_magang')
                                        @if($pengajuan->status == 'pending')
                                            <form action="{{ route('admin.pengajuan-judul.review', $pengajuan->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="status" value="accepted">
                                                <button type="submit" class="btn btn-sm btn-success me-1 mb-1">Terima</button>
                                            </form>
                                            <form action="{{ route('admin.pengajuan-judul.review', $pengajuan->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="btn btn-sm btn-danger mb-1">Tolak</button>
                                            </form>
                                        @else
                                            <span class="text-muted">Sudah direview</span>
                                        @endif
                                    @else
                                        @if($pengajuan->status == 'pending')
                                            <a href="{{ route('magang.pengajuan_judul.edit', $pengajuan->id) }}" class="btn btn-sm btn-warning me-1 mb-1">
                                                <i class="bi bi-pencil me-1"></i> Edit
                                            </a>
                                        @endif
                                        <span class="text-muted">{{ ucfirst($pengajuan->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-folder2-open display-4 mb-3"></i>
                                        <div class="fs-5 fw-semibold mb-2">Belum ada pengajuan judul.</div>
                                        <div>Silakan klik tombol ajukan untuk memulai proses bimbingan Anda.</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection