<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width,initial-scale=1"/>
    <title>
        @if (isset($__page_title)) {{{ $__page_title }}} | @endif
        {{{ $__meta_title }}}
    </title>
    {{ HTML::style('assets/css/layouts/master.css') }}
</head>
<body id="__{{{ $__current_controller }}}_{{{ $__current_method }}}"
      class="_c_{{{ $__current_controller }}} _m_{{{ $__current_method }}}">
    <header id="header">
        <nav>
            <a href="/">Home</a>
        </nav>
    </header>
    <div id="container">
        <div id="content">
            @yield('content')
        </div>
    </div>
    <footer id="footer">
        <div class="inner">
            © 2014 -
        </div>
    </footer>
</body>
</html>
