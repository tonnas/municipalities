<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        @include('inc.header')

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
