@extends('layouts.master')

@section('content')
    <form action="{{ URL::route('password_reset', ['token' => Input::get('token'), 'email' => Input::get('email')]) }}" method="POST">

        {{ Form::token() }}

        <fieldset class="{{{ $errors->has('password') ? '_error' : '' }}}">
            {{ Form::label('password') }}
            {{ Form::password('password', ['placeholder' => '*********']) }}
            {{ $errors->first('password', '<span class="_msg _error">:message</span>') }}
        </fieldset>

        <fieldset class="{{{ $errors->has('password_confirmation') ? '_error' : '' }}}">
            {{ Form::label('password_confirmation') }}
            {{ Form::password('password_confirmation', ['placeholder' => '*********']) }}
            {{ $errors->first('password_confirmation', '<span class="_msg _error">:message</span>') }}
        </fieldset>

        <div class="action">
            {{ Form::hidden('token', Input::get('token')) }}
            {{ Form::hidden('email', Input::get('email')) }}
            {{ Form::submit('Reset password') }}
        </div>

    </form>
@stop
