<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no' />
    <title>
        @if (isset($__page_title)) {{{ $__page_title }}} | @endif
        {{{ $__meta_title }}}
    </title>
    <link href="/assets/lib/css/normalize.min.css" rel="stylesheet"/>
    <link href="/assets/css/layouts/master/base.css" rel="stylesheet"/>

    <!-- Temporaire -->
    <link href="/assets/css/modules/account/main.css" rel="stylesheet"/>
    <link href="/assets/css/modules/home/main.css" rel="stylesheet"/>

    <!-- LIB -->
    <script src="/assets/lib/jquery/jquery.1.11.js"></script>
    <link href="/assets/lib/select2/select2.css" rel="stylesheet"/>
    <script src="/assets/lib/select2/select2.js"></script>

    <!-- JS -->
    <script src="/assets/js/main.js"></script>

    <!-- Font -->
    <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700,300italic' rel='stylesheet' type='text/css'>
</head>
<body id="__{{{ $__current_controller }}}_{{{ $__current_method }}}"
      class="_c_{{{ $__current_controller }}} _m_{{{ $__current_method }}} @if(isset($__body_class)) {{{ $__body_class }}}@endif">

    @include('layouts/master/header')

    <div id="container" class="_c @if (isset($_container_cls)) {{{ $_container_cls }}} @endif">
        <div id="content">
            @include('common/notice')

            @yield('content')
        </div>
    </div>

    @if (isset($__with_bg_map))
        @include('map/main', ['map_config' => $map_config])
    @endif

    @include('layouts/master/footer')

</body>
</html>
