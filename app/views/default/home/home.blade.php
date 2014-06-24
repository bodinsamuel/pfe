@extends('layouts.master', ['_content_cls' => 'centered'])

@section('handlebars')
    <script type="text/x-handlebars" id="index">
    <div id="search_main_box" class="_col _col_fixed15 _box _fullscreen centered">
        <div class="inner">
            <header class="full">
                <h2>Je trouve mon logement</h2>
            </header>
            <section id="search_type_selection">
                <ul class="">
                    <li class="type_search">
                        <form method="GET" action="/search/">
                            <div class="wrapper">
                                <input type="text" id="main_search_input" class="transparent typeahead"
                                       placeholder="Adresse, ville, arrondissement.." />
                            </div>
                            <input id="main_search_input_real" type="hidden" value="" name="" />
                            <input type="submit" value="go" disabled />
                        </form>
                    </li>
                </ul>
            </section>
            <footer>

            </footer>
        </div>
    </div>
    </script>
@stop
