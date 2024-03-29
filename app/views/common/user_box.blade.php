<div id="__box_user" class="@if ($_class){{ $_class }}@endif">
    <ul>
        @if(Custom\Account::check())
            <li><a href="/account/">{{ Custom\Account::user()->email }}</a></li>
            @if (Custom\Acl::isAtLeast('root'))
                <li><a href="http://{{{ Config::get('app.domain.admin') }}}/">admin</a></li>
            @endif
            <li>{{ HTML::linkRoute('logout', 'Logout') }}</li>
        @else
            <li>{{ HTML::linkRoute('login', 'Login') }}</li>
        @endif
    </ul>
</div>
