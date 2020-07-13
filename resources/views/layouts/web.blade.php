<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="sidebar-light sidebar-left-big-icons">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Facturación Electrónica</title>
    <!-- Scripts -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ asset('porto-light/vendor/bootstrap/css/bootstrap.css') }}" />
    <link rel="stylesheet" href="{{ asset('porto-light/vendor/animate/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('porto-light/vendor/font-awesome/css/fontawesome-all.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('porto-light/css/theme.css') }}" />
    <link rel="stylesheet" href="{{ asset('porto-light/css/custom.css') }}" />
    <link rel="stylesheet" href="{{asset('porto-light/vendor/jquery-loading/dist/jquery.loading.css')}}" />

    <style>
        .body-web {
            height: 100vh;
        }
        #main-wrapper {
            height: 100%;
        }
        .row {
            height: 100%;
            justify-content: center;
            align-items: center;
        }
        .card {
            margin: 0;
        }
    </style>
</head>
<body class="body-web">
    <div id="main-wrapper">
        <div class="row">
            <div class="col-md-6">
                @yield('content')
            </div>
        </div>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
