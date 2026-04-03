@extends('lab.layouts.unified', ['title' => 'Laporan Kerusakan'])

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1 text-dark">Laporan Kerusakan</h4>
                <p class="text-muted small mb-0">Monitor dan tindak lanjuti laporan kerusakan aset laboratorium.</p>
            </div>
            <a href="{{ route('lab.admin_new.kerusakan.create') }}" class="ui-btn ui-btn-danger">
                <i class="bi bi-plus-lg"></i> Tambah Laporan
            </a>
        </div>
    </div>
</div>

<div class="row g-4">
    @forelse($laporanKerusakan as $laporan)
        <div class="col-md-6">
            <x-ui.card class="h-100 border-0 shadow-sm border-start border-4 {{ str_contains(strtolower($laporan->inventaris?->kondisi ?? ''), 'berat') ? 'border-danger' : (str_contains(strtolower($laporan->inventaris?->kondisi ?? ''), 'sedang') ? 'border-warning' : 'border-info') }}">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="fw-bold text-dark mb-1">{{ $laporan->inventaris?->nama_inventaris ?? $laporan->nama_alat }}</h5>
                        <p class="text-muted small mb-0"><i class="bi bi-building me-1"></i> {{ $laporan->inventaris?->labor?->nama_labor ?? 'N/A' }}</p>
                    </div>
                    @php
                        $kondisiVariant = 'neutral';
                        $kondisiRaw = strtolower($laporan->inventaris?->kondisi ?? 'rusak');
                        if(str_contains($kondisiRaw, 'ringan')) $kondisiVariant = 'warning';
                        if(str_contains($kondisiRaw, 'sedang')) $kondisiVariant = 'warning';
                        if(str_contains($kondisiRaw, 'berat')) $kondisiVariant = 'danger';
                    @endphp
                    <x-ui.badge variant="{{ $kondisiVariant }}">{{ strtoupper($laporan->inventaris?->kondisi ?? 'RUSAK') }}</x-ui.badge>
                </div>
                
                <div class="bg-light rounded-4 p-3 mb-4">
                    <p class="text-dark small mb-2"><strong>Deskripsi Kerusakan:</strong></p>
                    <p class="text-muted small mb-0">{{ $laporan->deskripsi_kerusakan }}</p>
                </div>
                
                @if($laporan->tindakan_perbaikan)
                    <div class="mb-4">
                        <p class="text-dark small mb-1"><strong>Tindakan Perbaikan:</strong></p>
                        <p class="text-muted small mb-0 italic">"{{ $laporan->tindakan_perbaikan }}"</p>
                    </div>
                @endif
                
                <div class="d-flex flex-wrap gap-3 mb-4 text-muted small">
                    <span><i class="bi bi-person me-1"></i> {{ $laporan->reporter_info }}</span>
                    <span><i class="bi bi-calendar-event me-1"></i> {{ $laporan->tanggal_laporan ? \Carbon\Carbon::parse($laporan->tanggal_laporan)->format('d M Y') : 'N/A' }}</span>
                </div>

                <div class="pt-3 border-top d-flex justify-content-between align-items-center">
                    @php
                        $statusVariant = 'neutral';
                        if($laporan->status_perbaikan == 'selesai') $statusVariant = 'success';
                        if($laporan->status_perbaikan == 'dalam_proses') $statusVariant = 'warning';
                        if($laporan->status_perbaikan == 'menunggu') $statusVariant = 'neutral';
                    @endphp
                    <x-ui.badge variant="{{ $statusVariant }}">
                        <i class="bi bi-gear-fill me-1"></i> {{ strtoupper(str_replace('_', ' ', $laporan->status_perbaikan ?? 'MENUNGGU')) }}
                    </x-ui.badge>
                    
                    @if($laporan->status_perbaikan != 'selesai')
                        <div class="btn-group">
                            <button type="button" class="ui-btn ui-btn-primary btn-sm px-3" data-bs-toggle="modal" data-bs-target="#updateModal{{ $laporan->id }}">
                                <i class="bi bi-pencil-square"></i> Update
                            </button>
                            @if(!$laporan->is_eskalasi)
                                <button type="button" class="ui-btn ui-btn-outline-danger btn-sm px-3" data-bs-toggle="modal" data-bs-target="#escalateModal{{ $laporan->id }}">
                                    <i class="bi bi-arrow-up-circle"></i> Eskalasi
                                </button>
                            @endif
                        </div>
                    @endif
                </div>
                
                @if($laporan->is_eskalasi)
                    <div class="mt-3 p-3 rounded-4 bg-light-danger border border-danger border-opacity-10">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <small class="fw-bold text-danger">ESKALASI: {{ strtoupper(str_replace('_', ' ', $laporan->eskalasi_ke)) }}</small>
                            @php
                                $displayEskalasi = $laporan->eskalasi_status;
                                if ($displayEskalasi == 'disetujui') {
                                    $displayEskalasi = $laporan->status_perbaikan == 'selesai' ? 'selesai' : 'ditangani';
                                }
                            @endphp
                            <x-ui.badge variant="{{ $laporan->eskalasi_status == 'disetujui' ? 'success' : ($laporan->eskalasi_status == 'ditolak' ? 'danger' : 'warning') }}">
                                {{ strtoupper($displayEskalasi) }}
                            </x-ui.badge>
                        </div>
                        <p class="text-muted small mb-0"><i class="bi bi-chat-left-text me-1"></i> {{ $laporan->eskalasi_catatan }}</p>
                    </div>
                @endif
            </x-ui.card>
        </div>

        <!-- Update Modal -->
        <div class="modal fade" id="updateModal{{ $laporan->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <form action="{{ route('lab.admin_new.kerusakan.update', $laporan->id) }}" method="POST" class="w-100">
                    @csrf
                    @method('PATCH')
                    <div class="modal-content border-0 rounded-4 shadow">
                        <div class="modal-header border-0 pb-0">
                            <h5 class="fw-bold text-dark">Update Kondisi Alat</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body py-4">
                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted small mb-2">KONDISI BARU</label>
                                <select name="kondisi_baru" class="form-select border-2 rounded-4 p-3" required>
                                    @php $currentKondisi = $laporan->inventaris?->kondisi ?? ''; @endphp
                                    <option value="Sangat Baik" {{ $currentKondisi == 'Sangat Baik' ? 'selected' : '' }}>Sangat Baik</option>
                                    <option value="Baik" {{ $currentKondisi == 'Baik' ? 'selected' : '' }}>Baik</option>
                                    <option value="Rusak Ringan" {{ $currentKondisi == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                    <option value="Rusak Sedang" {{ $currentKondisi == 'Rusak Sedang' ? 'selected' : '' }}>Rusak Sedang</option>
                                    <option value="Rusak Berat" {{ $currentKondisi == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted small mb-2">STATUS PERBAIKAN</label>
                                <select name="status_perbaikan" class="form-select border-2 rounded-4 p-3" required>
                                    <option value="menunggu" {{ $laporan->status_perbaikan == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="dalam_proses" {{ $laporan->status_perbaikan == 'dalam_proses' ? 'selected' : '' }}>Dalam Proses</option>
                                    <option value="selesai" {{ $laporan->status_perbaikan == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label fw-bold text-muted small mb-2">TINDAKAN PERBAIKAN</label>
                                <textarea name="tindakan_perbaikan" class="form-control border-2 rounded-4" rows="3" placeholder="Contoh: Penggantian komponen motherboard, solder ulang kabel...">{{ $laporan->tindakan_perbaikan }}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="ui-btn ui-btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="ui-btn ui-btn-primary px-4">Simpan Perubahan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Escalate Modal -->
        <div class="modal fade" id="escalateModal{{ $laporan->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <form action="{{ route('lab.admin_new.kerusakan.eskalasi', $laporan->id) }}" method="POST" class="w-100">
                    @csrf
                    <div class="modal-content border-0 rounded-4 shadow">
                        <div class="modal-header bg-danger text-white border-0 rounded-top-4">
                            <h5 class="fw-bold mb-0">Eskalasi ke Atasan</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body py-4">
                            <p class="text-muted small mb-4">Gunakan fitur ini jika kerusakan memerlukan perhatian khusus atau persetujuan penggadaan dari pimpinan sekolah.</p>
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted small mb-2">ESKALASI KE</label>
                                <select name="eskalasi_ke" class="form-select border-2 rounded-4 p-3" required>
                                    <option value="kepala_lab">Kepala Laboratorium</option>
                                    <option value="waka_akademik">Waka Akademik / Kurikulum</option>
                                    <option value="kepala_sekolah">Kepala Sekolah</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="form-label fw-bold text-muted small mb-2">CATATAN ESKALASI & REKOMENDASI</label>
                                <textarea name="eskalasi_catatan" class="form-control border-2 rounded-4" rows="4" required placeholder="Jelaskan mengapa laporan ini perlu dinaikkan ke atasan..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="ui-btn ui-btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="ui-btn ui-btn-danger px-4">Kirim Eskalasi</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @empty
        <div class="col-12">
            <x-ui.empty-state icon="bi-exclamation-triangle" title="Tidak ada laporan" description="Semua peralatan laboratorium dalam kondisi terpantau." />
        </div>
    @endforelse
</div>
@endsection

@section('css')
<style>
    .italic { font-style: italic; }
</style>
@endsection

