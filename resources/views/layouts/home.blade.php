<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include("layouts.head")

    <body>
        <div id="app">
            @yield("content")
        </div>

        <script src="{{ mix('js/home.js') }}"></script>
    </body>
</html>
