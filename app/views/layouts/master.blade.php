<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width,initial-scale=1"/>
    <title>
        @if (isset($__page_title)) {{{ $__page_title }}} | @endif
        {{{ $__meta_title }}}
    </title>
    {{ HTML::style('assets/lib/css/normalize.min.css') }}
    {{ HTML::style('assets/css/layouts/master.css') }}
    <!-- Temporaire -->
    {{ HTML::style('assets/css/modules/account/main.css') }}
</head>
<body id="__{{{ $__current_controller }}}_{{{ $__current_method }}}"
      class="_c_{{{ $__current_controller }}} _m_{{{ $__current_method }}}@if(isset($__body_class)) {{{ $__body_class }}}@endif">

    <header id="header">

        <div class="inner">
            <div class="_fll">
                <div id="logo" class="_fll">
                    <a href="/" title="Homepage">
                        <i class="_32 logo_self"></i>
                        <h1>Loge'ici</h1>
                    </a>
                </div>
                @include('common/user_box', ['_class' => '_fll'])
            </div>

            <div class="_flr menus">
                <nav class="_fll">
                    <a href="/post/create">Poster une Annonce</a>
                </nav>

                <div class="_fll social">
                    <ul>
                        <li><a href="http://twitter.com/logeici"><i class="_32 icon_twitter"></i></a></li>
                        <li><a href="http://facebook.com/logeici"><i class="_32 icon_facebook"></i></a></li>
                    </ul>
                </div>

                <div class="_fll search">
                    <form>
                        <!-- <input type="text" name="q" value="" placeholder="Search" /> -->
                        <input type="submit" value="" />
                    </form>
                </div>
            </div>
        </div>

    </header>

    <div id="container" class="_c">
        <div id="content">
            @include('common/notice')

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
