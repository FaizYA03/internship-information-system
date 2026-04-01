@extends('lab.layouts.unified', ['title' => 'Kelola Peminjaman'])

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1 text-dark">Kelola Peminjaman</h4>
                <p class="text-muted small mb-0">Verifikasi dan monitor peminjaman alat serta ruangan laboratorium.</p>
            </div>
            <div class="dropdown">
                <button class="ui-btn ui-btn-primary dropdown-toggle rounded-pill px-4" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-plus-circle me-2"></i> Catat Peminjaman
                </button>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 p-2">
                    <li class="dropdown-header text-uppercase small fw-bold text-muted">Internal (Siswa/Guru)</li>
                    <li><a class="dropdown-item rounded-3 py-2" href="{{ route('lab.admin_new.manual_input.alat_siswa') }}"><i class="bi bi-person-badge me-2"></i> Alat - Siswa</a></li>
                    <li><a class="dropdown-item rounded-3 py-2" href="{{ route('lab.admin_new.manual_input.alat_guru') }}"><i class="bi bi-person-workspace me-2"></i> Alat - Guru</a></li>
                    <li><a class="dropdown-item rounded-3 py-2" href="{{ route('lab.admin_new.manual_input.ruangan_guru') }}"><i class="bi bi-door-open me-2"></i> Ruangan - Guru</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li class="dropdown-header text-uppercase small fw-bold text-muted">Eksternal (Orang Luar)</li>
                    <li><a class="dropdown-item rounded-3 py-2" href="{{ route('lab.admin_new.manual_input.alat_eksternal') }}"><i class="bi bi-tools me-2"></i> Alat - Eksternal</a></li>
                    <li><a class="dropdown-item rounded-3 py-2" href="{{ route('lab.admin_new.manual_input.ruangan_eksternal') }}"><i class="bi bi-building me-2"></i> Ruangan - Eksternal</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<x-ui.card>
    <!-- Custom Tabs -->
    <div class="nav-tabs-wrapper mb-4">
        <ul class="nav nav-tabs border-0 gap-2" id="peminjamanTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active border-0 rounded-4 px-4 py-3 fw-bold transition-all" id="alat-tab" data-bs-toggle="pill" data-bs-target="#alat" type="button" role="tab">
                    <i class="bi bi-tools me-2"></i> Peminjaman Alat
                    <span class="ms-2 badge rounded-pill bg-primary-soft text-primary">{{ $peminjaman->count() }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link border-0 rounded-4 px-4 py-3 fw-bold transition-all text-muted" id="ruangan-tab" data-bs-toggle="pill" data-bs-target="#ruangan" type="button" role="tab">
                    <i class="bi bi-building me-2"></i> Peminjaman Ruangan
                    <span class="ms-2 badge rounded-pill bg-secondary-soft text-secondary">{{ $peminjamanRuangan->count() }}</span>
                </button>
            </li>
        </ul>
    </div>

    <!-- Tabs Content -->
    <div class="tab-content mt-4" id="peminjamanTabsContent">
        <!-- Peminjaman Alat Tab -->
        <div class="tab-pane fade show active" id="alat" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 text-muted small fw-bold px-3 py-3">PEMINJAM</th>
                            <th class="border-0 text-muted small fw-bold px-3 py-3">ALAT</th>
                            <th class="border-0 text-muted small fw-bold px-3 py-3">JUMLAH</th>
                            <th class="border-0 text-muted small fw-bold px-3 py-3">PEMINJAMAN</th>
                            <th class="border-0 text-muted small fw-bold px-3 py-3">PENGEMBALIAN</th>
                            <th class="border-0 text-muted small fw-bold px-3 py-3">STATUS</th>
                            <th class="border-0 text-muted small fw-bold px-3 py-3 text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjaman as $item)
                            <tr>
                                <td class="px-3">
                                    <div class="fw-bold text-dark">{{ $item->user->nama ?? 'N/A' }}</div>
                                    @php
                                        $role    = $item->user->role ?? '';
                                        $jurusan = null;
                                        $kelas   = null;
                                        if ($role === 'siswa') {
                                            $jurusan = $item->user->siswa->jurusan ?? null;
                                            $kelas   = $item->user->siswa->kelas ?? null;
                                        } elseif ($role === 'guru') {
                                            $jurusan = $item->user->guru->jurusan ?? null;
                                        }
                                    @endphp
                                    <x-ui.badge variant="neutral" style="font-size: 0.65rem;">{{ ucfirst($role) }}</x-ui.badge>
                                    @if($jurusan)
                                        <div class="text-muted" style="font-size: 0.7rem; margin-top:2px;"><i class="bi bi-mortarboard me-1"></i>{{ $jurusan }}</div>
                                    @endif
                                    @if(!empty($kelas))
                                        <div class="text-muted" style="font-size: 0.7rem;"><i class="bi bi-door-closed me-1"></i>{{ $kelas }}</div>
                                    @endif
                                </td>
                                <td class="px-3">
                                    <div class="fw-semibold text-dark">{{ $item->inventaris->nama_inventaris ?? 'N/A' }}</div>
                                    <small class="text-muted small">{{ $item->inventaris->labor->nama_labor ?? '' }}</small>
                                </td>
                                <td class="px-3">
                                    <span class="fw-bold text-dark">{{ $item->jumlah }}</span> <small class="text-muted">Unit</small>
                                </td>
                                <td class="px-3">
                                    <div class="text-dark small"><i class="bi bi-calendar-event me-1"></i> {{ $item->tanggal_pinjam }}</div>
                                    <div class="text-muted small font-monospace"><i class="bi bi-clock me-1"></i> {{ $item->jam_pinjam }}</div>
                                </td>
                                <td class="px-3">
                                    @if($item->status == 'returned')
                                        <div class="text-dark small"><i class="bi bi-calendar-check me-1"></i> {{ $item->tanggal_kembali }}</div>
                                        <div class="text-muted small font-monospace"><i class="bi bi-clock me-1"></i> {{ $item->jam_kembali ?? '-' }}</div>
                                    @else
                                        <div class="text-muted small italic">Belum dikembalikan</div>
                                    @endif
                                </td>
                                <td class="px-3">
                                    @php
                                        $statusVariant = 'neutral';
                                        if($item->status == 'pending') $statusVariant = 'warning';
                                        if($item->status == 'approved') $statusVariant = 'info';
                                        if($item->status == 'returned') $statusVariant = 'success';
                                        if($item->status == 'rejected') $statusVariant = 'danger';
                                    @endphp
                                    <x-ui.badge variant="{{ $statusVariant }}">
                                        {{ $item->status == 'pending' ? 'MENUNGGU VERIFIKASI' : ($item->status == 'approved' ? 'SEDANG DIPINJAM' : ($item->status == 'returned' ? 'SUDAH KEMBALI' : 'DITOLAK')) }}
                                    </x-ui.badge>
                                </td>
                                <td class="px-3 text-center">
                                    <div class="d-flex gap-1 justify-content-center">
                                        @if($item->status == 'pending')
                                            <form action="{{ route('lab.admin_new.peminjaman.internal.approve', $item->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="ui-btn ui-btn-primary btn-sm px-2" title="Setujui">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                            <button type="button" class="ui-btn ui-btn-danger btn-sm px-2" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $item->id }}" title="Tolak">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        @elseif($item->status == 'approved')
                                            <button type="button" class="ui-btn ui-btn-info btn-sm px-2 text-white" data-bs-toggle="modal" data-bs-target="#returnModal{{ $item->id }}" title="Terima Kembali">
                                                <i class="bi bi-arrow-return-left"></i>
                                            </button>
                                        @endif
                                        
                                        <a href="{{ route('lab.admin_new.peminjaman.alat.edit', $item->id) }}" class="ui-btn ui-btn-warning btn-sm px-2 text-white" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        
                                        <form action="{{ route('lab.admin_new.peminjaman.alat.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="ui-btn ui-btn-danger btn-sm px-2" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <x-ui.empty-state icon="bi-tools" title="Tidak ada peminjaman" description="Data peminjaman alat akan muncul di sini." />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Modals for Alat -->
            @foreach($peminjaman as $item)
                @if($item->status == 'pending')
                    <!-- Reject Modal -->
                    <div class="modal fade" id="rejectModal{{ $item->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <form action="{{ route('lab.admin_new.peminjaman.internal.reject', $item->id) }}" method="POST" class="w-100">
                                @csrf
                                <div class="modal-content border-0 rounded-4 shadow">
                                    <div class="modal-header border-0 pb-0">
                                        <h5 class="fw-bold text-dark">Tolak Peminjaman</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body py-4">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-muted small">ALASAN PENOLAKAN</label>
                                            <textarea name="reason" class="form-control border-2 rounded-4" rows="4" placeholder="Contoh: Stok tidak mencukupi atau alat sedang dalam perbaikan..." required></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 pt-0">
                                        <button type="button" class="ui-btn ui-btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="ui-btn ui-btn-danger px-4">Tolak Sekarang</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @elseif($item->status == 'approved')
                    <!-- Return Modal -->
                    <div class="modal fade" id="returnModal{{ $item->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <form action="{{ route('lab.admin_new.peminjaman.internal.return', $item->id) }}" method="POST" class="w-100">
                                @csrf
                                <div class="modal-content border-0 rounded-4 shadow">
                                    <div class="modal-header border-0 pb-0">
                                        <h5 class="fw-bold text-dark">Proses Pengembalian</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body py-4">
                                        <div class="mb-4">
                                            <label class="form-label fw-bold text-muted small mb-2">KONDISI AKHIR ALAT</label>
                                            <select name="kondisi_akhir" class="form-select border-2 rounded-4 p-3" required>
                                                <option value="Sangat Baik">Sangat Baik</option>
                                                <option value="Baik" selected>Baik</option>
                                                <option value="Rusak Ringan">Rusak Ringan</option>
                                                <option value="Rusak Sedang">Rusak Sedang</option>
                                                <option value="Rusak Berat">Rusak Berat</option>
                                                <option value="Hilang">Hilang</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="form-label fw-bold text-muted small mb-2">CATATAN TAMBAHAN (OPSIONAL)</label>
                                            <textarea name="catatan" class="form-control border-2 rounded-4" rows="3" placeholder="Contoh: Barang sudah dibersihkan atau ada sedikit baret..."></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 pt-0">
                                        <button type="button" class="ui-btn ui-btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="ui-btn ui-btn-primary px-4">Selesaikan Pengembalian</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Peminjaman Ruangan Tab -->
        <div class="tab-pane fade" id="ruangan" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 text-muted small fw-bold px-3 py-3">PEMINJAM</th>
                            <th class="border-0 text-muted small fw-bold px-3 py-3">RUANGAN</th>
                            <th class="border-0 text-muted small fw-bold px-3 py-3">KEPERLUAN</th>
                            <th class="border-0 text-muted small fw-bold px-3 py-3">WAKTU</th>
                            <th class="border-0 text-muted small fw-bold px-3 py-3">STATUS</th>
                            <th class="border-0 text-muted small fw-bold px-3 py-3 text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamanRuangan as $item)
                            <tr>
                                <td class="px-3">
                                    <div class="fw-bold text-dark">{{ $item->user->nama ?? $item->nama ?? 'N/A' }}</div>
                                    @php
                                        $roleR    = $item->user->role ?? '';
                                        $jurusanR = null;
                                        $kelasR   = null;
                                        if ($roleR === 'siswa') {
                                            $jurusanR = $item->user->siswa->jurusan ?? null;
                                            $kelasR   = $item->user->siswa->kelas ?? null;
                                        } elseif ($roleR === 'guru') {
                                            $jurusanR = $item->user->guru->jurusan ?? null;
                                        }
                                    @endphp
                                    <x-ui.badge variant="neutral" style="font-size: 0.65rem;">{{ ucfirst($roleR ?: 'Eksternal') }}</x-ui.badge>
                                    @if(!empty($jurusanR))
                                        <div class="text-muted" style="font-size: 0.7rem; margin-top:2px;"><i class="bi bi-mortarboard me-1"></i>{{ $jurusanR }}</div>
                                    @endif
                                </td>
                                <td class="px-3">
                                    <div class="fw-semibold text-dark">{{ $item->labor->nama_labor ?? 'N/A' }}</div>
                                    <small class="text-muted small">Cap: {{ $item->labor->kapasitas ?? 30 }} Siswa</small>
                                </td>
                                <td class="px-3">
                                    <span class="text-dark small">{{ $item->keperluan ?? '-' }}</span>
                                </td>
                                <td class="px-3">
                                    <div class="text-dark small">{{ $item->tanggal ?? 'N/A' }}</div>
                                    <div class="text-muted small font-monospace">{{ $item->waktu ?? 'N/A' }}</div>
                                </td>
                                <td class="px-3">
                                    @php
                                        $statusVariant = 'neutral';
                                        if($item->status == 'pending') $statusVariant = 'warning';
                                        if($item->status == 'approved') $statusVariant = 'success';
                                        if($item->status == 'completed') $statusVariant = 'neutral';
                                        if($item->status == 'rejected') $statusVariant = 'danger';
                                    @endphp
                                    <x-ui.badge variant="{{ $statusVariant }}">
                                        {{ strtoupper($item->status) }}
                                    </x-ui.badge>
                                </td>
                                <td class="px-3 text-center">
                                    <div class="d-flex gap-1 justify-content-center">
                                        @if($item->status == 'pending')
                                            <form action="{{ route('lab.admin_new.peminjaman.ruangan.approve', $item->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="ui-btn ui-btn-primary btn-sm px-2" title="Setujui">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                            <button type="button" class="ui-btn ui-btn-danger btn-sm px-2" data-bs-toggle="modal" data-bs-target="#rejectRuanganModal{{ $item->id }}" title="Tolak">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        @endif
                                        
                                        <a href="{{ route('lab.admin_new.peminjaman.ruangan.edit', $item->id) }}" class="ui-btn ui-btn-warning btn-sm px-2 text-white" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        
                                        <form action="{{ route('lab.admin_new.peminjaman.ruangan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="ui-btn ui-btn-danger btn-sm px-2" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <x-ui.empty-state icon="bi-building" title="Tidak ada peminjaman" description="Data peminjaman ruangan akan muncul di sini." />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Modals for Ruangan -->
            @foreach($peminjamanRuangan as $item)
                @if($item->status == 'pending')
                    <!-- Reject Modal for Ruangan -->
                    <div class="modal fade" id="rejectRuanganModal{{ $item->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <form action="{{ route('lab.admin_new.peminjaman.ruangan.reject', $item->id) }}" method="POST" class="w-100">
                                @csrf
                                <div class="modal-content border-0 rounded-4 shadow">
                                    <div class="modal-header border-0 pb-0">
                                        <h5 class="fw-bold text-dark">Tolak Peminjaman Ruangan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body py-4">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-muted small">ALASAN PENOLAKAN</label>
                                            <textarea name="reason" class="form-control border-2 rounded-4" rows="4" placeholder="Contoh: Ruangan sudah digunakan atau sedang dalam pemeliharaan rutin..." required></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 pt-0">
                                        <button type="button" class="ui-btn ui-btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="ui-btn ui-btn-danger px-4">Tolak Sekarang</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</x-ui.card>
@endsection

@section('css')
<style>
    .nav-tabs .nav-link.active {
        background-color: #EFF6FF !important;
        color: #2563EB !important;
    }
    .nav-tabs .nav-link:hover:not(.active) {
        background-color: #F8FAFC;
        color: #64748B;
    }
    .bg-primary-soft { background-color: #EFF6FF; color: #2563EB; }
    .bg-secondary-soft { background-color: #F1F5F9; color: #64748B; }
    .font-monospace { font-family: 'JetBrains Mono', 'Courier New', monospace !important; }
    .transition-all { transition: all 0.2s ease; }
</style>
@endsection

