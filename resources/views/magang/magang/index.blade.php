@extends('magang.layouts.main')

@section('content')
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Status Pendaftaran Magang</h5>
    </div>

    <div class="card-body">
        @if($applications->count() > 0)

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Program</th>
                            <th>Perusahaan</th>
                            <th>Periode</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($applications as $application)
                        <tr>
                            <td>{{ $application->opening->judul ?? 'Program Magang' }}</td>
                            <td>{{ $application->wakilPerusahaan->nama_perusahaan ?? '-' }}</td>

                            <td>
                                {{ \Carbon\Carbon::parse($application->tanggal_mulai)->format('d M Y') }} -
                                {{ \Carbon\Carbon::parse($application->tanggal_selesai)->format('d M Y') }}
                            </td>

                            {{-- ================= STATUS BADGE ================= --}}
                            <td>
                                @if($application->status == 'Menunggu')
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-hourglass me-1"></i> Menunggu Mitra
                                    </span>

                                @elseif($application->status == 'Diterima Mitra')
                                    <span class="badge bg-info text-dark">
                                        <i class="bi bi-building-check me-1"></i> Disetujui Mitra
                                    </span>

                                @elseif($application->status == 'Disetujui Admin')
                                    <span class="badge bg-primary">
                                        <i class="bi bi-clock-history me-1"></i> Menunggu Admin
                                    </span>

                                    <span class="badge bg-success mt-1 d-block">
                                        <i class="bi bi-check-circle me-1"></i> Disetujui Admin
                                    </span>

                                @elseif($application->status == 'Ditolak')
                                    <span class="badge bg-danger">
                                        <i class="bi bi-x-circle me-1"></i> Ditolak
                                    </span>
                                @endif
                            </td>

                            {{-- ================= BUTTON DETAIL ================= --}}
                            <td>
                                <button 
                                    type="button" 
                                    class="btn btn-sm btn-info"
                                    data-bs-toggle="modal"
                                    data-bs-target="#detailModal{{ $application->id }}"
                                >
                                    <i class="bi bi-info-circle me-1"></i> Detail
                                </button>
                            </td>
                        </tr>

                        {{-- ================= MODAL DETAIL ================= --}}
                        <div class="modal fade" id="detailModal{{ $application->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 class="modal-title">Detail Pendaftaran</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">

                                        <h6>{{ $application->opening->judul ?? 'Program Magang' }}</h6>
                                        <p><strong>Perusahaan:</strong> {{ $application->wakilPerusahaan->nama_perusahaan ?? '-' }}</p>

                                        {{-- ================= STATUS INFO ================= --}}
                                        <div class="mb-3">
                                            <h6>Status</h6>

                                            <div class="alert 
                                                @if($application->status == 'Menunggu') alert-warning
                                                @elseif($application->status == 'Diterima Mitra') alert-info
                                                @elseif($application->status == 'Disetujui Admin') alert-success
                                                @else alert-danger
                                                @endif
                                            ">

                                                @if($application->status == 'Menunggu')
                                                    ⏳ Menunggu persetujuan dari perusahaan

                                                @elseif($application->status == 'Diterima Mitra')
                                                    🏢 Sudah diterima oleh perusahaan<br>
                                                    ⏳ Menunggu persetujuan admin

                                                @elseif($application->status == 'Disetujui Admin')
                                                    🎉 Selamat! Anda resmi diterima magang

                                                @elseif($application->status == 'Ditolak')
                                                    ❌ Pendaftaran ditolak
                                                @endif

                                            </div>
                                        </div>

                                        {{-- CATATAN --}}
                                        @if($application->catatan)
                                            <div class="mb-3">
                                                <h6>Catatan</h6>
                                                <div class="alert alert-light">
                                                    {{ $application->catatan }}
                                                </div>
                                            </div>
                                        @endif

                                        {{-- PERIODE --}}
                                        <div>
                                            <h6>Periode Magang</h6>
                                            <p><strong>Mulai:</strong> {{ \Carbon\Carbon::parse($application->tanggal_mulai)->format('d M Y') }}</p>
                                            <p><strong>Selesai:</strong> {{ \Carbon\Carbon::parse($application->tanggal_selesai)->format('d M Y') }}</p>
                                        </div>

                                    </div>

                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    </div>

                                </div>
                            </div>
                        </div>

                        @endforeach
                    </tbody>
                </table>
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