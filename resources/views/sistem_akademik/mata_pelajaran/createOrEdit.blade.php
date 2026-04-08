@extends('sistem_akademik.layouts.main', ['title' => !empty($mapel) && $mapel !== null ? 'Edit Mata Pelajaran' : 'Tambah Mata Pelajaran'])

@section('content')
@php
$isEdit = ! empty($mapel) && $mapel !== null;
@endphp

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold"><i class="bi bi-book text-primary me-2"></i> {{ $header }}</h5>
    </div>
    <div class="card-body p-4">

    <form method="POST"
        action="{{ $isEdit
              ? route('sistem_akademik.mata_pelajaran.update', ['mata_pelajaran' => $mapel->id])
              : route('sistem_akademik.mata_pelajaran.store')
          }}">
        @csrf
        @if($isEdit)
        @method('PUT')
        @endif

        {{-- Nama Mapel --}}
        {{-- Nama Mapel --}}
        <div class="mb-3" id="mapel-container">
            <label class="form-label">Nama Mata Pelajaran</label>
            @if($isEdit)
                <select id="nama_mata_pelajaran" name="nama_mata_pelajaran" class="form-select select2-mapel @error('nama_mata_pelajaran') is-invalid @enderror" required>
                    <option value="" disabled>-- Pilih Mata Pelajaran --</option>
                    @foreach($mapel_master ?? [] as $m)
                        <option value="{{ $m->nama_mapel }}" {{ (old('nama_mata_pelajaran', $mapel->nama_mata_pelajaran ?? '')) == $m->nama_mapel ? 'selected' : '' }}>
                            {{ $m->nama_mapel }}
                        </option>
                    @endforeach
                </select>
                @error('nama_mata_pelajaran')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            @else
                <div class="input-group mb-2 mapel-row">
                    <select name="nama_mata_pelajaran[]" class="form-select select2-mapel @error('nama_mata_pelajaran.*') is-invalid @enderror" required>
                        <option value="" disabled selected>-- Pilih Mata Pelajaran --</option>
                        @foreach($mapel_master ?? [] as $m)
                            <option value="{{ $m->nama_mapel }}">{{ $m->nama_mapel }}</option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-success btn-add-mapel" title="Tambah Mata Pelajaran">
                        <i class="bi bi-plus-lg"></i>
                    </button>
                </div>
                @error('nama_mata_pelajaran')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                @error('nama_mata_pelajaran.*')
                <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
                <div class="form-text">Anda dapat menambahkan lebih dari 1 mata pelajaran sekaligus dengan klik tombol (+)</div>
            @endif
        </div>

        @php
            $gurusList = $gurus ?? $users ?? collect();
            $groupedGurus = collect($gurusList)->groupBy(function($g) {
                $j = data_get($g, 'jurusan.nama_jurusan') ?? data_get($g, 'jurusan.nama') ?? data_get($g, 'jurusan');
                return (is_string($j) || is_numeric($j)) && !empty($j) ? (string) $j : 'Umum / Tanpa Jurusan';
            });
        @endphp

        {{-- Filter Jurusan --}}
        <div class="mb-3">
            <label for="filter_jurusan" class="form-label">Filter Jurusan</label>
            <select id="filter_jurusan" class="form-select select2-jurusan">
                <option value="">-- Semua Jurusan --</option>
                @foreach($groupedGurus->keys() as $jrs)
                    <option value="{{ $jrs }}">{{ $jrs }}</option>
                @endforeach
            </select>
        </div>

        {{-- Guru --}}
        <div class="mb-3">
            <label for="guru_id" class="form-label">Guru Pengampu <span class="text-muted">(Opsional / Bisa ditambahkan nanti)</span></label>
            <select id="guru_id" name="guru_id" class="form-select select2-guru @error('guru_id') is-invalid @enderror">
                <option value="">-- Belum Ditentukan / Ketik & Pilih Guru --</option>
                @foreach($groupedGurus as $jurusan => $listGuru)
                    <optgroup label="Jurusan: {{ $jurusan }}">
                        @foreach($listGuru as $g)
                            <option value="{{ $g->id }}"
                                {{ old('guru_id', $mapel->guru_id ?? '') == $g->id ? 'selected' : '' }}>
                                {{ $g->nama ?? $g->name ?? '-' }}
                            </option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
            @error('guru_id')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
            <div class="form-text">Gunakan fitur pencarian pada dropdown untuk kemudahan memfilter guru berdasarkan jurusan.</div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <a href="{{ route('sistem_akademik.mata_pelajaran.index') }}" class="btn btn-light border px-4">
                Batal
            </a>
            <button type="submit" class="btn btn-primary px-4">
                {{ $isEdit ? 'Update' : 'Simpan' }}
            </button>
        </div>
    </form>
    </div>
</div>
@endsection
@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Init select2 if jQuery is available
        if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {
            $('.select2-guru').select2({
                placeholder: "-- Ketik & Pilih Guru --",
                width: '100%',
                allowClear: true
            });
            $('.select2-jurusan').select2({
                placeholder: "-- Semua Jurusan --",
                width: '100%',
                allowClear: true
            });
            $('.select2-mapel').select2({
                placeholder: "-- Pilih Mata Pelajaran --",
                width: '100%',
                allowClear: true
            });
        }

        // Simpan referensi asli optgroup untuk filtering
        const guruSelect = document.getElementById('guru_id');
        const originalOptgroups = Array.from(guruSelect.querySelectorAll('optgroup')).map(optgroup => {
            return {
                label: optgroup.getAttribute('label'),
                jurusanName: optgroup.getAttribute('label').replace('Jurusan: ', ''),
                element: optgroup.cloneNode(true)
            };
        });

        const filterJurusan = $('#filter_jurusan');
        
        filterJurusan.on('change', function() {
            const selectedJurusan = $(this).val();

            // Kosongkan option (tapi tetapkan placeholder utama)
            while (guruSelect.firstChild) {
                guruSelect.removeChild(guruSelect.firstChild);
            }
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.selected = true;
            defaultOption.text = '-- Belum Ditentukan / Ketik & Pilih Guru --';
            guruSelect.appendChild(defaultOption);

            // Filter optgroup
            originalOptgroups.forEach(groupInfo => {
                if (selectedJurusan === '' || groupInfo.jurusanName === selectedJurusan) {
                    guruSelect.appendChild(groupInfo.element.cloneNode(true));
                }
            });

            // Trigger re-render untuk Select2 jika digunakan
            if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {
                $(guruSelect).trigger('change');
            }
        });

        // Dynamic rows logic
        const container = document.getElementById('mapel-container');
        if (container) {
            container.addEventListener('click', function(e) {
                // Add new row
                if (e.target.closest('.btn-add-mapel')) {
                    e.preventDefault();
                    // Clone the first row to preserve all options
                    const firstRow = container.querySelector('.mapel-row');
                    if (firstRow) {
                        const newRow = document.createElement('div');
                        newRow.className = 'input-group mb-2 mapel-row d-flex align-items-center';

                        const selectClone = firstRow.querySelector('select').cloneNode(true);
                        selectClone.value = '';
                        selectClone.classList.remove('select2-hidden-accessible');
                        selectClone.removeAttribute('data-select2-id');

                        newRow.innerHTML = `
                            <div class="flex-grow-1"></div>
                            <button type="button" class="btn btn-danger btn-remove-mapel ms-2" title="Hapus Mata Pelajaran">
                                <i class="bi bi-trash"></i>
                            </button>
                        `;
                        newRow.querySelector('.flex-grow-1').appendChild(selectClone);
                        
                        // if you want multiple rows with select2 styling, it might be complex without jquery trigger
                        // but simple select looks fine too. If you prefer select2, re-init it:
                        container.insertBefore(newRow, container.querySelector('.form-text'));
                        
                        if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {
                            $(selectClone).select2({
                                placeholder: "-- Pilih Mata Pelajaran --",
                                width: '100%',
                                allowClear: true
                            });
                        }
                    }
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