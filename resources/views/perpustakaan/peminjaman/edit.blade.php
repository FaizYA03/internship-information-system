@extends('perpustakaan.layouts.main')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10 col-12">
            <h1 class="page-title">Update Status Peminjaman</h1>
            <p class="text-muted mb-4">Ubah status peminjaman buku perpustakaan SMK Negeri 5 Padang</p>

            <div class="form-container" data-aos="fade-up">
                <form action="{{ route('perpustakaan.peminjaman.update', $peminjaman->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="nama" class="form-label">Nama Peminjam</label>
                        <input
                            type="text"
                            name="nama"
                            id="nama"
                            class="form-control"
                            value="{{ $peminjaman->nama }}"
                            readonly>
                    </div>

                    <div class="mb-4">
                        <label for="buku_judul" class="form-label">Buku yang Dipinjam</label>
                        <input
                            type="text"
                            name="buku_judul"
                            id="buku_judul"
                            class="form-control"
                            value="{{ $peminjaman->buku->judul }}"
                            readonly>
                        <div class="form-text">
                            Stok saat ini: <span data-book-stock="{{ $peminjaman->buku->stok }}">{{ $peminjaman->buku->stok }}</span> buku
                            @if ($peminjaman->status == 'Disetujui' && $peminjaman->buku->stok == 0)
                                <span class="text-danger"> • Buku ini sedang habis stok</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="tanggal_pinjam" class="form-label">Tanggal Peminjaman</label>
                        <input
                            type="text"
                            name="tanggal_pinjam"
                            id="tanggal_pinjam"
                            class="form-control"
                            value="{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d F Y') }}"
                            readonly>
                    </div>

                    <div class="mb-4">
                        <label for="tanggal_kembali" class="form-label">Tenggat Waktu Kembali (Expected)</label>
                        <input
                            type="date"
                            name="tanggal_kembali"
                            id="tanggal_kembali"
                            class="form-control"
                            value="{{ $peminjaman->tanggal_kembali ? \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('Y-m-d') : '' }}"
                            readonly>
                        <div class="form-text">
                            Batas waktu asli dilarang untuk diubah. Gunakan kolom "Tanggal Dikembalikan Aktual" di bawah untuk mencatat pengembalian fisik.
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="status" class="form-label">
                            Status Peminjaman
                            @if ($peminjaman->status == 'Menunggu')
                                <span class="status-badge status-pending">{{ $peminjaman->status }}</span>
                            @elseif ($peminjaman->status == 'Ditolak')
                                <span class="status-badge status-rejected">{{ $peminjaman->status }}</span>
                            @elseif ($peminjaman->status == 'Disetujui')
                                <span class="status-badge status-approved">{{ $peminjaman->status }}</span>
                            @elseif ($peminjaman->status == 'Dikembalikan')
                                <span class="status-badge status-returned">{{ $peminjaman->status }}</span>
                            @elseif ($peminjaman->status == 'Terlambat')
                                <span class="badge bg-danger">Terlambat</span>
                            @endif
                        </label>
                        <select name="status" id="status" class="form-select status-field" data-current-status="{{ $peminjaman->status }}">
                            <option value="Menunggu" {{ $peminjaman->status == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="Disetujui" {{ $peminjaman->status == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                            <option value="Ditolak" {{ $peminjaman->status == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                            <option value="Dikembalikan" {{ $peminjaman->status == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                            <option value="Terlambat" {{ $peminjaman->status == 'Terlambat' ? 'selected' : '' }}>Terlambat (Denda)</option>
                        </select>
                        <div class="form-text">
                            <i class="bi bi-info-circle"></i> Perubahan status ke "Disetujui" akan mengurangi stok buku.
                            Status "Dikembalikan" atau "Terlambat" akan menambah stok kembali.
                        </div>
                    </div>

                    <!-- Fom Tanggal Aktual -->
                    <div id="kembaliArea" style="display: {{ in_array($peminjaman->status, ['Dikembalikan', 'Terlambat']) ? 'block' : 'none' }}; background: #f8fafc; padding: 1.5rem; border-radius: 12px; border: 1px solid #e2e8f0; margin-bottom: 1.5rem;">
                        <h6 class="mb-3 text-secondary"><i class="bi bi-clock-history"></i> Rekam Jejak Pengembalian</h6>
                        <div class="mb-3">
                            <label for="tanggal_dikembalikan" class="form-label">Tanggal Dikembalikan Aktual</label>
                            <input
                                type="date"
                                name="tanggal_dikembalikan"
                                id="tanggal_dikembalikan"
                                class="form-control"
                                value="{{ old('tanggal_dikembalikan', $peminjaman->tanggal_dikembalikan ? \Carbon\Carbon::parse($peminjaman->tanggal_dikembalikan)->format('Y-m-d') : '') }}">
                        </div>

                        <div id="dendaArea" class="mb-3">
                            <label for="denda" class="form-label text-danger fw-bold">Nominal Denda (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input
                                    type="number"
                                    name="denda"
                                    id="denda"
                                    class="form-control text-danger fw-bold"
                                    value="{{ old('denda', $peminjaman->denda ?? 0) }}">
                            </div>
                            <div class="form-text text-danger" id="dendaNote">
                                Denda otomatis adalah Rp 5.000 per minggu setelah melewati batas tenggat. Form ini dapat Anda ubah manual bila ada penyesuaian khusus.
                            </div>
                        </div>

                        <div class="mb-3 form-check" id="lunasArea" style="display: {{ old('denda', $peminjaman->denda ?? 0) > 0 ? 'block' : 'none' }};">
                            <input type="hidden" name="denda_dibayar" value="0">
                            <input class="form-check-input" type="checkbox" value="1" id="denda_dibayar" name="denda_dibayar" {{ old('denda_dibayar', $peminjaman->denda_dibayar) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold text-success" for="denda_dibayar">
                                Tandai Denda Telah Dilunasi
                            </label>
                            <div class="form-text">Ceklis jika siswa sudah membayar lunas dendanya untuk memulihkan akses pinjamnya.</div>
                        </div>
                    </div>

                    <div class="d-flex mt-5 flex-wrap">
                        <a href="{{ route('perpustakaan.peminjaman.index') }}" class="btn-secondary-app mb-2 me-2">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn-secondary-app mb-2">
                            <i class="bi bi-check-circle"></i> Update Status
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.getElementById('status');
        const kembaliArea = document.getElementById('kembaliArea');
        const tglAktual = document.getElementById('tanggal_dikembalikan');
        const tglTenggat = document.getElementById('tanggal_kembali');
        const dendaInput = document.getElementById('denda');

        function updateLogic() {
            if (statusSelect.value === 'Dikembalikan' || statusSelect.value === 'Terlambat') {
                kembaliArea.style.display = 'block';
                if (!tglAktual.value) {
                    const today = new Date().toISOString().split('T')[0];
                    tglAktual.value = today;
                }
                kalkulasiDenda();
            } else {
                kembaliArea.style.display = 'none';
            }
        }

        function kalkulasiDenda() {
            if (!tglAktual.value || !tglTenggat.value) return;

            const aktual = new Date(tglAktual.value);
            const tenggat = new Date(tglTenggat.value);
            
            // Set both to midnight to strictly compare dates
            aktual.setHours(0,0,0,0);
            tenggat.setHours(0,0,0,0);

            const diffTime = aktual - tenggat;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

            if (diffDays > 0) {
                // Late!
                statusSelect.value = 'Terlambat';
                const weeksLate = Math.ceil(diffDays / 7);
                dendaInput.value = weeksLate * 5000;
            } else {
                // Not late, reset completely if status was automatically 'Terlambat'
                if (statusSelect.value === 'Terlambat') {
                    statusSelect.value = 'Dikembalikan';
                }
                dendaInput.value = 0;
            }
            
            toggleLunasArea();
        }
        
        function toggleLunasArea() {
            const lunasArea = document.getElementById('lunasArea');
            if (parseInt(dendaInput.value) > 0) {
                lunasArea.style.display = 'block';
            } else {
                lunasArea.style.display = 'none';
                document.getElementById('denda_dibayar').checked = false;
            }
        }

        dendaInput.addEventListener('input', toggleLunasArea);

        statusSelect.addEventListener('change', updateLogic);
        tglAktual.addEventListener('change', kalkulasiDenda);
    });
</script>
@endpush
