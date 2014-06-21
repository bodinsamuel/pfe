@extends('layouts.master', ['__body_class' => 'fixed'])

@section('content')
    @include('default/account/inc/menu')

    <section  class="_col _col5">
        <h3>Informations</h3>
        <form method="POST" action="/account/edit">
            {{ Form::token() }}

            <fieldset class="{{{ $errors->has('email') ? '_error' : '' }}}">
                <label>Email</label>
                <input type="text" name="email" value="@if(Input::old('email')){{{ Input::old('email') }}}@else{{ \Custom\Account::user()->email }}@endif" />
            </fieldset>

            <fieldset class="{{{ $errors->has('last_name') ? '_error' : '' }}}">
                <label>Last Name</label>
                <input type="text" name="last_name" value="@if(Input::old('last_name')){{{ Input::old('last_name') }}}@else{{ \Custom\Account::user()->last_name }}@endif" />
            </fieldset>

            <fieldset class="{{{ $errors->has('first_name') ? '_error' : '' }}}">
                <label>First Name</label>
                <input type="text" name="first_name" value="@if(Input::old('first_name')){{{ Input::old('first_name') }}}@else{{ \Custom\Account::user()->first_name }}@endif" />
            </fieldset>

            <fieldset class="{{{ $errors->has('phone_mobile') ? '_error' : '' }}}">
                <label>Mobile Phone Number</label>
                <input type="text" name="phone_mobile" value="@if(Input::old('phone_mobile')){{{ Input::old('phone_mobile') }}}@else{{ \Custom\Account::user()->phone_mobile }}@endif" />
            </fieldset>

            <fieldset>
                <input type="submit" value="Save" />
            </fieldset>
        </form>
    </section>

    <section  class="_col _col5">
        <h3>Password</h3>
        <form method="POST" action="/account/changepassword">
            {{ Form::token() }}

            <fieldset class="{{{ $errors->has('current_pwd') ? '_error' : '' }}}">
                <label>Current Password</label>
                <input type="text" name="current_pwd" value="" />
            </fieldset>

            <fieldset class="{{{ $errors->has('new_pwd') ? '_error' : '' }}}">
                <label>New password</label>
                <input type="text" name="new_pwd" value="" />
            </fieldset>

            <fieldset class="{{{ $errors->has('new_pwd_confirmation') ? '_error' : '' }}}">
                <label>Confirmation</label>
                <input type="text" name="new_pwd_confirmation" value="" />
            </fieldset>

            <fieldset>
                <input type="submit" value="Save" />
            </fieldset>
        </form>
    </section>
@stop
