@extends('sistem_akademik.layouts.main')

@section('content')
<div class="container animate-fade-in">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10 col-12">
            <h1 class="page-title">{{ $header }}</h1>
            <p class="text-muted mb-4">{{ isset($jurusan) ? 'Edit data jurusan yang sudah ada' : 'Tambahkan data jurusan baru' }}</p>

            <div class="form-container">
                <form action="{{ isset($jurusan) ? route('sistem_akademik.jurusan.update', $jurusan->id) : route('sistem_akademik.jurusan.store') }}" method="POST">
                    @csrf
                    @if(isset($jurusan))
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
                        <label for="nama_jurusan" class="form-label">Nama Jurusan</label>
                        <input
                            type="text"
                            class="form-control @error('nama_jurusan') is-invalid @enderror"
                            id="nama_jurusan"
                            name="nama_jurusan"
                            value="{{ old('nama_jurusan', $jurusan->nama_jurusan ?? '') }}"
                            placeholder="Contoh: Teknik Komputer Jaringan"
                            required>
                        @error('nama_jurusan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex mt-4">
                        <a href="{{ route('sistem_akademik.jurusan.index') }}" class="btn-secondary-app">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn-primary-app ms-auto">
                            <i class="bi bi-{{ isset($jurusan) ? 'save' : 'plus-circle' }}"></i>
                            {{ isset($jurusan) ? 'Update Jurusan' : 'Simpan Jurusan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
