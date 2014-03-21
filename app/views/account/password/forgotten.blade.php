@extends('layouts.master')

@section('content')
<div class="_col _col_fixed1 _box _fullscreen">
    <div class="inner _full_form">
        {{ Form::open(['action' => 'AccountController@post_ForgotPassword', 'method' => 'post']) }}

            <fieldset class="{{{ $errors->has('email') ? '_error' : '' }}}">
                {{ Form::label('email') }}
                {{ Form::text('email', Input::old('email'), ['placeholder' => 'john.doe@example.com']) }}
                {{ $errors->first('email', '<span class="_msg _error">:message</span>') }}
            </fieldset>

            <footer class="action">
                {{ Form::submit('Reset password') }}
            </footer>

        {{ Form::close() }}
    </div>
</div>
@stop
