<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" href="{{ URL::asset('favicon.ico') }}" type="image/x-icon"/>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>

    <body style="background-color: {{ ($pageData["darkmode"] ?? true)  ? 'hsl(210, 60%, 2%)' : 'hsl(210, 40%, 98%)' }};">
        <div id="darkmode-status" style="display: none;">{{ $pageData["darkmode"] ?? true }}</div>
        <div id="app">
            @include('layouts.navbar', compact("pageData"))

            <main class="py-4">
                @yield('wrapper')
            </main>
        </div>

        <script src="{{ asset('js/app.js') }}" defer></script>
        @yield('script')
    </body>
</html>
