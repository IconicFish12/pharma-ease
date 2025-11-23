<aside
    class="fixed top-0 left-0 h-full bg-primary text-primary-foreground w-64 z-40
           transition-transform duration-300 ease-in-out
           lg:translate-x-0"
    :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }">
    <div class="p-6 border-b border-sidebar-border">
        <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-8 w-8">
                <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
            </svg>
            <div>
                <h1 class="text-xl">Pharma Ease</h1>
                <p class="text-xs text-primary-foreground/80">Pharmacy Management</p>
            </div>
        </div>
    </div>

    <nav class="p-4 space-y-1">

        @foreach ($menuItems as $item)
            @php
                $isActive = request()->routeIs($item['route'] . '*');
            @endphp
            <a href="{{ route($item['route']) }}" data-active="{{ $isActive }}"
                class="w-full flex items-center gap-3 px-4 py-3 rounded-lg
                       transition-colors duration-200
                       text-sm
                       hover:bg-primary/80 text-primary-foreground
                       data-[active=true]:bg-accent data-[active=true]:text-accent-foreground"
                @click="sidebarOpen = false">

                <x-dynamic-component :component="'icons.' . $item['icon']" class="h-5 w-5 flex-shrink-0" />

                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach

    </nav>

    <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-sidebar-border">
        <div class="text-xs text-primary-foreground/60 text-center">
            <p>© 2025 Pharma Ease</p>
            <p class="mt-1">v1.0.0</p>
        </div>
    </div>
</aside>
