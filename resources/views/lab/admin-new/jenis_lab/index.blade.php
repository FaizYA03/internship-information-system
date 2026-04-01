@extends('lab.layouts.unified', ['title' => 'Manajemen Jenis Laboratorium'])

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-small">
        <li class="breadcrumb-item"><a href="{{ route('lab.admin_new.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Jenis Laboratorium</a></li>
    </ol>
</nav>
@endsection

@section('css')
<style>
    /* ====== Color Palette Cards ====== */
    .jenis-card {
        border: 1px solid #e8ecf4;
        border-radius: 16px;
        transition: all 0.25s ease;
        overflow: hidden;
    }
    .jenis-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(67, 97, 238, 0.12);
        border-color: #c7d5f8;
    }
    .jenis-card .card-icon-wrap {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }
    .jenis-card .lab-count-badge {
        font-size: 0.7rem;
        font-weight: 600;
        border-radius: 20px;
        padding: 3px 10px;
    }
    .prefix-chip {
        font-family: 'Courier New', monospace;
        font-size: 0.72rem;
        font-weight: 700;
        background: #f0f4ff;
        border: 1px dashed #c7d5f8;
        color: #4361ee;
        border-radius: 6px;
        padding: 2px 8px;
        letter-spacing: 0.06em;
    }

    /* ====== Warna Options Visual Grid ====== */
    .color-picker-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    .color-option {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        cursor: pointer;
        border: 3px solid transparent;
        transition: all 0.15s;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .color-option:hover {
        transform: scale(1.15);
    }
    .color-option.selected {
        border-color: #2d3748;
        box-shadow: 0 0 0 2px white, 0 0 0 4px #2d3748;
    }

    /* ====== Ikon Picker Grid ====== */
    .ikon-picker-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        max-height: 140px;
        overflow-y: auto;
    }
    .ikon-option {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        cursor: pointer;
        border: 2px solid #e2e8f0;
        background: #f8faff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        transition: all 0.15s;
        color: #4361ee;
    }
    .ikon-option:hover {
        border-color: #4361ee;
        background: #e8efff;
        transform: scale(1.1);
    }
    .ikon-option.selected {
        border-color: #4361ee;
        background: #e8efff;
        box-shadow: 0 0 0 2px #4361ee;
    }

    /* ====== Section Title ====== */
    .section-title {
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #4361ee;
        margin-bottom: 12px;
    }

    /* Warna bg options */
    .bg-purple { background-color: #7b2d8b !important; }
    .text-purple { color: #7b2d8b !important; }
</style>
@endsection

@section('content')

{{-- ===== Alerts ===== --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4 d-flex align-items-center gap-2" role="alert" style="border-radius: 12px;">
        <i class="bi bi-check-circle-fill fs-5"></i>
        <span>{{ session('success') }}</span>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4 d-flex align-items-center gap-2" role="alert" style="border-radius: 12px;">
        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
        <span>{{ session('error') }}</span>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ===== Page Header ===== --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color: #1a1f36;">
            <i class="bi bi-grid-3x3-gap-fill me-2 text-primary"></i>Jenis Laboratorium
        </h4>
        <p class="text-muted small mb-0">Kelola daftar jenis laboratorium — tambah, edit, dan hapus sesuai kebutuhan sekolah.</p>
    </div>
    <button class="btn btn-primary rounded-pill px-4 shadow-sm"
            data-bs-toggle="modal" data-bs-target="#modalTambah"
            id="btnTambahJenis">
        <i class="bi bi-plus-lg me-2"></i>Tambah Jenis Baru
    </button>
</div>

{{-- ===== Stats Row ===== --}}
<div class="row g-3 mb-4">
    <div class="col-sm-4">
        <div class="card border-0 shadow-sm text-center py-3" style="border-radius: 14px; background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
            <div class="text-white display-6 fw-bold">{{ $jenisLab->count() }}</div>
            <div class="text-white-75 small">Total Jenis Lab</div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card border-0 shadow-sm text-center py-3" style="border-radius: 14px; background: linear-gradient(135deg, #2a9d8f 0%, #264653 100%);">
            <div class="text-white display-6 fw-bold">{{ $totalLabor }}</div>
            <div class="text-white-75 small">Total Laboratorium</div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card border-0 shadow-sm text-center py-3" style="border-radius: 14px; background: linear-gradient(135deg, #f4a261 0%, #e76f51 100%);">
            <div class="text-white display-6 fw-bold">{{ $jenisLab->where('lab_count', 0)->count() }}</div>
            <div class="text-white-75 small">Jenis Belum Dipakai</div>
        </div>
    </div>
</div>

{{-- ===== Grid Jenis Lab ===== --}}
@if($jenisLab->isEmpty())
    <div class="card border-0 shadow-sm text-center py-5" style="border-radius: 16px;">
        <div class="mb-3" style="font-size: 3rem; opacity: 0.3;">🧪</div>
        <h5 class="text-muted fw-semibold">Belum Ada Jenis Laboratorium</h5>
        <p class="text-muted small mb-4">Mulai tambahkan jenis lab pertama untuk sistem Anda.</p>
        <div>
            <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="bi bi-plus-lg me-2"></i>Tambah Sekarang
            </button>
        </div>
    </div>
@else
    <div class="row g-3">
        @foreach($jenisLab as $jenis)
        @php
            $colorHex = [
                'primary'   => '#4361ee',
                'danger'    => '#e63946',
                'warning'   => '#f4a261',
                'success'   => '#2a9d8f',
                'purple'    => '#7b2d8b',
                'info'      => '#0dcaf0',
                'secondary' => '#6c757d',
            ][$jenis->warna] ?? '#6c757d';

            $bgSoft = [
                'primary'   => '#e8efff',
                'danger'    => '#ffeef0',
                'warning'   => '#fff4e8',
                'success'   => '#e8f8f6',
                'purple'    => '#f5e8ff',
                'info'      => '#e8f7ff',
                'secondary' => '#f0f0f0',
            ][$jenis->warna] ?? '#f0f0f0';
        @endphp
        <div class="col-lg-4 col-md-6">
            <div class="card jenis-card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start gap-3 mb-3">
                        <div class="card-icon-wrap" style="background: {{ $bgSoft }}; color: {{ $colorHex }}">
                            <i class="bi {{ $jenis->ikon ?? 'bi-building' }}"></i>
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <h6 class="fw-bold mb-1 text-truncate" style="color: #1a1f36; font-size: 1rem;">
                                {{ $jenis->nama }}
                            </h6>
                            {{-- Prefix chip removed --}}
                        </div>
                        <span class="lab-count-badge badge {{ $jenis->lab_count > 0 ? 'bg-success' : 'bg-light text-muted border' }}">
                            {{ $jenis->lab_count }} Lab
                        </span>
                    </div>

                    @if($jenis->deskripsi)
                        <p class="text-muted small mb-3" style="line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ $jenis->deskripsi }}
                        </p>
                    @else
                        <p class="text-muted small mb-3 fst-italic">Belum ada deskripsi</p>
                    @endif

                    <div class="d-flex align-items-center gap-2 mt-auto pt-2 border-top">
                        <span class="badge" style="background: {{ $colorHex }}; font-size: 0.65rem; padding: 4px 8px;">
                            <i class="bi {{ $jenis->ikon ?? 'bi-building' }} me-1"></i>{{ ucfirst($jenis->warna) }}
                        </span>
                        <div class="ms-auto d-flex gap-1">
                            <button class="btn btn-sm btn-outline-primary rounded-pill px-3"
                                    onclick="openEditModal({{ $jenis->id }}, '{{ addslashes($jenis->nama) }}', '{{ addslashes($jenis->deskripsi ?? '') }}', '{{ $jenis->ikon ?? 'bi-building' }}', '{{ $jenis->warna }}')"
                                    title="Edit">
                                <i class="bi bi-pencil me-1"></i>Edit
                            </button>
                            <button class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                    onclick="confirmDelete({{ $jenis->id }}, '{{ addslashes($jenis->nama) }}', {{ $jenis->lab_count }})"
                                    title="Hapus">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endif

{{-- ============================= MODAL TAMBAH ============================= --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <div>
                    <h5 class="modal-title fw-bold" style="color: #1a1f36;">
                        <i class="bi bi-plus-circle-fill me-2 text-primary"></i>Tambah Jenis Laboratorium Baru
                    </h5>
                    <p class="text-muted small mb-0">Isi form berikut untuk mendaftarkan jenis lab baru.</p>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('lab.admin_new.master_data.jenis_lab.store') }}" method="POST">
                @csrf
                <div class="modal-body px-4 py-3">
                    <div class="row g-3">
                        {{-- Nama --}}
                        <div class="col-md-8">
                            <label class="form-label fw-semibold small">Nama Jenis <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" placeholder="Contoh: Biologi, Elektronika..." required maxlength="100">
                        </div>
                        {{-- Prefix Kode removed --}}

                        {{-- Deskripsi --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="2"
                                      placeholder="Deskripsi singkat tentang jenis laboratorium ini..."></textarea>
                        </div>

                        {{-- Ikon --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">
                                <i class="bi bi-grid me-1"></i>Pilih Ikon
                            </label>
                            <input type="hidden" name="ikon" id="addIkon" value="bi-building">
                            <div class="ikon-picker-grid" id="addIkonGrid">
                                @foreach($ikonOptions as $ikonClass => $label)
                                    <div class="ikon-option {{ $ikonClass === 'bi-building' ? 'selected' : '' }}"
                                         data-ikon="{{ $ikonClass }}"
                                         title="{{ $label }}"
                                         onclick="selectIkon('{{ $ikonClass }}', 'add')">
                                        <i class="bi {{ $ikonClass }}"></i>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Warna --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">
                                <i class="bi bi-palette me-1"></i>Pilih Warna
                            </label>
                            <input type="hidden" name="warna" id="addWarna" value="secondary">
                            <div class="color-picker-grid" id="addWarnaGrid">
                                @php
                                $colorMap = [
                                    'primary'   => '#4361ee',
                                    'danger'    => '#e63946',
                                    'warning'   => '#f4a261',
                                    'success'   => '#2a9d8f',
                                    'purple'    => '#7b2d8b',
                                    'info'      => '#0dcaf0',
                                    'secondary' => '#6c757d',
                                ];
                                @endphp
                                @foreach($colorMap as $warna => $hex)
                                    <div class="color-option {{ $warna === 'secondary' ? 'selected' : '' }}"
                                         data-warna="{{ $warna }}"
                                         title="{{ $warnaOptions[$warna] }}"
                                         style="background-color: {{ $hex }};"
                                         onclick="selectWarna('{{ $warna }}', 'add')">
                                        <i class="bi bi-check text-white" style="display: {{ $warna === 'secondary' ? 'block' : 'none' }};"></i>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-2">
                                <span class="text-muted small" id="addWarnaLabel">Abu-abu</span>
                            </div>
                        </div>

                        {{-- Preview --}}
                        <div class="col-12">
                            <div class="d-flex align-items-center gap-3 p-3" style="background: #f8faff; border-radius: 12px; border: 1px dashed #c7d5f8;">
                                <div id="addPreviewIcon" class="d-flex align-items-center justify-content-center"
                                     style="width: 44px; height: 44px; border-radius: 12px; background: #f0f0f0; color: #6c757d; font-size: 1.2rem;">
                                    <i class="bi bi-building"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold text-dark small" id="addPreviewNama">Nama Jenis Lab</div>
                                    <div class="text-muted" style="font-size: 0.7rem;" id="addPreviewDesc">Preview kartu laboratorium</div>
                                </div>
                                <span class="badge ms-auto" style="background: #6c757d; font-size: 0.7rem;" id="addPreviewBadge">
                                    <i class="bi bi-building me-1"></i>Abu-abu
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-5">
                        <i class="bi bi-plus-lg me-2"></i>Tambah Jenis Lab
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ============================= MODAL EDIT ============================= --}}
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <div>
                    <h5 class="modal-title fw-bold" style="color: #1a1f36;">
                        <i class="bi bi-pencil-square me-2 text-primary"></i>Edit Jenis Laboratorium
                    </h5>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body px-4 py-3">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Nama Jenis <span class="text-danger">*</span></label>
                            <input type="text" name="nama" id="editNama" class="form-control" required maxlength="100">
                        </div>
                        {{-- Prefix Kode removed --}}

                        <div class="col-12">
                            <label class="form-label fw-semibold small">Deskripsi</label>
                            <textarea name="deskripsi" id="editDeskripsi" class="form-control" rows="2"></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small"><i class="bi bi-grid me-1"></i>Pilih Ikon</label>
                            <input type="hidden" name="ikon" id="editIkon">
                            <div class="ikon-picker-grid" id="editIkonGrid">
                                @foreach($ikonOptions as $ikonClass => $label)
                                    <div class="ikon-option"
                                         data-ikon="{{ $ikonClass }}"
                                         title="{{ $label }}"
                                         onclick="selectIkon('{{ $ikonClass }}', 'edit')">
                                        <i class="bi {{ $ikonClass }}"></i>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small"><i class="bi bi-palette me-1"></i>Pilih Warna</label>
                            <input type="hidden" name="warna" id="editWarna">
                            <div class="color-picker-grid" id="editWarnaGrid">
                                @foreach($colorMap as $warna => $hex)
                                    <div class="color-option"
                                         data-warna="{{ $warna }}"
                                         title="{{ $warnaOptions[$warna] }}"
                                         style="background-color: {{ $hex }};"
                                         onclick="selectWarna('{{ $warna }}', 'edit')">
                                        <i class="bi bi-check text-white" style="display: none;"></i>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-2">
                                <span class="text-muted small" id="editWarnaLabel"></span>
                            </div>
                        </div>

                        {{-- Preview --}}
                        <div class="col-12">
                            <div class="d-flex align-items-center gap-3 p-3" style="background: #f8faff; border-radius: 12px; border: 1px dashed #c7d5f8;">
                                <div id="editPreviewIcon" class="d-flex align-items-center justify-content-center"
                                     style="width: 44px; height: 44px; border-radius: 12px; font-size: 1.2rem;">
                                    <i></i>
                                </div>
                                <div>
                                    <div class="fw-semibold text-dark small" id="editPreviewNama"></div>
                                    <div class="text-muted" style="font-size: 0.7rem;" id="editPreviewDesc">Preview kartu laboratorium</div>
                                </div>
                                <span class="badge ms-auto" id="editPreviewBadge" style="font-size: 0.7rem;"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-5">
                        <i class="bi bi-check-lg me-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ============================= MODAL HAPUS ============================= --}}
<div class="modal fade" id="modalHapus" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow" style="border-radius: 20px;">
            <div class="modal-body text-center p-4">
                <div class="mb-3" style="font-size: 2.5rem;">🗑️</div>
                <h5 class="fw-bold mb-2" id="hapusTitle">Hapus Jenis Lab?</h5>
                <p class="text-muted small mb-4" id="hapusDesc">Yakin ingin menghapus jenis ini?</p>
                <form id="hapusForm" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="d-flex gap-2 justify-content-center">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger rounded-pill px-4">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
// ================================================================
// Konfigurasi Warna
// ================================================================
const warnaConfig = {
    'primary'   : { hex: '#4361ee', bg: '#e8efff', label: 'Biru' },
    'danger'    : { hex: '#e63946', bg: '#ffeef0', label: 'Merah' },
    'warning'   : { hex: '#f4a261', bg: '#fff4e8', label: 'Oranye' },
    'success'   : { hex: '#2a9d8f', bg: '#e8f8f6', label: 'Hijau' },
    'purple'    : { hex: '#7b2d8b', bg: '#f5e8ff', label: 'Ungu' },
    'info'      : { hex: '#0dcaf0', bg: '#e8f7ff', label: 'Cyan' },
    'secondary' : { hex: '#6c757d', bg: '#f0f0f0', label: 'Abu-abu' },
};

// ================================================================
// Pilih Ikon
// ================================================================
function selectIkon(ikonClass, context) {
    const prefix = context; // 'add' or 'edit'

    // Update hidden input
    document.getElementById(prefix + 'Ikon').value = ikonClass;

    // Highlight terpilih
    document.querySelectorAll(`#${prefix}IkonGrid .ikon-option`).forEach(el => {
        el.classList.remove('selected');
    });
    document.querySelector(`#${prefix}IkonGrid [data-ikon="${ikonClass}"]`)?.classList.add('selected');

    // Update preview icon
    updatePreview(prefix);
}

// ================================================================
// Pilih Warna
// ================================================================
function selectWarna(warna, context) {
    const prefix = context;

    document.getElementById(prefix + 'Warna').value = warna;

    // Highlight terpilih - tampilkan checkmark
    document.querySelectorAll(`#${prefix}WarnaGrid .color-option`).forEach(el => {
        el.classList.remove('selected');
        el.querySelector('i').style.display = 'none';
    });
    const selected = document.querySelector(`#${prefix}WarnaGrid [data-warna="${warna}"]`);
    selected?.classList.add('selected');
    if (selected) selected.querySelector('i').style.display = 'block';

    // Update label
    const label = warnaConfig[warna]?.label ?? warna;
    const labelEl = document.getElementById(prefix + 'WarnaLabel');
    if (labelEl) labelEl.textContent = label;

    updatePreview(prefix);
}

// ================================================================
// Update Preview Card
// ================================================================
function updatePreview(prefix) {
    const ikonClass = document.getElementById(prefix + 'Ikon')?.value ?? 'bi-building';
    const warna = document.getElementById(prefix + 'Warna')?.value ?? 'secondary';
    const nama = (prefix === 'add')
        ? (document.querySelector('[name="nama"]')?.value || 'Nama Jenis Lab')
        : (document.getElementById('editNama')?.value || 'Nama Jenis Lab');

    const cfg = warnaConfig[warna] ?? warnaConfig['secondary'];

    // Icon wrap
    const iconWrap = document.getElementById(prefix + 'PreviewIcon');
    if (iconWrap) {
        iconWrap.style.background = cfg.bg;
        iconWrap.style.color = cfg.hex;
        iconWrap.innerHTML = `<i class="bi ${ikonClass}"></i>`;
    }

    // Nama
    const namaEl = document.getElementById(prefix + 'PreviewNama');
    if (namaEl) namaEl.textContent = nama || 'Nama Jenis Lab';

    // Badge
    const badgeEl = document.getElementById(prefix + 'PreviewBadge');
    if (badgeEl) {
        badgeEl.style.background = cfg.hex;
        badgeEl.innerHTML = `<i class="bi ${ikonClass} me-1"></i>${cfg.label}`;
    }
}

document.querySelector('#modalTambah [name="nama"]')?.addEventListener('input', function () {
    updatePreview('add');
});

// ================================================================
// Buka Modal Edit
// ================================================================
function openEditModal(id, nama, deskripsi, ikon, warna) {
    const form = document.getElementById('editForm');
    form.action = `/lab/admin-new/master-data/jenis-lab/${id}`;

    document.getElementById('editNama').value = nama;
    document.getElementById('editDeskripsi').value = deskripsi;

    // Select ikon
    selectIkon(ikon || 'bi-building', 'edit');

    // Select warna
    selectWarna(warna || 'secondary', 'edit');

    // Update preview
    updatePreview('edit');

    const modal = new bootstrap.Modal(document.getElementById('modalEdit'));
    modal.show();
}

// ================================================================
// Confirm Hapus
// ================================================================
function confirmDelete(id, nama, labCount) {
    const form = document.getElementById('hapusForm');
    form.action = `/lab/admin-new/master-data/jenis-lab/${id}`;

    document.getElementById('hapusTitle').textContent = `Hapus "${nama}"?`;

    if (labCount > 0) {
        document.getElementById('hapusDesc').innerHTML =
            `<span class="text-danger fw-semibold">⚠️ Tidak dapat dihapus!</span><br>
             Jenis ini masih digunakan oleh <strong>${labCount} laboratorium</strong>.<br>
             Hapus/ubah laboratorium tersebut terlebih dahulu.`;
        document.querySelector('#hapusForm button[type="submit"]').disabled = true;
    } else {
        document.getElementById('hapusDesc').innerHTML =
            `Yakin ingin menghapus jenis <strong>"${nama}"</strong>?<br>
             <span class="text-muted small">Tindakan ini tidak dapat dibatalkan.</span>`;
        document.querySelector('#hapusForm button[type="submit"]').disabled = false;
    }

    new bootstrap.Modal(document.getElementById('modalHapus')).show();
}

// ================================================================
// Init add form preview
// ================================================================
document.addEventListener('DOMContentLoaded', function () {
    updatePreview('add');
});
</script>
@endsection
