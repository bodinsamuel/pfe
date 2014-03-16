@extends('layouts.master')

@section('content')
<div class="_col _col_fixed2 _box _fullscreen">
    <div class="inner _full_form">
        <header>
            <h2>Déposer une annonce</h2>
        </header>
        <form method="POST" action="">

            <section id="_f_s_general">
                <fieldset>
                    <label for="_f_type">Type</label>
                    <select id="_f_type" name="type">
                        <option value="1">Sell</option>
                        <option value="2">Location</option>
                    </select>
                </fieldset>
                <fieldset>
                    <label for="_f_street_type">Street Type</label>
                    <select id="_f_street_type" name="street_type">
                        <option value="1">Avenue</option>
                        <option value="2">Street</option>
                    </select>
                </fieldset>
                <fieldset>
                    <label for="_f_street_number">Street Number</label>
                    <input id="_f_street_number" type="text" name="street_number" placeholder="" value="" />
                </fieldset>
                <fieldset>
                    <label for="_f_street_name">Street Name</label>
                    <input id="_f_street_name" type="text" name="street_name" placeholder="" value="" />
                </fieldset>
                <fieldset>
                    <label for="_f_other">Other</label>
                    <input id="_f_other" type="text" name="other" placeholder="" value="" />
                </fieldset>
                <fieldset>
                    <label for="_f_zipcode">Zipcode</label>
                    <input id="_f_zipcode" type="text" name="zipcode" placeholder="" value="" />
                </fieldset>
                <fieldset>
                    <label for="_f_city">City</label>
                    <input id="_f_city" type="" name="city" placeholder="" value="" />
                </fieldset>

                <footer class="action">
                    <input type="submit" value="Next" />
                </footer>
            </section>

            <section id="_f_s_sell">

                <footer class="action">
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
