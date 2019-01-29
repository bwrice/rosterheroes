<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">

    <!-- Material Design Icon -->
    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans:100,100i,300,300i,400,400i,600,600i,700,700i" rel="stylesheet">
    {{--<link href="https://fonts.googleapis.com/css?family=Acme" rel="stylesheet">--}}
    {{--<link href="https://fonts.googleapis.com/css?family=Cabin:400,400i,500,500i,600,600i,700,700i" rel="stylesheet">--}}
    {{--<link href="https://fonts.googleapis.com/css?family=Cuprum:400,400i,700,700i" rel="stylesheet">--}}
    {{--<link href="https://fonts.googleapis.com/css?family=Hammersmith+One" rel="stylesheet">--}}
    {{--<link href="https://fonts.googleapis.com/css?family=Lato:100,100i,300,300i,400,400i,700,700i,900,900i" rel="stylesheet">--}}
    <link href="https://fonts.googleapis.com/css?family=Philosopher:400,400i,700,700i" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900|Material+Icons' rel="stylesheet">
</head>
<body>
    <div id="app">
        @yield('content')
    </div>
</body>

<!-- Scripts -->
<script src="{{ mix('/js/create-squad.js') }}"></script>
</html>
