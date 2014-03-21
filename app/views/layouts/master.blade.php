<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no' />
    <title>
        @if (isset($__page_title)) {{{ $__page_title }}} | @endif
        {{{ $__meta_title }}}
    </title>
    {{ HTML::style('assets/lib/css/normalize.min.css') }}
    {{ HTML::style('assets/css/layouts/master.css') }}
    <!-- Temporaire -->
    {{ HTML::style('assets/css/modules/account/main.css') }}
    {{ HTML::style('assets/css/modules/home/main.css') }}

    <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700,300italic' rel='stylesheet' type='text/css'>
</head>
<body id="__{{{ $__current_controller }}}_{{{ $__current_method }}}"
      class="_c_{{{ $__current_controller }}} _m_{{{ $__current_method }}}@if(isset($__body_class)) {{{ $__body_class }}}@endif">

    @include('layouts/master/header')

    <div id="container" class="_c">
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
