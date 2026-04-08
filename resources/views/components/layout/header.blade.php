<header class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-border bg-background px-4 md:px-6 shadow-sm">
    <!-- Mobile sidebar toggle -->
    <button type="button" @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 -ml-2 text-muted-foreground hover:text-foreground">
        <i data-lucide="menu" class="w-6 h-6"></i>
    </button>
    
    <div class="flex-1 flex items-center justify-end">
        @auth
            <div class="flex items-center gap-3">
                <div class="hidden md:block text-right">
                    <p class="text-sm font-medium leading-none text-foreground">{{ auth()->user()->nama ?? auth()->user()->name }}</p>
                    <p class="text-xs text-muted-foreground mt-1">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</p>
                </div>
                <div class="w-9 h-9 rounded-full bg-primary text-primary-foreground flex items-center justify-center font-bold text-sm">
                    {{ strtoupper(substr(auth()->user()->nama ?? auth()->user()->name ?? 'U', 0, 1)) }}
                </div>
            </div>
        @endauth
    </div>
</header>
