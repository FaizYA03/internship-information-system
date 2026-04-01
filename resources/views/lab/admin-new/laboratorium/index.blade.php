@extends('lab.layouts.unified', ['title' => 'Daftar Laboratorium'])

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-small">
        <li class="breadcrumb-item"><a href="#">SMK N 5 Padang</a></li>
        <li class="breadcrumb-item active" aria-current="page">Laboratorium</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1 text-dark">Kelola Laboratorium</h4>
                <p class="text-muted small mb-0">Monitor status dan inventaris setiap ruangan laboratorium.</p>
            </div>
            <a href="{{ route('lab.admin_new.laboratorium.create') }}" class="ui-btn ui-btn-primary">
                <i class="bi bi-plus-lg"></i> Tambah Lab Baru
            </a>
        </div>
    </div>
</div>

<div class="row g-4">
    @forelse($laboratories as $lab)
        @php
            $jenisData = $lab->jenisData;
            $ikon  = $jenisData->ikon ?? 'bi-building-fill';
            $warna = $jenisData->warna ?? 'primary';
            
            // Map bootstrap color to background soft class
            $bgSoft = "bg-{$warna}-soft";
            $textClass = "text-{$warna}";
            
            // Fallback for custom purple color if needed
            if($warna == 'purple') {
                $bgSoft = 'style="background: #f5e8ff;"';
                $textClass = 'style="color: #7b2d8b;"';
            } else {
                $bgSoft = 'class="p-3 rounded-4 ' . $bgSoft . ' ' . $textClass . '"';
                $textClass = ''; // already in bgSoft combined
            }
        @endphp
        <div class="col-md-4">
            <x-ui.card :hover="true" class="h-100 border-0 shadow-sm" style="cursor: pointer;" onclick="window.location='{{ route('lab.admin_new.laboratorium.show', $lab->id) }}'">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div {!! str_contains($bgSoft, 'style') ? $bgSoft : $bgSoft !!}>
                        <i class="bi {{ $ikon }} fs-3" {!! $textClass !!}></i>
                    </div>
                    @php $status = $lab->getCurrentStatus(); @endphp
                    <x-ui.badge variant="{{ $status == 'digunakan' ? 'warning' : 'success' }}">
                        {{ strtoupper($status) }}
                    </x-ui.badge>
                </div>
                
                <h5 class="fw-bold text-dark mb-1">{{ $lab->nama_labor }}</h5>
                <p class="text-muted small mb-3"><i class="bi bi-tag me-1"></i> {{ $lab->jenis_labor ?? 'Lainnya' }}</p>
                
                <div class="d-flex align-items-center gap-4 mb-4">
                    <div>
                        <small class="text-muted d-block small fw-medium">KAPASITAS</small>
                        <span class="fw-bold text-dark">{{ $lab->kapasitas ?? 30 }} Siswa</span>
                    </div>
                    <div>
                        <small class="text-muted d-block small fw-medium">INVENTARIS</small>
                        <span class="fw-bold text-dark">{{ $lab->inventaris_count }} Unit</span>
                    </div>
                </div>

                <div class="pt-3 border-top d-flex justify-content-between align-items-center">
                    <span class="text-primary small fw-bold">Lihat Detail <i class="bi bi-arrow-right ms-1"></i></span>
                    <div class="d-flex gap-2">
                        <a href="{{ route('lab.admin_new.laboratorium.edit', $lab->id) }}" class="btn btn-light btn-sm rounded-circle p-2" title="Edit">
                            <i class="bi bi-pencil-fill text-muted"></i>
                        </a>
                    </div>
                </div>
            </x-ui.card>
        </div>
    @empty
        <div class="col-12">
            <x-ui.empty-state icon="bi-building" title="Belum ada data lab" description="Silakan tambah laboratorium baru untuk memulai pengelolaan." />
        </div>
    @endforelse
</div>
@endsection

