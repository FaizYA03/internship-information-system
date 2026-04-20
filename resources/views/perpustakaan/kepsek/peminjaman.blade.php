@extends('perpustakaan.layouts.main')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12" data-aos="fade-up">
            <h2 class="fw-bold mb-1">Daftar Peminjaman Aktif</h2>
            <p class="text-muted">Pantau rincian log peminjaman perpustakaan keseluruhan</p>
        </div>
    </div>

    <div class="row g-4" data-aos="fade-up" data-aos-delay="100">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 12px; height: 100%;">
                <div class="card-header bg-white p-4 border-bottom">
                    <!-- Filter Data -->
                    <form action="{{ route('kepsek.peminjaman') }}" method="GET" class="row align-items-end g-3">
                        <div class="col-md-2">
                            <label class="form-label form-label-sm mb-1 text-muted">Mulai</label>
                            <input type="date" class="form-control form-control-sm" name="dari_tanggal" value="{{ request('dari_tanggal') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label form-label-sm mb-1 text-muted">Sampai</label>
                            <input type="date" class="form-control form-control-sm" name="sampai_tanggal" value="{{ request('sampai_tanggal') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label form-label-sm mb-1 text-muted">Kategori</label>
                            <select class="form-select form-select-sm" name="kategori_id">
                                <option value="">Semua Kategori</option>
                                @foreach($kategoris as $kat)
                                    <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label form-label-sm mb-1 text-muted">Status</label>
                            <select class="form-select form-select-sm" name="status">
                                <option value="">Semua Status</option>
                                <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam (Aktif)</option>
                                <option value="Dikembalikan" {{ request('status') == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                                <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-sm btn-primary w-100" style="background-color: #4ecdc4; border:none;">
                                <i class="bi bi-funnel"></i> Filter
                            </button>
                            @if(request('dari_tanggal') || request('sampai_tanggal') || request('status') || request('kategori_id'))
                            <a href="{{ route('kepsek.peminjaman') }}" class="btn btn-sm btn-secondary w-100">
                                <i class="bi bi-x-circle"></i> Reset
                            </a>
                            @endif
                        </div>
                    </form>
                </div>
                
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">No</th>
                                    <th>Peminjam</th>
                                    <th>Buku</th>
                                    <th>Kategori</th>
                                    <th>Tgl Pinjam</th>
                                    <th>Tgl Kembali</th>
                                    <th class="pe-4">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($daftarpeminjaman as $index => $item)
                                <tr>
                                    <td class="ps-4 text-muted">{{ $index + 1 }}</td>
                                    <td><span class="fw-medium text-dark">{{ $item->nama }}</span></td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 200px;" title="{{ $item->buku->judul ?? '-' }}">
                                            {{ $item->buku->judul ?? '-' }}
                                        </div>
                                    </td>
                                    <td><span class="badge bg-light text-dark border">{{ $item->buku->category->nama_kategori ?? '-' }}</span></td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}</td>
                                    <td>
                                        {{ $item->tanggal_kembali ? \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M Y') : '-' }}
                                    </td>
                                    <td class="pe-4">
                                        @if($item->status == 'Dikembalikan')
                                            <span class="badge bg-success bg-opacity-75">{{ $item->status }}</span>
                                        @elseif($item->status == 'Disetujui')
                                            <span class="badge bg-primary bg-opacity-75">Dipinjam</span>
                                        @elseif($item->status == 'Menunggu')
                                            <span class="badge bg-warning text-dark bg-opacity-75">{{ $item->status }}</span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-75">{{ $item->status }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-clipboard-x fs-2 d-block mb-3"></i>
                                        Tidak ada data yang cocok dengan filter yang Anda pasang.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
