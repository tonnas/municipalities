<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        @include('inc.header')

        @section('meta')
            <title>Vyhľadávanie obcí</title>
            <meta name="description" content="Vyhľadávanie v databáze obcí"/>
            <meta property="og:site_name" content="Databaza obci">
            <meta property="og:url" content="{{ route('index') }}">
            <meta property="og:type" content="website">
            <meta property="og:title" content="Vyhľadávanie obcí">
            <meta property="og:description" content="Vyhľadávanie v databáze obcí">
            <meta property="og:image" content="{{ route('index') }}/logo.png">
        @stop

        @yield('styles')
    </head>
    <body>
        <nav class="navbar">
            @include('inc.navbar')
        </nav>
        <div class="main container">

            <div class="row">
                @yield('content')
            </div>

        </div>
        <footer class="footer">
            @include('inc.footer')
        </footer>
    </body>
</html>
