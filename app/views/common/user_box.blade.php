<div id="__box_user" class="@if ($_class){{ $_class }}@endif">
    <ul>
        @if(Auth::check())
            <li>{{ HTML::linkRoute('account', Auth::user()->email ) }}</li>
            <li>{{ HTML::linkRoute('logout', 'Logout') }}</li>
        @else
            <li>{{ HTML::linkRoute('login', 'Login') }}</li>
        @endif
    </ul>
</div>
