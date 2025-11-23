@php
    // Data dummy sementara
    $dummyName = 'Admin User';
    $dummyEmail = 'admin@example.com';
    $dummyRole = 'Administrator';

    // Logika inisial dari data dummy
    $nameParts = explode(' ', $dummyName);
    $initials =
        count($nameParts) > 1
            ? strtoupper(substr($nameParts[0], 0, 1) . substr(end($nameParts), 0, 1))
            : strtoupper(substr($nameParts[0], 0, 2));
@endphp

<div
    class="lg:hidden fixed top-0 left-0 right-0 bg-primary text-primary-foreground p-4 flex items-center justify-between z-50 shadow-lg">

    <div class="flex items-center gap-2">
        {{-- Ikon Activity (Logo) --}}
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6">
            <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
        </svg>
        <h1 class="text-lg font-medium">Pharma Ease</h1>
    </div>

    <div class="flex items-center gap-2">

        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="text-primary-foreground hover:bg-primary/80 rounded-full p-0.5">
                <div
                    class="h-8 w-8 rounded-full bg-accent text-accent-foreground text-xs flex items-center justify-center font-semibold">
                    {{ $initials }}
                </div>
            </button>

            <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute right-0 mt-2 w-56 z-50 origin-top-right bg-popover text-popover-foreground rounded-md border p-1 shadow-md"
                x-cloak>
                <div class="px-2 py-1.5 text-sm font-medium">
                    <div>
                        <p>{{ $dummyName }}</p>
                        <p class="text-xs text-muted-foreground">{{ $dummyEmail }}</p>
                    </div>
                </div>
                <hr class="bg-border -mx-1 my-1 h-px">
                <a href="#"
                    class="focus:bg-accent focus:text-accent-foreground relative flex cursor-default items-center gap-2 rounded-sm px-2 py-1.5 text-sm outline-none select-none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="mr-2 h-4 w-4">
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                    View Profile
                </a>
                <a href="#"
                    class="focus:bg-accent focus:text-accent-foreground relative flex cursor-default items-center gap-2 rounded-sm px-2 py-1.5 text-sm outline-none select-none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="mr-2 h-4 w-4">
                        <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                        <path d="m15 5 4 4" />
                    </svg>
                    Edit Profile
                </a>
                <hr class="bg-border -mx-1 my-1 h-px">

                <a href="#"
                    class="w-full text-left focus:bg-accent focus:text-accent-foreground relative flex cursor-default items-center gap-2 rounded-sm px-2 py-1.5 text-sm outline-none select-none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="mr-2 h-4 w-4">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                        <polyline points="16 17 21 12 16 7" />
                        <line x1="21" x2="9" y1="12" y2="12" />
                    </svg>
                    Logout
                </a>
            </div>
        </div>

        <button @click="sidebarOpen = !sidebarOpen"
            class="inline-flex items-center justify-center rounded-md h-10 w-10
                   text-primary-foreground hover:bg-primary/80">
            {{-- Ikon 'X' (tampil saat sidebarOpen = true) --}}
            <svg x-show="sidebarOpen" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" class="h-6 w-6">
                <path d="M18 6 6 18" />
                <path d="m6 6 12 12" />
            </svg>
            {{-- Ikon 'Menu' (tampil saat sidebarOpen = false) --}}
            <svg x-show="!sidebarOpen" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" class="h-6 w-6">
                <line x1="3" x2="21" y1="6" y2="6" />
                <line x1="3" x2="21" y1="12" y2="12" />
                <line x1="3" x2="21" y1="18" y2="18" />
            </svg>
            <span class="sr-only">Toggle Sidebar</span>
        </button>

    </div>
</div>


<div class="hidden lg:block fixed top-0 right-0 left-64 bg-white border-b border-border p-4 z-30 h-16">
    <div class="flex items-center justify-between">

        <h1 class="font-semibold text-lg">@yield('title', 'Dashboard')</h1>

        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open"
                class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-muted transition-colors cursor-pointer">
                <div
                    class="h-8 w-8 rounded-full bg-primary text-primary-foreground text-xs flex items-center justify-center font-semibold">
                    {{ $initials }}
                </div>
                <div class="text-left">
                    <p class="text-sm font-medium">{{ $dummyName }}</p>
                    <p class="text-xs text-muted-foreground">{{ $dummyRole }}</p>
                </div>
            </button>

            <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute right-0 mt-2 w-56 z-50 origin-top-right bg-popover text-popover-foreground rounded-md border p-1 shadow-md"
                x-cloak>
                <div class="px-2 py-1.5 text-sm font-medium">My Account</div>
                <hr class="bg-border -mx-1 my-1 h-px">
                <a href="#"
                    class="focus:bg-accent focus:text-accent-foreground relative flex cursor-default items-center gap-2 rounded-sm px-2 py-1.5 text-sm outline-none select-none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="mr-2 h-4 w-4">
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                    View Profile
                </a>
                <a href="#"
                    class="focus:bg-accent focus:text-accent-foreground relative flex cursor-default items-center gap-2 rounded-sm px-2 py-1.5 text-sm outline-none select-none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="mr-2 h-4 w-4">
                        <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                        <path d="m15 5 4 4" />
                    </svg>
                    Edit Profile
                </a>
                <hr class="bg-border -mx-1 my-1 h-px">

                <a href="#"
                    class="w-full text-left focus:bg-accent focus:text-accent-foreground relative flex cursor-default items-center gap-2 rounded-sm px-2 py-1.5 text-sm outline-none select-none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="mr-2 h-4 w-4">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                        <polyline points="16 17 21 12 16 7" />
                        <line x1="21" x2="9" y1="12" y2="12" />
                    </svg>
                    Logout
                </a>
            </div>
        </div>
    </div>
</div>
