<aside 
    class="fixed inset-y-0 left-0 z-50 w-64 bg-background border-r border-border transform transition-transform duration-300 md:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    @click.away="sidebarOpen = false"
>
    <!-- Logo area -->
    <div class="h-16 flex items-center px-6 border-b border-border">
        <span class="text-xl font-bold text-foreground">Sistem Sekolah</span>
    </div>
    
    <!-- Navigation -->
    <nav class="p-4 space-y-1 overflow-y-auto h-[calc(100vh-4rem)]">
        @php
            $menuItems = config('menu.items', []);
            $userRole = auth()->user() ? auth()->user()->role : null;
        @endphp
        
        @foreach($menuItems as $item)
            @if(in_array('*', $item['roles']) || in_array($userRole, $item['roles']))
                <a href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}" 
                   class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-md transition-colors 
                   {{ request()->routeIs($item['route']) ? 'bg-secondary text-secondary-foreground' : 'text-muted-foreground hover:bg-secondary hover:text-foreground' }}">
                    <i data-lucide="{{ $item['icon'] }}" class="w-4 h-4"></i>
                    {{ $item['title'] }}
                </a>
            @endif
        @endforeach
    </nav>
</aside>
