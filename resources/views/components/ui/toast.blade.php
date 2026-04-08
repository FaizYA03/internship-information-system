@if(session()->has('success') || session()->has('error') || session()->has('message'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
     class="fixed bottom-4 right-4 z-50 flex flex-col gap-2">
    
    @if(session()->has('success'))
        <div class="flex items-center gap-3 bg-card border border-border shadow-lg rounded-lg p-4 max-w-sm"
             x-transition:enter="transform ease-out duration-300 transition" x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2" x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
             x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <i data-lucide="check-circle" class="w-5 h-5 text-primary"></i>
            <div>
                <p class="font-semibold text-sm">Success</p>
                <p class="text-sm text-muted-foreground">{{ session('success') }}</p>
            </div>
            <button @click="show = false" class="ml-auto text-muted-foreground hover:text-foreground"><i data-lucide="x" class="w-4 h-4"></i></button>
        </div>
    @endif

    @if(session()->has('error'))
        <div class="flex items-center gap-3 bg-destructive text-destructive-foreground shadow-lg rounded-lg p-4 max-w-sm"
             x-transition:enter="transform ease-out duration-300 transition" x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2" x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
             x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <i data-lucide="alert-circle" class="w-5 h-5"></i>
            <div>
                <p class="font-semibold text-sm">Error</p>
                <p class="text-sm opacity-90">{{ session('error') }}</p>
            </div>
            <button @click="show = false" class="ml-auto opacity-70 hover:opacity-100"><i data-lucide="x" class="w-4 h-4"></i></button>
        </div>
    @endif

</div>
@endif
