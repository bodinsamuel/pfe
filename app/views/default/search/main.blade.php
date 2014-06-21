@extends('layouts.master')

@section('content')

<div class="_col _col_fixed2 _box _fullscreen " style="max-height: 85vh;position: relative;overflow-y:scroll;">
    <div class="inner">
        <header>
            <h2>{{{ $meta['total'] }}} resultats</h2>
        </header>
        @if(count($posts) > 0)
            <ul>
            @foreach($posts AS $id_post => $post)
                <li id="post_id_{{{ $id_post }}}" class="_c" style="margin: 10px 10px 0px;padding: 10px 0 10px 0;">
                    @include('default/post/one')
                </li>
            @endforeach
            </ul>
        @else
            <p>
                No results for your query
            </p>
        @endif
    </div>
</div>
@stop
