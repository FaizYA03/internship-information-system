@props(['title' => 'Tidak ada data', 'description' => 'Maaf, belum ada data yang tersedia saat ini.', 'icon' => 'bi-inbox'])

<div {{ $attributes->merge(['class' => 'text-center py-5']) }}>
    <div class="mb-3">
        <i class="bi {{ $icon }} text-muted opacity-25" style="font-size: 4rem;"></i>
    </div>
    <h5 class="fw-bold text-secondary">{{ $title }}</h5>
    <p class="text-muted">{{ $description }}</p>
    {{ $slot }}
</div>
