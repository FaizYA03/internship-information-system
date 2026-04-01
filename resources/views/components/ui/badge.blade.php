@props(['variant' => 'neutral'])

@php
    $classes = [
        'success' => 'ui-badge-success',
        'warning' => 'ui-badge-warning',
        'danger' => 'ui-badge-danger',
        'info' => 'ui-badge-info',
        'neutral' => 'ui-badge-neutral',
    ][$variant] ?? 'ui-badge-neutral';
@endphp

<span {{ $attributes->merge(['class' => 'ui-badge ' . $classes]) }}>
    {{ $slot }}
</span>
