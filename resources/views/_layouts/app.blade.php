<!DOCTYPE html>
<html class="h-full" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset("favicon.png") }}">
    <title>{{ config('app.name', 'True Blue software') }}</title>

    <link rel="stylesheet" href="{{ mix("css/app.css") }}">
</head>
<body class="h-full flex items-stretch">
<nav class="bg-blue-200 p-5 w-1/8 shadow-xl text-sm">
    <div class="logo pb-3">
        <a class="" href="{{ url('/') }}">
            <span class="logo-text">True <span class="true-blue">Blue</span></span>
        </a>
    </div>
    <hr class="border-gray-700">
    @auth
        <form class="py-3" action="{{ route('logout') }}">
            @csrf
            <button class="btn">@lang('gui.logout')</button>
        </form>
        <hr class="border-gray-700">
    @endauth
    <ul class="list-none flex flex-col">
        @include('_partials.menu')
    </ul>
</nav>
<main class="bg-orange-200 p-5 flex-auto">
    <header class="flex justify-between border-b border-gray-700 py-3 mb-3 bg-blue-200">
        <h1 class="font-bold">@yield('title')</h1>
        <div>
            @yield('actions')
        </div>
    </header>
    <main class="">
        @yield('content')
    </main>
</main>
<script src="{{ mix("js/app.js") }}"></script>
</body>
</html>
