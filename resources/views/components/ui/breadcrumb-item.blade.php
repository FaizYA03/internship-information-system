@props(['href' => null, 'active' => false])

<li class="flex items-center">
    @if($href)
        <a href="{{ $href }}" class="hover:text-foreground transition-colors {{ $active ? 'text-foreground font-medium' : '' }}">
            {{ $slot }}
        </a>
    @else
        <span class="{{ $active ? 'text-foreground font-medium' : '' }}">
            {{ $slot }}
        </span>
    @endif
</li>
