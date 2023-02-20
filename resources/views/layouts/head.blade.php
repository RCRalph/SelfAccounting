<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Title -->
    <title>{{ config('app.name', 'SelfAccounting') }}</title>

    <!-- Icons -->
    <link rel="icon" href="{{ URL::asset('favicon.ico') }}" sizes="any">
    <link rel="icon" href="{{ URL::asset('icon.svg') }}" type="image/svg+xml">
    <link rel="apple-touch-icon" href="{{ URL::asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="/manifest.webmanifest">

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
