@extends('sistem_akademik.layouts.main')

@section('content')
@php
$isEdit = ! empty($mapel) && $mapel !== null;
@endphp

<div class="container animate-fade-in">
    <h1 class="page-title">{{ $isEdit ? 'Edit Mata Pelajaran' : 'Tambah Mata Pelajaran' }}</h1>

    <form method="POST"
        action="{{ $isEdit
              ? route('sistem_akademik.mapels.update', ['mapel' => $mapel->id])
              : route('sistem_akademik.mapels.store')
          }}">
        @csrf
        @if($isEdit)
        @method('PUT')
        @endif

        {{-- Nama Mapel --}}
        <div class="mb-3" id="mapel-container">
            <label class="form-label">Nama Mata Pelajaran</label>
            @if($isEdit)
                <input type="text" id="nama_mapel" name="nama_mapel"
                    class="form-control @error('nama_mapel') is-invalid @enderror"
                    value="{{ old('nama_mapel', $mapel->nama_mapel ?? '') }}"
                    required>
                @error('nama_mapel')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            @else
                <div class="input-group mb-2 mapel-row">
                    <input type="text" name="nama_mapel[]"
                        class="form-control @error('nama_mapel.*') is-invalid @enderror"
                        placeholder="Contoh: Pemrograman Web" required>
                    <button type="button" class="btn btn-success btn-add-mapel" title="Tambah Kosong">
                        <i class="bi bi-plus-lg"></i>
                    </button>
                </div>
                @error('nama_mapel')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                @error('nama_mapel.*')
                <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
                <div class="form-text">Anda dapat menambahkan lebih dari 1 data master sekaligus.</div>
            @endif
        </div>

        <div class="d-flex mt-4">
            <a href="{{ route('sistem_akademik.mapels.index') }}" class="btn-secondary-app">
                <i class="bi bi-arrow-left"></i> Batal
            </a>
            <button type="submit" class="btn-primary-app ms-auto">
                <i class="bi bi-{{ $isEdit ? 'save' : 'plus-circle' }}"></i>
                {{ $isEdit ? 'Update' : 'Simpan' }}
            </button>
        </div>
    </form>
</div>
@endsection
@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('mapel-container');
        if (container) {
            container.addEventListener('click', function(e) {
                // Add new row
                if (e.target.closest('.btn-add-mapel')) {
                    e.preventDefault();
                    const newRow = document.createElement('div');
                    newRow.className = 'input-group mb-2 mapel-row';
                    newRow.innerHTML = `
                        <input type="text" name="nama_mapel[]" class="form-control" placeholder="Contoh: Pemrograman Dasar" required>
                        <button type="button" class="btn btn-danger btn-remove-mapel" title="Hapus Baris">
                            <i class="bi bi-trash"></i>
                        </button>
                    `;
                    container.insertBefore(newRow, container.querySelector('.form-text'));
                }
                
                // Remove row
                if (e.target.closest('.btn-remove-mapel')) {
                    e.preventDefault();
                    e.target.closest('.mapel-row').remove();
                }
            });
        }
    });
</script>
@endsection
