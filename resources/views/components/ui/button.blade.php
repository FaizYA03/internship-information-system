@props(['variant' => 'primary', 'type' => 'button', 'icon' => null])

@php
    $classes = [
        'primary' => 'ui-btn-primary',
        'secondary' => 'ui-btn-secondary',
        'danger' => 'ui-btn-danger',
    ][$variant] ?? 'ui-btn-primary';
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => 'ui-btn ' . $classes]) }}>
    @if($icon)
        <i class="{{ $icon }}"></i>
    @endif
    <span>{{ $slot }}</span>
</button>
