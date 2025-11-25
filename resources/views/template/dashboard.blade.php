<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Pharma Ease')</title>

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- AlpineJS (Optional for interactivity) -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-muted/30 text-foreground font-sans antialiased overflow-hidden">

    <div class="flex h-screen w-full relative" x-data="{ sidebarOpen: false }">

        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity
            class="fixed inset-0 bg-black/50 z-20 lg:hidden" style="display: none;"></div>

        <!-- Sidebar Component -->
        @include('template.partials.sidebar')

        <!-- Main Content Area -->
        <div class="flex flex-col flex-1 overflow-hidden h-full">

            <!-- Header Component -->
            @include('template.partials.header')

            <!-- Main Scrollable Content -->
            <main class="flex-1 overflow-y-auto p-6 md:p-8 bg-muted/20">
                @yield('admin-dashboard')
                {{-- Gunakan 'content' atau nama section lain jika untuk halaman selain dashboard --}}
            </main>
        </div>
    </div>

</body>

</html>
