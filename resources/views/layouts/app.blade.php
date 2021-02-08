<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Plateful') }}</title>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="{{ mix('js/app.js') }}" defer></script>
    <!--<script src="{{ asset('js/app.js') }}" defer></script>-->



    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">

    <!-- Styles -->

    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <!--<link href="{{ asset('css/app.css') }}" rel="stylesheet">-->
</head>
<body >
    @yield('content')
</body>
</html>
