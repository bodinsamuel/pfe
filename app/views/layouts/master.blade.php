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
    <link href="/assets/css/default/account/main.css" rel="stylesheet"/>
    <link href="/assets/css/default/home/main.css" rel="stylesheet"/>
    <link href="/assets/css/default/search/search.css" rel="stylesheet"/>
    <link href="/assets/css/default/map.css" rel="stylesheet"/>

    <!-- LIB -->
    <script src="/assets/lib/jquery/jquery.1.11.js"></script>
    <script src="/assets/lib/ember/handlebars-1.3.0.js"></script>
    <script src="/assets/lib/ember/ember-1.5.1.js"></script>
    <script src="/assets/lib/typeahead/typeahead.min.js"></script>
    <link href="/assets/lib/typeahead/typeahead.css" rel="stylesheet"/>

    <script src="/assets/js/map.js"></script>

    <!-- Font -->
    <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700,300italic' rel='stylesheet' type='text/css'>
</head>
<body id="@if(isset($__current_controller))__{{{ $__current_controller }}}_{{{ $__current_method }}}@endif"
      class="@if(isset($__current_controller))_c_{{{ $__current_controller }}} _m_{{{ $__current_method }}}@endif @if(isset($__body_class)) {{{ $__body_class }}}@endif">

    @include('layouts/master/header')

    <div id="container" class="_c @if (isset($_container_cls)) {{{ $_container_cls }}} @endif">
        <div id="content" class="_c @if (isset($_content_cls)) {{{ $_content_cls }}} @endif">
            @include('common/notice')

            @yield('content')
        </div>
    </div>

    @yield('handlebars')
    @if (isset($__map))
        @include('default/map/main')
    @endif


    @include('layouts/master/footer')


    <!-- JS -->
    <script src="/assets/js/application.js"></script>
    <script src="/assets/js/router.js"></script>

    <!-- EMBER -->
    <script src="/assets/js/views/index.js"></script>
</body>
</html>
