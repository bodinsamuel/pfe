@extends('layouts.admin')

@section('content')
    @if ($pending['count'] > 0)
        @foreach($pending['posts'] AS $id_post => $post)
            <div>
                #{{{ $id_post }}} |
                {{{ $post->title }}}
                <a href="/posts/validate?id_post={{$id_post}}" class="btn success">Validate</a>
                <a href="/posts/reject?id_post={{$id_post}}">Reject</a>
            </div>
        @endforeach
    @else
        <div class="">
            No posts are pending validation.
        </div>
    @endif
@stop
