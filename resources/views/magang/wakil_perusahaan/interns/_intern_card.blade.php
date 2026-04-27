<div class="intern-card">
    <div class="intern-header">
        <strong>{{ $intern->nama }}</strong>
        <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
    </div>

    <div class="intern-body">

        <!-- POSISI -->
        <div class="fw-bold mb-2">
            {{ optional($intern->opening)->posisi ?? 'Program tidak tersedia' }}
        </div>

        <!-- META -->
        <div class="intern-meta">
            <div><i class="bi bi-envelope"></i> {{ $intern->email ?? '-' }}</div>
            <div><i class="bi bi-telephone"></i> {{ $intern->no_hp ?? '-' }}</div>
        </div>

        <!-- TANGGAL -->
        <div class="intern-meta">
            <div>
                <i class="bi bi-calendar-event"></i>
                Mulai:
                {{ $intern->tanggal_mulai ? \Carbon\Carbon::parse($intern->tanggal_mulai)->format('d M Y') : '-' }}
            </div>
            <div>
                <i class="bi bi-calendar-check"></i>
                Selesai:
                {{ $intern->tanggal_selesai ? \Carbon\Carbon::parse($intern->tanggal_selesai)->format('d M Y') : '-' }}
            </div>
        </div>

        <!-- SUPERVISOR (PEMBIMBING LAPANGAN) -->
        @php
            $currentSupervisorId = $intern->mitra_supervisor_id;
            $defaultSupervisorName = $wakilPerusahaan->nama ?? $wakilPerusahaan->nama_perusahaan;
        @endphp
        <div class="mt-3 p-3 bg-light rounded border border-light">
            <form action="{{ route('magang.wakil_perusahaan.interns.set_supervisor', $intern->id) }}" method="POST" class="d-flex align-items-center gap-2">
                @csrf @method('PUT')
                <div style="flex-grow: 1;">
                    <label class="form-label text-muted small mb-1"><i class="bi bi-person-badge me-1"></i> Supervisor / Pembimbing Lapangan</label>
                    <select name="mitra_supervisor_id" class="form-select form-select-sm" {{ in_array($intern->status, ['Ditolak']) ? 'disabled' : '' }}>
                        <option value="">Default: {{ $defaultSupervisorName }}</option>
                        @foreach($supervisors as $spv)
                            <option value="{{ $spv->id }}" {{ $currentSupervisorId == $spv->id ? 'selected' : '' }}>
                                {{ $spv->nama_lengkap }} ({{ $spv->jabatan ?? 'Supervisor' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                @if(!in_array($intern->status, ['Ditolak']))
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary btn-sm mt-1" title="Simpan Supervisor">
                        <i class="bi bi-save"></i>
                    </button>
                </div>
                @endif
            </form>
        </div>

        <!-- ACTION -->
        @if($intern->status == 'Menunggu')
        <div class="mt-3">
            <form action="{{ route('magang.wakil_perusahaan.interns.approve',$intern->id) }}" method="POST" class="d-inline">
                @csrf @method('PUT')
                <button class="btn btn-success btn-sm">✔ Terima</button>
            </form>

            <form action="{{ route('magang.wakil_perusahaan.interns.reject',$intern->id) }}" method="POST" class="d-inline">
                @csrf @method('PUT')
                <input type="hidden" name="alasan" value="Tidak sesuai">
                <button class="btn btn-danger btn-sm">✖ Tolak</button>
            </form>
        </div>
        @endif

        <!-- CATATAN -->
        @if($intern->status == 'Ditolak')
        <div class="mt-2 text-danger small">
            <strong>Alasan:</strong> {{ $intern->catatan ?? '-' }}
        </div>
        @endif

    </div>
</div>
