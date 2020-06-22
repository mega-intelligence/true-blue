<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset("favicon.png") }}">
    <title>{{ config('app.name', 'True Blue software') }}</title>

    <link rel="stylesheet" href="{{ mix("css/app.css") }}">
</head>
<body>
@include('_partials.menu')
@yield('content')
<script src="{{ mix("js/app.js") }}"></script>
</body>
</html>
