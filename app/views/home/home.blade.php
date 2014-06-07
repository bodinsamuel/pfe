@extends('layouts.master')

@section('content')

    <div id="search_main_box" class="_col _col_fixed1 _box _fullscreen">
        <div class="inner">
            <header>
                <h2>Je trouve mon logement</h2>
            </header>
            <section id="search_type_selection">
                <ul class="">
                    <li class="type_search">
                        <form method="GET" action="/search">
                            <div><input type="hidden" id="main_search_input" placeholder="Adresse, ville, arrondissement.." name="zipcode"  style="width:250px;height: 70px;" /></div>
                            <input type="submit" value="go" style="float: right;" />
                        </form>
                    </li>
                </ul>
            </section>
            <footer>

            </footer>
        </div>
    </div>
@stop
