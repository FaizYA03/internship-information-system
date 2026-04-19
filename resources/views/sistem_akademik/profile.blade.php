@extends('sistem_akademik.layouts.main', ['title' => 'Profil Saya'])

@section('css')
@include('sistem_akademik.layouts.css')
@endsection

@section('content')
@php
$user = auth()->user();
$siswa = $user->siswa ?? null;
$guru = $user->guru ?? null;
$admin = $user->adminProfile ?? null;
$image = $siswa->image ?? $guru->image ?? $admin->image ?? null;
$imageUrl = $image ? asset('assets/profile/' . $image) : asset('assets/profile/default.png');
$identifier = $user->nis_nip ?? ($siswa->nis ?? ($guru->nip ?? '-'));
@endphp

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold"><i class="bi bi-person text-primary me-2"></i> Profil Saya</h5>
    </div>
    <div class="card-body profile-page p-0">
        <!-- Top row: avatar + basic info -->
        <div class="card-body top text-center border-bottom pb-4 mb-3" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
            <div class="profile-photo-container mx-auto" id="photoContainer" style="position: relative; width: 150px; height: 150px; display: inline-block;">
                <form id="photoForm"
                    action="{{ route('sistem_akademik.updatePhoto') }}"
                    method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <!-- FOTO -->
                    <img id="avatarPreview" class="avatar" src="{{ $imageUrl }}" alt="Foto profil {{ $user->nama }}" style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%; border: 4px solid #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.1); cursor: pointer; transition: 0.3s;">

                    <!-- Overlay -->
                    <div class="overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border-radius: 50%; background: rgba(0,0,0,0.5); color: #fff; display: flex; flex-direction: column; justify-content: center; align-items: center; opacity: 0; transition: 0.3s; cursor: pointer;">
                        <i class="fas fa-camera mb-1 fs-4"></i>
                        <span style="font-size: 0.85rem;">Ganti Foto</span>
                    </div>

                    <input id="photoInput" type="file" name="image" accept="image/*" hidden>
                </form>
            </div>

            <div class="profile-info mt-3">
                <h2 class="mb-1 text-dark fw-bold" style="font-size: 1.5rem;">{{ $user->nama }}</h2>
                <div class="identifier text-muted mb-2">{{ $identifier }}</div>
                <span class="badge bg-primary px-3 py-2 rounded-pill">
                    @if($user->role === 'siswa')
                    Siswa | {{ $siswa ? ($siswa->kelas . ' - ' . $siswa->jurusan) : 'Siswa' }}
                    @elseif($user->role === 'guru')
                    Guru | {{ $guru ? ($guru->kelas . ' - ' . $guru->jurusan) : 'Guru' }}
                    @elseif(in_array($user->role, ['kepala_sekolah', 'kepsek']))
                    Kepala Sekolah
                    @elseif(in_array($user->role, ['waka', 'waka_akademik']))
                    Wakil Kepala Sekolah
                    @else
                    Administrator
                    @endif
                </span>
            </div>
        </div>

        <div class="card-body form-area px-4 py-3">
            {{-- Profile data update --}}
            <form action="{{ route('sistem_akademik.updateProfile') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4 mt-1">
                    <!-- LEFT COLUMN -->
                    <div class="col-md-6 border-end pe-md-4">
                        <h5 class="text-primary mb-3 pb-2 border-bottom"><i class="fas fa-user-graduate me-2"></i>Data Akademik</h5>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Lengkap @if(in_array($user->role, ['siswa', 'guru']))<i class="fas fa-lock text-secondary fs-sm ms-1"></i>@endif</label>
                            @if(in_array($user->role, ['siswa', 'guru']))
                                <input type="text" class="form-control bg-light" value="{{ old('nama', $user->nama) }}" readonly disabled style="cursor: not-allowed;">
                                <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">Data ini dikelola oleh admin</small>
                            @else
                                <input name="nama" type="text" class="form-control" value="{{ old('nama', $user->nama) }}" required>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">NIS / NIP @if(in_array($user->role, ['siswa', 'guru']))<i class="fas fa-lock text-secondary fs-sm ms-1"></i>@endif</label>
                            <!-- Disable input for UX improvement as requested - NIS/NIP shouldn't be changed randomly -->
                            <input type="text" class="form-control bg-light" value="{{ old('nis_nip', $user->nis_nip ?? $siswa->nis ?? $guru->nip ?? '') }}" readonly disabled title="Nomor Induk tidak dapat diubah sendiri" @if(in_array($user->role, ['siswa', 'guru'])) style="cursor: not-allowed;" @endif>
                            @if(in_array($user->role, ['siswa', 'guru']))<small class="text-muted d-block mt-1" style="font-size: 0.75rem;">Data ini dikelola oleh admin</small>@endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Kelas @if($user->role === 'siswa')<i class="fas fa-lock text-secondary fs-sm ms-1" style="font-size: 0.85rem;"></i>@elseif($user->role === 'guru') (Wali Kelas) <i class="fas fa-lock text-secondary fs-sm ms-1" style="font-size: 0.85rem;"></i>@else<span class="text-danger">*</span>@endif</label>
                            @if($user->role === 'siswa')
                                <input type="hidden" name="kelas_id" value="{{ $siswa->kelas_id ?? '' }}">
                                <input type="text" class="form-control bg-light" value="{{ $siswa->dataKelas->nama_kelas ?? 'Belum ditentukan' }}" readonly disabled style="cursor: not-allowed;">
                                <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">Data ini dikelola oleh admin</small>
                            @elseif($user->role === 'guru')
                                <input type="hidden" name="kelas_id" value="">
                                <input type="text" class="form-control bg-light" value="{{ $guru->kelasWali->nama_kelas ?? 'Tidak menjadi wali kelas' }}" readonly disabled style="cursor: not-allowed;">
                                <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">Data wali kelas dikelola oleh admin</small>
                            @else
                                <input name="kelas_id" type="hidden" value="{{ $guru->kelas_id ?? '' }}">
                                <input type="text" class="form-control bg-light" value="{{ $guru->kelas ?? '-' }}" readonly>
                            @endif
                        </div>

                        @if($user->role !== 'guru')
                        <div class="mb-3">
                            <label class="form-label fw-bold">Wali Kelas @if($user->role === 'siswa')<i class="fas fa-lock text-secondary fs-sm ms-1" style="font-size: 0.85rem;"></i>@endif</label>
                            @if($user->role === 'siswa')
                                <input type="text" class="form-control bg-light" value="{{ $siswa->dataKelas && $siswa->dataKelas->waliKelas ? $siswa->dataKelas->waliKelas->nama : 'Belum ada wali kelas' }}" readonly disabled title="Hanya Admin yang dapat mengubah Wali Kelas" style="cursor: not-allowed;">
                                <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">Data ini diambil dari sistem akademik</small>
                            @else
                                <input type="text" class="form-control bg-light" value="-" readonly disabled>
                            @endif
                        </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label fw-bold">Jurusan @if(in_array($user->role, ['siswa', 'guru']))<i class="fas fa-lock text-secondary fs-sm ms-1" style="font-size: 0.85rem;"></i>@endif</label>
                            @if($user->role === 'siswa')
                                <input type="text" class="form-control bg-light" value="{{ $siswa->jurusan ?? 'Teknik Audio Video' }}" readonly disabled style="cursor: not-allowed;">
                                <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">Data ini dikelola oleh admin</small>
                            @elseif($user->role === 'guru')
                                <input type="text" class="form-control bg-light" value="{{ $guru->jurusan->nama_jurusan ?? 'Belum ditentukan' }}" readonly disabled style="cursor: not-allowed;">
                                <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">Data ini dikelola oleh admin</small>
                            @else
                                <input name="jurusan_id" type="hidden" value="{{ $guru->jurusan_id ?? '' }}">
                                <input name="jurusan" type="text" class="form-control" value="{{ old('jurusan', $siswa->jurusan ?? $admin->jurusan ?? '') }}">
                            @endif
                        </div>

                        @if($user->role === 'guru')
                        <div class="mb-3">
                            <label class="form-label fw-bold">Mata Pelajaran <i class="fas fa-lock text-secondary fs-sm ms-1" style="font-size: 0.85rem;"></i></label>
                            <input type="text" class="form-control bg-light" value="{{ $guru->mapels->pluck('nama_mata_pelajaran')->implode(', ') ?: 'Belum ada mata pelajaran' }}" readonly disabled style="cursor: not-allowed;">
                            <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">Data ini dikelola oleh admin</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Status Guru <i class="fas fa-lock text-secondary fs-sm ms-1" style="font-size: 0.85rem;"></i></label>
                            <input type="text" class="form-control bg-light" value="{{ $guru->status ?? 'Aktif' }}" readonly disabled style="cursor: not-allowed;">
                            <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">Data ini dikelola oleh admin</small>
                        </div>
                        @endif
                    </div>

                    <!-- RIGHT COLUMN -->
                    <div class="col-md-6 ps-md-4">
                        <h5 class="text-primary mb-3 pb-2 border-bottom"><i class="fas fa-id-card me-2"></i>Informasi Pribadi & Kontak</h5>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Email @if($user->role === 'siswa')<i class="fas fa-lock text-secondary fs-sm ms-1" style="font-size: 0.85rem;"></i>@endif</label>
                            @if($user->role === 'siswa')
                                <input type="email" class="form-control bg-light" value="{{ $user->email }}" readonly disabled style="cursor: not-allowed;">
                                <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">Data ini dikelola oleh admin</small>
                            @else
                                <input name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">No HP / WhatsApp</label>
                            <input name="no_hp" type="text" class="form-control" value="{{ old('no_hp', $siswa->no_hp ?? $guru->no_hp ?? $admin->no_hp ?? '') }}" placeholder="Misal: 081234567890">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal Lahir @if(in_array($user->role, ['siswa', 'guru']))<i class="fas fa-lock text-secondary fs-sm ms-1" style="font-size: 0.85rem;"></i>@endif</label>
                            @if(in_array($user->role, ['siswa', 'guru']))
                                <input type="date" class="form-control bg-light" value="{{ $siswa->tanggal_lahir ?? $guru->tanggal_lahir ?? '' }}" readonly disabled style="cursor: not-allowed;">
                                <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">Data ini dikelola oleh admin</small>
                            @else
                                <input name="tanggal_lahir" type="date" class="form-control" value="{{ old('tanggal_lahir', $siswa->tanggal_lahir ?? $guru->tanggal_lahir ?? $admin->tanggal_lahir ?? '') }}">
                            @endif
                        </div>

                        @if($user->role !== 'guru')
                        <div class="mb-3">
                            <label class="form-label fw-bold">Status Siswa @if($user->role === 'siswa')<i class="fas fa-lock text-secondary fs-sm ms-1" style="font-size: 0.85rem;"></i>@endif</label>
                            @if($user->role === 'siswa')
                                <input type="text" class="form-control bg-light" value="{{ $siswa->status_siswa ?? 'Aktif' }}" readonly disabled style="cursor: not-allowed;">
                                <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">Data ini dikelola oleh admin</small>
                            @else
                                <input type="text" class="form-control bg-light" value="-" readonly>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Tahun Masuk / Angkatan @if($user->role === 'siswa')<i class="fas fa-lock text-secondary fs-sm ms-1" style="font-size: 0.85rem;"></i>@endif</label>
                            @if($user->role === 'siswa')
                                <input type="text" class="form-control bg-light" value="{{ $siswa->tahun_masuk ?? '2023' }}" readonly disabled style="cursor: not-allowed;" placeholder="Contoh: 2022">
                                <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">Data ini dikelola oleh admin</small>
                            @else
                                <input type="text" class="form-control bg-light" value="-" readonly>
                            @endif
                        </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="fas fa-map-marker-alt text-primary me-2"></i>Alamat Lengkap</label>
                            <textarea name="alamat" class="form-control" rows="3" placeholder="Masukkan alamat domisili saat ini" style="resize: vertical;">{{ old('alamat', $siswa->alamat ?? $guru->alamat ?? $admin->alamat ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- BOTTOM (Removed old alamat block) -->
                <div class="row mt-4 mb-4">
                    <div class="col-12 border-top pt-4">
                    </div>
                </div>

                <div class="profile-actions mt-2 d-flex justify-content-end align-items-center bg-light p-3 rounded">
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary px-4 me-3"><i class="fas fa-times me-2"></i>Batal</a>
                    <button type="submit" class="btn btn-primary px-5 shadow-sm"><i class="fas fa-save me-2"></i>Simpan Perubahan</button>
                </div>
            </form>

            <hr class="mt-4 mb-4">

            {{-- Password update --}}
            <form action="{{ route('sistem_akademik.updatePassword') }}" method="POST">
                @csrf
                @method('PATCH')

                <h5>Ubah Password</h5>

                <div class="row g-3 equals-cols">
                    <div class="col-md-4">
                        <label class="form-label">Password Saat Ini</label>
                        <div class="input-group">
                            <input name="current_password" type="password" class="form-control password-field" required>
                            <span class="input-group-text password-toggle"><i class="fa fa-eye"></i></span>
                        </div>
                        @error('current_password') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Password Baru</label>
                        <div class="input-group">
                            <input name="password" type="password" class="form-control password-field" required>
                            <span class="input-group-text password-toggle"><i class="fa fa-eye"></i></span>
                        </div>
                        @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Konfirmasi Password</label>
                        <div class="input-group">
                            <input name="password_confirmation" type="password" class="form-control password-field" required>
                            <span class="input-group-text password-toggle"><i class="fa fa-eye"></i></span>
                        </div>
                    </div>
                </div>

                <div class="profile-actions mt-3">
                    <button type="submit" class="btn btn-warning">Update Password</button>
                </div>
            </form>
        </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const photoContainer = document.getElementById('photoContainer');
        const photoInput = document.getElementById('photoInput');
        const photoForm = document.getElementById('photoForm');
        const avatar = document.getElementById('avatarPreview');

        if (!photoContainer || !photoInput || !photoForm || !avatar) return;

        // Klik container -> buka file picker
        photoContainer.addEventListener('click', function(e) {
            e.stopPropagation();
            photoInput.click();
            photoContainer.classList.add('editing');
        });

        // Keyboard accessibility
        photoContainer.addEventListener('keydown', function(ev) {
            if (ev.key === 'Enter' || ev.key === ' ') {
                ev.preventDefault();
                photoInput.click();
                photoContainer.classList.add('editing');
            }
        });

        photoInput.addEventListener('change', function() {
            const file = this.files && this.files[0];
            if (!file) {
                photoContainer.classList.remove('editing');
                return;
            }

            // preview lokal segera
            const reader = new FileReader();
            reader.onload = function(ev) {
                avatar.src = ev.target.result;
            };
            reader.readAsDataURL(file);

            // Siapkan FormData (tambahkan _method supaya Laravel menganggap ini PATCH)
            const fd = new FormData();
            fd.append('image', file);
            fd.append('_method', 'PATCH'); // <-- penting: override method

            // ambil CSRF token dari meta (pastikan ada di layout utama)
            const tokenEl = document.querySelector('meta[name="csrf-token"]');
            const token = tokenEl ? tokenEl.getAttribute('content') : '{{ csrf_token() }}';

            // Kirim sebagai POST (Laravel akan mem-override menjadi PATCH karena _method)
            fetch(photoForm.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: fd,
                    credentials: 'same-origin'
                })
                .then(res => res.json().catch(() => ({
                    success: false,
                    message: 'Invalid response from server'
                })))
                .then(json => {
                    if (json && json.success) {
                        if (json.url) avatar.src = json.url;
                        photoContainer.classList.remove('editing');
                    } else {
                        alert(json.message || 'Gagal mengunggah foto. Silakan refresh halaman dan coba lagi.');
                        window.location.reload();
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Terjadi kesalahan saat mengunggah foto.');
                    window.location.reload();
                });
        });

        // Jika user membatalkan file dialog, buang class editing
        photoInput.addEventListener('blur', function() {
            setTimeout(() => photoContainer.classList.remove('editing'), 250);
        });
    });

    document.addEventListener('click', function(e) {
        const toggle = e.target.closest('.password-toggle');
        if (!toggle) return;
        const group = toggle.closest('.input-group');
        if (!group) return;
        const input = group.querySelector('.password-field');
        const icon = toggle.querySelector('i');

        if (!input) return;
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
</script>
@endsection