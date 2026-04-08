@extends('sistem_akademik.layouts.main')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .loading-spinner {
        display: inline-block;
        width: 1rem;
        height: 1rem;
        vertical-align: middle;
        border: .12em solid currentColor;
        border-right-color: transparent;
        border-radius: 50%;
        animation: spinner-border .75s linear infinite;
        margin-right: .5rem;
    }

    @keyframes spinner-border {
        to {
            transform: rotate(360deg);
        }
    }
</style>
@endsection

@section('content')
@php
// fallback variable names supported
$mpList = $mataPelajaran ?? $mapels ?? collect();
$siswaList = $siswa ?? collect();
$selectedSiswaIds = old('siswa_ids', $selectedSiswaIds ?? []);
$selectedMataPelajaran = old('mata_pelajaran_id', $course->mata_pelajaran_id ?? '');
$selectedKelasId = old('kelas_id', $course->kelas_id ?? '');
$selectedSlotStart = old('slot_start', $selected['slot_start'] ?? null);
$selectedSlotEnd = old('slot_end', $selected['slot_end'] ?? null);
$slots = $slots ?? []; // controller should send $slots = $this->selectableSlots()

// safe URLs: students route must exist; recommendations fallback to URL if route missing
$studentsUrl = route('sistem_akademik.get-students-by-jurusan');
// if your named route for recommendations exists, use it; otherwise fallback path
$recommendationsUrl = (Route::has('sistem_akademik.get-recommendations'))
? route('sistem_akademik.get-recommendations')
: url('/sistem-akademik/course/get-recommendations');

// conflict details sent back from controller on redirect
$conflictDetails = session('conflict_details', null);
@endphp

<div id="course-form"
    data-students-url="{{ $studentsUrl }}"
    data-recommendations-url="{{ $recommendationsUrl }}"
    data-conflict-url="{{ route('sistem_akademik.course.check-conflicts') }}"
    data-current-course-id="{{ isset($course) ? $course->id : '' }}"
    data-initial-kelas="{{ $selectedKelasId }}"
    data-preselect-siswa='@json($selectedSiswaIds)'
    data-initial-hari="{{ old('hari', $course->hari ?? '') }}"
    data-slot-ids='@json(array_keys($slots))'
    data-slot-details='@json($slots)'
    class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold"><i class="bi bi-calendar-event text-primary me-2"></i> {{ $header }}</h5>
    </div>
    <div class="card-body p-4">
        <form action="{{ isset($course) ? route('sistem_akademik.course.update', $course->id) : route('sistem_akademik.course.store') }}" method="POST">
            @csrf
            @if(isset($course)) @method('PUT') @endif

            {{-- KELAS --}}
            <div class="mb-3">
                <label for="kelas_id" class="form-label">Kelas</label>
                <select class="form-control" id="kelas_id" name="kelas_id" required>
                    <option value="" disabled {{ $selectedKelasId == '' ? 'selected' : '' }}>-- Pilih Kelas --</option>
                    @foreach($kelas as $k)
                    <option value="{{ $k->id }}" data-jurusan="{{ $k->jurusan }}" {{ (string)$selectedKelasId === (string)$k->id ? 'selected' : '' }}>
                        {{ $k->nama_kelas }} - {{ $k->jurusan }} ({{ $k->tahun_ajaran }})
                    </option>
                    @endforeach
                </select>
                <small class="form-text text-muted"><i class="bi bi-info-circle"></i> Pilih kelas — siswa akan dimuat berdasarkan kelas.</small>

                {{-- RUANGAN CONFLICT WARNING (tampil bila controller mengirim conflict_details.ruangan) --}}
                @if($conflictDetails && isset($conflictDetails['ruangan']) && count($conflictDetails['ruangan']))
                <div class="alert alert-warning mt-2" role="alert">
                    <strong>Perhatian — Bentrok Ruangan:</strong>
                    <div class="small mt-1">
                        Ruangan yang Anda pilih bentrok dengan jadwal lain pada hari dan slot yang sama. Silakan pilih ruangan lain atau ubah slot/jadwal.
                    </div>
                    <ul class="mb-0 mt-2 small">
                        @foreach($conflictDetails['ruangan'] as $c)
                        <li>
                            <strong>{{ $c['kelas'] ?? '-' }}</strong>
                            @if(!empty($c['mata_pelajaran'])) — {{ $c['mata_pelajaran'] }} @endif
                            ({{ \Illuminate\Support\Str::limit(substr($c['jam_mulai'] ?? '',0,5),5,'') ?: '-' }} - {{ \Illuminate\Support\Str::limit(substr($c['jam_selesai'] ?? '',0,5),5,'') ?: '-' }})
                            @if(!empty($c['ruangan'])) — Ruangan: <code>{{ $c['ruangan'] }}</code>@endif
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>

            {{-- MATA PELAJARAN --}}
            <div class="mb-3">
                <label for="mata_pelajaran_id" class="form-label">Mata Pelajaran</label>
                <select class="form-control" id="mata_pelajaran_id" name="mata_pelajaran_id" required>
                    <option value="" disabled {{ $selectedMataPelajaran == '' ? 'selected' : '' }}>-- Pilih Mata Pelajaran --</option>
                    @foreach($mpList as $mp)
                    @php $mpGuruName = data_get($mp, 'guru.nama', data_get($mp, 'guru.name', '')); @endphp
                    <option value="{{ $mp->id }}" {{ (string)$selectedMataPelajaran === (string)$mp->id ? 'selected' : '' }}>
                        {{ $mp->nama_mata_pelajaran }} @if($mpGuruName) - {{ $mpGuruName }} @endif
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- SISWA (AJAX populated) --}}
            <div class="mb-3">
                <label for="siswa_ids" class="form-label">
                    Siswa
                    <span id="students-loading" class="d-none"><span class="loading-spinner"></span>Memuat data siswa...</span>
                </label>
                <select class="form-control select2-multiple" id="siswa_ids" name="siswa_ids[]" multiple>
                    {{-- fallback: if controller passed a siswa list without kelas context --}}
                    @if($siswaList->isNotEmpty() && empty($selectedKelasId))
                    @foreach($siswaList as $s)
                    <option value="{{ $s->id }}" {{ in_array($s->id, (array)$selectedSiswaIds) ? 'selected' : '' }}>
                        {{ optional($s->user)->nama ?? ($s->nisn ?? '-') }}
                    </option>
                    @endforeach
                    @endif
                </select>
                <small class="form-text text-muted"><i class="bi bi-info-circle"></i> Pilih siswa dari kelas yang dipilih.</small>
            </div>

            {{-- HARI --}}
            <div class="mb-3">
                <label for="hari" class="form-label">Hari</label>
                <select class="form-control" id="hari" name="hari" required>
                    <option value="" disabled {{ old('hari', $course->hari ?? '') == '' ? 'selected' : '' }}>-- Pilih Hari --</option>
                    @foreach(['Senin','Selasa','Rabu','Kamis','Jumat'] as $h)
                    <option value="{{ $h }}" {{ old('hari', $course->hari ?? '') == $h ? 'selected' : '' }}>{{ $h }}</option>
                    @endforeach
                </select>
                <small class="form-text text-muted">Pilih hari untuk melihat rekomendasi slot kosong.</small>
            </div>

            {{-- SLOTS --}}
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="slot_start" class="form-label">Slot Awal</label>
                    <select class="form-control" id="slot_start" name="slot_start" required>
                        <option value="" disabled {{ $selectedSlotStart ? '' : 'selected' }}>-- Pilih Slot Awal --</option>
                        @foreach($slots as $id => $s)
                        <option value="{{ $id }}" {{ (string)$selectedSlotStart === (string)$id ? 'selected' : '' }}>
                            {{ $s['label'] }} ({{ $s['start'] }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="slot_end" class="form-label">Slot Akhir</label>
                    <select class="form-control" id="slot_end" name="slot_end" required>
                        <option value="" disabled {{ $selectedSlotEnd ? '' : 'selected' }}>-- Pilih Slot Akhir --</option>
                        @foreach($slots as $id => $s)
                        <option value="{{ $id }}" {{ (string)$selectedSlotEnd === (string)$id ? 'selected' : '' }}>
                            {{ $s['label'] }} ({{ $s['end'] }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12">
                    <div id="recommendations-area" class="mt-2">
                        <small class="text-muted">Rekomendasi slot kosong (terkait hari & kelas):</small>
                        <div id="recommendations" class="recommendations"></div>
                    </div>
                </div>
            </div>

            {{-- RUANGAN --}}
            <div class="mb-3">
                <label for="ruangan" class="form-label">Ruangan / Laboratorium</label>
                <select class="form-select select2-ruangan" id="ruangan" name="ruangan" required>
                    <option value="" disabled {{ old('ruangan', $course->ruangan ?? '') == '' ? 'selected' : '' }}>-- Pilih Ruangan --</option>
                    @php 
                        $currentRuangan = old('ruangan', $course->ruangan ?? ''); 
                        $groupedRuangans = ($ruangans ?? collect())->groupBy('jenis_ruangan');
                    @endphp
                    
                    @foreach($groupedRuangans as $jenis => $ruanganGroup)
                        <optgroup label="Ruangan: {{ $jenis }}">
                            @foreach($ruanganGroup as $r)
                                <option value="{{ $r->nama_ruangan }}" {{ $currentRuangan === $r->nama_ruangan ? 'selected' : '' }}>
                                    {{ $r->nama_ruangan }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach

                    @if(isset($labors) && count($labors) > 0)
                    <optgroup label="Ruangan: Laboratorium (Sistem Lab)">
                        @foreach($labors as $lab)
                            <option value="LAB_{{ $lab->id }}_{{ $lab->nama_labor }}" data-labor-id="{{ $lab->id }}" {{ $currentRuangan === "LAB_{$lab->id}_{$lab->nama_labor}" ? 'selected' : '' }}>
                                {{ $lab->nama_labor }} (Laboratorium)
                            </option>
                        @endforeach
                    </optgroup>
                    @endif
                </select>
                <input type="hidden" name="labor_id" id="labor_id" value="{{ old('labor_id', $course->labor_id ?? '') }}">
                {{-- juga tampilkan ringkasan konflik ruangan di bawah input ruangan (opsional) --}}
                @if($conflictDetails && isset($conflictDetails['ruangan']) && count($conflictDetails['ruangan']))
                <small class="text-danger d-block mt-1">
                    Ruangan ini bentrok dengan jadwal lain. Lihat detail di atas dan pilih ruangan berbeda.
                </small>
                @endif
                <div id="live-conflict-warning" class="mt-2"></div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <a href="{{ route('sistem_akademik.course.index') }}" class="btn btn-light border px-4">Batal</a>
                <button type="submit" class="btn btn-primary px-4">
                    {{ isset($course) ? 'Update Course' : 'Simpan Course' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
    (function() {
        var el = document.getElementById('course-form');
        window.CourseFormConfig = {
            studentsUrl: el.dataset ? el.dataset.studentsUrl : '{{ route("sistem_akademik.get-students-by-jurusan") }}',
            recommendationsUrl: el.dataset ? el.dataset.recommendationsUrl : '{{ url("/sistem-akademik/course/get-recommendations") }}',
            initialKelas: el.dataset ? el.dataset.initialKelas : null,
            preselectSiswa: el.dataset ? JSON.parse(el.dataset.preselectSiswa || '[]') : [],
            initialHari: el.dataset ? el.dataset.initialHari : '',
            slotIds: el.dataset ? JSON.parse(el.dataset.slotIds || '[]') : @json(array_keys($slots)),
            slotDetails: el.dataset ? JSON.parse(el.dataset.slotDetails || '{}') : @json($slots)
        };
    })();
</script>

<script src="{{ asset('assets/js/course.js') }}"></script>
@endsection