<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include("layouts.head")

    <body>
        <div id="app">
            <app-component></app-component>
        </div>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>

        <script src="{{ mix('js/app.js') }}"></script>
    </body>
</html>
