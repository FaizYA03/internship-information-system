@extends('admin.layouts.main')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="fw-bold mb-0">Form Pengajuan Pengadaan Alat & Bahan</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('lab.kepala_lab.pengadaan.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Nama Barang/Alat</label>
                            <input type="text" name="nama_barang" class="form-control rounded-3" placeholder="Contoh: Osiloskop Digital Rigol" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Spesifikasi Singkat</label>
                            <textarea name="spesifikasi" class="form-control rounded-3" rows="2" placeholder="Contoh: 100MHz, 2 Channels, Memory depth 24Mpts"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jumlah (Unit)</label>
                            <input type="number" name="jumlah" class="form-control rounded-3" min="1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Estimasi Harga Satuan (Rp)</label>
                            <input type="number" name="estimasi_harga" class="form-control rounded-3" placeholder="Contoh: 5000000">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Tingkat Urgensi</label>
                            <select name="urgensi" class="form-select rounded-3" required>
                                <option value="rendah">Rendah (Penyimpanan Stok)</option>
                                <option value="sedang" selected>Sedang (Kebutuhan Praktikum Rutin)</option>
                                <option value="tinggi">Tinggi (Mendesak/Alat Utama Rusak)</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Alasan Pengadaan</label>
                            <textarea name="alasan" class="form-control rounded-3" rows="3" placeholder="Sebutkan alasan atau kaitan dengan kurikulum kompetensi keahlian" required></textarea>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top d-flex gap-2">
                        <button type="submit" class="btn btn-primary rounded-pill px-5">Kirim Pengajuan</button>
                        <a href="{{ route('lab.kepala_lab.pengadaan.index') }}" class="btn btn-light rounded-pill px-4">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
