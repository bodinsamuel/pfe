@extends('layouts.master')

@section('content')
    {{ Form::open(['action' => 'AccountController@post_Login', 'method' => 'post']) }}

        <fieldset class="{{{ $errors->has('email') ? '_error' : '' }}}">
            {{ Form::label('email') }}
            {{ Form::text('email', Input::old('email'), ['placeholder' => 'john.doe@example.com']) }}
            {{ $errors->first('email', '<span class="_msg _error">:message</span>') }}
        </fieldset>

        <fieldset class="{{{ $errors->has('password') ? '_error' : '' }}}">
            {{ Form::label('password') }}
            {{ Form::password('password', ['placeholder' => '*********']) }}
            {{ $errors->first('password', '<span class="_msg _error">:message</span>') }}
        </fieldset>

        {{ HTML::linkRoute('account_forgot_password', 'Forgot password ?' ) }}

        <div class="action">
            {{ Form::submit('Login') }}
        </div>

    {{ Form::close() }}
@stop
