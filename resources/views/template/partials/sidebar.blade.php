<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-30 w-64 transform -translate-x-full lg:translate-x-0 lg:static lg:block transition-transform duration-300 ease-in-out bg-sidebar text-sidebar-foreground flex flex-col">
    <!-- Logo Section -->
    <div class="h-16 flex items-center gap-2 px-6 border-b border-white/10">
        {{-- Logo Icon (SVG) --}}
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6">
            <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
        </svg>
        <div class="flex flex-col">
            <span class="font-bold text-lg tracking-tight leading-none">Pharma Ease</span>
            <span class="text-[10px] opacity-70 leading-none font-light">Pharmacy Management</span>
        </div>
    </div>

    <!-- Navigation Links -->
    <div class="flex-1 overflow-y-auto py-4 px-3 space-y-1 no-scrollbar">

        {{-- Helper function untuk class active --}}
        @php
            $activeClass = 'bg-sidebar-primary text-sidebar-primary-foreground shadow-sm';
            $inactiveClass =
                'hover:bg-sidebar-accent hover:text-sidebar-accent-foreground text-sidebar-foreground/80 transition-colors';
        @endphp

        {{-- Dashboard --}}
        @foreach ($menuItems as $item)
            @php
                $isActive = false;
                if (isset($item['route'])) {
                    $isActive = request()->routeIs($item['route']) || request()->routeIs($item['route'] . '*');
                }
                if (!$isActive && isset($item['active_pattern'])) {
                    $isActive = request()->is($item['active_pattern']);
                }
            @endphp

            <a href="{{ isset($item['route']) ? route($item['route']) : '#' }}"
               class="group flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium
                      {{ $isActive ? $activeClass : $inactiveClass }}">

                {{-- Render Icon Dynamic --}}
                <x-dynamic-component
                    :component="'lucide-'.$item['icon']"
                    class="h-5 w-5 shrink-0 transition-transform group-hover:scale-110"
                />

                {{ $item['label'] }}
            </a>
        @endforeach
    </div>

    <!-- Footer -->
    <div class="p-4 border-t border-sidebar-border/10 text-xs text-center opacity-60">
        <p>&copy; {{ date('Y') }} Pharma Ease</p>
        <p>v1.0.0</p>
    </div>
</aside>
