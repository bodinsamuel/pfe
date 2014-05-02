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
                            <i class="i_cursor _invrt"></i>
                        </div>
                        <span>Séléctionnez la ville sur la carte</span>
                    </li>
                    <li class="type_geo">
                        <div class="avatar">
                            <i class="i_geo _invrt"></i>
                        </div>
                        <span>Me géolocaliser</span>
                    </li>
                    <li class="type_classic">
                        <div class="avatar">
                            <i class="i_loop _invrt"></i>
                        </div>
                        <span>Faire une recherche</span>
                    </li>
                    <li class="type_search">
                        <form method="GET" action="/search">
                            <div><input type="hidden" id="main_search_input" placeholder="Adresse, ville, arrondissement.." name="q"  style="width:250px;height: 70px;" /></div>
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
