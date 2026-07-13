@extends('magang.layouts.main')

@section('content')
<div class="container">
    <h3>Form Pengajuan Judul Laporan Akhir Magang</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('pengajuan-judul.store') }}" method="POST">
        @csrf

        <!-- Nama -->
        <div class="mb-3">
            <label>Nama Lengkap</label>
            <input type="text" class="form-control" value="{{ Auth::user()->nama }}" disabled>
        </div>

        <!-- NIS -->
        <div class="mb-3">
            <label>NIS/NISN</label>
            <input type="text" class="form-control" value="{{ Auth::user()->nis_nip ?? 'Belum diatur' }}" disabled>
        </div>

        <!-- Jurusan -->
        <div class="mb-3">
            <label for="jurusan">Jurusan</label>
            <select name="jurusan" class="form-control" required>
                <option value="">-- Pilih Jurusan --</option>
                <option value="Bisnis Konstruksi dan Properti">Bisnis Konstruksi dan Properti</option>
                <option value="Desain Pemodelan dan Informasi Bangunan">Desain Pemodelan dan Informasi Bangunan</option>
                <option value="Teknik Audio Video A">Teknik Audio Video A</option>
                <option value="Teknik Elektronika Industri">Teknik Elektronika Industri</option>
                <option value="Teknik Instalasi Tenaga Listrik A">Teknik Instalasi Tenaga Listrik A</option>
                <option value="Teknik Pemesinan A">Teknik Pemesinan A</option>
                <option value="Teknik Kendaraan Ringan A">Teknik Kendaraan Ringan A</option>
                <option value="Teknik Bodi Kendaraan Ringan">Teknik Bodi Kendaraan Ringan</option>
                <option value="Teknik Bisnis Sepeda Motor A">Teknik Bisnis Sepeda Motor A</option>
                <option value="Teknik Pendingin dan Tata Udara">Teknik Pendingin dan Tata Udara</option>
                <option value="Teknik Komputer Jaringan A">Teknik Komputer Jaringan A</option>
            </select>
        </div>

        <!-- Perusahaan -->
        <div class="mb-3">
            <label>Nama Perusahaan</label>
            <input type="text" class="form-control" value="{{ $namaPerusahaan ?? 'Belum terhubung' }}" disabled>
            <input type="hidden" name="wakil_perusahaan_id" value="{{ $wakilPerusahaanId }}">
        </div>

        <!-- Judul -->
        <div class="mb-3">
            <label for="judul_laporan">Judul Laporan</label>
            <div class="d-flex gap-2 mb-2">
                <input type="text" name="judul_laporan" id="judul_laporan" class="form-control" required>
                <button type="button" class="btn btn-outline-info text-nowrap" id="btnGenerateAi">
                    <i class="bi bi-magic me-1"></i> Generate AI
                </button>
            </div>
            
            <!-- Tempat untuk menampilkan rekomendasi judul -->
            <div id="aiRecommendations" class="d-none bg-light border border-info rounded p-3 mb-2 shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-info fw-bold"><i class="bi bi-robot me-1"></i> Rekomendasi Judul dari AI:</span>
                    <button type="button" class="btn-close btn-sm" id="btnCloseRecommendations"></button>
                </div>
                <div id="aiRecommendationList" class="d-flex flex-column gap-2">
                    <!-- List judul akan muncul di sini -->
                </div>
                <small class="text-muted mt-2 d-block"><i class="bi bi-info-circle me-1"></i> Klik salah satu judul di atas untuk menggunakannya.</small>
            </div>
        </div>

        <!-- ✅ LINK DRIVE (WAJIB SESUAI DB) -->
        <div class="mb-3">
            <label for="link_drive">Link Google Drive</label>
            <input type="url" name="link_drive" class="form-control" placeholder="https://drive.google.com/..." required>
            <small class="text-muted">Upload proposal / file pendukung ke Google Drive</small>
        </div>

        <button type="submit" class="btn btn-primary">Kirim</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnGenerateAi = document.getElementById('btnGenerateAi');
    const judulInput = document.getElementById('judul_laporan');
    const jurusanSelect = document.querySelector('select[name="jurusan"]');
    const aiContainer = document.getElementById('aiRecommendations');
    const aiList = document.getElementById('aiRecommendationList');
    const btnClose = document.getElementById('btnCloseRecommendations');

    btnClose.addEventListener('click', () => {
        aiContainer.classList.add('d-none');
    });

    btnGenerateAi.addEventListener('click', async function() {
        const jurusan = jurusanSelect.value;
        if (!jurusan) {
            alert('Mohon pilih jurusan terlebih dahulu!');
            jurusanSelect.focus();
            return;
        }

        // Ubah state tombol
        const originalText = btnGenerateAi.innerHTML;
        btnGenerateAi.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Loading...';
        btnGenerateAi.disabled = true;

        try {
            const response = await fetch("{{ route('magang.pengajuan_judul.generate_ai') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ jurusan: jurusan })
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.error || 'Terjadi kesalahan sistem');
            }

            // Bersihkan list sebelumnya
            aiList.innerHTML = '';
            
            if (data.judul && data.judul.length > 0) {
                data.judul.forEach(judul => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'btn btn-sm btn-outline-secondary text-start p-2 border-1 rounded';
                    btn.style.whiteSpace = 'normal';
                    btn.innerHTML = judul;
                    
                    btn.addEventListener('click', () => {
                        judulInput.value = judul;
                        // Tambahkan efek highlight singkat
                        judulInput.classList.add('is-valid');
                        setTimeout(() => judulInput.classList.remove('is-valid'), 2000);
                        aiContainer.classList.add('d-none');
                    });
                    
                    aiList.appendChild(btn);
                });
                
                // Tampilkan container
                aiContainer.classList.remove('d-none');
            } else {
                alert('AI tidak mengembalikan rekomendasi judul. Coba lagi.');
            }
        } catch (error) {
            alert(error.message);
        } finally {
            // Kembalikan tombol
            btnGenerateAi.innerHTML = originalText;
            btnGenerateAi.disabled = false;
        }
    });
});
</script>
@endsection