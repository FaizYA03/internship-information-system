@extends('sistem_akademik.layouts.main')

@section('content')
<div class="container animate-fade-in">
    <div class="row justify-content-center">
        <div class="col-lg-12 col-md-10 col-12">
            <h1 class="page-title">{{ $header }}</h1>
            <p class="text-muted mb-4">{{ isset($kelas) ? 'Edit data kelas yang sudah ada' : 'Tambahkan data kelas baru' }}</p>

            <div class="form-container">
                <form action="{{ isset($kelas) ? route('sistem_akademik.kelas.update', $kelas->id) : route('sistem_akademik.kelas.store') }}" method="POST">
                    @csrf
                    @if(isset($kelas))
                    @method('PUT')
                    @endif

                    @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <div class="d-flex">
                            <div class="me-2">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                            </div>
                            <div>
                                <h5 class="alert-heading mb-1">Terdapat kesalahan pada formulir</h5>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="mb-3">
                        <label for="nama_kelas" class="form-label">Nama Kelas</label>
                        <input
                            type="text"
                            class="form-control @error('nama_kelas') is-invalid @enderror"
                            id="nama_kelas"
                            name="nama_kelas"
                            value="{{ old('nama_kelas', $kelas->nama_kelas ?? '') }}"
                            placeholder="Contoh: X, XI, XII, etc."
                            required>
                        @error('nama_kelas')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="jurusan" class="form-label">Jurusan</label>
                        <select
                            class="form-select @error('jurusan') is-invalid @enderror"
                            id="jurusan"
                            name="jurusan"
                            required>
                            <option value="" disabled {{ old('jurusan', $kelas->jurusan ?? '') == '' ? 'selected' : '' }}>-- Pilih Jurusan --</option>
                            @foreach($jurusans as $j)
                            <option value="{{ $j->nama_jurusan }}" {{ (old('jurusan', $kelas->jurusan ?? '') == $j->nama_jurusan) ? 'selected' : '' }}>{{ $j->nama_jurusan }}</option>
                            @endforeach
                        </select>

                        @error('jurusan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="tahun_ajaran" class="form-label">Tahun Ajaran</label>
                        <input
                            type="text"
                            class="form-control @error('tahun_ajaran') is-invalid @enderror"
                            id="tahun_ajaran"
                            name="tahun_ajaran"
                            placeholder="Contoh: 2025/2026"
                            value="{{ old('tahun_ajaran', $kelas->tahun_ajaran ?? date('Y').'/'.((int)date('Y')+1)) }}"
                            required>
                        @error('tahun_ajaran')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Wali Kelas --}}
                    <div class="mb-3">
                        <label for="wali_kelas_id" class="form-label">Wali Kelas (satu guru per kelas)</label>
                        <select name="wali_kelas_id" id="wali_kelas_id" class="form-select @error('wali_kelas_id') is-invalid @enderror">
                            <option value="" {{ old('wali_kelas_id', $kelas->wali_kelas_id ?? '') == '' ? 'selected' : '' }}>-- Tidak ada --</option>

                            @if(isset($availableWali) && $availableWali->count())
                            @foreach($availableWali as $w)
                            <option value="{{ $w->id }}"
                                {{ (string)old('wali_kelas_id', isset($kelas) ? $kelas->wali_kelas_id : '') === (string)$w->id ? 'selected' : '' }}>
                                {{ $w->nama }}
                            </option>
                            @endforeach
                            @endif

                            {{-- include current wali if not in available list (defensive) --}}
                            @if(isset($kelas) && $kelas->wali_kelas_id && !($availableWali->pluck('id')->contains($kelas->wali_kelas_id ?? null)))
                            <option value="{{ $kelas->wali_kelas_id }}" selected>{{ optional($kelas->waliKelas)->nama ?? 'Guru (terpilih)' }}</option>
                            @endif
                        </select>
                        @error('wali_kelas_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Catatan: guru yang sudah menjadi wali kelas lain tidak akan muncul di daftar.</div>
                    </div>

                    {{-- Guru BK --}}
                    <div class="mb-3">
                        <label for="guru_bk_id" class="form-label">Guru BK (maks 2 penugasan)</label>
                        <select name="guru_bk_id" id="guru_bk_id" class="form-select @error('guru_bk_id') is-invalid @enderror">
                            <option value="" {{ old('guru_bk_id', $kelas->guru_bk_id ?? '') == '' ? 'selected' : '' }}>-- Tidak ada --</option>

                            @if(isset($availableGuruBk) && $availableGuruBk->count())
                            @foreach($availableGuruBk as $g)
                            {{-- availableGuruBk mungkin berisi model User dengan atribut kelas_count --}}
                            @php
                            $count = isset($g->kelas_count) ? (int)$g->kelas_count : (method_exists($g,'kelas_count') ? $g->kelas_count : 0);
                            @endphp
                            <option value="{{ $g->id }}"
                                {{ (string)old('guru_bk_id', isset($kelas) ? $kelas->guru_bk_id : '') === (string)$g->id ? 'selected' : '' }}>
                                {{ $g->nama }} @if($count !== null) (terisi: {{ $count }}) @endif
                            </option>
                            @endforeach
                            @endif

                            {{-- include current guru_bk if not in available list --}}
                            @if(isset($kelas) && $kelas->guru_bk_id && (isset($availableGuruBk) ? !$availableGuruBk->pluck('id')->contains($kelas->guru_bk_id) : true))
                            <option value="{{ $kelas->guru_bk_id }}" selected>{{ optional($kelas->guruBK)->nama ?? 'Guru BK (terpilih)' }}</option>
                            @endif
                        </select>
                        @error('guru_bk_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Catatan: guru BK dapat menangani maksimal 2 kelas; bila sudah 2, guru tidak muncul di pilihan.</div>
                    </div>

                    {{-- Ruangan --}}
                    <div class="mb-3">
                        <label for="ruangan" class="form-label">Ruangan</label>
                        <input
                            type="text"
                            class="form-control @error('ruangan') is-invalid @enderror"
                            id="ruangan"
                            name="ruangan"
                            placeholder="Contoh: R-101 / Lab Komputer"
                            value="{{ old('ruangan', $kelas->ruangan ?? '') }}">
                        @error('ruangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Nama ruangan harus unik. Sistem akan menolak jika nama ruangan sudah digunakan oleh kelas lain.</div>
                    </div>

                    <div class="d-flex mt-4">
                        <a href="{{ route('sistem_akademik.kelas.index') }}" class="btn-secondary-app">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn-primary-app ms-auto">
                            <i class="bi bi-{{ isset($kelas) ? 'save' : 'plus-circle' }}"></i>
                            {{ isset($kelas) ? 'Update Kelas' : 'Simpan Kelas' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection