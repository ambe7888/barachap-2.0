<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
  
    <link rel="shortcut icon" href="assets/frontend/img/favicon.png" type="image/x-icon">
    
    <!--Plugin file-->
    <link rel="stylesheet" href="./assets/frontend/css/plugin.css">
    <!-- Style Css File -->
    <link rel="stylesheet" href="./assets/frontend/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('assets/backend/css/animate.css')}}">
    <link rel="stylesheet" href="{{asset('assets/common/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/backend/css/icon.css')}}">
    <link rel="stylesheet" href="{{asset('assets/backend/css/slick.css')}}">
    <link rel="stylesheet" href="{{asset('assets/backend/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/backend/css/sweetalert.css')}}">
    <link rel="stylesheet" href="{{asset('assets/backend/css/flatpickr.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/backend/css/niceCountryInput.css')}}">
    <link rel="stylesheet" href="{{asset('assets/backend/css/jsuites.css')}}">
    <link rel="stylesheet" href="{{asset('assets/backend/css/fancybox.css')}}">
    <link rel="stylesheet" href="{{asset('assets/backend/css/dashboard.css')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
</head>
<body>
    @yield('content')

    {{-- All JS Scripts --}}
    <script src="{{ asset('assets/common/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/common/js/jquery-migrate-3.4.1.min.js') }}"></script>
    <script src="{{ asset('assets/common/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/backend/js/slick.js') }}"></script>
    <script src="{{ asset('assets/backend/js/plugin.js') }}"></script>
    <script src="{{ asset('assets/backend/js/niceCountryInput.js') }}"></script>
    <script src="{{ asset('assets/backend/js/jsuites.js') }}"></script>
    <script src="{{ asset('assets/backend/js/fancybox.umd.js') }}"></script>
    <script src="{{ asset('assets/backend/js/map/raphael.min.js') }}"></script>
    <script src="{{ asset('assets/backend/js/map/jquery.mapael.js') }}"></script>
    <script src="{{ asset('assets/backend/js/map/world_countries.js') }}"></script>
    <script src="{{ asset('assets/backend/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/plugin.js') }}"></script>
    {{-- <script src="{{ asset('assets/frontend/js/main.js') }}"></script> --}}
    <script src="{{ asset('assets/backend/js/main.js') }}"></script>

    {{-- Custom Page Scripts --}}
    @yield('script')
</body>

</html>
