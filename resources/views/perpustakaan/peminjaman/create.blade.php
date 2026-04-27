@extends('perpustakaan.layouts.main')

@section('content')
<section class="form-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10 col-12">
                <div class="page-header text-center mb-4">
                    <h1 class="page-title">Formulir Peminjaman Buku</h1>
                    <p class="text-muted">Isi formulir berikut untuk meminjam buku dari perpustakaan SMK Negeri 5 Padang</p>
                </div>

                <div class="form-card">
                    <div class="form-card-body">
                        <form action="{{ route('perpustakaan.peminjaman.store') }}" method="POST">
                            @csrf

                            <div class="form-group mb-4">
                                <label for="nama" class="form-label">
                                    <i class="bi bi-person"></i> Nama Peminjam
                                </label>
                                <div class="input-wrapper {{ $isStudent ? 'input-readonly' : '' }}">
                                    <input
                                        type="text"
                                        name="nama"
                                        id="nama"
                                        class="form-control @error('nama') is-invalid @enderror"
                                        value="{{ $isStudent ? $nama : old('nama') }}"
                                        placeholder="Masukkan nama lengkap"
                                        {{ $isStudent ? 'readonly' : '' }}
                                        required>
                                    @if($isStudent)
                                        <span class="input-tag">
                                            <i class="bi bi-lock"></i> Otomatis
                                        </span>
                                    @endif
                                </div>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if($isStudent)
                                    <div class="form-text">
                                        <i class="bi bi-info-circle"></i> Nama peminjam otomatis menggunakan nama akun Anda
                                    </div>
                                @endif
                            </div>

                            <div class="form-group mb-4">
                                <label for="buku_id" class="form-label">
                                    <i class="bi bi-journal"></i> Pilih Buku (Maksimal 2 buku)
                                </label>
                                <p class="text-muted small mb-3">
                                    <i class="bi bi-info-circle"></i> Anda dapat meminjam maksimal 2 buku. Jika Anda sudah memiliki pinjaman aktif, pastikan totalnya tidak melebihi 2.
                                </p>
                                
                                @if($selectedBuku)
                                    {{-- Slot 1: Pre-selected Book --}}
                                    <div class="card bg-light border-0 shadow-sm mb-3 position-relative">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                @if($selectedBuku->cover_path)
                                                    <div class="col-md-3 text-center mb-3 mb-md-0">
                                                        <img src="{{ asset('storage/' . $selectedBuku->cover_path) }}" alt="Cover" class="img-fluid rounded" style="max-height: 100px; object-fit: cover;">
                                                    </div>
                                                @endif
                                                <div class="{{ $selectedBuku->cover_path ? 'col-md-9' : 'col-12' }}">
                                                    <h6 class="fw-bold text-primary mb-1">{{ $selectedBuku->judul }}</h6>
                                                    <p class="small mb-0 text-muted">Buku 1 (Terpilih)</p>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="buku_id[]" value="{{ $selectedBuku->id }}">
                                    </div>

                                    {{-- Slot 2: Optional Second Book --}}
                                    <div class="select-wrapper">
                                        <select name="buku_id[]" id="buku_id_2" class="form-select @error('buku_id') is-invalid @enderror" onchange="updateBookPreview(this, 2)">
                                            <option value="">-- Pilih Buku Kedua (Opsional) --</option>
                                            @foreach($buku as $item)
                                                @if($item->id != $selectedBuku->id)
                                                <option value="{{ $item->id }}" data-pengarang="{{ $item->pengarang }}" data-penerbit="{{ $item->penerbit }}" data-cover="{{ $item->cover_path ? asset('storage/' . $item->cover_path) : asset('images/default-cover.png') }}">
                                                    {{ $item->judul }} (Stok: {{ $item->stok }})
                                                </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="book-preview-2" class="card bg-light border-0 shadow-sm mt-3 d-none">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-md-3 text-center mb-3 mb-md-0">
                                                    <img id="preview-cover-2" src="" alt="Cover" class="img-fluid rounded" style="max-height: 80px; object-fit: cover;">
                                                </div>
                                                <div class="col-md-9">
                                                    <h6 id="preview-judul-2" class="fw-bold text-primary mb-1"></h6>
                                                    <p class="small mb-0 text-muted">Buku 2</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    {{-- Direct Access: Two Selection Slots --}}
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="small text-muted mb-1">Buku Pertama</label>
                                            <select name="buku_id[]" id="buku_id_1" class="form-select @error('buku_id') is-invalid @enderror" required onchange="updateBookPreview(this, 1)">
                                                <option value="">-- Pilih Buku 1 --</option>
                                                @foreach($buku as $item)
                                                    <option value="{{ $item->id }}" data-pengarang="{{ $item->pengarang }}" data-penerbit="{{ $item->penerbit }}" data-cover="{{ $item->cover_path ? asset('storage/' . $item->cover_path) : asset('images/default-cover.png') }}">
                                                        {{ $item->judul }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="small text-muted mb-1">Buku Kedua (Opsional)</label>
                                            <select name="buku_id[]" id="buku_id_2" class="form-select" onchange="updateBookPreview(this, 2)">
                                                <option value="">-- Pilih Buku 2 --</option>
                                                @foreach($buku as $item)
                                                    <option value="{{ $item->id }}" data-pengarang="{{ $item->pengarang }}" data-penerbit="{{ $item->penerbit }}" data-cover="{{ $item->cover_path ? asset('storage/' . $item->cover_path) : asset('images/default-cover.png') }}">
                                                        {{ $item->judul }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div id="previews-container" class="mt-3 d-none">
                                        <div id="book-preview-1" class="card bg-light border-0 shadow-sm mb-2 d-none">
                                            <div class="card-body py-2">
                                                <div class="d-flex align-items-center gap-3">
                                                    <img id="preview-cover-1" src="" alt="" class="rounded" style="height: 50px; width: 40px; object-fit: cover;">
                                                    <div>
                                                        <h6 id="preview-judul-1" class="small fw-bold mb-0"></h6>
                                                        <span class="badge bg-secondary" style="font-size: 10px;">Buku 1</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="book-preview-2" class="card bg-light border-0 shadow-sm d-none">
                                            <div class="card-body py-2">
                                                <div class="d-flex align-items-center gap-3">
                                                    <img id="preview-cover-2" src="" alt="" class="rounded" style="height: 50px; width: 40px; object-fit: cover;">
                                                    <div>
                                                        <h6 id="preview-judul-2" class="small fw-bold mb-0"></h6>
                                                        <span class="badge bg-secondary" style="font-size: 10px;">Buku 2</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @error('buku_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label for="tanggal_pinjam" class="form-label">
                                    <i class="bi bi-calendar"></i> Tanggal Peminjaman
                                </label>
                                <div class="input-wrapper input-readonly">
                                    <input
                                        type="date"
                                        name="tanggal_pinjam"
                                        id="tanggal_pinjam"
                                        class="form-control @error('tanggal_pinjam') is-invalid @enderror"
                                        value="{{ old('tanggal_pinjam', date('Y-m-d')) }}"
                                        readonly>
                                    <span class="input-tag">
                                        <i class="bi bi-lock"></i> Otomatis
                                    </span>
                                </div>
                                @error('tanggal_pinjam')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <i class="bi bi-info-circle"></i> Tanggal peminjaman otomatis menggunakan tanggal hari ini
                                </div>
                                {{-- Notifikasi deadline pengembalian --}}
                                @php
                                    $tanggalPinjam = old('tanggal_pinjam', date('Y-m-d'));
                                    try {
                                        $deadline = \Carbon\Carbon::parse($tanggalPinjam)->addDays(7)->format('d-m-Y');
                                        $deadlineForInput = \Carbon\Carbon::parse($tanggalPinjam)->addDays(7)->format('Y-m-d');
                                    } catch (\Exception $e) {
                                        $deadline = '-';
                                        $deadlineForInput = '';
                                    }
                                @endphp
                                <div class="alert alert-warning mt-2" role="alert" style="font-size:0.95em;">
                                    <i class="bi bi-exclamation-triangle"></i>
                                    <strong>Perhatian:</strong> Batas waktu pengembalian buku adalah <b>{{ $deadline }}</b>. Mohon kembalikan buku sebelum tanggal tersebut untuk menghindari denda.
                                </div>
                            </div>

                            {{-- Tanggal Pengembalian --}}
                            <div class="form-group mb-4">
                                <label for="tanggal_kembali" class="form-label">
                                    <i class="bi bi-calendar-check"></i> Tanggal Pengembalian
                                </label>
                                <div class="input-wrapper input-readonly">
                                    <input
                                        type="date"
                                        name="tanggal_kembali"
                                        id="tanggal_kembali"
                                        class="form-control"
                                        value="{{ $deadlineForInput }}"
                                        readonly>
                                    <span class="input-tag">
                                        <i class="bi bi-lock"></i> Otomatis
                                    </span>
                                </div>
                                <div class="form-text">
                                    <i class="bi bi-info-circle"></i> Tanggal pengembalian otomatis 7 hari setelah tanggal peminjaman.
                                </div>
                            </div>

                            <div class="form-actions">
                                <a href="{{ route('perpustakaan.buku.index') }}" class="btn-secondary-app">
                                    <i class="bi bi-arrow-left"></i> Kembali
                                </a>
                                <button type="submit" class="btn-secondary-app">
                                    <i class="bi bi-check-circle"></i> Simpan Peminjaman
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .form-section {
        background-color: var(--bg-gray);
    }

    .form-card {
        background-color: white;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow);
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.03);
        animation: fadeIn 0.6s ease;
    }

    .form-card-body {
        padding: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-label i {
        color: var(--secondary);
    }

    .input-wrapper, .select-wrapper {
        position: relative;
    }

    .input-readonly {
        background-color: var(--secondary-light);
        border-radius: var(--radius);
    }

    .form-control, .form-select {
        padding: 0.75rem 1rem;
        border-radius: var(--radius);
        border: 1px solid rgba(0, 0, 0, 0.1);
        transition: var(--transition-fast);
        font-size: 1rem;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--secondary);
        box-shadow: 0 0 0 0.2rem rgba(78, 205, 196, 0.25);
    }

    .input-tag {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        background-color: rgba(0, 0, 0, 0.05);
        color: var(--text-muted);
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 20px;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .select-icon {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        pointer-events: none;
    }

    .form-text {
        font-size: 0.85rem;
        color: var(--text-muted);
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .invalid-feedback {
        color: #dc3545;
        font-size: 0.85rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .invalid-feedback:before {
        content: "\F33E";
        font-family: "bootstrap-icons";
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
    }

    .btn-secondary-app {
        background-color: var(--bg-gray);
        color: var(--text-dark);
        padding: 0.75rem 1.5rem;
        border-radius: var(--radius-sm);
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: var(--transition);
        text-decoration: none;
        border: none;
    }

    .btn-secondary-app:hover {
        background-color: var(--text-muted);
        color: white;
        transform: translateY(-2px);
    }

    .btn-secondary-app {
        background-color: var(--secondary);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: var(--radius-sm);
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: var(--transition);
        text-decoration: none;
        border: none;
        margin-left: auto;
    }

    .btn-secondary-app:hover {
        background-color: var(--secondary-dark);
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm);
    }

    @media (max-width: 767px) {
        .form-card-body {
            padding: 1.5rem;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-secondary-app, .btn-secondary-app {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endsection

@section('script')
@if(isset($hasUnpaidFine) && $hasUnpaidFine)
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'error',
            title: 'Penangguhan Akun!',
            html: 'Sistem mendeteksi Anda memiliki denda keterlambatan pengembalian buku yang <b>belum dilunasi</b>.<br><br>Harap lunasi denda Anda ke Admin Perpustakaan terlebih dahulu untuk mencabut penangguhan peminjaman buku.',
            confirmButtonText: 'Lihat Riwayat Saya',
            allowOutsideClick: false,
            allowEscapeKey: false,
            confirmButtonColor: '#dc3545'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ route('perpustakaan.peminjaman.history') }}";
            }
        });
        
        // Disable form entirely 
        const formElements = document.querySelectorAll('form input, form select, form button');
        formElements.forEach(el => el.disabled = true);
    });
</script>
@endif

<script>
    function updateBookPreview(select, slot) {
        const preview = document.getElementById('book-preview-' + slot);
        const container = document.getElementById('previews-container');
        
        if (select.value) {
            const option = select.options[select.selectedIndex];
            const judulElement = document.getElementById('preview-judul-' + slot);
            const coverElement = document.getElementById('preview-cover-' + slot);
            
            if (judulElement) judulElement.textContent = option.text;
            if (coverElement) {
                coverElement.src = option.getAttribute('data-cover');
                coverElement.classList.remove('d-none');
            }
            
            if (preview) preview.classList.remove('d-none');
            if (container) container.classList.remove('d-none');
        } else {
            if (preview) preview.classList.add('d-none');
            
            // Hide container only if both slots are empty
            if (container) {
                const otherSlot = slot === 1 ? 2 : 1;
                const otherPreview = document.getElementById('book-preview-' + otherSlot);
                if (!otherPreview || otherPreview.classList.contains('d-none')) {
                    container.classList.add('d-none');
                }
            }
        }
    }
</script>
@endsection
