<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="css-path" content="{{ asset('css/') }}">

    <title>{{ config('app.name', 'True Blue software') }}</title>


    <link rel="icon" type="image/png" href="{{ asset("favicon.png") }}">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset("font/iconsmind-s/css/iconsminds.css") }}"/>
    <link rel="stylesheet" href="{{ asset("font/simple-line-icons/css/simple-line-icons.css") }}"/>


    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset("css/vendor/bootstrap.min.css") }}"/>
    <link rel="stylesheet" href="{{ asset("css/vendor/bootstrap.rtl.only.min.css") }}"/>
    <link rel="stylesheet" href="{{ asset("css/vendor/component-custom-switch.min.css") }}"/>
    <link rel="stylesheet" href="{{ asset("css/vendor/perfect-scrollbar.css") }}"/>

    <link rel="stylesheet" href="{{ asset("css/main.css") }}"/>
    <link rel="stylesheet" href="{{ mix("css/app.css") }}">
</head>

<body id="app-container" class="menu-default show-spinner">

@include('_partials.navigation.top-nav')

@include('_partials.menu.main-menu')

<main>
    <div class="container-fluid">

        @yield('content')
        {{--<div class="row">
            <div class="col-12">
                <h1>Blank Page</h1>
                <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
                    <ol class="breadcrumb pt-0">
                        <li class="breadcrumb-item">
                            <a href="#">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="#">Library</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Data</li>
                    </ol>
                </nav>
                <div class="separator mb-5"></div>
            </div>
        </div>--}}
    </div>
</main>

<footer class="page-footer">
    <div class="footer-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-sm-6">
                    <p class="mb-0 text-muted">ColoredStrategies 2019</p>
                </div>
                <div class="col-sm-6 d-none d-sm-block">
                    <ul class="breadcrumb pt-0 pr-0 float-right">
                        <li class="breadcrumb-item mb-0">
                            <a href="#" class="btn-link">Review</a>
                        </li>
                        <li class="breadcrumb-item mb-0">
                            <a href="#" class="btn-link">Purchase</a>
                        </li>
                        <li class="breadcrumb-item mb-0">
                            <a href="#" class="btn-link">Docs</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>


<script src="{{ asset("js/vendor/jquery-3.3.1.min.js") }}"></script>
<script src="{{ asset("js/vendor/bootstrap.bundle.min.js") }}"></script>
<script src="{{ asset("js/vendor/perfect-scrollbar.min.js") }}"></script>
<script src="{{ asset("js/vendor/mousetrap.min.js") }}"></script>
<script src="{{ asset("js/dore.script.js") }}"></script>
<script src="{{ asset("js/scripts.js") }}"></script>
</body>

</html>
