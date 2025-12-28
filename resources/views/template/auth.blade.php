<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? 'Pharma Ease' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-full bg-muted/30 flex flex-col justify-center items-center py-12 sm:px-6 lg:px-8">
    <div class="fixed inset-0 -z-10 h-full w-full bg-white [background:radial-gradient(125%_125%_at_50%_10%,#fff_40%,#0F6643_100%)] opacity-5"></div>
    <div class="sm:mx-auto sm:w-full sm:max-w-md w-full px-4">
        @yield("auth")
        <div class="mt-8 text-center text-xs text-muted-foreground">
            &copy; {{ date('Y') }} Pharma Ease. All rights reserved.
        </div>
    </div>

</body>
</html>
