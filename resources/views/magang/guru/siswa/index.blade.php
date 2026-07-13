@extends('magang.layouts.main')

@section('content')
<style>
    .guru-page {
        min-height: 100vh;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        padding: 2rem 0;
    }

    .guru-header {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border-radius: 20px;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(16, 185, 129, 0.2);
    }

    .guru-header h1 {
        font-size: 2.25rem;
        font-weight: 700;
        margin: 0;
    }

    .guru-header p {
        margin: 0.5rem 0 0;
        color: rgba(255,255,255,0.9);
        font-size: 1.1rem;
    }

    .data-table-container {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0,0,0,0.05);
        border: 1px solid rgba(16, 185, 129, 0.1);
        padding: 2rem;
    }

    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .modern-table thead th {
        background: #f8fafc;
        color: #475569;
        font-weight: 600;
        font-size: 0.85rem;
        text-align: left;
        padding: 1rem 1.5rem;
        border-bottom: 2px solid #e2e8f0;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .modern-table tbody tr {
        transition: all 0.2s ease;
    }

    .modern-table tbody tr:hover {
        background-color: #f8fafc;
        transform: translateY(-1px);
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }

    .modern-table tbody td {
        padding: 1.25rem 1.5rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        color: #374151;
        font-size: 0.95rem;
    }

    .student-profile {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .student-avatar {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
        color: #4f46e5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.2rem;
        box-shadow: 0 4px 6px rgba(79, 70, 229, 0.1);
    }

    .student-details {
        display: flex;
        flex-direction: column;
    }

    .student-name {
        font-weight: 600;
        color: #1e293b;
        font-size: 1rem;
    }

    .student-email {
        font-size: 0.8rem;
        color: #64748b;
    }

    .company-wrapper {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .company-icon {
        width: 35px;
        height: 35px;
        border-radius: 8px;
        background-color: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
    }

    .company-info {
        display: flex;
        flex-direction: column;
    }
    
    .company-name {
        font-weight: 600;
        color: #334155;
    }
    
    .position-name {
        font-size: 0.85rem;
        color: #10b981;
        font-weight: 500;
    }

    .badge-status {
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        text-transform: capitalize;
    }
    
    .badge-status.disetujui, .badge-status.rekomendasi { 
        background: #dcfce7; 
        color: #166534; 
        border: 1px solid #bbf7d0;
    }
    
    .badge-status.menunggu { 
        background: #fef3c7; 
        color: #92400e; 
        border: 1px solid #fde68a;
    }

    /* Journal Badges */
    .jurnal-badge {
        font-size: 0.75rem;
        padding: 0.35rem 0.75rem;
        border-radius: 8px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }
    .jurnal-badge.success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .jurnal-badge.warning { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
    .jurnal-badge.danger { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }

    /* Customizing DataTables */
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        margin-left: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .dataTables_wrapper .dataTables_filter input:focus {
        outline: none;
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);
    }
    
    .dataTables_wrapper .dataTables_length select {
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 0.35rem 2rem 0.35rem 0.75rem;
    }
    
    .dataTables_wrapper .dataTables_info {
        color: #64748b;
        font-size: 0.9rem;
        padding-top: 1.5rem;
    }
    
    .dataTables_wrapper .dataTables_paginate {
        padding-top: 1.25rem;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border-radius: 8px !important;
        border: 1px solid transparent !important;
        padding: 0.4em 0.8em !important;
        margin: 0 2px;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: #10b981 !important;
        color: white !important;
        border: 1px solid #059669 !important;
        box-shadow: 0 4px 6px rgba(16, 185, 129, 0.2);
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.current) {
        background: #f1f5f9 !important;
        color: #334155 !important;
        border-color: #e2e8f0 !important;
    }
</style>

<div class="guru-page">
    <div class="container-fluid px-4">
        
        <div class="guru-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1><i class="fas fa-users-class me-2"></i> Siswa Bimbingan</h1>
                    <p>Daftar lengkap siswa magang yang berada di bawah bimbingan Anda.</p>
                </div>
            </div>
        </div>

        <div class="data-table-container">
            <table id="tableSiswa" class="modern-table">
                <thead>
                    <tr>
                        <th>Peserta Magang</th>
                        <th>Perusahaan / Mitra</th>
                        <th>Status Jurnal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $item)
                        @php
                            $laporans = optional($item->siswa->magangSiswa)->laporans;
                            $jurnalCount = $laporans ? $laporans->count() : 0;
                            
                            $statusJurnal = 'Belum Ada Jurnal';
                            $badgeColor = 'danger'; // red
                            $icon = 'fa-times-circle';
                            
                            if ($jurnalCount > 0) {
                                $latestJurnal = $laporans->first();
                                $daysDiff = \Carbon\Carbon::parse($latestJurnal->created_at)->diffInDays(now());
                                
                                if ($daysDiff <= 3) {
                                    $statusJurnal = 'Aktif (Terakhir ' . $daysDiff . ' hari lalu)';
                                    $badgeColor = 'success'; // green
                                    $icon = 'fa-check-circle';
                                } else {
                                    $statusJurnal = 'Tidak Aktif (Terakhir ' . $daysDiff . ' hari lalu)';
                                    $badgeColor = 'warning'; // yellow
                                    $icon = 'fa-exclamation-triangle';
                                }
                            }
                            
                            $penilaian = optional($item->siswa)->penilaian;
                            $statusNilai = $penilaian ? 'Sudah Dinilai' : 'Belum Dinilai';
                            $nilaiBadge = $penilaian ? 'success' : 'secondary';
                            
                            $noHp = optional($item->siswa->magangSiswa)->no_hp ?? optional($item->siswa)->no_hp;
                            $waLink = '#';
                            if($noHp) {
                                $waFormatted = preg_replace('/^0/', '62', preg_replace('/\D/', '', $noHp));
                                $waLink = "https://wa.me/" . $waFormatted;
                            }
                        @endphp
                        <tr>
                            <td>
                                <div class="student-profile">
                                    <div class="student-avatar">
                                        {{ substr($item->siswa->user->nama ?? 'S', 0, 1) }}
                                    </div>
                                    <div class="student-details">
                                        <span class="student-name">{{ $item->siswa->user->nama ?? '-' }}</span>
                                        <span class="student-email">{{ $item->siswa->user->email ?? 'Tidak ada email' }}</span>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div class="company-wrapper">
                                    <div class="company-icon">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <div class="company-info">
                                        <span class="company-name">{{ optional(optional($item->siswa->magangSiswa)->wakilPerusahaan)->nama_perusahaan ?? 'Siswa Mengajukan Mandiri' }}</span>
                                        <span class="position-name">
                                            <i class="fas fa-briefcase me-1"></i> 
                                            {{ optional(optional($item->siswa->magangSiswa)->opening)->posisi ?? '-' }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="jurnal-badge {{ $badgeColor }}">
                                    <i class="fas {{ $icon }}"></i> {{ $statusJurnal }}
                                </span>
                            </td>

                            <td>
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#detailModal{{ $item->id }}">
                                    <i class="fas fa-eye me-1"></i> Detail
                                </button>
                            </td>
                        </tr>
                        
                        <!-- Modal Detail -->
                        <div class="modal fade" id="detailModal{{ $item->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $item->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-light">
                                        <h5 class="modal-title" id="detailModalLabel{{ $item->id }}">
                                            <i class="fas fa-user-graduate me-2 text-primary"></i> Detail Siswa Bimbingan
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="text-center mb-4">
                                            <div class="student-avatar mx-auto mb-3" style="width: 70px; height: 70px; font-size: 2rem;">
                                                {{ substr($item->siswa->user->nama ?? 'S', 0, 1) }}
                                            </div>
                                            <h5 class="fw-bold mb-1">{{ $item->siswa->user->nama ?? '-' }}</h5>
                                            <p class="text-muted mb-0">{{ optional(optional($item->siswa->magangSiswa)->wakilPerusahaan)->nama_perusahaan ?? '-' }}</p>
                                        </div>
                                        
                                        <ul class="list-group list-group-flush mb-3">
                                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                <span><i class="fas fa-book me-2 text-muted"></i> Progres Jurnal</span>
                                                <span class="badge bg-{{ $badgeColor }} rounded-pill">{{ $jurnalCount }} Laporan</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                <span><i class="fas fa-star me-2 text-muted"></i> Status Nilai</span>
                                                <span class="badge bg-{{ $nilaiBadge }} rounded-pill">{{ $statusNilai }}</span>
                                            </li>
                                        </ul>
                                        
                                        <div class="d-grid gap-2">
                                            @if($waLink !== '#')
                                                <a href="{{ $waLink }}" target="_blank" class="btn btn-success">
                                                    <i class="fab fa-whatsapp me-2"></i> Chat Siswa
                                                </a>
                                            @else
                                                <button class="btn btn-secondary" disabled>
                                                    <i class="fab fa-whatsapp me-2"></i> Nomor HP Tidak Tersedia
                                                </button>
                                            @endif
                                            
                                            <!-- Cetak is a placeholder for now -->
                                            <button type="button" class="btn btn-outline-dark" onclick="alert('Fitur cetak sedang dalam pengembangan.')">
                                                <i class="fas fa-print me-2"></i> Cetak Data Bimbingan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <div style="font-size: 3rem; color: #cbd5e1; margin-bottom: 1rem;">
                                    <i class="fas fa-inbox"></i>
                                </div>
                                <h5>Belum ada siswa bimbingan</h5>
                                <p>Anda akan melihat daftar siswa di sini setelah ditugaskan sebagai pembimbing.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $(document).ready(function () {
        $('#tableSiswa').DataTable({
            responsive: true,
            autoWidth: false,
            language: {
                search: "Cari Siswa/Perusahaan:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                paginate: {
                    first: "Awal",
                    last: "Akhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                }
            }
        });
    });
</script>
@endpush