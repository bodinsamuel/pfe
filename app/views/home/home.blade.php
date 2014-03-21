@extends('layouts.master')

@section('content')

    <div id="search_main_box" class="_col _col_fixed1 _box _fullscreen">
        <div class="inner">
            <header>
                <h2>Trouvez votre logement</h2>
            </header>
            <section id="search_type_selection">
                <ul class="">
                    <li class="type_select">
                        <div class="avatar">
                            <i class="icon_cursor"></i>
                        </div>
                        <span>Séléctionnez votre ville</span>
                    </li>
                    <li class="type_geo">
                        <div class="avatar">
                            <i class="icon_geo"></i>
                        </div>
                        <span>Me géolocaliser</span>
                    </li>
                    <li class="type_classic">
                        <div class="avatar">
                            <i class="icon_loop"></i>
                        </div>
                        <span>Faire une recherche</span>
                    </li>
                </ul>
            </section>
            <footer>

            </footer>
        </div>
    </div>
@stop
