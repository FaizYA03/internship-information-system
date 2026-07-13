@extends('magang.layouts.main')

@section('content')
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Status Pendaftaran Magang</h5>
    </div>

    <div class="card-body">
        @if($applications->count() > 0)

            <div class="row g-4">
                @foreach($applications as $application)
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="transition: transform 0.2s ease, box-shadow 0.2s ease;">
                        
                        <!-- Header Status -->
                        <div class="card-header border-0 py-3 d-flex flex-wrap justify-content-between align-items-center bg-light">
                            <span class="text-muted fw-bold" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Status Pendaftaran:</span>
                            
                            @if($application->status == 'Menunggu')
                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill mt-2 mt-sm-0">
                                    <i class="bi bi-hourglass-split me-1"></i> Menunggu Mitra
                                </span>
                            @elseif($application->status == 'Diterima Mitra')
                                <span class="badge bg-info text-dark px-3 py-2 rounded-pill mt-2 mt-sm-0">
                                    <i class="bi bi-building-check me-1"></i> Disetujui Mitra
                                </span>
                            @elseif($application->status == 'Disetujui Admin')
                                <div>
                                    <span class="badge bg-success px-3 py-2 rounded-pill mt-2 mt-sm-0 d-block">
                                        <i class="bi bi-check-circle-fill me-1"></i> Disetujui Admin
                                    </span>
                                </div>
                            @elseif($application->status == 'Ditolak')
                                <span class="badge bg-danger px-3 py-2 rounded-pill mt-2 mt-sm-0">
                                    <i class="bi bi-x-circle-fill me-1"></i> Ditolak
                                </span>
                            @endif
                        </div>

                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <!-- Judul & Perusahaan -->
                                <div class="col-md-5 mb-4 mb-md-0">
                                    <h5 class="fw-bold mb-2" style="color: #1f2937; font-size: 1.25rem;">{{ $application->opening->judul ?? 'Program Magang Reguler' }}</h5>
                                    <div class="d-flex align-items-center text-muted" style="font-size: 1rem;">
                                        <i class="bi bi-building me-2"></i> {{ $application->wakilPerusahaan->nama_perusahaan ?? 'Perusahaan Mitra' }}
                                    </div>
                                </div>

                                <!-- Info Grid (Periode) -->
                                <div class="col-md-4 mb-4 mb-md-0 border-start-md px-md-4">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded p-3 me-3 text-primary d-none d-sm-block">
                                            <i class="bi bi-calendar-range fs-5"></i>
                                        </div>
                                        <div>
                                            <div class="text-uppercase text-muted fw-semibold mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Periode Pelaksanaan</div>
                                            <div class="fw-semibold" style="font-size: 1rem; color: #374151;">
                                                {{ \Carbon\Carbon::parse($application->tanggal_mulai)->format('d M Y') }} <span class="text-muted mx-1">&mdash;</span> {{ \Carbon\Carbon::parse($application->tanggal_selesai)->format('d M Y') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Footer Actions -->
                                <div class="col-md-3 text-md-end mt-2 mt-md-0">
                                    <button type="button" class="btn btn-outline-primary rounded-pill fw-semibold w-100 py-2" data-bs-toggle="modal" data-bs-target="#detailModal{{ $application->id }}">
                                        <i class="bi bi-info-circle me-2"></i> Lihat Detail
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MODAL DETAIL -->
                <div class="modal fade" id="detailModal{{ $application->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content rounded-4 border-0 shadow">
                            <div class="modal-header border-bottom-0 pb-0">
                                <h5 class="modal-title fw-bold">Detail Pendaftaran</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-4">
                                <h6 class="fs-5 fw-bold text-dark mb-1">{{ $application->opening->judul ?? 'Program Magang' }}</h6>
                                <p class="text-muted mb-4"><i class="bi bi-building me-2"></i>{{ $application->wakilPerusahaan->nama_perusahaan ?? '-' }}</p>

                                <div class="alert 
                                    @if($application->status == 'Menunggu') alert-warning
                                    @elseif($application->status == 'Diterima Mitra') alert-info
                                    @elseif($application->status == 'Disetujui Admin') alert-success
                                    @else alert-danger
                                    @endif
                                    border-0 rounded-3 mb-4"
                                >
                                    <h6 class="alert-heading fw-bold mb-2">Status Saat Ini</h6>
                                    <p class="mb-0 fs-6">
                                        @if($application->status == 'Menunggu')
                                            <i class="bi bi-hourglass-split me-2"></i>Menunggu persetujuan dari pihak perusahaan mitra.
                                        @elseif($application->status == 'Diterima Mitra')
                                            <i class="bi bi-building-check me-2"></i>Telah diterima oleh perusahaan. Menunggu persetujuan akhir dari admin sekolah.
                                        @elseif($application->status == 'Disetujui Admin')
                                            <i class="bi bi-check-circle-fill me-2"></i>Selamat! Pendaftaran Anda disetujui. Anda resmi memulai program magang.
                                        @elseif($application->status == 'Ditolak')
                                            <i class="bi bi-x-circle-fill me-2"></i>Maaf, pendaftaran Anda belum dapat diterima saat ini.
                                        @endif
                                    </p>
                                </div>

                                @if($application->catatan)
                                    <div class="mb-4">
                                        <h6 class="fw-bold text-muted text-uppercase" style="font-size: 0.75rem;">Catatan</h6>
                                        <div class="bg-light p-3 rounded-3 text-dark">
                                            {{ $application->catatan }}
                                        </div>
                                    </div>
                                @endif

                                <div>
                                    <h6 class="fw-bold text-muted text-uppercase mb-3" style="font-size: 0.75rem;">Periode Pelaksanaan</h6>
                                    <div class="d-flex justify-content-between align-items-center bg-light p-3 rounded-3">
                                        <div>
                                            <div class="text-muted" style="font-size: 0.8rem;">Tanggal Mulai</div>
                                            <div class="fw-semibold">{{ \Carbon\Carbon::parse($application->tanggal_mulai)->format('d M Y') }}</div>
                                        </div>
                                        <div class="text-muted"><i class="bi bi-arrow-right"></i></div>
                                        <div class="text-end">
                                            <div class="text-muted" style="font-size: 0.8rem;">Tanggal Selesai</div>
                                            <div class="fw-semibold">{{ \Carbon\Carbon::parse($application->tanggal_selesai)->format('d M Y') }}</div>
                                        </div>
                                    </div>
                                </div>

                                @if(in_array($application->status, ['Disetujui', 'Disetujui Admin']))
                                <hr class="my-4">
                                <div>
                                    <h6 class="fw-bold text-muted text-uppercase mb-3" style="font-size: 0.75rem;">Laporan Akhir (Softcopy)</h6>
                                    
                                    @if($application->laporan_akhir_file)
                                        <div class="alert alert-success d-flex align-items-center mb-3">
                                            <i class="bi bi-file-earmark-check-fill fs-3 me-3"></i>
                                            <div>
                                                <div class="fw-bold mb-1">Laporan Sudah Diunggah</div>
                                                <a href="{{ asset('storage/laporan_akhir_magang/' . $application->laporan_akhir_file) }}" target="_blank" class="btn btn-sm btn-outline-success">
                                                    <i class="bi bi-eye me-1"></i> Lihat Laporan
                                                </a>
                                            </div>
                                        </div>
                                        <div class="text-muted small mb-2">Ingin memperbarui laporan?</div>
                                    @else
                                        <div class="alert alert-warning d-flex align-items-center mb-3">
                                            <i class="bi bi-exclamation-triangle-fill fs-3 me-3"></i>
                                            <div>
                                                <div class="fw-bold mb-1">Laporan Belum Diunggah</div>
                                                <small>Silakan unggah softcopy laporan akhir magang Anda agar penilai dapat memeriksa sebelum memberikan nilai.</small>
                                            </div>
                                        </div>
                                    @endif

                                    <form action="{{ route('magang.upload_laporan_akhir', $application->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="input-group">
                                            <input type="file" class="form-control" name="laporan_akhir_file" accept=".pdf,.doc,.docx" required>
                                            <button class="btn btn-primary" type="submit">
                                                <i class="bi bi-upload me-1"></i> {{ $application->laporan_akhir_file ? 'Perbarui' : 'Unggah' }}
                                            </button>
                                        </div>
                                        <small class="text-muted mt-1 d-block">Maksimal ukuran file: 5MB (Format: PDF, DOC, DOCX)</small>
                                    </form>
                                </div>
                                @endif

                            </div>
                            <div class="modal-footer border-top-0 pt-0 pb-4 px-4">
                                <button type="button" class="btn btn-secondary w-100 rounded-pill fw-semibold" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        @else
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                Anda belum mendaftar program magang apapun.
            </div>
        @endif
    </div>
</div>
@endsection