@extends('layouts.master', ['_content_cls' => '_centered'])

@section('content')

    <div id="search_main_box" class="_col _col_fixed15 _box _fullscreen _centered">
        <div class="inner">
            <header>
                <h2>Je trouve mon logement</h2>
            </header>
            <section id="search_type_selection">
                <ul class="">
                    <li class="type_search">
                        <form method="GET" action="/search/">
                            <div class="select2-wrapper">
                                <input type="hidden" id="main_search_input"
                                       placeholder="Adresse, ville, arrondissement.." />
                            </div>
                            <input id="main_search_input_states" type="hidden" value="" name="states" />
                            <input id="main_search_input_provinces" type="hidden" value="" name="provinces" />
                            <input id="main_search_input_cities" type="hidden" value="" name="cities" />
                            <input type="submit" value="go" />
                        </form>
                    </li>
                </ul>
            </section>
            <footer>

            </footer>
        </div>
    </div>
@stop
