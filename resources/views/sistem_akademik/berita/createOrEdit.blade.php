@extends('sistem_akademik.layouts.main', ['title' => isset($berita) ? 'Edit Berita' : 'Tambah Berita'])

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold"><i class="bi bi-file-earmark-text text-primary me-2"></i> {{ isset($berita) ? 'Edit Berita' : 'Tambah Berita' }}</h5>
    </div>
    <div class="card-body p-4">
        <form id="beritaForm"
            action="{{ isset($berita) ? route('sistem_akademik.berita.update', $berita->id) : route('sistem_akademik.berita.store') }}"
            method="POST"
            enctype="multipart/form-data">
            @csrf
            @if(isset($berita))
            @method('PUT')
            @endif

            <!-- Judul -->
            <div class="mb-3">
                <label for="judul" class="form-label">Judul</label>
                <input
                    type="text"
                    class="form-control"
                    id="judul"
                    name="judul"
                    value="{{ old('judul', $berita->judul ?? '') }}"
                    required>
            </div>

            <!-- Isi -->
            <div class="mb-3">
                <label for="isi" class="form-label">Isi</label>
                <textarea
                    class="form-control"
                    id="isi"
                    name="isi"
                    rows="6"
                    required>{{ old('isi', $berita->isi ?? '') }}</textarea>
            </div>

            <!-- Preview gambar saat ini -->
            <div class="mb-3">
                <label for="foto" class="form-label">Foto</label><br>
                @if(isset($berita) && $berita->foto)
                <img src="{{ asset('assets/berita/' . $berita->foto) }}" id="output_image" class="img-preview mb-2" alt="">
                @else
                <img src="" id="output_image" class="img-preview mb-2" style="display:none;">
                @endif

                <input type="file" id="upload" name="foto" onchange="preview_image(event)" class="form-control" />
            </div>

            <!-- File attachment (dokumen/pdf/etc) -->
            <div class="mb-3">
                <label for="file" class="form-label">Lampirkan File (opsional)</label>
                <input type="file" name="file" class="form-control mb-2" />
                @if(isset($berita) && $berita->file)
                <a href="{{ asset('file/' . $berita->file) }}" target="_blank" class="current-file">File saat ini: {{ $berita->file }}</a>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="remove_file" id="remove_file" value="1">
                    <label class="form-check-label" for="remove_file">Hapus file saat ini</label>
                </div>
                @endif
            </div>

            <!-- Kategori -->
            <div class="mb-3">
                <label for="kategori" class="form-label">Kategori</label>
                <select name="kategori" id="kategori" class="form-select" required>
                    <option value="">Pilih kategori</option>
                    <option value="informasi" {{ old('kategori', $berita->kategori ?? '') === 'informasi' ? 'selected' : '' }}>Informasi</option>
                    <option value="prestasi" {{ old('kategori', $berita->kategori ?? '') === 'prestasi' ? 'selected' : '' }}>Prestasi</option>
                    <option value="pemberitahuan" {{ old('kategori', $berita->kategori ?? '') === 'pemberitahuan' ? 'selected' : '' }}>Pemberitahuan</option>
                </select>
            </div>

            <!-- Aksi: Simpan + (Jika edit) tombol Hapus -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="d-flex gap-2">
                    <a href="{{ route('sistem_akademik.berita.index') }}" class="btn btn-light border px-4">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        Simpan
                    </button>
                </div>

                @isset($berita)
                <div>
                    <button type="button" class="btn btn-outline-danger" id="btnDelete">
                        <i class="bi bi-trash me-1"></i> Hapus
                    </button>
                </div>
                @endisset
            </div>
        </form>

        @isset($berita)
        <form id="deleteForm"
            action="{{ route('sistem_akademik.berita.destroy', $berita->id) }}"
            method="POST">
            @csrf
            @method('DELETE')
        </form>
        @endisset
    </div>
</div>
@endsection

@section('script')
<script type='text/javascript'>
    function preview_image(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('output_image');
            output.src = reader.result;
            output.style.display = 'block';
        }
        if (event.target.files && event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }

    (function() {
        // Hapus: konfirmasi menggunakan SweetAlert2 bila ada, fallback ke confirm()
        const btnDelete = document.getElementById('btnDelete');
        if (!btnDelete) return;

        btnDelete.addEventListener('click', function() {
            // jika SweetAlert2 tersedia (biasanya sudah dipakai di project)
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Anda yakin?',
                    text: 'Data berita akan dihapus dan tidak dapat dikembalikan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('deleteForm').submit();
                    }
                });
                return;
            }

            // fallback sederhana
            if (confirm('Hapus berita ini? Tindakan ini tidak dapat dibatalkan.')) {
                document.getElementById('deleteForm').submit();
            }
        });
    })();
</script>
@endsection