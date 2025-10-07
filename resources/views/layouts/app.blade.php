<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">
    <meta name="csrf-token"
          content="{{ csrf_token() }}">
    <link rel="icon"
          type="image/x-icon"
          href="{{ asset('logo-JasaReceh.ico') }}">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <script src="//unpkg.com/alpinejs"
            defer></script>

    <link rel="preconnect"
          href="https://fonts.googleapis.com">
    <link rel="preconnect"
          href="https://fonts.gstatic.com"
          crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap"
          rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Font Awesome 6 (versi terbaru) -->
    <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIjK8C4D4w3aDJYqk6a5z6mH2Vv3d9V0Y9O4nUu5xF0Vw=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer"
    />

</head>

<body class="font-sans antialiased bg-gray-100 text-gray-900">
    <div class="min-h-screen">
        @include('layouts.navigation')

        @isset($header)
            <header class="bg-white shadow-sm">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main>
            {{ $slot }}
        </main>
    </div>

    @include('components.footer')
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>

    <!-- =================================== -->
    <!-- MODERN COOKIE BANNER -->
    <!-- =================================== -->
    <div id="cookie-banner"
         class="fixed bottom-6 left-1/2 -translate-x-1/2 w-[90%] sm:w-[400px] bg-white/90 backdrop-blur-md shadow-lg rounded-xl border border-gray-200 p-5 flex flex-col sm:flex-row sm:items-center gap-3 transition-all duration-500 opacity-0 pointer-events-none z-50">
        <div class="flex-1 text-sm text-gray-700">
            <span class="font-medium">Kami gunakan cookies</span> untuk meningkatkan pengalamanmu di situs ini.
        </div>
        <div class="flex gap-2 justify-end w-full sm:w-auto">
            <button id="decline-cookies"
                    class="text-gray-500 border border-gray-300 hover:bg-gray-100 px-3 py-1.5 rounded-md text-sm transition">
                Tolak
            </button>
            <button id="accept-cookies"
                    class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-md text-sm transition">
                Terima
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const banner = document.getElementById('cookie-banner');
            const acceptBtn = document.getElementById('accept-cookies');
            const declineBtn = document.getElementById('decline-cookies');

            const consent = localStorage.getItem('cookieConsent');
            if (!consent) {
                // Tampilkan dengan animasi fade-in
                banner.classList.remove('pointer-events-none');
                banner.classList.remove('opacity-0');
                banner.classList.add('opacity-100');
            }

            function hideBanner() {
                banner.classList.add('opacity-0');
                banner.classList.add('pointer-events-none');
            }

            acceptBtn.addEventListener('click', () => {
                localStorage.setItem('cookieConsent', 'accepted');
                hideBanner();
                toastr.success('Cookies diaktifkan untuk pengalaman lebih baik.');
            });

            declineBtn.addEventListener('click', () => {
                localStorage.setItem('cookieConsent', 'declined');
                hideBanner();
                toastr.info('Kamu menolak cookies.');
            });
        });
    </script>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

</body>

</html>
