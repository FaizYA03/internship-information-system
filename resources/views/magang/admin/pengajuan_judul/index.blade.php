@extends('magang.layouts.main')

@section('content')
<div class="container">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
        <div>
            <h3 class="mb-1 fw-bold">Pengajuan Judul Siswa Bimbingan</h3>
            <p class="text-muted mb-0">Review dan berikan catatan untuk setiap pengajuan judul.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <span class="badge bg-info bg-opacity-10 text-info me-2">Total Pengajuan</span>
                    <span class="fs-5 fw-semibold">{{ count($pengajuan) }}</span>
                </div>
                <div class="text-muted small">
                    Klik tombol "Review" untuk memberikan tanggapan
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-primary text-white">
                        <tr class="text-uppercase small">
                            <th class="border-0">Nama</th>
                            <th class="border-0">NIS</th>
                            <th class="border-0">Perusahaan</th>
                            <th class="border-0">Jurusan</th>
                            <th class="border-0">Judul</th>
                            <th class="border-0">Link Drive</th>
                            <th class="border-0">Status</th>
                            <th class="border-0">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengajuan as $item)
                        <tr class="bg-white">
                            <td class="fw-semibold text-secondary">{{ $item->user->nama ?? '-' }}</td>
                            <td class="text-muted">{{ $item->user->nis_nip ?? '-' }}</td>
                            <td>{{ $item->wakilPerusahaan->nama_perusahaan ?? '-' }}</td>
                            <td>{{ $item->jurusan ?? '-' }}</td>
                            <td>{{ $item->judul_laporan ?? '-' }}</td>
                            <td>
                                @if($item->link_drive)
                                    <a href="{{ $item->link_drive }}" target="_blank" class="text-decoration-none text-primary">
                                        <i class="bi bi-link-45deg me-1"></i> Buka
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $badge = 'secondary';
                                    if ($item->status == 'accepted') $badge = 'success';
                                    elseif ($item->status == 'rejected') $badge = 'danger';
                                    elseif ($item->status == 'pending') $badge = 'warning text-dark';
                                @endphp
                                <span class="badge rounded-pill bg-{{ $badge }}">
                                    {{ ucfirst($item->status ?? 'pending') }}
                                </span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#reviewModal{{ $item->id }}">
                                    <i class="bi bi-pencil-square me-1"></i> Review
                                </button>
                            </td>
                        </tr>

                        <!-- Modal Review -->
                        <div class="modal fade" id="reviewModal{{ $item->id }}" tabindex="-1" aria-labelledby="reviewModalLabel{{ $item->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content border-0 shadow-lg">
                                    <div class="modal-header bg-primary text-white border-0">
                                        <h5 class="modal-title fw-bold" id="reviewModalLabel{{ $item->id }}">
                                            <i class="bi bi-pencil-square me-2"></i> Review Pengajuan Judul
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <!-- Info Siswa -->
                                        <div class="mb-4">
                                            <h6 class="fw-bold text-secondary mb-3">
                                                <i class="bi bi-person-circle me-2"></i> Data Siswa
                                            </h6>
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="bg-light p-3 rounded-3">
                                                        <small class="text-muted d-block mb-1">Nama</small>
                                                        <strong>{{ $item->user->nama ?? '-' }}</strong>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="bg-light p-3 rounded-3">
                                                        <small class="text-muted d-block mb-1">NIS/NISN</small>
                                                        <strong>{{ $item->user->nis_nip ?? '-' }}</strong>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="bg-light p-3 rounded-3">
                                                        <small class="text-muted d-block mb-1">Jurusan</small>
                                                        <strong>{{ $item->jurusan ?? '-' }}</strong>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="bg-light p-3 rounded-3">
                                                        <small class="text-muted d-block mb-1">Perusahaan</small>
                                                        <strong>{{ $item->wakilPerusahaan->nama_perusahaan ?? '-' }}</strong>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="bg-light p-3 rounded-3">
                                                        <small class="text-muted d-block mb-1">Judul Laporan</small>
                                                        <strong>{{ $item->judul_laporan ?? '-' }}</strong>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="bg-light p-3 rounded-3">
                                                        <small class="text-muted d-block mb-1">Link Google Drive</small>
                                                        @if($item->link_drive)
                                                            <a href="{{ $item->link_drive }}" target="_blank" class="text-primary text-decoration-none">
                                                                <i class="bi bi-link-45deg me-1"></i> {{ $item->link_drive }}
                                                            </a>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <hr class="my-4">

                                        <!-- Form Review -->
                                        <form action="{{ route('admin.pengajuan-judul.review', $item->id) }}" method="POST">
                                            @csrf
                                            <h6 class="fw-bold text-secondary mb-3">
                                                <i class="bi bi-chat-left-text me-2"></i> Review & Catatan
                                            </h6>

                                            <!-- Status -->
                                            <div class="mb-3">
                                                <label for="status{{ $item->id }}" class="form-label fw-semibold">
                                                    Status Pengajuan
                                                </label>
                                                <select name="status" id="status{{ $item->id }}" class="form-select form-select-lg" required>
                                                    <option value="pending" {{ $item->status == 'pending' ? 'selected' : '' }}>
                                                        <i class="bi bi-hourglass-split"></i> Pending (Belum Direview)
                                                    </option>
                                                    <option value="accepted" {{ $item->status == 'accepted' ? 'selected' : '' }}>
                                                        <i class="bi bi-check-circle"></i> Diterima
                                                    </option>
                                                    <option value="rejected" {{ $item->status == 'rejected' ? 'selected' : '' }}>
                                                        <i class="bi bi-x-circle"></i> Ditolak
                                                    </option>
                                                </select>
                                            </div>

                                            <!-- Catatan -->
                                            <div class="mb-3">
                                                <label for="catatan{{ $item->id }}" class="form-label fw-semibold">
                                                    Catatan & Masukan
                                                </label>
                                                <textarea 
                                                    name="catatan" 
                                                    id="catatan{{ $item->id }}"
                                                    class="form-control" 
                                                    placeholder="Berikan catatan atau masukan untuk siswa (opsional)"
                                                    rows="4"
                                                >{{ $item->catatan }}</textarea>
                                                <small class="text-muted d-block mt-2">Catatan akan ditampilkan kepada siswa.</small>
                                            </div>

                                            <div class="d-flex gap-2 justify-content-end">
                                                <button type="button" class="btn btn-secondary btn-lg" data-bs-dismiss="modal">
                                                    <i class="bi bi-x-circle me-2"></i> Batal
                                                </button>
                                                <button type="submit" class="btn btn-primary btn-lg">
                                                    <i class="bi bi-check-circle me-2"></i> Simpan Review
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-folder2-open display-4 mb-3"></i>
                                    <div class="fs-5 fw-semibold mb-2">Belum ada pengajuan judul</div>
                                    <div>Menunggu siswa bimbingan untuk mengajukan judul laporan akhir magang.</div>
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