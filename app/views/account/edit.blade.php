@extends('layouts.master', ['__body_class' => 'fixed'])

@section('content')
    @include('account/inc/menu')

    <section  class="_col _col5">
        <h3>Informations</h3>
        <form method="POST" action="/account/edit">
            {{ Form::token() }}

            <fieldset class="{{{ $errors->has('email') ? '_error' : '' }}}">
                <label>Email</label>
                <input type="text" name="email" value="{{ \Custom\Account::user()->email }}" />
            </fieldset>

            <fieldset class="{{{ $errors->has('last_name') ? '_error' : '' }}}">
                <label>Last Name</label>
                <input type="text" name="last_name" value="{{ \Custom\Account::user()->last_name }}" />
            </fieldset>

            <fieldset class="{{{ $errors->has('first_name') ? '_error' : '' }}}">
                <label>First Name</label>
                <input type="text" name="first_name" value="{{ \Custom\Account::user()->first_name }}" />
            </fieldset>

            <fieldset class="{{{ $errors->has('phone_mobile') ? '_error' : '' }}}">
                <label>Mobile Phone Number</label>
                <input type="text" name="phone_mobile" value="{{ \Custom\Account::user()->phone_mobile }}" />
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

            <fieldset class="{{{ $errors->has('new_pwd_confirm') ? '_error' : '' }}}">
                <label>Confirmation</label>
                <input type="text" name="new_pwd_confirm" value="" />
            </fieldset>

            <fieldset>
                <input type="submit" value="Save" />
            </fieldset>
        </form>
    </section>
@stop
