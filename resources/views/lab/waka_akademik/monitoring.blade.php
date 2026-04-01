@extends('lab.layouts.unified', ['title' => 'Monitoring Akademik'])

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="fw-bold mb-0">Log Monitoring Aktivitas Laboratorium</h5>
                <p class="small text-muted mb-0">Audit jejak digital penggunaan dan perubahan data sistem laboratorium.</p>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0">Waktu</th>
                                <th class="border-0">User</th>
                                <th class="border-0">Tindakan</th>
                                <th class="border-0">Deskripsi</th>
                                <th class="border-0">Subject</th>
                                <th class="border-0">IP Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activities as $log)
                                <tr>
                                    <td>
                                        <div class="small fw-semibold">{{ $log->created_at->format('d/m/Y') }}</div>
                                        <div class="small text-muted">{{ $log->created_at->format('H:i:s') }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $log->user->nama ?? 'Sistem' }}</div>
                                        <small class="text-muted">{{ ucfirst($log->user->role ?? '') }}</small>
                                    </td>
                                    <td>
                                        @php
                                            $badgeClass = 'bg-secondary';
                                            if($log->action == 'created' || $log->action == 'approved') $badgeClass = 'bg-success';
                                            if($log->action == 'deleted' || $log->action == 'rejected') $badgeClass = 'bg-danger';
                                            if($log->action == 'updated') $badgeClass = 'bg-info';
                                        @endphp
                                        <span class="badge {{ $badgeClass }} px-3 rounded-pill">{{ strtoupper($log->action) }}</span>
                                    </td>
                                    <td><small>{{ $log->description }}</small></td>
                                    <td>
                                        <div class="small text-truncate" style="max-width: 150px;">
                                            {{ class_basename($log->subject_type) }} #{{ $log->subject_id }}
                                        </div>
                                    </td>
                                    <td><code class="small">{{ $log->ip_address }}</code></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">Belum ada catatan aktivitas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $activities->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
