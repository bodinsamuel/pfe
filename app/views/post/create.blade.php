@extends('layouts.master')

@section('content')
<div class="_col _col_fixed2 _box _fullscreen">
    <div class="inner _full_form">
        <header>
            <h2>Déposer une annonce</h2>
        </header>
        <form method="POST" action="/post/create">

            <section id="_f_s_general">
                <fieldset>
                    <label><input type="radio" name="id_post_type" id="_f_type1" checked="checked" value="1">Vente</label>
                    <label><input type="radio" name="id_post_type" id="_f_type2" value="2">Location</label>
                </fieldset>
                <fieldset class="{{{ $errors->has('address1') ? '_error' : '' }}}">
                    <label for="_f_address1">Adresse ligne 1</label>
                    <input id="_f_address1" type="text" name="address1" placeholder="Adresse ligne 1" value="{{{ Input::old('address1') }}}" />
                    {{ $errors->first('address1', '<span class="_msg _error">:message</span>') }}
                </fieldset>
                <fieldset>
                    <label for="_f_address2">Adresse ligne 2</label>
                    <input id="_f_address2" type="text" name="address2" placeholder="Adresse ligne 2" value="{{{ Input::old('address2') }}}" />
                </fieldset>
                <fieldset class="{{{ $errors->has('id_city') ? '_error' : '' }}}">
                    <label for="_f_city">Ville</label>
                    <input id="_f_city" type="text" name="city" placeholder="Ville" value="{{{ Input::old('city') }}}" />
                    {{ $errors->first('id_city', '<span class="_msg _error">:message</span>') }}
                </fieldset>
                <fieldset>
                    <label for="_f_content">Description</label>
                    <textarea id="_f_content" name="content" placeholder="Description"></textarea>
                </fieldset>

                <footer class="action">
                    <input type="submit" value="Next" />
                </footer>
            </section>

            <section id="_f_s_sell">

                <footer class="action">
                    <input type="hidden" name="id_city" value="30840" />
                    <input type="hidden" name="origin" value="post" />
                    <input type="hidden" name="primary" value="0" />
                    <input type="submit" value="One more step" />
                </footer>
            </section>

            <section id="_f_s_location">
                <footer class="action">
                    <input type="submit" value="One more step" />
                </footer>
            </section>

            <section id="_f_s_last">

                <footer class="action">
                    <input type="submit" value="Save" />
                </footer>
            </section>
        </form>
    </div>
</div>
@stop
