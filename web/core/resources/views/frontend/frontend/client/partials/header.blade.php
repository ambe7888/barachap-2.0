<!DOCTYPE html>
<html class="no-js" lang="{{get_default_language()}}" dir="{{get_default_language_direction()}}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>

        @if(get_static_option('site_title'))
            {{ get_static_option('site_title') }}
        @else
            @yield('site-title')
        @endif
    </title>
    <!-- favicon -->
    @php $site_favicon = get_attachment_image_by_id(get_static_option('site_favicon'),"full", false); @endphp
    @if(!empty($site_favicon))
        <link rel="icon" href="{{$site_favicon['img_url']}}" sizes="16x16" type="image/x-icon">
    @endif
    <!--Plugin file-->
    <link rel="stylesheet" href="{{asset('/assets/frontend/frontend/css/plugin.css')}}">
    <!-- Style Css File -->
    <link rel="stylesheet" href="{{asset('/assets/frontend/frontend/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('/assets/frontend/frontend/css/responsive.css')}}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('assets/frontend/css/animate.css')}}">
    <link rel="stylesheet" href="{{asset('assets/common/css/bootstrap.min.css')}}">
    {{-- <link rel="stylesheet" href="{{asset('assets/frontend/css/fontawesome.min.css')}}"> --}}
    {{-- <link rel="stylesheet" href="{{asset('assets/frontend/css/fontawesome-iconpicker.min.css')}}"> --}}
    <link rel="stylesheet" href="{{asset('assets/frontend/css/icon.css')}}">
    <link rel="stylesheet" href="{{asset('assets/frontend/css/slick.css')}}">
    <link rel="stylesheet" href="{{asset('assets/frontend/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/frontend/css/sweetalert.css')}}">
    <link rel="stylesheet" href="{{asset('assets/frontend/css/flatpickr.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/frontend/css/dashboard.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/common/css/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/telInput-plugin.css') }}">
    <link rel="stylesheet" href="{{asset('assets/frontend/css/custom-style.css')}}">
    @yield('style')
    @if(get_user_lang_direction() == 'rtl')
        <link rel="stylesheet" href="{{asset('assets/frontend/css/rtl.css')}}">
    @endif
</head>
