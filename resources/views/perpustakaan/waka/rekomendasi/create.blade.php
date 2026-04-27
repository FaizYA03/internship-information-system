@extends('perpustakaan.layouts.main')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="mb-4">
                <a href="{{ route('perpustakaan.waka.rekomendasi.index') }}" class="text-decoration-none text-muted mb-2 d-inline-block">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Rekomendasi
                </a>
                <h2 class="fw-bold mb-1"><i class="bi bi-bookmark-plus text-primary me-2"></i>{{ $header }}</h2>
                <p class="text-muted mb-0">Ajukan referensi buku baru yang dibutuhkan dalam proses pembelajaran kurikulum.</p>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form action="{{ route('perpustakaan.waka.rekomendasi.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Judul Buku <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="judul_buku" required placeholder="Masukkan judul buku lengkap">
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Pengarang</label>
                                <input type="text" class="form-control" name="pengarang" placeholder="Nama pengarang">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Penerbit</label>
                                <input type="text" class="form-control" name="penerbit" placeholder="Nama penerbit">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Mata Pelajaran Terkait</label>
                                <select name="mapel_id" class="form-select">
                                    <option value="">-- Pilih Mata Pelajaran --</option>
                                    @foreach($mapels as $mapel)
                                        <option value="{{ $mapel->id }}">{{ $mapel->nama_mapel }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Jurusan Terkait</label>
                                <select name="jurusan_id" class="form-select">
                                    <option value="">-- Umum (Berlaku Semua Jurusan) --</option>
                                    @foreach($jurusans as $jurusan)
                                        <option value="{{ $jurusan->id }}">{{ $jurusan->nama_jurusan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Prioritas <span class="text-danger">*</span></label>
                            <div class="d-flex gap-3 mt-1">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="prioritas" id="prioritasRendah" value="Low">
                                    <label class="form-check-label badge bg-info text-dark" for="prioritasRendah">
                                        Rendah (Opsional)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="prioritas" id="prioritasSedang" value="Medium" checked>
                                    <label class="form-check-label badge bg-warning text-dark" for="prioritasSedang">
                                        Sedang (Dianjurkan)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="prioritas" id="prioritasTinggi" value="High">
                                    <label class="form-check-label badge bg-danger" for="prioritasTinggi">
                                        Tinggi (Wajib Segera)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Alasan Rekomendasi & Kompetensi Dasar <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="alasan" rows="3" required placeholder="Jelaskan untuk kebutuhan kompetensi dasar apa buku ini diperlukan..."></textarea>
                            <small class="text-muted">Alasan yang kuat akan membantu Admin dan Kepala Sekolah dalam meyetujui pengadaan.</small>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('perpustakaan.waka.rekomendasi.index') }}" class="btn btn-light me-2">Batal</a>
                            <button type="submit" class="btn btn-primary d-flex align-items-center">
                                <i class="bi bi-send me-2"></i> Ajukan Rekomendasi
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer bg-light p-3">
                    <div class="d-flex align-items-start text-muted small">
                        <i class="bi bi-info-circle-fill text-primary me-2 fs-5 mt-n1"></i>
                        <p class="mb-0">Sistem akan secara otomatis mengecek apakah judul yang diajukan sudah ada di koleksi perpustakaan. Jika belum, data akan diteruskan sebagai draft pengadaan kepada Admin Perpustakaan.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
