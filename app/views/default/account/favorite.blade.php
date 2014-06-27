@extends('layouts.master', ['__body_class' => 'fixed'])

@section('content')
    @include('default/account/inc/menu')

    @if(isset($favorites['linked']['meta']['count']) && $favorites['linked']['meta']['count'] > 0)
        <ul>
            @foreach($favorites['linked']['results'] AS $id_post => $post)
                @include('default/post/one')
            @endforeach
        </ul>
    @else
        <p>
            No favorites
        </p>
    @endif
@stop
