<div id="main-menu">
    <div class="user-box">
        <div class="avatar rounded">
            <img src="/assets/img/admin/avatar.jpg" alt="" />
        </div>
        <div class="_flr">
            <p>{{ \Session::get('acl_name') }}</p>
            <p>{{ \Custom\Account::user()->email }}</p>
        </div>
    </div>

    <div>
        <a href="/">Dashboard</a>
        <a href="{{{ Config::get('app.url') }}}">Back to site</a>
    </div>
    <div>
        <h2>Posts</h2>
        <ul>
            <li>
                <a href="/posts/list">List</a>
            </li>
            <li>
                <a href="/posts/pending">Pending Validation</a>
            </li>
        </ul>
    </div>
</div>
