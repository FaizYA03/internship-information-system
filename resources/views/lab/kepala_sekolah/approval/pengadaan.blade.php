@extends('lab.layouts.unified', ['title' => 'Persetujuan Pengadaan Fasilitas Lab'])

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="fw-bold mb-0">Persetujuan Pengadaan Fasilitas Lab</h5>
                <p class="small text-muted mb-0">Halaman ini menampilkan antrean pengajuan pengadaan alat dari Kepala Lab.</p>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0">Pengaju</th>
                                <th class="border-0">Barang & Spesifikasi</th>
                                <th class="border-0">Estimasi Anggaran</th>
                                <th class="border-0">Alasan</th>
                                <th class="border-0 text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requests as $item)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $item->user->nama ?? 'Kepala Lab' }}</div>
                                        <div class="small text-muted">{{ \Carbon\Carbon::parse($item->created_at)->isoFormat('D MMM Y') }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-primary">{{ $item->nama_barang }}</div>
                                        <div class="small text-muted">{{ $item->jumlah }} Unit &bullet; {{ $item->urgensi }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-bold fs-6">Rp {{ number_format($item->estimasi_harga * $item->jumlah, 0, ',', '.') }}</div>
                                        <small class="text-muted">@Rp {{ number_format($item->estimasi_harga, 0, ',', '.') }}</small>
                                    </td>
                                    <td><small class="text-truncate d-block" style="max-width: 200px;" title="{{ $item->alasan }}">{{ $item->alasan }}</small></td>
                                    <td class="text-center">
                                        <div class="d-flex gap-2 justify-content-center">
                                            <button type="button" class="btn btn-success btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#approveModal{{ $item->id }}">Setujui</button>
                                            <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $item->id }}">Tolak</button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modals for Approval/Rejection with notes -->
                                <div class="modal fade" id="approveModal{{ $item->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <form action="{{ route('lab.kepala_sekolah.approval.pengadaan.approve', $item->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-content border-0 rounded-4">
                                                <div class="modal-header border-0">
                                                    <h5 class="fw-bold">Konfirmasi Persetujuan Anggaran</h5>
                                                </div>
                                                <div class="modal-body text-start">
                                                    <p>Apakah Anda menyetujui pengadaan <strong>{{ $item->nama_barang }}</strong> dengan total estimasi <strong>Rp {{ number_format($item->estimasi_harga * $item->jumlah, 0, ',', '.') }}</strong>?</p>
                                                    <label class="form-label">Catatan (Opsional)</label>
                                                    <textarea name="catatan" class="form-control" placeholder="Contoh: Disetujui menggunakan dana BOS Tahap 2"></textarea>
                                                </div>
                                                <div class="modal-footer border-0">
                                                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-success rounded-pill px-4">Setujui Anggaran</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                
                                <div class="modal fade" id="rejectModal{{ $item->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <form action="{{ route('lab.kepala_sekolah.approval.pengadaan.reject', $item->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-content border-0 rounded-4">
                                                <div class="modal-header border-0">
                                                    <h5 class="fw-bold text-danger">Tolak Pengajuan Pengadaan</h5>
                                                </div>
                                                <div class="modal-body text-start">
                                                    <label class="form-label">Alasan Penolakan</label>
                                                    <textarea name="catatan" class="form-control" placeholder="Contoh: Anggaran dialihkan untuk prioritas lain" required></textarea>
                                                </div>
                                                <div class="modal-footer border-0">
                                                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-danger rounded-pill px-4">Tolak Pengajuan</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">Tidak ada pengajuan pengadaan yang butuh persetujuan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
