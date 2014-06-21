<h2 class="_col _col1">Account</h2>
<ul class="onglets _c">
    <li class="@if ($__current_method == 'get') selected @endif">
        <a href="/account/" >Home</a>
    </li>
    <li class="@if ($__current_method == 'getSearch') selected @endif">
        <a href="/account/search">Search</a>
    </li>
    <li class="@if ($__current_method == 'getEdit') selected @endif">
        <a href="/account/edit">Edit</a>
    </li>
    <li class="@if ($__current_method == 'getAddress') selected @endif">
        <a href="/account/address">Addresses</a>
    </li>
    <li class="@if ($__current_method == 'getAlert') selected @endif">
        <a href="/account/alert">Alerts</a>
    </li>
    <li class="@if ($__current_method == 'getFavorite') selected @endif">
        <a href="/account/favorite">Favorites</a>
    </li>
</ul>
