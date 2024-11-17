<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@php
    use Illuminate\Support\Facades\DB;
    $shortcut = DB::table('seo')->where('key', 'shortcut')->first();
@endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{$shortcut->value ?? '' }}">
    <link rel="preload" href="{{asset('assets/fonts/quicksand-v8-vietnamese_latin-ext_latin-regular.woff2')}}" as="font" type="font/woff" crossorigin>
    <link rel="preload" href="{{asset('assets/fonts/fontawesome-webfont.woff2')}}" as="font" type="font/woff" crossorigin>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/style.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/dark_style.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/font-awesome.min.css')}}">
    @stack('header')
    @yield('metadata')
    @yield('styles')
    <script>
        var urlSearch = '{{route('search')}}';
        var csrf_token = '{{ csrf_token() }}';
        var urlRegister = '{{route('auth-register')}}';
        var urlLogin = '{{route('auth-login')}}';
    </script>
</head>

<body class="@yield('class')">
    @yield('main_content')
    <script src="{{asset('assets/js/jquery.min.js')}}"></script>
    <script src="{{asset('assets/js/jquery.lazy.min.js')}}"></script>
    <script src="{{asset('assets/js/readmore.min.js')}}"></script>
    <script src="{{asset('assets/js/main.js')}}"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js" async></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lozad.js/1.0.8/lozad.min.js"></script>
    <script>
        const observer = lozad();
        observer.observe();
    </script>
    @yield('scripts')
</body>

</html>
