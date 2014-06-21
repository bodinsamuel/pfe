@extends('layouts.master', ['__body_class' => 'fixed'])

@section('content')
    @include('account/inc/menu')

    @if($favorites['linked']['meta']['count'] > 0)
        <ul>
            @foreach($favorites['linked']['results'] AS $id_post => $post)
                @include('modules/post/one')
            @endforeach
        </ul>
    @else
        <p>
            No favorites
        </p>
    @endif
@stop
