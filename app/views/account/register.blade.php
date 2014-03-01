@extends('layouts.master')

@section('content')
    {{ Form::open(array('action' => 'AccountController@post_Register', 'method' => 'post')) }}

        <fieldset class="{{{ $errors->has('email') ? '_error' : '' }}}">
            {{ Form::label('email', 'E-Mail') }}
            {{ Form::text('email', Input::old('email'), array('placeholder' => 'john.doe@example.com')) }}
            {{ $errors->first('email', '<span class="_msg _error">:message</span>') }}
        </fieldset>

        <fieldset class="{{{ $errors->has('password') ? '_error' : '' }}}">
            {{ Form::label('password') }}
            {{ Form::password('password', '', array('placeholder' => 'password')) }}
            {{ $errors->first('password', '<span class="_msg _error">:message</span>') }}
        </fieldset>

        <div class="action">
            {{ Form::submit('Register') }}
        </div>
    {{ Form::close() }}
@stop
