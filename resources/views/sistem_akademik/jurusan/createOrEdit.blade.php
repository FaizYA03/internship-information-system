@extends('sistem_akademik.layouts.main', ['title' => isset($jurusan) ? 'Edit Jurusan' : 'Tambah Jurusan'])

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold"><i class="bi bi-diagram-3 text-primary me-2"></i> {{ isset($jurusan) ? 'Edit data jurusan yang sudah ada' : 'Tambahkan data jurusan baru' }}</h5>
    </div>
    <div class="card-body p-4">
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

                    <div class="d-flex gap-2 mt-4">
                        <a href="{{ route('sistem_akademik.jurusan.index') }}" class="btn btn-light border px-4">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary px-4">
                            {{ isset($jurusan) ? 'Update Jurusan' : 'Simpan Jurusan' }}
                        </button>
                    </div>
                </form>
    </div>
</div>
@endsection
