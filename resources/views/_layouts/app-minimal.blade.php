<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'True Blue software') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <link rel="icon" type="image/png" href="{{ asset("favicon.png") }}">
    <meta name="css-path" content="{{ asset('css/') }}">


    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset("font/iconsmind-s/css/iconsminds.css") }}"/>
    <link rel="stylesheet" href="{{ asset("font/simple-line-icons/css/simple-line-icons.css") }}"/>

    <link rel="stylesheet" href="{{ asset("css/vendor/bootstrap.min.css") }}"/>
    <link rel="stylesheet" href="{{ asset("css/vendor/bootstrap.rtl.only.min.css") }}"/>
    <link rel="stylesheet" href="{{ asset("css/vendor/bootstrap-float-label.min.css") }}"/>
    <link rel="stylesheet" href="{{ asset("css/main.css") }}"/>
    <link rel="stylesheet" href="{{ mix("css/app.css") }}">
</head>

<body class="background show-spinner no-footer">
<div class="fixed-background"></div>
<main>
    <div class="container">
        <div class="row h-100">
            <div class="col-12 col-md-10 mx-auto my-auto">
                <div class="card auth-card">
                    <div class="position-relative image-side ">

                        <p class=" text-white h2">@yield('title')</p>

                        <p class="white mb-0">
                            @yield('description')
                        </p>
                    </div>
                    <div class="form-side">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script src="{{ asset("js/vendor/jquery-3.3.1.min.js") }}"></script>
<script src="{{ asset("js/vendor/bootstrap.bundle.min.js") }}"></script>
<script src="{{ asset("js/dore.script.js") }}"></script>
<script src="{{ asset("js/scripts.js") }}"></script>
</body>

</html>
