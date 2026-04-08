@props(['value'])
<button 
    type="button"
    @click="tab = '{{ $value }}'"
    :class="{ 'bg-background text-foreground shadow-sm': tab === '{{ $value }}' }"
    class="inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 flex-1 sm:flex-auto">
    {{ $slot }}
</button>
