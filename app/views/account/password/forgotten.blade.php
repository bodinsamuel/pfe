@extends('layouts.master')

@section('content')
    {{ Form::open(['action' => 'AccountController@post_ForgotPassword', 'method' => 'post']) }}

        <fieldset class="{{{ $errors->has('email') ? '_error' : '' }}}">
            {{ Form::label('email') }}
            {{ Form::text('email', Input::old('email'), ['placeholder' => 'john.doe@example.com']) }}
            {{ $errors->first('email', '<span class="_msg _error">:message</span>') }}
        </fieldset>

        <div class="action">
            {{ Form::submit('Reset password') }}
        </div>

    {{ Form::close() }}
@stop
