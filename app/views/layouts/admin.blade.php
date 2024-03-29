<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>    <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no' />
    <title>
        @if (isset($__page_title)) {{{ $__page_title }}} | @endif
        Admin
    </title>

    <link href="/assets/lib/css/normalize.min.css" rel="stylesheet"/>
    <link href="/assets/css/layouts/admin/base.css" rel="stylesheet"/>

    <script src="/assets/lib/jquery/jquery.1.11.js"></script>
    <!-- Font -->
    <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700,300italic' rel='stylesheet' type='text/css'>
</head>
<body id="__{{{ $__current_controller }}}_{{{ $__current_method }}}"
      class="_c_{{{ $__current_controller }}} _m_{{{ $__current_method }}} @if(isset($__body_class)) {{{ $__body_class }}}@endif">

    @include('layouts/admin/header')
    @include('layouts/admin/menu')
    <div id="container" class="_c @if (isset($_container_cls)) {{{ $_container_cls }}} @endif">
        <div id="content" class="_c @if (isset($_content_cls)) {{{ $_content_cls }}} @endif">
            @include('common/notice')

            @yield('content')

        </div>
    </div>

    @include('layouts/admin/footer')
</body>
</html>
