<div x-data="{ open: false }" class="relative inline-block text-left">
    <div @click="open = !open">
        {{ $trigger }}
    </div>

    <div x-show="open" 
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute right-0 mt-2 w-56 origin-top-right rounded-md bg-popover text-popover-foreground shadow-lg ring-1 ring-border focus:outline-none z-50">
        <div class="p-1">
            {{ $slot }}
        </div>
    </div>
</div>
