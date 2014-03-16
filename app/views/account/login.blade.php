@extends('layouts.master', ['__body_class' => 'fullscreen'])

@section('content')
    <div class="_fullscreen-box">
        <div class="inner _full_form">
            {{ Form::open(['action' => 'AccountController@post_Login', 'method' => 'post']) }}

                <fieldset class="{{{ $errors->has('email') ? '_error' : '' }}}">
                    {{ Form::text('email', Input::old('email'), ['placeholder' => 'john.doe@example.com']) }}
                    {{ $errors->first('email', '<span class="_msg _error">:message</span>') }}
                </fieldset>

                <fieldset class="{{{ $errors->has('password') ? '_error' : '' }}}">
                    {{ Form::password('password', ['placeholder' => 'password']) }}
                    {{ $errors->first('password', '<span class="_msg _error">:message</span>') }}
                </fieldset>

                <div class="action">
                    {{ Form::submit('Login') }}
                </div>

            {{ Form::close() }}
        </div>
        <div class="outter_bottom">
            <a href="/password/forgotten" class="discret">Forgot password ?</a>
        </div>
    </div>
@stop
