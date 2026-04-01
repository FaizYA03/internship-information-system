@extends('lab.layouts.unified')

@section('css')
<style>
    .filter-card {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 2px 12px rgba(37,99,235,0.06);
        border: none;
        margin-bottom: 24px;
    }

    .stat-card {
        border-radius: 14px;
        border: none;
        padding: 20px 24px;
        display: flex;
        align-items: center;
        gap: 18px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 20px;
        background: #fff;
    }

    .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .hari-section {
        margin-bottom: 28px;
    }

    .hari-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 20px;
        border-radius: 10px 10px 0 0;
        font-weight: 700;
        font-size: 0.95rem;
        letter-spacing: 0.5px;
    }

    .jadwal-table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        border-radius: 0 0 12px 12px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    }

    .jadwal-table thead th {
        background: #F8FAFF;
        font-size: 0.78rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748B;
        padding: 10px 16px;
        border-bottom: 1px solid #E2E8F0;
    }

    .jadwal-table tbody td {
        padding: 12px 16px;
        border-bottom: 1px solid #F1F5F9;
        font-size: 0.875rem;
        color: #334155;
        vertical-align: middle;
    }

    .jadwal-table tbody tr:last-child td {
        border-bottom: none;
    }

    .jadwal-table tbody tr:hover {
        background: #F8FAFF;
    }

    .time-badge {
        background: #EFF6FF;
        color: #1D4ED8;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .lab-badge {
        background: #F0FDF4;
        color: #15803D;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .action-btn {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        font-size: 0.8rem;
        transition: all 0.2s;
    }

    .action-btn-edit {
        background: #EFF6FF;
        color: #2563EB;
    }

    .action-btn-edit:hover {
        background: #2563EB;
        color: #fff;
    }

    .action-btn-delete {
        background: #FEF2F2;
        color: #DC2626;
    }

    .action-btn-delete:hover {
        background: #DC2626;
        color: #fff;
    }

    .empty-hari {
        text-align: center;
        padding: 20px;
        color: #94A3B8;
        font-size: 0.85rem;
        background: #fff;
        border-radius: 0 0 12px 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    }

    .hari-colors {
        Senin: #2563EB;
    }

    .hari-senin    { background: linear-gradient(90deg, #2563EB, #3B82F6); color: #fff; }
    .hari-selasa   { background: linear-gradient(90deg, #7C3AED, #A78BFA); color: #fff; }
    .hari-rabu     { background: linear-gradient(90deg, #0891B2, #22D3EE); color: #fff; }
    .hari-kamis    { background: linear-gradient(90deg, #16A34A, #4ADE80); color: #fff; }
    .hari-jumat    { background: linear-gradient(90deg, #EA580C, #FB923C); color: #fff; }
    .hari-sabtu    { background: linear-gradient(90deg, #DC2626, #F87171); color: #fff; }

    .add-jadwal-btn {
        background: linear-gradient(135deg, #2563EB, #1D4ED8);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 8px 18px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
        cursor: pointer;
    }

    .add-jadwal-btn:hover {
        background: linear-gradient(135deg, #1D4ED8, #1E40AF);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(37,99,235,0.3);
        color: #fff;
    }

    @media (max-width: 768px) {
        .jadwal-table thead { display: none; }
        .jadwal-table tbody td {
            display: block;
            padding: 6px 12px;
        }
        .jadwal-table tbody tr {
            border-bottom: 1px solid #E2E8F0;
            padding: 8px 0;
            display: block;
        }
    }
</style>
@endsection

@section('content')
@php $title = 'Jadwal Laboratorium'; @endphp

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0" style="color:#1E293B;">Jadwal Laboratorium</h4>
        <p class="text-muted mb-0" style="font-size:0.85rem;">Kelola jadwal penggunaan semua laboratorium</p>
    </div>
    <button class="add-jadwal-btn" data-bs-toggle="modal" data-bs-target="#modalTambahJadwal">
        <i class="bi bi-plus-circle"></i> Tambah Jadwal
    </button>
</div>

{{-- Alerts --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
@if(session('warning'))
<div class="alert alert-warning alert-dismissible fade show rounded-3" role="alert">
    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('warning') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Stats Row --}}
<div class="row mb-3">
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#EFF6FF;">
                <i class="bi bi-calendar-week" style="color:#2563EB;"></i>
            </div>
            <div>
                <div style="font-size:1.5rem;font-weight:700;color:#1E293B;">{{ $totalJadwal }}</div>
                <div style="font-size:0.78rem;color:#64748B;">Total Jadwal</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#F0FDF4;">
                <i class="bi bi-building" style="color:#16A34A;"></i>
            </div>
            <div>
                <div style="font-size:1.5rem;font-weight:700;color:#1E293B;">{{ $laboratoriums->count() }}</div>
                <div style="font-size:0.78rem;color:#64748B;">Laboratorium</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#FFF7ED;">
                <i class="bi bi-clock-history" style="color:#EA580C;"></i>
            </div>
            <div>
                <div style="font-size:1.5rem;font-weight:700;color:#1E293B;">
                    {{ $jadwals->where('hari', now()->locale('id')->isoFormat('dddd'))->count() }}
                </div>
                <div style="font-size:0.78rem;color:#64748B;">Jadwal Hari Ini</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div class="stat-icon" style="background:#FDF4FF;">
                <i class="bi bi-people-fill" style="color:#7C3AED;"></i>
            </div>
            <div>
                <div style="font-size:1.5rem;font-weight:700;color:#1E293B;">
                    {{ $jadwals->whereNotNull('guru_id')->unique('guru_id')->count() }}
                </div>
                <div style="font-size:0.78rem;color:#64748B;">Guru Terdaftar</div>
            </div>
        </div>
    </div>
</div>

{{-- Filter Card --}}
<div class="filter-card p-4 mb-4">
    <form method="GET" action="{{ route('lab.admin_new.jadwal.index') }}" class="row g-3 align-items-end">
        <div class="col-md-4">
            <label class="form-label fw-semibold" style="font-size:0.82rem;color:#374151;">Laboratorium</label>
            <select name="labor_id" class="form-select form-select-sm">
                <option value="">Semua Laboratorium</option>
                @foreach($laboratoriums as $lab)
                    <option value="{{ $lab->id }}" {{ request('labor_id') == $lab->id ? 'selected' : '' }}>
                        {{ $lab->nama_labor }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold" style="font-size:0.82rem;color:#374151;">Hari</label>
            <select name="hari" class="form-select form-select-sm">
                <option value="">Semua Hari</option>
                @foreach($hariList as $h)
                    <option value="{{ $h }}" {{ request('hari') == $h ? 'selected' : '' }}>{{ $h }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold" style="font-size:0.82rem;color:#374151;">Cari</label>
            <input type="text" name="search" class="form-control form-control-sm"
                   placeholder="Mata pelajaran, kelas..." value="{{ request('search') }}">
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm w-100">
                <i class="bi bi-search me-1"></i>Filter
            </button>
            <a href="{{ route('lab.admin_new.jadwal.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-x-lg"></i>
            </a>
        </div>
    </form>
</div>

{{-- Jadwal by Hari --}}
@foreach($hariList as $hari)
@php
    $jadwalHari = $jadwalByHari[$hari];
    $hariClass = 'hari-' . strtolower($hari);
    $hariIcons = [
        'Senin' => 'bi-1-circle-fill',
        'Selasa' => 'bi-2-circle-fill',
        'Rabu' => 'bi-3-circle-fill',
        'Kamis' => 'bi-4-circle-fill',
        'Jumat' => 'bi-5-circle-fill',
        'Sabtu' => 'bi-6-circle-fill',
    ];
@endphp

<div class="hari-section">
    <div class="hari-header {{ $hariClass }}">
        <i class="bi {{ $hariIcons[$hari] ?? 'bi-calendar' }}"></i>
        <span>{{ $hari }}</span>
        <span class="ms-auto badge bg-white bg-opacity-30" style="font-size:0.75rem;font-weight:600;">
            {{ $jadwalHari->count() }} jadwal
        </span>
    </div>

    @if($jadwalHari->count() > 0)
    <div class="table-responsive">
        <table class="jadwal-table">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Mata Pelajaran</th>
                    <th>Kelas</th>
                    <th>Guru</th>
                    <th>Laboratorium</th>
                    <th>Keterangan</th>
                    <th style="width:80px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($jadwalHari as $jadwal)
                <tr>
                    <td>
                        <span class="time-badge">
                            <i class="bi bi-clock me-1"></i>
                            {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} -
                            {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                        </span>
                    </td>
                    <td>
                        <span class="fw-semibold">{{ $jadwal->mata_pelajaran }}</span>
                    </td>
                    <td>
                        @if($jadwal->kelas_id && $jadwal->kelas_relation)
                            <span class="badge bg-primary text-white border">{{ $jadwal->kelas_relation->nama_kelas }}</span>
                        @elseif($jadwal->kelas)
                            <span class="badge bg-light text-dark border">{{ $jadwal->kelas }}</span>
                        @else
                            <span class="text-muted small">-</span>
                        @endif
                    </td>
                    <td>
                        @if($jadwal->guru)
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#2563EB,#7C3AED);display:flex;align-items:center;justify-content:center;color:#fff;font-size:0.7rem;font-weight:700;flex-shrink:0;">
                                    {{ strtoupper(substr($jadwal->guru->nama ?? $jadwal->guru->name, 0, 1)) }}
                                </div>
                                <span>{{ $jadwal->guru->nama ?? $jadwal->guru->name }}</span>
                            </div>
                        @else
                            <span class="text-muted small">-</span>
                        @endif
                    </td>
                    <td>
                        @if($jadwal->labor)
                            <span class="lab-badge">
                                <i class="bi bi-building me-1"></i>{{ $jadwal->labor->nama_labor }}
                            </span>
                        @else
                            <span class="text-muted small">-</span>
                        @endif
                    </td>
                    <td>
                        <span class="text-muted small">{{ $jadwal->keterangan ?? '-' }}</span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <button class="action-btn action-btn-edit"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditJadwal"
                                    data-id="{{ $jadwal->id }}"
                                    data-labor_id="{{ $jadwal->labor_id }}"
                                    data-mata_pelajaran="{{ $jadwal->mata_pelajaran }}"
                                    data-guru_id="{{ $jadwal->guru_id }}"
                                    data-kelas_id="{{ $jadwal->kelas_id }}"
                                    data-kelas="{{ $jadwal->kelas }}"
                                    data-hari="{{ $jadwal->hari }}"
                                    data-jam_mulai="{{ $jadwal->jam_mulai }}"
                                    data-jam_selesai="{{ $jadwal->jam_selesai }}"
                                    data-keterangan="{{ $jadwal->keterangan }}"
                                    title="Edit Jadwal">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('lab.admin_new.jadwal.destroy', $jadwal->id) }}" method="POST"
                                  onsubmit="return confirm('Hapus jadwal ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn action-btn-delete" title="Hapus Jadwal">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="empty-hari">
        <i class="bi bi-calendar-x me-2" style="font-size:1.2rem;color:#CBD5E1;"></i>
        Belum ada jadwal untuk hari {{ $hari }}
    </div>
    @endif
</div>
@endforeach

{{-- Modal Tambah Jadwal --}}
<div class="modal fade" id="modalTambahJadwal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius:16px;border:none;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-calendar-plus me-2 text-primary"></i>Tambah Jadwal Lab
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-3">
                <form action="#" method="POST" id="formTambahJadwal">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Laboratorium <span class="text-danger">*</span></label>
                            <select name="labor_id_select" id="laborId_tambah" class="form-select" required
                                    onchange="updateFormAction(this.value)">
                                <option value="">-- Pilih Laboratorium --</option>
                                @foreach($laboratoriums as $lab)
                                    <option value="{{ $lab->id }}">{{ $lab->nama_labor }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Mata Pelajaran <span class="text-danger">*</span></label>
                            <select name="mata_pelajaran" class="form-select select-mata-pelajaran" data-target="#guru_id_tambah" required>
                                <option value="">-- Pilih Mata Pelajaran --</option>
                                @foreach($mataPelajaranList as $mp)
                                    <option value="{{ $mp->nama_mata_pelajaran }}">{{ $mp->nama_mata_pelajaran }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Hubungkan ke Kelas <span class="text-muted" style="font-size: 0.7rem">(Penting agar muncul di Jadwal Siswa)</span></label>
                            <select name="kelas_id" class="form-select">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($kelasList as $kelas)
                                    <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}{{ $kelas->jurusan ? ' - ' . $kelas->jurusan : '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Label Kelas (Tampilan)</label>
                            <input type="text" name="kelas" class="form-control" placeholder="Contoh: XI RPL 1">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Guru Pengampu</label>
                            <select name="guru_id" id="guru_id_tambah" class="form-select">
                                <option value="">-- Pilih Guru (opsional) --</option>
                                <!-- Akan terisi otomatis oleh JS berdasar mapel -->
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Hari <span class="text-danger">*</span></label>
                            <select name="hari" class="form-select" required>
                                <option value="">-- Pilih Hari --</option>
                                @foreach($hariList as $h)
                                    <option value="{{ $h }}">{{ $h }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Jam Mulai <span class="text-danger">*</span></label>
                            <input type="time" name="jam_mulai" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Jam Selesai <span class="text-danger">*</span></label>
                            <input type="time" name="jam_selesai" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="2"
                                      placeholder="Keterangan tambahan (opsional)"></textarea>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-2"></i>Simpan Jadwal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal Edit Jadwal --}}
<div class="modal fade" id="modalEditJadwal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius:16px;border:none;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-pencil-square me-2 text-warning"></i>Edit Jadwal
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-3">
                <form id="formEditJadwal" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Laboratorium <span class="text-danger">*</span></label>
                            <select name="labor_id_edit" id="laborId_edit" class="form-select" required>
                                <option value="">-- Pilih Laboratorium --</option>
                                @foreach($laboratoriums as $lab)
                                    <option value="{{ $lab->id }}">{{ $lab->nama_labor }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Mata Pelajaran <span class="text-danger">*</span></label>
                            <select name="mata_pelajaran" id="edit_mata_pelajaran" class="form-select select-mata-pelajaran" data-target="#edit_guru_id" required>
                                <option value="">-- Pilih Mata Pelajaran --</option>
                                @foreach($mataPelajaranList as $mp)
                                    <option value="{{ $mp->nama_mata_pelajaran }}">{{ $mp->nama_mata_pelajaran }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Hubungkan ke Kelas</label>
                            <select name="kelas_id" id="edit_kelas_id" class="form-select">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($kelasList as $kelas)
                                    <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}{{ $kelas->jurusan ? ' - ' . $kelas->jurusan : '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Label Kelas (Tampilan)</label>
                            <input type="text" name="kelas" id="edit_kelas" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Guru Pengampu</label>
                            <select name="guru_id" id="edit_guru_id" class="form-select">
                                <option value="">-- Pilih Guru (opsional) --</option>
                                <!-- Akan diisi otomatis oleh JS berdasar mapel -->
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Hari <span class="text-danger">*</span></label>
                            <select name="hari" id="edit_hari" class="form-select" required>
                                <option value="">-- Pilih Hari --</option>
                                @foreach($hariList as $h)
                                    <option value="{{ $h }}">{{ $h }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Jam Mulai <span class="text-danger">*</span></label>
                            <input type="time" name="jam_mulai" id="edit_jam_mulai" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Jam Selesai <span class="text-danger">*</span></label>
                            <input type="time" name="jam_selesai" id="edit_jam_selesai" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Keterangan</label>
                            <textarea name="keterangan" id="edit_keterangan" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning px-4">
                            <i class="bi bi-save me-2"></i>Update Jadwal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    // Update action form tambah berdasarkan labor yang dipilih
    function updateFormAction(laborId) {
        if (laborId) {
            const baseUrl = '{{ url("lab/admin-new/laboratorium") }}';
            document.getElementById('formTambahJadwal').action = baseUrl + '/' + laborId + '/jadwal';
        }
    }

    // Populate edit modal
    document.querySelectorAll('[data-bs-target="#modalEditJadwal"]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const id          = this.dataset.id;
            const laborId     = this.dataset.labor_id;
            const mataPel     = this.dataset.mata_pelajaran;
            const guruId      = this.dataset.guru_id;
            const kelas       = this.dataset.kelas;
            const hari        = this.dataset.hari;
            const jamMulai    = this.dataset.jam_mulai;
            const jamSelesai  = this.dataset.jam_selesai;
            const keterangan  = this.dataset.keterangan;

            // Set form action
            const editUrl = '{{ url("lab/admin-new/jadwal") }}/' + id;
            document.getElementById('formEditJadwal').action = editUrl;

            // Fill fields
            document.getElementById('laborId_edit').value       = laborId;
            document.getElementById('edit_mata_pelajaran').value = mataPel;
            document.getElementById('edit_mata_pelajaran').dispatchEvent(new Event('change')); // Trigger update of gurus
            document.getElementById('edit_guru_id').value        = guruId || '';
            document.getElementById('edit_kelas_id').value       = this.dataset.kelas_id || '';
            document.getElementById('edit_kelas').value          = kelas || '';
            document.getElementById('edit_hari').value           = hari;
            document.getElementById('edit_jam_mulai').value      = jamMulai;
            document.getElementById('edit_jam_selesai').value    = jamSelesai;
            document.getElementById('edit_keterangan').value     = keterangan || '';
        });
    });

    // Validate form tambah — wajib pilih laboratorium
    document.getElementById('formTambahJadwal').addEventListener('submit', function(e) {
        const laborId = document.getElementById('laborId_tambah').value;
        if (!laborId) {
            e.preventDefault();
            alert('Silakan pilih laboratorium terlebih dahulu!');
        }
    });

    // Auto-fill Hubungkan Ke Kelas
    document.querySelectorAll('select[name="kelas_id"]').forEach(function(select) {
        select.addEventListener('change', function() {
            const form = this.closest('form');
            const targetInput = form.querySelector('input[name="kelas"]');
            
            if (this.value) {
                targetInput.value = this.options[this.selectedIndex].text;
            } else {
                targetInput.value = '';
            }
        });
    });

    // Validasi dan Filter Guru Berdasarkan Mata Pelajaran
    const mapelGuruMap = {!! $mapelGuruMapJson !!};

    document.querySelectorAll('.select-mata-pelajaran').forEach(function(select) {
        select.addEventListener('change', function() {
            const targetId = this.getAttribute('data-target');
            const guruSelect = document.querySelector(targetId);
            const selectedMapel = this.value;
            
            // clear existing options
            guruSelect.innerHTML = '<option value="">-- Pilih Guru (opsional) --</option>';
            
            // populate if there are matched gurus
            if (selectedMapel && mapelGuruMap[selectedMapel]) {
                const gurus = mapelGuruMap[selectedMapel];
                gurus.forEach(function(guru) {
                    const option = document.createElement('option');
                    option.value = guru.id;
                    option.textContent = guru.nama;
                    guruSelect.appendChild(option);
                });
            }
        });
    });
</script>
@endsection
