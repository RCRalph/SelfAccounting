<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" href="{{ URL::mix('favicon.ico') }}" type="image/x-icon"/>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

        <!-- Styles -->
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    </head>

    <body class="{{ ($pageData["darkmode"] ?? true) ? "" : "lightmode" }}">
        <div id="app">
            @include('layouts.navbar', compact("pageData"))

            <main class="py-4">
                @yield('wrapper')
            </main>
        </div>

        <script src="{{ mix('js/app.js') }}" defer></script>
        @yield('script')
    </body>
</html>
