<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body class="font-sans antialiased touch-manipulation overscroll-none">
    <div class="min-h-screen bg-gray-100 flex flex-col">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white pt-16  shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="flex-1">
            {{ $slot }}
        </main>

        <x-footer />
    </div>
    <!-- Offline Indicator -->
    <div id="offlineIndicator" class="fixed bottom-4 left-4 right-4 z-50 transform translate-y-20 transition-transform duration-300 pointer-events-none">
        <div class="bg-slate-900/90 backdrop-blur-md text-white px-4 py-3 rounded-xl shadow-lg flex items-center justify-center gap-3">
            <span class="material-symbols-outlined text-red-400 animate-pulse">wifi_off</span>
            <div class="text-sm font-medium">
                <p>Anda sedang offline</p>
                {{-- <p class="text-xs text-slate-400">Beberapa fitur mungkin tidak berjalan</p> --}}
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('offline', () => {
             document.getElementById('offlineIndicator').classList.remove('translate-y-20');
        });
        window.addEventListener('online', () => {
             document.getElementById('offlineIndicator').classList.add('translate-y-20');
        });
        
        // Check initial state
        if(!navigator.onLine) {
            document.getElementById('offlineIndicator').classList.remove('translate-y-20');
        }
    </script>

    @stack('scripts')
</body>

</html>
