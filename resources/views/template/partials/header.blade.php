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
        <div class="relative" x-data="{ open: false }">
            <div @click="open = !open" @click.outside="open = false"
                class="flex items-center gap-3 pl-4 border-l border-border cursor-pointer">
                <div
                    class="flex h-9 w-9 items-center justify-center rounded-full bg-sidebar text-sidebar-foreground font-medium text-sm">
                    {{-- Initials Dinamis --}}
                    @php
                        $name = Auth::user()->name ?? 'User';
                        $initials = collect(explode(' ', $name))
                            ->map(function ($word) {
                                return strtoupper(substr($word, 0, 1));
                            })
                            ->take(2)
                            ->implode('');
                    @endphp
                    {{ $initials }}
                </div>
                <div class="hidden md:block text-sm">
                    <p class="font-medium text-foreground leading-none">{{ Auth::user()->name ?? 'Guest' }}</p>
                    <p class="text-xs text-muted-foreground mt-1 capitalize">{{ Auth::user()->role ?? 'Role' }}</p>
                </div>
                <!-- Chevron Down -->
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="text-muted-foreground transition-transform duration-200" :class="open ? 'rotate-180' : ''">
                    <path d="m6 9 6 6 6-6" />
                </svg>
            </div>

            <!-- Dropdown Menu -->
            <div x-show="open" x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95"
                class="absolute right-0 z-50 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                style="display: none;">

                <div class="px-4 py-3 border-b border-gray-100">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name ?? 'Guest' }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email ?? '' }}</p>
                </div>

                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Your Profile</a>
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>

                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit"
                        class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 font-medium">
                        Sign out
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
