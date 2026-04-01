@extends('lab.layouts.unified', ['title' => 'Monitoring Akademik'])

@section('content')
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="ui-card bg-primary text-white border-0" style="background: linear-gradient(135deg, #4F46E5 0%, #3730A3 100%) !important;">
            <div class="ui-card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="fw-bold mb-2">Monitor Akademik Laboratorium</h2>
                        <p class="mb-0 opacity-75">Pantau penggunaan laboratorium untuk menunjang kegiatan belajar mengajar di SMK Negeri 5 Padang.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <x-ui.card title="Aktivitas Terbaru">
            <div class="activity-scroll" style="max-height: 500px; overflow-y: auto;">
                @forelse($logs as $log)
                    <div class="p-3 border-bottom hover-bg-light transition-all">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">{{ $log->user->nama ?? 'Sistem' }}</h6>
                                <p class="mb-0 small text-muted">{{ $log->description }}</p>
                            </div>
                            <span class="text-muted" style="font-size: 0.75rem;">{{ $log->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="mt-2 text-end">
                            <x-ui.badge variant="neutral" style="font-size: 0.65rem;">{{ strtoupper($log->action) }}</x-ui.badge>
                        </div>
                    </div>
                @empty
                    <x-ui.empty-state icon="bi-activity" title="Belum ada aktivitas" description="Aktivitas penggunaan lab akan muncul di sini." />
                @endforelse
            </div>
            @if(count($logs) > 0)
            <div class="text-center mt-3">
                <a href="{{ route('lab.waka_akademik.monitoring') }}" class="ui-btn ui-btn-secondary btn-sm px-4">
                    Lihat Semua Aktivitas
                </a>
            </div>
            @endif
        </x-ui.card>
    </div>
    
    <div class="col-md-4">
        <x-ui.card title="Statistik Akademik" class="mb-4">
            <div class="mb-4">
                <p class="small text-muted mb-1">Total Laboratorium</p>
                <h3 class="fw-bold text-dark mb-0">{{ App\Models\Labor::count() }}</h3>
            </div>
            <hr class="opacity-10">
            <div>
                <p class="small text-muted mb-1">Jadwal Minggu Ini</p>
                <h4 class="fw-bold text-primary mb-0">{{ App\Models\Laboratorium::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count() }} Sesi</h4>
            </div>
        </x-ui.card>
        
        <x-ui.card class="bg-primary-soft border-0">
            <div class="d-flex gap-3 align-items-center mb-3">
                <div class="p-2 rounded-3 bg-white text-primary">
                    <i class="bi bi-calendar-check-fill fs-4"></i>
                </div>
                <h6 class="fw-bold mb-0">Penggunaan Lab</h6>
            </div>
            <p class="small text-muted">Pastikan ketersediaan ruang untuk praktikum siswa sesuai dengan kurikulum yang berlaku.</p>
            <a href="{{ route('lab.admin_new.jadwal.index') }}" class="ui-btn ui-btn-primary w-100 justify-content-center">
                Cek Jadwal Praktikum
            </a>
        </x-ui.card>
    </div>
</div>
@endsection

@section('css')
<style>
    .hover-bg-light:hover { background-color: #F8FAFC; }
    .transition-all { transition: all 0.2s; }
</style>
@endsection

