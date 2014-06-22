@extends('layouts.master')

@section('content')
<div class="_col _col_fixed2 _box centered bg">
    <div class="inner _full_form">
        <header class="full">
            <h2>Déposer une annonce</h2>
        </header>
        <form method="POST" action="/post/create">
            <input type="hidden" name="address[id_city]" value="30840" />
            <input type="hidden" name="address[origin]" value="post" />
            <input type="hidden" name="address[primary]" value="0" />

            @if ($errors)
                <div class="message error">
                    Il y a {{{ count($errors) }}} erreurs
                </div>
            @endif
            <section id="_f_s_general">
                <fieldset class="inline">
                    <label><input type="radio" name="post[id_post_type]" id="_f_type1" checked="checked" value="1">Vente</label>
                    <label><input type="radio" name="post[id_post_type]" id="_f_type2" value="2">Location</label>
                </fieldset>
                <div class="_c">
                    <fieldset class="_col _col45 {{{ $errors->has('address1') ? '_error' : '' }}}">
                        <label for="_f_address1">Adresse ligne 1</label>
                        <input id="_f_address1" type="text" required name="address[address1]" placeholder="Adresse ligne 1" value="{{{ Input::old('address.address1') }}}" />
                        {{ $errors->first('address1', '<span class="_msg _error">:message</span>') }}
                    </fieldset>
                    <fieldset class="_col _col45">
                        <label for="_f_address2">Adresse ligne 2</label>
                        <input id="_f_address2" type="text"  name="address[address2]" placeholder="Adresse ligne 2" value="{{{ Input::old('address.address2') }}}" />
                    </fieldset>
                </div>
                <fieldset class="{{{ $errors->has('id_city') ? '_error' : '' }}}">
                    <label for="_f_city">Ville</label>
                    <input id="_f_city" type="text" name="city" required placeholder="Ville" value="{{{ Input::old('city') }}}" />
                    {{ $errors->first('id_city', '<span class="_msg _error">:message</span>') }}
                </fieldset>
            </section>

            <section id="_f_s_property" class="_c">
                <header class="full">
                    <h2>Votre bien</h2>
                </header>
                <fieldset>
                    <label>Type</label>
                    <select name="post[id_property_type]">
                        @foreach (\Custom\Post::$property_type AS $id_type => $name)
                            <option value="{{ $id_type }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </fieldset>
                <fieldset class="_col _col45">
                    <label for="_f_address2">Surface Habitable (m²)</label>
                    <input type="number" name="details[surface_living]" required value="{{{ Input::old('details.surface_living', 0) }}}" min="7" />
                </fieldset>
                <fieldset class="_col _col45">
                    <label for="_f_address2">Nombres de Pièces</label>
                    <input type="number" name="details[room]" value="{{{ Input::old('details.room', 0) }}}" required min="1" max="99" />
                </fieldset>
                <fieldset>
                    <label for="_f_content">Description</label>
                    <textarea id="_f_content" name="post[content]" required placeholder="Description">{{{ Input::old('post.content') }}}</textarea>
                </fieldset>
            </section>

<!--             <section id="_f_s_sell">
                <header class="full">
                    <h2>Vente</h2>
                </header>
                <fieldset class="">
                    <label for="_f_address2">Prix</label>
                    <input type="number" name="price[value]" value="{{{ Input::old('price.value', 0) }}}" required min="1" />
                </fieldset>
            </section> -->

            <section id="_f_s_location" class="_c">
                <header class="full">
                    <h2>Location</h2>
                </header>
                <fieldset class="_col _col45">
                    <label for="_f_address2">Prix</label>
                    <input type="number" name="price[value]" value="{{{ Input::old('price.value', 0) }}}" required min="1" />
                </fieldset>
                <fieldset class="_col _col45 inline">
                    Charges incluses<br>
                    <label><input type="radio" name="price[type]" value="1" required />Oui</label>
                    <label><input type="radio" name="price[type]" value="2" required />Non</label>
                </fieldset>
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
