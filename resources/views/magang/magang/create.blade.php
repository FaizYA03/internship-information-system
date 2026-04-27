@extends('magang.layouts.main')

@section('css')
<style>
    .card {
        transition: transform 0.3s, box-shadow 0.3s;
        margin-bottom: 20px;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    .program-card {
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #eaeaea;
    }
    .program-header {
        background-color: var(--primary);
        color: white;
        padding: 15px;
    }
    .program-body {
        padding: 20px;
    }
    .company-info {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }
    .company-icon {
        width: 50px;
        height: 50px;
        background-color: #f8f9fa;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        font-size: 1.5rem;
    }
    .quota-badge {
        background-color: #e9f7f6;
        color: #3bafa6;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    .position-badge {
        background-color: #f0f4f8;
        color: #4a6fa5;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
        margin-right: 10px;
    }
    /* Add missing modal styles */
    .detail-section-title {
        font-weight: 600;
        margin-bottom: 0.75rem;
        color: var(--primary);
        border-bottom: 1px solid #eaeaea;
        padding-bottom: 0.5rem;
    }
    .description-content {
        white-space: pre-line;
        color: #444;
    }
    .company-name {
        font-size: 1.2rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 0.25rem;
    }
    .company-address {
        color: #666;
    }
    .modal:hover,
    .btn:hover,
    .card:hover {
    animation: none !important;
    transform: none !important;
}

</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Pendaftaran Program Magang</h4>
                </div>
                <div class="card-body">
                    <p class="text-muted">Silakan pilih program magang yang tersedia di bawah ini atau isi form pendaftaran secara manual.</p>

                    <ul class="nav nav-tabs mb-4">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#programs">
                                <i class="bi bi-briefcase me-1"></i> Program Tersedia
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#manual">
                                <i class="bi bi-pencil-square me-1"></i> Pendaftaran Manual
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <!-- Program List Tab -->
                        <div class="tab-pane fade show active" id="programs">
                            @if(count($openings) > 0)
                                <div class="row">
                                    @foreach($openings as $opening)
                                        <div class="col-md-6 col-lg-4">
                                            <div class="program-card card">
                                                <div class="program-header">
                                                    <h5 class="mb-1">{{ $opening->judul }}</h5>
                                                    <div class="d-flex align-items-center">
                                                        <span class="position-badge">
                                                            <i class="bi bi-briefcase-fill me-1"></i>
                                                            {{ $opening->posisi }}
                                                        </span>
                                                        <span class="quota-badge">
                                                            <i class="bi bi-people-fill me-1"></i>
                                                            Kuota: {{ $opening->jumlah_posisi }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="program-body">
                                                    <div class="company-info">
                                                        <div class="company-icon">
                                                            <i class="bi bi-building"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0">{{ $opening->wakilPerusahaan->nama_perusahaan ?? '-' }}</h6>
                                                            <small class="text-muted">{{ Str::limit($opening->wakilPerusahaan->alamat ?? '-', 40) }}</small>
                                                        </div>
                                                    </div>

                                                    <p class="mb-2"><i class="bi bi-calendar-event me-2"></i>
                                                        {{ \Carbon\Carbon::parse($opening->tanggal_mulai)->format('d M Y') }} -
                                                        {{ \Carbon\Carbon::parse($opening->tanggal_selesai)->format('d M Y') }}
                                                    </p>

                                                    <p class="mb-3">{{ Str::limit($opening->deskripsi, 100) }}</p>

                                                    <div class="text-end">
                                                        <button type="button" class="btn btn-secondary btn-sm" onclick="showDetailModal({{ $opening->id }})">
                                                            <i class="bi bi-info-circle me-1"></i> Detail
                                                        </button>

                                                        <button class="btn btn-success btn-sm" onclick="showConfirmation({{ $opening->id }}, '{{ addslashes($opening->judul) }}', '{{ addslashes($opening->wakilPerusahaan->nama_perusahaan ?? '-') }}')">
                                                            <i class="bi bi-check-circle me-1"></i> Pilih
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Detail Modal -->
                                            <div class="modal fade" id="detailModal{{ $opening->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $opening->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-primary text-white">
                                                            <h5 class="modal-title" id="detailModalLabel{{ $opening->id }}">{{ $opening->judul }}</h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row mb-4">
                                                                <div class="col-md-8">
                                                                    <h5 class="company-name">{{ $opening->wakilPerusahaan->nama_perusahaan ?? '-' }}</h5>
                                                                    <p class="company-address">
                                                                        <i class="bi bi-geo-alt me-1"></i>
                                                                        {{ $opening->wakilPerusahaan->alamat ?? '-' }}
                                                                    </p>
                                                                </div>
                                                                <div class="col-md-4 text-md-end">
                                                                    <span class="badge bg-primary mb-2">{{ $opening->posisi }}</span>
                                                                    <p class="mb-0"><i class="bi bi-people-fill me-1"></i> Kuota: {{ $opening->jumlah_posisi }} posisi</p>
                                                                </div>
                                                            </div>

                                                            <div class="row mb-4">
                                                                <div class="col-md-6">
                                                                    <h6 class="detail-section-title"><i class="bi bi-calendar-event me-2"></i>Periode Magang</h6>
                                                                    <p><strong>Mulai:</strong> {{ \Carbon\Carbon::parse($opening->tanggal_mulai)->format('d M Y') }}</p>
                                                                    <p><strong>Selesai:</strong> {{ \Carbon\Carbon::parse($opening->tanggal_selesai)->format('d M Y') }}</p>
                                                                    <p><strong>Pendaftaran ditutup:</strong> {{ \Carbon\Carbon::parse($opening->tanggal_penutupan)->format('d M Y') }}</p>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <h6 class="detail-section-title"><i class="bi bi-info-circle me-2"></i>Informasi Tambahan</h6>
                                                                    <p><strong>Status:</strong> {{ $opening->status }}</p>
                                                                    <p><strong>Lokasi:</strong> {{ $opening->lokasi }}</p>
                                                                    <p><strong>Tipe:</strong> {{ $opening->tipe ?? 'Full-time' }}</p>
                                                                </div>
                                                            </div>

                                                            <div class="mb-4">
                                                                <h6 class="detail-section-title"><i class="bi bi-file-text me-2"></i>Deskripsi Program</h6>
                                                                <div class="description-content">
                                                                    {{ $opening->deskripsi }}
                                                                </div>
                                                            </div>

                                                            <div class="mb-4">
                                                                <h6 class="detail-section-title"><i class="bi bi-list-check me-2"></i>Kualifikasi</h6>
                                                                <div class="description-content">
                                                                    {{ $opening->kualifikasi ?? 'Tidak ada kualifikasi khusus yang diperlukan.' }}
                                                                </div>
                                                            </div>

                                                            <div class="mb-4">
                                                                <h6 class="detail-section-title"><i class="bi bi-award me-2"></i>Benefit</h6>
                                                                <div class="description-content">
                                                                    {{ $opening->benefit ?? 'Informasi benefit tidak tersedia.' }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                            <button type="button" class="btn btn-success" onclick="showConfirmation({{ $opening->id }}, '{{ addslashes($opening->judul) }}', '{{ addslashes($opening->wakilPerusahaan->nama_perusahaan ?? '-') }}')" data-bs-dismiss="modal">
                                                                <i class="bi bi-check-circle me-1"></i> Pilih Program Ini
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i> Belum ada program magang yang tersedia saat ini.
                                </div>
                            @endif
                        </div>

                        <!-- Manual Registration Tab -->
                        <div class="tab-pane fade" id="manual">
                            <input type="hidden" name="opening_id" id="opening_id" value="">
                            <!-- Your existing form here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmSelectionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Konfirmasi Pendaftaran Magang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning mb-3">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Perhatian:</strong> Pastikan keahlian Anda sesuai dengan persyaratan program magang ini.
                </div>
                <p>Apakah Anda yakin ingin mendaftar ke program magang:</p>
                <h5 id="selectedProgramTitle" class="mb-2"></h5>
                <p id="selectedProgramCompany" class="text-muted"></p>

                <div class="mt-3">
                    <p><strong>Catatan:</strong></p>
                    <ul>
                        <li>Pendaftaran Anda akan menunggu konfirmasi dari pihak perusahaan</li>
                        <li>Anda akan mendapatkan notifikasi ketika status pendaftaran berubah</li>
                        <li>Pastikan data profil Anda sudah lengkap dan akurat</li>
                    </ul>
                </div>

                <input type="hidden" id="selectedOpeningId" value="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-secondary" id="confirmSelectionBtn">
                    <i class="bi bi-check-circle me-1"></i> Ya, Saya Yakin
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
   function showDetailModal(openingId) {
    // Cari elemen modal berdasarkan ID opening
    const modalId = 'detailModal' + openingId;
    const modalElement = document.getElementById(modalId);

    // Cek apakah elemen ada
    if (modalElement) {
        const detailModal = new bootstrap.Modal(modalElement);
        detailModal.show();
    } else {
        console.error('Modal dengan ID ' + modalId + ' tidak ditemukan.');
    }
}


    function showConfirmation(openingId, title, company) {
        // Fill the confirmation modal with program details
        document.getElementById('selectedProgramTitle').textContent = title;
        document.getElementById('selectedProgramCompany').textContent = 'di ' + company;
        document.getElementById('selectedOpeningId').value = openingId;

        // Show the confirmation modal
        var confirmModal = new bootstrap.Modal(document.getElementById('confirmSelectionModal'));
        confirmModal.show();
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Add event listener to confirmation button
        document.getElementById('confirmSelectionBtn').addEventListener('click', function() {
            const openingId = document.getElementById('selectedOpeningId').value;
            const title = document.getElementById('selectedProgramTitle').textContent;
            const company = document.getElementById('selectedProgramCompany').textContent.replace('di ', '');

            // Hide the confirmation modal
            bootstrap.Modal.getInstance(document.getElementById('confirmSelectionModal')).hide();

            // Submit application
            submitApplication(openingId, title, company);
        });
    });

    function submitApplication(openingId, title, company) {
        // Show loading overlay or spinner
        const loadingOverlay = document.createElement('div');
        loadingOverlay.className = 'position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center bg-dark bg-opacity-50';
        loadingOverlay.style.zIndex = '9999';
        loadingOverlay.innerHTML = '<div class="spinner-border text-light" role="status"><span class="visually-hidden">Loading...</span></div>';
        document.body.appendChild(loadingOverlay);

        // Submit AJAX request to register for the internship
        fetch('{{ route('magang.apply') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                opening_id: openingId
            })
        })
        .then(response => response.json())
        .then(data => {
            // Remove loading overlay
            document.body.removeChild(loadingOverlay);

            if (data.success) {
                // Show success message
                Swal.fire({
                    title: 'Pendaftaran Berhasil!',
                    text: 'Pendaftaran magang Anda telah dikirim. Status pendaftaran: Menunggu Konfirmasi.',
                    icon: 'success',
                    confirmButtonText: 'Ok'
                }).then(() => {
                    window.location.href = '{{ route('magang.magang.index') }}';
                });
            } else {
                // Show error message
                Swal.fire({
                    title: 'Pendaftaran Gagal',
                    text: data.message || 'Terjadi kesalahan saat mendaftar program magang.',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                });
            }
        })
        .catch(error => {
            // Remove loading overlay
            document.body.removeChild(loadingOverlay);

            // Show error message
            Swal.fire({
                title: 'Terjadi Kesalahan',
                text: 'Gagal menghubungi server. Silakan coba lagi nanti.',
                icon: 'error',
                confirmButtonText: 'Ok'
            });
            console.error('Error:', error);
        });
    }
</script>
@endsection
