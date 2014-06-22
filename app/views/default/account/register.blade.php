@extends('layouts.master', ['_content_cls' => 'centered'])

@section('content')
<div class="_col _col_fixed1 _box _fullscreen centered">
    <div class="inner _full_form">
        {{ Form::open(array('action' => 'AccountController@post_Register', 'method' => 'post')) }}

            <fieldset class="{{{ $errors->has('email') ? '_error' : '' }}}">
                {{ Form::text('email', Input::old('email'), ['placeholder' => 'john.doe@example.com']) }}
                {{ $errors->first('email', '<span class="_msg _error">:message</span>') }}
            </fieldset>

            <fieldset class="{{{ $errors->has('password') ? '_error' : '' }}}">
                {{ Form::password('password', ['placeholder' => 'password']) }}
                {{ $errors->first('password', '<span class="_msg _error">:message</span>') }}
            </fieldset>

            <footer class="action">
                {{ Form::submit('Register') }}
            </footer>
        {{ Form::close() }}
    </div>
    <div class="outter_bottom">
        <a href="/login" class="discret">Already have an account? Login !</a>
    </div>
</div>
@stop
