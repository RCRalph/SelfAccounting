<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include("layouts.head")

    <body>
        <div id="app"></div>

        @vite(["resources/ts/index.ts"])
    </body>
</html>
