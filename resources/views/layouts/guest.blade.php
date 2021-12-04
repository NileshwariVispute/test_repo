<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>e-Study - Login</title>

    <!-- Custom fonts -->
    <link href="{{ asset('asset/fonts/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles -->
    <link href="{{ asset('asset/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/css/app.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script src="{{ asset('asset/js/jquery/jquery.min.js') }}" defer></script>
    <script src="{{ asset('asset/js/jquery-easing/jquery.easing.min.js') }}" defer></script>
    <script src="{{ asset('asset/js/bootstrap/js/bootstrap.bundle.min.js') }}" defer></script>
    <script src="{{ asset('asset/js/app.js') }}" defer></script>
</head>
<body class="bg-gradient-light">
    <div id="app">
        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
