@extends('sistem_akademik.layouts.main')

@section('content')
@php
$isEdit = ! empty($ruangan) && $ruangan !== null;
@endphp

<div class="container animate-fade-in">
    <h1 class="page-title">{{ $isEdit ? 'Edit Ruangan' : 'Tambah Ruangan' }}</h1>

    <form method="POST"
        action="{{ $isEdit
              ? route('sistem_akademik.ruangans.update', ['ruangan' => $ruangan->id])
              : route('sistem_akademik.ruangans.store')
          }}">
        @csrf
        @if($isEdit)
        @method('PUT')
        @endif

        <div id="ruangan-container">
            @if($isEdit)
                <div class="card p-3 mb-3">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Kategori Ruangan</label>
                            <select name="jenis_ruangan" class="form-select @error('jenis_ruangan') is-invalid @enderror" required>
                                <option value="" disabled>-- Pilih Kategori --</option>
                                <option value="Kelas" {{ old('jenis_ruangan', $ruangan->jenis_ruangan ?? '') == 'Kelas' ? 'selected' : '' }}>Kelas</option>
                                <option value="Laboratorium" {{ old('jenis_ruangan', $ruangan->jenis_ruangan ?? '') == 'Laboratorium' ? 'selected' : '' }}>Laboratorium</option>
                                <option value="Lapangan" {{ old('jenis_ruangan', $ruangan->jenis_ruangan ?? '') == 'Lapangan' ? 'selected' : '' }}>Lapangan</option>
                                <option value="Lainnya" {{ old('jenis_ruangan', $ruangan->jenis_ruangan ?? '') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('jenis_ruangan')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Nama / Label Ruangan</label>
                            <input type="text" name="nama_ruangan"
                                class="form-control @error('nama_ruangan') is-invalid @enderror"
                                value="{{ old('nama_ruangan', $ruangan->nama_ruangan ?? '') }}"
                                placeholder="Contoh: R101, Lab Komputer, dll" required>
                            @error('nama_ruangan')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            @else
                <div class="card p-3 mb-3 ruangan-row">
                    <div class="row align-items-end">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Kategori Ruangan</label>
                            <select name="jenis_ruangan[]" class="form-select" required>
                                <option value="" disabled>-- Pilih Kategori --</option>
                                <option value="Kelas" selected>Kelas</option>
                                <option value="Laboratorium">Laboratorium</option>
                                <option value="Lapangan">Lapangan</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-7 mb-3">
                            <label class="form-label">Nama / Label Ruangan</label>
                            <input type="text" name="nama_ruangan[]"
                                class="form-control"
                                placeholder="Contoh: R101" required>
                        </div>
                        <div class="col-md-1 mb-3 text-end d-flex align-items-center justify-content-end">
                            <button type="button" class="btn btn-success btn-add-ruangan flex-grow-1" title="Tambah Kosong">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                @error('nama_ruangan.*')
                <div class="text-danger small mt-1 mb-3">Pastikan seluru inputan nama ruangan valid.</div>
                @enderror
                @error('jenis_ruangan.*')
                <div class="text-danger small mt-1 mb-3">Pastikan kategori ruangan dipilih.</div>
                @enderror
                <div class="form-text ms-2">Anda dapat menambahkan lebih dari 1 data sekaligus dengan menekan tombol (+)</div>
            @endif
        </div>

        <div class="d-flex mt-4">
            <a href="{{ route('sistem_akademik.ruangans.index') }}" class="btn-secondary-app">
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
        const container = document.getElementById('ruangan-container');
        if (container) {
            container.addEventListener('click', function(e) {
                // Add new row
                if (e.target.closest('.btn-add-ruangan')) {
                    e.preventDefault();
                    
                    const newRow = document.createElement('div');
                    newRow.className = 'card p-3 mb-3 ruangan-row';
                    newRow.innerHTML = `
                        <div class="row align-items-end">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Kategori Ruangan</label>
                                <select name="jenis_ruangan[]" class="form-select" required>
                                    <option value="" disabled>-- Pilih Kategori --</option>
                                    <option value="Kelas" selected>Kelas</option>
                                    <option value="Laboratorium">Laboratorium</option>
                                    <option value="Lapangan">Lapangan</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div class="col-md-7 mb-3">
                                <label class="form-label">Nama / Label Ruangan</label>
                                <input type="text" name="nama_ruangan[]" class="form-control" placeholder="Contoh: Lab Komputer" required>
                            </div>
                            <div class="col-md-1 mb-3 text-end d-flex align-items-center justify-content-end">
                                <button type="button" class="btn btn-danger btn-remove-ruangan flex-grow-1" title="Hapus Baris">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    `;
                    container.insertBefore(newRow, container.querySelector('.form-text'));
                }
                
                // Remove row
                if (e.target.closest('.btn-remove-ruangan')) {
                    e.preventDefault();
                    e.target.closest('.ruangan-row').remove();
                }
            });
        }
    });
</script>
@endsection
