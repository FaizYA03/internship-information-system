@extends('lab.layouts.unified', ['title' => 'Kelola Peminjaman'])

@section('content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1 fs-3">Kelola Peminjaman</h2>
        <p class="text-muted mb-0">Verifikasi dan monitor peminjaman alat serta ruangan laboratorium.</p>
    </div>
    <div class="dropdown">
        <button class="btn btn-primary dropdown-toggle d-flex align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-plus-circle me-2"></i> Catat Peminjaman
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
            <li><h6 class="dropdown-header">Internal (Siswa/Guru)</h6></li>
            <li><a class="dropdown-item d-flex align-items-center" href="{{ route('lab.admin_new.manual_input.alat_siswa') }}"><i class="bi bi-person me-2"></i> Alat - Siswa</a></li>
            <li><a class="dropdown-item d-flex align-items-center" href="{{ route('lab.admin_new.manual_input.alat_guru') }}"><i class="bi bi-briefcase me-2"></i> Alat - Guru</a></li>
            <li><a class="dropdown-item d-flex align-items-center" href="{{ route('lab.admin_new.manual_input.ruangan_guru') }}"><i class="bi bi-door-open me-2"></i> Ruangan - Guru</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><h6 class="dropdown-header">Eksternal (Orang Luar)</h6></li>
            <li><a class="dropdown-item d-flex align-items-center" href="{{ route('lab.admin_new.manual_input.alat_eksternal') }}"><i class="bi bi-tools me-2"></i> Alat - Eksternal</a></li>
            <li><a class="dropdown-item d-flex align-items-center" href="{{ route('lab.admin_new.manual_input.ruangan_eksternal') }}"><i class="bi bi-building me-2"></i> Ruangan - Eksternal</a></li>
        </ul>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-bottom-0 pb-0 pt-3 px-3">
        <ul class="nav nav-tabs border-bottom-0" id="peminjamanTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active px-4 py-3 fw-medium d-flex align-items-center" id="alat-tab" data-bs-toggle="tab" data-bs-target="#alat-pane" type="button" role="tab" aria-controls="alat-pane" aria-selected="true" style="color: #495057;">
                    <i class="bi bi-tools me-2"></i> Peminjaman Alat
                    <span class="badge bg-secondary ms-2 rounded-pill">{{ $peminjaman->count() }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link px-4 py-3 fw-medium d-flex align-items-center" id="ruangan-tab" data-bs-toggle="tab" data-bs-target="#ruangan-pane" type="button" role="tab" aria-controls="ruangan-pane" aria-selected="false" style="color: #495057;">
                    <i class="bi bi-building me-2"></i> Peminjaman Ruangan
                    <span class="badge bg-secondary ms-2 rounded-pill">{{ $peminjamanRuangan->count() }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link px-4 py-3 fw-medium d-flex align-items-center" id="eksternal-tab" data-bs-toggle="tab" data-bs-target="#eksternal-pane" type="button" role="tab" aria-controls="eksternal-pane" aria-selected="false" style="color: #495057;">
                    <i class="bi bi-box-arrow-up-right me-2"></i> Alat Eksternal
                    <span class="badge bg-secondary ms-2 rounded-pill">{{ $peminjamanEksternal->count() }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link px-4 py-3 fw-medium d-flex align-items-center" id="ruangan-eksternal-tab" data-bs-toggle="tab" data-bs-target="#ruangan-eksternal-pane" type="button" role="tab" aria-controls="ruangan-eksternal-pane" aria-selected="false" style="color: #495057;">
                    <i class="bi bi-building-add me-2"></i> Ruangan Eksternal
                    <span class="badge bg-secondary ms-2 rounded-pill">{{ $peminjamanRuanganEksternal->count() }}</span>
                </button>
            </li>
        </ul>
    </div>
    
    <div class="card-body p-0">
        <div class="tab-content" id="peminjamanTabsContent">
            
            <!-- ALAT TAB -->
            <div class="tab-pane fade show active" id="alat-pane" role="tabpanel" aria-labelledby="alat-tab" tabindex="0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Peminjam</th>
                                <th>Alat</th>
                                <th>Jumlah</th>
                                <th>Peminjaman</th>
                                <th>Pengembalian</th>
                                <th>Status</th>
                                <th class="text-center pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($peminjaman as $item)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-medium text-dark">{{ $item->user->nama ?? 'N/A' }}</div>
                                    @php
                                        $role    = $item->user->role ?? '';
                                        $jurusan = null;
                                        if ($role === 'siswa') $jurusan = $item->user->siswa->jurusan ?? null;
                                        elseif ($role === 'guru') $jurusan = $item->user->guru->jurusan?->nama_jurusan ?? null;
                                    @endphp
                                    <span class="badge bg-secondary mb-1" style="font-size: 0.65rem;">{{ ucfirst($role) }}</span>
                                    @if($jurusan)
                                        <div class="text-muted" style="font-size: 0.75rem;"><i class="bi bi-mortarboard me-1"></i>{{ $jurusan }}</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-medium text-dark">{{ $item->inventaris->nama_inventaris ?? 'N/A' }}</div>
                                    <div class="text-muted" style="font-size: 0.8rem;">{{ $item->inventaris->labor->nama_labor ?? '' }}</div>
                                </td>
                                <td>
                                    <span class="fw-medium">{{ $item->jumlah }}</span> <span class="text-muted" style="font-size: 0.8rem;">Unit</span>
                                </td>
                                <td>
                                    <div class="text-dark" style="font-size: 0.875rem;"><i class="bi bi-calendar me-1"></i> {{ $item->tanggal_pinjam }}</div>
                                    <div class="text-muted font-monospace mt-1" style="font-size: 0.75rem;"><i class="bi bi-clock me-1"></i> {{ $item->jam_pinjam }}</div>
                                </td>
                                <td>
                                    @if($item->status == 'returned')
                                        <div class="text-dark" style="font-size: 0.875rem;"><i class="bi bi-calendar-check me-1"></i> {{ $item->tanggal_kembali }}</div>
                                        <div class="text-muted font-monospace mt-1" style="font-size: 0.75rem;"><i class="bi bi-clock me-1"></i> {{ $item->jam_kembali ?? '-' }}</div>
                                    @else
                                        <div class="fst-italic text-muted" style="font-size: 0.8rem;">Belum dikembalikan</div>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $bgClass = 'bg-secondary';
                                        if($item->status == 'pending') $bgClass = 'bg-warning text-dark';
                                        if($item->status == 'approved') $bgClass = 'bg-info text-dark';
                                        if($item->status == 'returned') $bgClass = 'bg-success';
                                        if($item->status == 'rejected') $bgClass = 'bg-danger';
                                    @endphp
                                    <span class="badge {{ $bgClass }} px-2 py-1">
                                        {{ $item->status == 'pending' ? 'MENUNGGU VERIFIKASI' : ($item->status == 'approved' ? 'SEDANG DIPINJAM' : ($item->status == 'returned' ? 'SUDAH KEMBALI' : 'DITOLAK')) }}
                                    </span>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        @if($item->status == 'pending')
                                            <form action="{{ route('lab.admin_new.peminjaman.internal.approve', $item->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-primary" title="Setujui"><i class="bi bi-check-lg"></i></button>
                                            </form>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $item->id }}" title="Tolak">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        @elseif($item->status == 'approved')
                                            <button type="button" class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#returnModal{{ $item->id }}" title="Terima Kembali">
                                                <i class="bi bi-arrow-return-left"></i>
                                            </button>
                                        @endif
                                        
                                        <a href="{{ route('lab.admin_new.peminjaman.alat.edit', $item->id) }}" class="btn btn-sm btn-warning text-dark" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        
                                        <form action="{{ route('lab.admin_new.peminjaman.alat.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">Tidak ada peminjaman alat internal.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- RUANGAN TAB -->
            <div class="tab-pane fade" id="ruangan-pane" role="tabpanel" aria-labelledby="ruangan-tab" tabindex="0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Peminjam</th>
                                <th>Ruangan</th>
                                <th>Keperluan</th>
                                <th>Waktu</th>
                                <th>Status</th>
                                <th class="text-center pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($peminjamanRuangan as $item)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-medium text-dark">{{ $item->user->nama ?? $item->nama ?? 'N/A' }}</div>
                                    @php
                                        $roleR    = $item->user->role ?? '';
                                        $jurusanR = null;
                                        if ($roleR === 'siswa') $jurusanR = $item->user->siswa->jurusan ?? null;
                                        elseif ($roleR === 'guru') $jurusanR = $item->user->guru->jurusan?->nama_jurusan ?? null;
                                    @endphp
                                    <span class="badge bg-secondary mb-1" style="font-size: 0.65rem;">{{ ucfirst($roleR ?: 'Eksternal') }}</span>
                                    @if($jurusanR)
                                        <div class="text-muted" style="font-size: 0.75rem;"><i class="bi bi-mortarboard me-1"></i>{{ $jurusanR }}</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-medium text-dark">{{ $item->labor->nama_labor ?? 'N/A' }}</div>
                                    <div class="text-muted" style="font-size: 0.8rem;">Kapasitas: {{ $item->labor->kapasitas ?? 30 }} Siswa</div>
                                </td>
                                <td><span style="font-size: 0.875rem;">{{ $item->keperluan ?? '-' }}</span></td>
                                <td>
                                    <div class="text-dark" style="font-size: 0.875rem;"><i class="bi bi-calendar me-1"></i> {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</div>
                                    <div class="text-muted font-monospace mt-1" style="font-size: 0.75rem;"><i class="bi bi-clock me-1"></i> {{ $item->waktu ?? 'N/A' }}</div>
                                </td>
                                <td>
                                    @php
                                        $bgClass = 'bg-secondary';
                                        if($item->status == 'pending') $bgClass = 'bg-warning text-dark';
                                        if($item->status == 'approved') $bgClass = 'bg-success';
                                        if($item->status == 'completed') $bgClass = 'bg-secondary';
                                        if($item->status == 'rejected') $bgClass = 'bg-danger';
                                    @endphp
                                    <span class="badge {{ $bgClass }} px-2 py-1">{{ strtoupper($item->status) }}</span>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        @if($item->status == 'pending')
                                            <form action="{{ route('lab.admin_new.peminjaman.ruangan.approve', $item->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-primary" title="Setujui"><i class="bi bi-check-lg"></i></button>
                                            </form>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectRuangan{{ $item->id }}" title="Tolak">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        @endif
                                        
                                        <a href="{{ route('lab.admin_new.peminjaman.ruangan.edit', $item->id) }}" class="btn btn-sm btn-warning text-dark" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        
                                        <form action="{{ route('lab.admin_new.peminjaman.ruangan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">Tidak ada peminjaman ruangan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- EKSTERNAL ALAT TAB -->
            <div class="tab-pane fade" id="eksternal-pane" role="tabpanel" aria-labelledby="eksternal-tab" tabindex="0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Peminjam</th>
                                <th>Barang</th>
                                <th>Tanggal Pinjam</th>
                                <th>Status</th>
                                <th class="text-center pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($peminjamanEksternal as $p)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-medium text-dark">{{ $p->nama_peminjam }}</div>
                                    <div class="text-muted" style="font-size: 0.8rem;">{{ $p->instansi }}</div>
                                </td>
                                <td>
                                    <div class="fw-medium text-dark">{{ $p->inventaris->nama_inventaris ?? 'N/A' }}</div>
                                    <span class="fw-medium" style="font-size: 0.875rem;">{{ $p->jumlah }}</span> <span class="text-muted" style="font-size: 0.8rem;">Unit</span>
                                </td>
                                <td>
                                    <div class="text-dark" style="font-size: 0.875rem;"><i class="bi bi-calendar me-1"></i> {{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d M Y') }}</div>
                                </td>
                                <td>
                                    @php
                                        $extStatus = 'bg-secondary';
                                        if($p->status == 'pending') $extStatus = 'bg-warning text-dark';
                                        if($p->status == 'recommended') $extStatus = 'bg-info text-dark';
                                        if($p->status == 'approved' || $p->status == 'aktif') $extStatus = 'bg-success';
                                        if($p->status == 'selesai') $extStatus = 'bg-secondary';
                                        if($p->status == 'rejected') $extStatus = 'bg-danger';
                                    @endphp
                                    <span class="badge {{ $extStatus }} px-2 py-1">{{ strtoupper($p->status == 'aktif' ? 'SEDANG DIPINJAM' : $p->status) }}</span>
                                </td>
                                <td class="text-center pe-4">
                                    <a href="{{ route('lab.admin_new.eksternal.index') }}" class="btn btn-sm btn-outline-secondary rounded-circle shadow-sm" style="width:32px; height:32px; display:inline-flex; align-items:center; justify-content:center;">
                                        <i class="bi bi-arrow-right"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">Tidak ada peminjaman alat eksternal.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- EKSTERNAL RUANGAN TAB -->
            <div class="tab-pane fade" id="ruangan-eksternal-pane" role="tabpanel" aria-labelledby="ruangan-eksternal-tab" tabindex="0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Peminjam</th>
                                <th>Ruangan</th>
                                <th>Keperluan</th>
                                <th>Waktu</th>
                                <th>Status</th>
                                <th class="text-center pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($peminjamanRuanganEksternal as $item)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-medium text-dark">{{ $item->nama ?? 'N/A' }}</div>
                                    <span class="badge bg-secondary mt-1" style="font-size: 0.65rem;">Eksternal</span>
                                </td>
                                <td>
                                    <div class="fw-medium text-dark">{{ $item->labor->nama_labor ?? 'N/A' }}</div>
                                    <div class="text-muted" style="font-size: 0.8rem;">Kapasitas: {{ $item->labor->kapasitas ?? 30 }} Siswa</div>
                                </td>
                                <td><span style="font-size: 0.875rem;">{{ $item->keperluan ?? '-' }}</span></td>
                                <td>
                                    <div class="text-dark" style="font-size: 0.875rem;"><i class="bi bi-calendar me-1"></i> {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</div>
                                    <div class="text-muted font-monospace mt-1" style="font-size: 0.75rem;"><i class="bi bi-clock me-1"></i> {{ $item->waktu ?? 'N/A' }}</div>
                                </td>
                                <td>
                                    @php
                                        $bgClass = 'bg-secondary';
                                        if($item->status == 'pending') $bgClass = 'bg-warning text-dark';
                                        if($item->status == 'approved') $bgClass = 'bg-success';
                                        if($item->status == 'completed') $bgClass = 'bg-secondary';
                                        if($item->status == 'rejected') $bgClass = 'bg-danger';
                                    @endphp
                                    <span class="badge {{ $bgClass }} px-2 py-1">{{ strtoupper($item->status) }}</span>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        @if($item->status == 'pending')
                                            <form action="{{ route('lab.admin_new.peminjaman.ruangan.approve', $item->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-primary" title="Setujui"><i class="bi bi-check-lg"></i></button>
                                            </form>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectRuEx{{ $item->id }}" title="Tolak">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        @endif
                                        
                                        <a href="{{ route('lab.admin_new.peminjaman.ruangan.edit', $item->id) }}" class="btn btn-sm btn-warning text-dark" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        
                                        <form action="{{ route('lab.admin_new.peminjaman.ruangan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">Tidak ada peminjaman ruangan eksternal.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </div>
</div>

<!-- Render Modals Using Bootstrap 5 -->
@foreach($peminjaman as $item)
    @if($item->status == 'pending')
    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal{{ $item->id }}" tabindex="-1" aria-labelledby="rejectModalLabel{{ $item->id }}" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form action="{{ route('lab.admin_new.peminjaman.internal.reject', $item->id) }}" method="POST">
             @csrf
             <div class="modal-header border-bottom-0 pb-0">
               <h1 class="modal-title fs-5 fw-bold" id="rejectModalLabel{{ $item->id }}">Tolak Peminjaman</h1>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body">
               <p class="text-muted mb-3" style="font-size:0.9rem;">Pastikan untuk mengisi alasan penolakan agar peminjam dapat memahaminya.</p>
               <div class="mb-3">
                   <label class="form-label fw-medium w-100">Alasan Penolakan</label>
                   <textarea name="reason" class="form-control" rows="4" placeholder="Contoh: Stok tidak mencukupi atau alat sedang dalam perbaikan..." required></textarea>
               </div>
             </div>
             <div class="modal-footer border-top-0 pt-0">
               <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
               <button type="submit" class="btn btn-danger">Tolak Sekarang</button>
             </div>
          </form>
        </div>
      </div>
    </div>
    @elseif($item->status == 'approved')
    <!-- Return Modal -->
    <div class="modal fade" id="returnModal{{ $item->id }}" tabindex="-1" aria-labelledby="returnModalLabel{{ $item->id }}" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form action="{{ route('lab.admin_new.peminjaman.internal.return', $item->id) }}" method="POST">
             @csrf
             <div class="modal-header border-bottom-0 pb-0">
               <h1 class="modal-title fs-5 fw-bold" id="returnModalLabel{{ $item->id }}">Proses Pengembalian</h1>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body">
               <p class="text-muted mb-3" style="font-size:0.9rem;">Silakan isi kondisi akhir barang setelah dikembalikan.</p>
               <div class="mb-3">
                   <label class="form-label fw-medium w-100">Kondisi Akhir Alat</label>
                   <select name="kondisi_akhir" class="form-select" required>
                       <option value="Sangat Baik">Sangat Baik</option>
                       <option value="Baik" selected>Baik</option>
                       <option value="Rusak Ringan">Rusak Ringan</option>
                       <option value="Rusak Sedang">Rusak Sedang</option>
                       <option value="Rusak Berat">Rusak Berat</option>
                       <option value="Hilang">Hilang</option>
                   </select>
               </div>
               <div class="mb-3">
                   <label class="form-label fw-medium w-100">Catatan Tambahan (Opsional)</label>
                   <textarea name="catatan" class="form-control" rows="3" placeholder="Contoh: Barang sudah dibersihkan atau ada sedikit baret..."></textarea>
               </div>
             </div>
             <div class="modal-footer border-top-0 pt-0">
               <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
               <button type="submit" class="btn btn-primary">Selesaikan Pengembalian</button>
             </div>
          </form>
        </div>
      </div>
    </div>
    @endif
@endforeach

@foreach($peminjamanRuangan as $item)
    @if($item->status == 'pending')
    <!-- Reject Ruangan Modal -->
    <div class="modal fade" id="rejectRuangan{{ $item->id }}" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form action="{{ route('lab.admin_new.peminjaman.ruangan.reject', $item->id) }}" method="POST">
             @csrf
             <div class="modal-header border-bottom-0 pb-0">
               <h1 class="modal-title fs-5 fw-bold">Tolak Peminjaman Ruangan</h1>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body">
               <p class="text-muted mb-3" style="font-size:0.9rem;">Pastikan untuk mengisi alasan penolakan agar peminjam dapat memahaminya.</p>
               <div class="mb-3">
                   <label class="form-label fw-medium w-100">Alasan Penolakan</label>
                   <textarea name="reason" class="form-control" rows="4" placeholder="Contoh: Ruangan digunakan untuk rapat jurusan..." required></textarea>
               </div>
             </div>
             <div class="modal-footer border-top-0 pt-0">
               <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
               <button type="submit" class="btn btn-danger">Tolak Sekarang</button>
             </div>
          </form>
        </div>
      </div>
    </div>
    @endif
@endforeach

@foreach($peminjamanRuanganEksternal as $item)
    @if($item->status == 'pending')
    <div class="modal fade" id="rejectRuEx{{ $item->id }}" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form action="{{ route('lab.admin_new.peminjaman.ruangan.reject', $item->id) }}" method="POST">
             @csrf
             <div class="modal-header border-bottom-0 pb-0">
               <h1 class="modal-title fs-5 fw-bold">Tolak Peminjaman (Eksternal)</h1>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body">
               <p class="text-muted mb-3" style="font-size:0.9rem;">Masukan alasan penolakan ruangan.</p>
               <div class="mb-3">
                   <label class="form-label fw-medium w-100">Alasan Penolakan</label>
                   <textarea name="reason" class="form-control" rows="4" placeholder="Contoh: Ruangan sudah digunakan atau sedang perbaikan..." required></textarea>
               </div>
             </div>
             <div class="modal-footer border-top-0 pt-0">
               <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
               <button type="submit" class="btn btn-danger">Tolak Sekarang</button>
             </div>
          </form>
        </div>
      </div>
    </div>
    @endif
@endforeach

@endsection

@section('css')
<style>
    /* Styling nav-tabs to match unified aesthetic */
    .nav-tabs .nav-link {
        border: none;
        border-bottom: 2px solid transparent;
        color: #6c757d;
        transition: all 0.2s ease-in-out;
    }
    .nav-tabs .nav-link:hover {
        border-color: transparent;
        color: #0d6efd;
    }
    .nav-tabs .nav-link.active {
        color: #0d6efd !important;
        border-bottom: 2px solid #0d6efd;
        background-color: transparent;
    }
    .table > :not(caption) > * > * {
        padding: 1rem 0.5rem;
    }
    .card-header {
        border-radius: 0.5rem 0.5rem 0 0 !important;
    }
</style>
@endsection
