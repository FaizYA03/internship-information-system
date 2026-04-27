@extends('perpustakaan.layouts.main')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="page-title">Detail Pengadaan Buku</h1>
            <p class="text-muted">ID: #{{ str_pad($pengadaan->id, 4, '0', STR_PAD_LEFT) }} | Diajukan pada: {{ \Carbon\Carbon::parse($pengadaan->created_at)->format('d M Y') }}</p>
        </div>
        <div class="col-md-4 text-md-end">
            @if($pengadaan->status == 'Draft')
                <span class="badge bg-secondary fs-6 px-3 py-2">Draft</span>
            @elseif($pengadaan->status == 'Menunggu Persetujuan')
                <span class="badge bg-info fs-6 px-3 py-2">Menunggu Persetujuan</span>
            @elseif($pengadaan->status == 'Disetujui')
                <span class="badge bg-success fs-6 px-3 py-2">Disetujui</span>
            @elseif($pengadaan->status == 'Ditolak')
                <span class="badge bg-danger fs-6 px-3 py-2">Ditolak</span>
            @else
                <span class="badge bg-warning text-dark fs-6 px-3 py-2">Diterima</span>
            @endif
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <!-- Main details -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold text-primary">Daftar Buku</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3">Judul Buku</th>
                                    <th class="py-3">ISBN / Penerbit</th>
                                    <th class="py-3 text-center">Qty</th>
                                    <th class="py-3 text-end">Harga Satuan</th>
                                    <th class="py-3 text-end px-4">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pengadaan->details as $d)
                                <tr>
                                    <td class="px-4">
                                        <h6 class="mb-0">{{ $d->judul }}</h6>
                                        <small class="text-muted">{{ $d->penulis ?? 'Tanpa Penulis' }}</small>
                                        @if($d->buku_id)
                                            <span class="badge bg-light text-dark ms-1" title="Sudah Ada di Katalog"><i class="bi bi-book"></i> Katalog</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="d-block">{{ $d->isbn ?? '-' }}</small>
                                        <small class="text-muted">{{ $d->penerbit ?? '-' }}</small>
                                    </td>
                                    <td class="text-center fw-bold">{{ $d->jumlah }}</td>
                                    <td class="text-end text-muted">Rp {{ number_format($d->harga_per_unit, 0, ',', '.') }}</td>
                                    <td class="text-end px-4 fw-bold">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                                <tr class="bg-light">
                                    <td colspan="4" class="text-end px-4 fw-bold">TOTAL ESTIMASI:</td>
                                    <td class="text-end px-4 fw-bold text-primary fs-5">Rp {{ number_format($pengadaan->total_estimasi, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Jika sudah diterima, tampilkan buku yang dikatalogisasikan -->
            @if($pengadaan->status == 'Diterima' && $pengadaan->bookCopies->count() > 0)
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold text-success"><i class="bi bi-check-circle me-2"></i>Data Inventaris (Katalogisasi Otomatis)</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3">Judul Buku</th>
                                    <th class="py-3">No Inventaris</th>
                                    <th class="py-3">Barcode</th>
                                    <th class="py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pengadaan->bookCopies as $copy)
                                <tr>
                                    <td class="px-4">{{ $copy->buku->judul }}</td>
                                    <td class="fw-bold font-monospace">{{ $copy->inventaris_no }}</td>
                                    <td class="font-monospace text-muted">{{ $copy->barcode }}</td>
                                    <td><span class="badge bg-success">{{ $copy->kondisi }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar Actions -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Informasi Tambahan</h5>
                    <div class="mb-2">
                        <span class="text-muted d-block small">Judul Pengadaan</span>
                        <span class="fw-bold">{{ $pengadaan->judul }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted d-block small">Deskripsi</span>
                        <span>{{ $pengadaan->deskripsi ?? '-' }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted d-block small">Vendor / Pemasok</span>
                        <span class="fw-bold text-primary">{{ $pengadaan->vendor->nama ?? 'Belum Ditentukan' }}</span>
                    </div>
                    
                    @if($pengadaan->status == 'Diterima')
                    <hr>
                    <div class="mb-2">
                        <span class="text-muted d-block small">No. Faktur</span>
                        <span class="fw-bold">{{ $pengadaan->faktur_no ?? '-' }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted d-block small">Total Pembayaran Aktual</span>
                        <span class="fw-bold text-danger">Rp {{ number_format($pengadaan->total_aktual, 0, ',', '.') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons based on Role & Status -->
            <div class="d-grid gap-2">
                @if(auth()->user()->role == 'admin_perpus')
                    @if($pengadaan->status == 'Draft' || $pengadaan->status == 'Ditolak')
                        <a href="{{ route('perpustakaan.pengadaan.edit', $pengadaan->id) }}" class="btn btn-outline-primary">
                            <i class="bi bi-pencil"></i> Edit Draft
                        </a>
                        <!-- Button untuk ajukan persetujuan -->
                        <form action="{{ route('perpustakaan.pengadaan.update', $pengadaan->id) }}" method="POST" class="d-grid">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="ajukan_persetujuan" value="1">
                            <!-- Field dummy required if controller validasi was not adjusted properly. Tapi controller kita punya branch ajukan_persetujuan -> return early -->
                            <button type="submit" class="btn btn-primary" style="background-color: var(--primary); border:none;" onclick="return confirm('Kirim pengajuan ke Kepala Sekolah?')">
                                <i class="bi bi-send"></i> Ajukan Persetujuan
                            </button>
                        </form>
                    @endif

                    @if($pengadaan->status == 'Disetujui')
                        <button type="button" class="btn btn-success fw-bold" data-bs-toggle="modal" data-bs-target="#receiveModal">
                            <i class="bi bi-box-seam"></i> Terima Buku & Katalogisasi
                        </button>
                    @endif
                @endif
                
                @if(in_array(auth()->user()->role, ['kepala_sekolah', 'waka_akademik']) || auth()->user()->role == 'waka')
                    @if($pengadaan->status == 'Menunggu Persetujuan')
                        @if(!$budgetTahunIni || $budgetTahunIni->sisa_anggaran < $pengadaan->total_estimasi)
                            <div class="alert alert-danger px-3 py-2 mb-2 small">
                                <i class="bi bi-exclamation-octagon-fill"></i> Sisa Anggaran (<span class="fw-bold">Rp {{ number_format($budgetTahunIni->sisa_anggaran ?? 0, 0, ',', '.') }}</span>) TIDAK MENCUKUPI untuk pengadaan ini (Rp {{ number_format($pengadaan->total_estimasi, 0, ',', '.') }}). Sistem merekomendasikan Penolakan atau Revisi Plafon.
                            </div>
                        @else
                            <div class="alert alert-success px-3 py-2 mb-2 small">
                                <i class="bi bi-check-circle-fill"></i> Limit Anggaran Tersedia: <span class="fw-bold">Rp {{ number_format($budgetTahunIni->sisa_anggaran, 0, ',', '.') }}</span>. Memadai untuk pengajuan ini.
                            </div>
                        @endif

                        <button type="button" class="btn btn-success fw-bold d-grid mb-2 w-100" data-bs-toggle="modal" data-bs-target="#approveModal">
                            <i class="bi bi-shield-check"></i> Cek & Setujui Pengajuan
                        </button>
                        
                        <form action="{{ route('perpustakaan.pengadaan.reject', $pengadaan->id) }}" method="POST" class="d-grid w-100">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Anda yakin menolak draf ini?')"><i class="bi bi-x-octagon"></i> Tolak Pengadaan</button>
                        </form>
                    @endif
                @endif
                
                <a href="{{ route('perpustakaan.pengadaan.index') }}" class="btn btn-light mt-2 border">Kembali</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal Terima Buku -->
@if($pengadaan->status == 'Disetujui' && auth()->user()->role == 'admin_perpus')
<div class="modal fade" id="receiveModal" tabindex="-1" aria-labelledby="receiveModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('perpustakaan.pengadaan.receive', $pengadaan->id) }}" method="POST">
          @csrf
          <div class="modal-header bg-success text-white">
            <h5 class="modal-title" id="receiveModalLabel">Penerimaan & Katalogisasi Buku</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p>Sistem akan secara otomatis:<br>
            1. Menambahkan buku ke Katalog.<br>
            2. Melakukan generate <kbd>Nomor Inventaris</kbd> unik per eksemplar.<br>
            3. Melakukan generate <kbd>Barcode (CODE-128)</kbd> per eksemplar.<br>
            4. Menambah Stok Buku secara riil.</p>
            
            <hr>
            <div class="mb-3">
                <label class="form-label fw-bold">Nomor Faktur Pembelian</label>
                <input type="text" name="faktur_no" class="form-control" placeholder="Contoh: INV-VB-2026/05">
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-bold">Tanggal Faktur</label>
                <input type="date" name="faktur_tanggal" class="form-control" value="{{ date('Y-m-d') }}">
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-bold">Total Pembayaran Aktual</label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="number" name="total_aktual" class="form-control" value="{{ $pengadaan->total_estimasi }}" min="0">
                </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-success fw-bold" onclick="this.innerHTML='<span class=\'spinner-border spinner-border-sm\'></span> Sedang Memproses...'; this.disabled=true; this.form.submit();">Eksekusi Penerimaan</button>
          </div>
      </form>
    </div>
  </div>
</div>
@endif

<!-- Modal Smart Approval (Kepsek) -->
@if(in_array(auth()->user()->role, ['kepala_sekolah', 'waka_akademik']) || auth()->user()->role == 'waka')
@if($pengadaan->status == 'Menunggu Persetujuan')
<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('perpustakaan.pengadaan.approve', $pengadaan->id) }}" method="POST">
          @csrf
          <div class="modal-header bg-success text-white">
            <h5 class="modal-title" id="approveModalLabel"><i class="bi bi-shield-check"></i> Persetujuan Eksekutif</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body p-4">
            <div class="alert alert-light border border-secondary bg-light mb-4">
                <small class="d-block text-muted text-uppercase fw-bold mb-1">Status Keuangan Perpus</small>
                <div>Total Pagu: <b>Rp {{ number_format($budgetTahunIni->total_anggaran ?? 0, 0, ',', '.') }}</b></div>
                <div>Sisa Saat Ini: <b class="{{ ($budgetTahunIni->sisa_anggaran ?? 0) < $pengadaan->total_estimasi ? 'text-danger' : 'text-success' }}">Rp {{ number_format($budgetTahunIni->sisa_anggaran ?? 0, 0, ',', '.') }}</b></div>
                <hr class="my-2">
                <div>Draft Pengajuan Ini: <b class="text-primary">Rp {{ number_format($pengadaan->total_estimasi, 0, ',', '.') }}</b></div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Setujui Plafon Anggaran Akhir</label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="number" name="override_estimasi" class="form-control fw-bold" value="{{ $pengadaan->total_estimasi }}" max="{{ $budgetTahunIni->sisa_anggaran ?? 0 }}" required>
                </div>
                <small class="text-muted"><i class="bi bi-info-circle"></i> Otoritas Anda dapat memotong plafon nilai maksimal. Jika Anda menurunkan plafon, Admin Perpus harus mengurangi daftar belanja agar sesuai target uang Anda.</small>
            </div>
          </div>
          <div class="modal-footer border-top-0 pt-0 px-4 pb-4">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tinjau Ulang</button>
            <button type="submit" class="btn btn-success fw-bold flex-grow-1"><i class="bi bi-check2-all"></i> Tetapkan & Setujui</button>
          </div>
      </form>
    </div>
  </div>
</div>
@endif
@endif
@endsection
