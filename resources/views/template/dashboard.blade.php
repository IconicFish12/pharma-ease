<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') ?? $title_page }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body x-data="{ sidebarOpen: false }" x-cloak>

    @include('template.partials.sidebar')

    <div class="flex flex-col flex-1 lg:ml-64">

        @include('template.partials.header')

        <main class="flex-1 p-4 md:p-6">
            @yield('admin-dashboard')
        </main>

        {{-- Footer (jika ada) --}}
        {{-- @include('template.partials.footer') --}}
    </div>

    {{--
      == TAMBAHAN BARU ==
      Overlay Mobile (dari kode 'mobile overlay' Anda)
      Ini akan muncul saat sidebarOpen = true, dan menutup sidebar saat diklik.
    --}}
    <div x-show="sidebarOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/50 z-30 lg:hidden" @click="sidebarOpen = false"></div>


    @yield('script')
</body>

</html>
