<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <header>
        <nav>
            <h1>{{ config('app.name', 'Laravel') }}</h1>
            <ul>
                <li><a href="">About me</a></li>
                <li><a href="">Contact me</a></li>
                <li><a href=""></a></li>



                <li><a href="{{ route('login') }}">Login</a></li>
                <li><a href="{{ route('register') }}">Register</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="hero">
            <h2>Selamat datang di {{ config('app.name', 'Laravel') }}</h2>
            <p>Website penyedia jasa receh – semua kebutuhan cepat, ada di sini!</p>
            <a href="{{ route('register') }}" class="btn">Mulai Sekarang</a>
        </section>
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}</p>
    </footer>
</body>

</html>