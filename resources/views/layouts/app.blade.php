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
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
          integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIjK8C4D4w3aDJYqk6a5z6mH2Vv3d9V0Y9O4nUu5xF0Vw=="
          crossorigin="anonymous"
          referrerpolicy="no-referrer" />

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
