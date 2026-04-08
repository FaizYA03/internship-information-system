@props(['name' => null, 'id' => null, 'title' => null, 'description' => null, 'show' => false, 'maxWidth' => '2xl'])

@php
$name = $name ?? $id;
$maxWidth = [
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
][$maxWidth];
@endphp

<div
    x-data="{
        show: @js($show)
    }"
    x-on:close-modal.window="$event.detail == '{{ $name }}' ? show = false : null"
    x-on:open-modal.window="$event.detail == '{{ $name }}' ? show = true : null"
    x-on:close-dialog.window="$event.detail == '{{ $name }}' ? show = false : null"
    x-on:open-dialog.window="$event.detail == '{{ $name }}' ? show = true : null"
    x-show="show"
    class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50 flex items-center justify-center border-border"
    style="display: {{ $show ? 'flex' : 'none' }};"
>
    <!-- Backdrop -->
    <div x-show="show" class="fixed inset-0 transform transition-all" x-on:click="show = false" 
         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="absolute inset-0 bg-background/80 backdrop-blur-sm"></div>
    </div>

    <!-- Modal Panel -->
    <div x-show="show" class="bg-card text-card-foreground rounded-lg overflow-hidden shadow-lg transform transition-all sm:w-full {{ $maxWidth }} mx-auto border border-border z-10" 
         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
        
        @if($title)
        <div class="px-6 py-4 border-b border-border bg-muted/20">
            <h3 class="text-lg font-semibold text-foreground">{{ $title }}</h3>
            @if($description)
            <p class="text-sm text-muted-foreground mt-1">{{ $description }}</p>
            @endif
        </div>
        <div class="p-6 pt-4">
            {{ $slot }}
        </div>
        @else
            {{ $slot }}
        @endif
    </div>
</div>
