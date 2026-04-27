@extends('perpustakaan.layouts.main')

@section('content')
<div class="container-fluid py-4" style="background-color: #f8f9fa;">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold text-dark mb-1">Evaluasi Koleksi EWS</h2>
            <p class="text-muted">Daftar buku yang tidak aktif lebih dari 6 bulan</p>
        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 border-start border-success border-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($bukuPasif->isEmpty())
        <div class="alert alert-success border-0 shadow-sm">
            <i class="bi bi-shield-check me-2"></i> Tidak ada buku pasif saat ini. Semua koleksi masih aktif dipinjam.
        </div>
    @else
    <!-- Summary Badge -->
    <div class="alert alert-warning border-start border-warning border-4 shadow-sm mb-4">
        <div class="d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill fs-3 text-warning me-3"></i>
            <div>
                <div class="fw-bold">Perhatian: Koleksi Pasif Terdeteksi</div>
                <div>Terdapat <strong>{{ $bukuPasif->count() }} buku</strong> yang tidak aktif dipinjam selama lebih dari 6 bulan. Berikan instruksi tindakan untuk setiap buku.</div>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-2">
            <h5 class="fw-semibold text-primary m-0"><i class="bi bi-list-task me-2"></i> Tinjauan Tindakan Koleksi</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('kepsek.ews.store') }}" id="formEvaluasi">
                @csrf
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" class="text-center" style="width: 50px;">No</th>
                                <th scope="col">Judul Buku</th>
                                <th scope="col">Kategori</th>
                                <th scope="col" class="text-center">Tahun Terbit</th>
                                <th scope="col" class="text-center">Terakhir Dipinjam</th>
                                <th scope="col" style="min-width: 200px;">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bukuPasif as $index => $buku)
                            <tr>
                                <td class="text-center text-muted">{{ $index + 1 }}</td>
                                <td>
                                    <div class="fw-bold">{{ $buku->judul }}</div>
                                    <small class="text-muted">{{ $buku->pengarang }}</small>
                                </td>
                                <td>{{ $buku->category ? $buku->category->nama_kategori : '-' }}</td>
                                <td class="text-center">{{ $buku->tahun_terbit ?? '-' }}</td>
                                <td class="text-center">
                                    @php
                                        $lastPinjam = $buku->peminjaman->sortByDesc('tanggal_pinjam')->first();
                                    @endphp
                                    @if($lastPinjam)
                                        <span class="text-warning fw-semibold">
                                            {{ \Carbon\Carbon::parse($lastPinjam->tanggal_pinjam)->translatedFormat('d M Y') }}
                                        </span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25">Belum Pernah</span>
                                    @endif
                                </td>
                                <td>
                                    <input type="hidden" name="id_buku[]" value="{{ $buku->id }}">
                                    <select class="form-select form-select-sm" name="tindakan[]" required>
                                        <option value="" selected disabled>-- Pilih Tindakan --</option>
                                        <option value="Promosi">Promosi Buku</option>
                                        <option value="Evaluasi">Evaluasi ke Kurikulum</option>
                                        <option value="Pemutihan">Pemutihan (Gudangkan/Weeding)</option>
                                    </select>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold d-flex align-items-center shadow-sm" id="btnSubmit">
                        <i class="bi bi-send-check me-2 fs-5"></i> Kirim Instruksi Tindakan
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>

<style>
    .table-hover tbody tr:hover { background-color: #f8f9fa; transition: background-color 0.2s ease; }
    .badge { font-weight: 500; }
</style>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('formEvaluasi')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;

        // Pastikan semua dropdown sudah dipilih
        const selects = form.querySelectorAll('select[name="tindakan[]"]');
        let adaKosong = false;
        selects.forEach(s => { if (!s.value) adaKosong = true; });

        if (adaKosong) {
            Swal.fire({ icon: 'warning', title: 'Belum Lengkap', text: 'Harap pilih tindakan untuk setiap buku sebelum mengirim.' });
            return;
        }

        Swal.fire({
            title: 'Kirim Instruksi Tindakan?',
            text: "Instruksi ini akan dikirim ke Admin Perpustakaan dan Waka Kurikulum sesuai jenis tindakannya.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Kirim!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('formEvaluasi').submit();
            }
        });
    });
</script>
@endsection
