@extends('sistem_akademik.layouts.main', ['title' => 'Kelola Peminatan Siswa'])

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold"><i class="bi bi-star text-primary me-2"></i> {{ $header }}</h5>
    </div>
    <div class="card-body p-4">
        <form action="{{ isset($peminatan) 
                ? route('sistem_akademik.peminatan.update', $peminatan->id) 
                : route('sistem_akademik.peminatan.store') }}"
            method="POST">
            @csrf
            @if(isset($peminatan))
            @method('PUT')
            @endif

            <div class="mb-3">
                <label>Nama:</label>
                @if(Auth::user()->role === 'admin_sa')
                <select name="user_id" class="form-control" required>
                    <option value="">-- Pilih Nama --</option>
                    @foreach($users as $user)
                    <option
                        value="{{ $user->id }}"
                        {{ old('user_id', $peminatan->user_id ?? '') == $user->id 
                                ? 'selected' 
                                : '' }}>
                        {{ $user->nama }}
                    </option>
                    @endforeach
                </select>
                @else
                {{-- siswa --}}
                <input
                    type="hidden"
                    name="user_id"
                    value="{{ Auth::id() }}">
                <input
                    type="text"
                    class="form-control-plaintext"
                    readonly
                    value="{{ Auth::user()->nama }}">
                @endif
            </div>

            <div class="mb-3">
                <label for="minat">Minat:</label>
                <select id="minat" name="minat" class="form-control" required>
                    <option value="">-- Pilih Minat --</option>
                    @php
                    $options = ['bekerja'=>'Bekerja','wirausaha'=>'Wirausaha','kuliah'=>'Kuliah','lainnya'=>'Lainnya'];
                    $selectedMinat = old('minat', $peminatan->minat ?? '');
                    @endphp
                    @foreach($options as $key => $label)
                    <option value="{{ $key }}"
                        {{ $selectedMinat === $key ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Alasan:</label>
                <textarea name="alasan" class="form-control">{{ old('alasan', $peminatan->alasan ?? '') }}</textarea>
            </div>

            {{-- PEMILIHAN JURUSAN (untuk minat = kuliah) --}}
            <div class="mb-3 conditional-field" id="group-pemilihan-jurusan" data-for="kuliah" aria-hidden="true" style="display:none;">
                <label>Jurusan yang dipilih:</label>
                <input type="text" id="pemilihan_jurusan" name="pemilihan_jurusan" class="form-control"
                    value="{{ old('pemilihan_jurusan', $peminatan->pemilihan_jurusan ?? '') }}">
            </div>

            {{-- JENIS PEKERJAAN (untuk minat = bekerja) --}}
            <div class="mb-3 conditional-field" id="group-jenis-pekerjaan" data-for="bekerja" aria-hidden="true" style="display:none;">
                <label>Jenis Pekerjaan:</label>
                <input type="text" id="jenis_pekerjaan" name="jenis_pekerjaan" class="form-control"
                    value="{{ old('jenis_pekerjaan', $peminatan->jenis_pekerjaan ?? '') }}">
            </div>

            {{-- IDE BISNIS (untuk minat = wirausaha) --}}
            <div class="mb-3 conditional-field" id="group-ide-bisnis" data-for="wirausaha" aria-hidden="true" style="display:none;">
                <label>Ide Bisnis:</label>
                <input type="text" id="ide_bisnis" name="ide_bisnis" class="form-control"
                    value="{{ old('ide_bisnis', $peminatan->ide_bisnis ?? '') }}">
            </div>

            <div class="mb-3">
                <label>Penghasilan Orang Tua:</label>
                <input type="number" name="penghasilan_ortu" class="form-control"
                    value="{{ old('penghasilan_ortu', $peminatan->penghasilan_ortu ?? '') }}">
            </div>

            <div class="mb-3">
                <label>Tanggungan Keluarga:</label>
                <input type="number" name="tanggungan_keluarga" class="form-control"
                    value="{{ old('tanggungan_keluarga', $peminatan->tanggungan_keluarga ?? '') }}">
            </div>

            {{-- Kolom Link Raport --}}
            <div class="mb-3">
                <label>Link Google Drive Raport:</label>
                <input type="url" name="file_raport" class="form-control"
                    placeholder="https://drive.google.com/..."
                    value="{{ old('file_raport', $peminatan->file_raport ?? '') }}">
                @if(isset($peminatan) && $peminatan->file_raport)
                <a href="{{ $peminatan->file_raport }}" target="_blank">
                    Lihat Link Raport Saat Ini
                </a>
                @endif
                @error('file_raport')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Kolom Link Angket --}}
            <div class="mb-3">
                <label>Link Google Drive Angket:</label>
                <input type="url" name="file_angket" class="form-control"
                    placeholder="https://drive.google.com/..."
                    value="{{ old('file_angket', $peminatan->file_angket ?? '') }}">
                @if(isset($peminatan) && $peminatan->file_angket)
                <a href="{{ $peminatan->file_angket }}" target="_blank">
                    Lihat Link Angket Saat Ini
                </a>
                @endif
                @error('file_angket')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2 mt-4">
                <a href="{{ route('sistem_akademik.peminatan.index') }}" class="btn btn-light border px-4">Batal</a>
                <button class="btn btn-primary px-4" type="submit">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectMinat = document.getElementById('minat');
        const groups = {
            pemilihan_jurusan: document.getElementById('group-pemilihan-jurusan'),
            jenis_pekerjaan: document.getElementById('group-jenis-pekerjaan'),
            ide_bisnis: document.getElementById('group-ide-bisnis')
        };

        // helper to show/hide and toggle required
        function setGroupVisibility(groupEl, visible) {
            if (!groupEl) return;
            const inputs = groupEl.querySelectorAll('input, textarea, select');
            if (visible) {
                groupEl.style.display = '';
                groupEl.setAttribute('aria-hidden', 'false');
                inputs.forEach(i => i.setAttribute('required', 'required'));
            } else {
                groupEl.style.display = 'none';
                groupEl.setAttribute('aria-hidden', 'true');
                inputs.forEach(i => {
                    i.removeAttribute('required');
                    // do not clear value here: keep user input in case they switch back
                });
            }
        }

        function updateVisibility() {
            const val = selectMinat.value;
            // default hide all
            setGroupVisibility(groups.pemilihan_jurusan, false);
            setGroupVisibility(groups.jenis_pekerjaan, false);
            setGroupVisibility(groups.ide_bisnis, false);

            if (val === 'kuliah') {
                setGroupVisibility(groups.pemilihan_jurusan, true);
            } else if (val === 'bekerja') {
                setGroupVisibility(groups.jenis_pekerjaan, true);
            } else if (val === 'wirausaha') {
                setGroupVisibility(groups.ide_bisnis, true);
            }
            // 'lainnya' -> keep all hidden
        }

        // init on load (uses server-side old()/model values)
        updateVisibility();

        // listen for changes
        selectMinat.addEventListener('change', updateVisibility);
    });
</script>
@endsection