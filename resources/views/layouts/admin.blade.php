<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="UTF-8">
    <title>{{ env('APP_NAME') }}</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="manifest" href="/manifest.json">

    <link rel="stylesheet" href="/css/adminlte/AdminLTE.min.css"/>
    <link rel="stylesheet" href="/css/adminlte/skins/skin-blue.min.css"/>
    <link rel="stylesheet" href="{{ mix('css/admin.css') }}"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.12/datatables.min.css"/>


    <meta name="theme-color" content="#d400ff">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('head')
</head>


<body class="hold-transition skin-blue "> {{--layout-boxed--}}
<div class="wrapper" id="app">
    @include('layouts.components.admin.header')
    @include('layouts.components.admin.navigation')
    <div class="content-wrapper p-1">
        @include('layouts.components.admin.sessionMessages')
        @yield('content')
    </div>
    @include('layouts.components.admin.footer')
    @stack('aside')
</div>

<!-- ./wrapper -->
<script src="{{ asset('js/app.js', (env('APP_ENV') != 'production' ? false : true ) )}}"></script>
@stack('scripts-footer')
</body>
