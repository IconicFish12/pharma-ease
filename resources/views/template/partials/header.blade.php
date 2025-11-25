<header
    class="h-16 flex items-center justify-between gap-4 border-b border-border bg-background px-6 shadow-sm shrink-0">

    <!-- Mobile Sidebar Trigger -->
    <div class="flex items-center gap-4">
        <button @click="sidebarOpen = !sidebarOpen"
            class="lg:hidden p-2 -ml-2 text-muted-foreground hover:text-foreground">
            <span class="sr-only">Toggle Sidebar</span>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="4" x2="20" y1="12" y2="12" />
                <line x1="4" x2="20" y1="6" y2="6" />
                <line x1="4" x2="20" y1="18" y2="18" />
            </svg>
        </button>
    </div>

    <!-- Right Side Actions -->
    <div class="flex items-center gap-4">

        <!-- Notification Icon -->
        <button
            class="relative p-2 text-muted-foreground hover:text-foreground transition-colors rounded-full hover:bg-muted focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">
            <span class="absolute top-2 right-2 h-2 w-2 rounded-full bg-destructive ring-2 ring-background"></span>
            <span class="sr-only">View notifications</span>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9" />
                <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0" />
            </svg>
        </button>

        <!-- User Profile Dropdown -->
        <div class="flex items-center gap-3 pl-4 border-l border-border cursor-pointer">
            <div
                class="flex h-9 w-9 items-center justify-center rounded-full bg-sidebar text-sidebar-foreground font-medium text-sm">
                {{-- Initials --}}
                IS
            </div>
            <div class="hidden md:block text-sm">
                <p class="font-medium text-foreground leading-none">Ibnu Syawal</p>
                <p class="text-xs text-muted-foreground mt-1">Admin</p>
            </div>
        </div>
    </div>
</header>
