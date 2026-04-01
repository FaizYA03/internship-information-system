@extends('lab.layouts.unified', ['title' => 'Log Aktivitas'])

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="fw-bold mb-1 text-dark">Log Aktivitas Sistem</h4>
        <p class="text-muted small mb-0">Monitor seluruh aktivitas yang dilakukan oleh admin dan kepala lab.</p>
    </div>
</div>

<x-ui.card class="border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="bg-light">
                <tr>
                    <th class="border-0 small fw-bold text-muted">WAKTU</th>
                    <th class="border-0 small fw-bold text-muted">USER</th>
                    <th class="border-0 small fw-bold text-muted">AKSI</th>
                    <th class="border-0 small fw-bold text-muted">KETERANGAN</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td class="small text-muted">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                        <td class="small fw-bold text-dark">{{ $log->user->name ?? 'System' }}</td>
                        <td><x-ui.badge variant="info">{{ strtoupper(str_replace('_', ' ', $log->action)) }}</x-ui.badge></td>
                        <td class="small text-muted">{{ $log->description }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted small">Belum ada aktivitas tercatat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $logs->links() }}
    </div>
</x-ui.card>
@endsection
