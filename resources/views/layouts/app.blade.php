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
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">

        <!-- Web Manifest -->
        <link rel="manifest" href="manifest.json">
    </head>

    <body class="{{ ($pageData["darkmode"] ?? true) ? "" : "lightmode" }}">
        <div id="tutorial-modal">
            <div class="tutorial-wrapper">
                <div class="tutorial-content">
                    {!! Markdown::parse(Illuminate\Support\Facades\Storage::disk("local")->get("files/tutorial.md")) !!}
                </div>

                <hr class="hr">

                <div class="buttons">
                    <div id="tutorial-never-show" class="button-stop-tutorials">Don't show me any tutorials</div>
                    <div id="tutorial-stop-showing" class="button-stop-showing">Don't show again</div>
                    <div id="tutorial-close" class="button-close">Close</div>
                </div>
            </div>
        </div>

        <div id="app">
            @include('layouts.navbar', compact("pageData"))

            <main class="py-4">
                @yield('wrapper')
            </main>
        </div>

        <script>
            const SERVER_DATA = {
                user_id: {{ json_encode($pageData["id"]) }},
                hide_all_tutorials: {{ json_encode($pageData["hide_all_tutorials"]) }}
            };
        </script>

        <script src="{{ mix('js/app.js') }}" defer></script>
        @yield('script')
    </body>
</html>
