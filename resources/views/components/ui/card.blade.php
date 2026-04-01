@props(['title' => null, 'footer' => null, 'hover' => true])

<div {{ $attributes->merge(['class' => 'ui-card' . ($hover ? ' hover-lift' : '')]) }}>
    @if($title || isset($header))
        <div class="ui-card-header">
            @if(isset($header))
                {{ $header }}
            @else
                <h5 class="mb-0 fw-bold">{{ $title }}</h5>
            @endif
        </div>
    @endif

    <div class="ui-card-body">
        {{ $slot }}
    </div>

    @if(isset($footer))
        <div class="ui-card-footer mt-3 pt-3 border-top">
            {{ $footer }}
        </div>
    @endif
</div>
