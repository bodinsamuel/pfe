@extends('layouts.admin')

@section('content')
    @if ($list['count'] > 0)
        @foreach($list['posts'] AS $id_post => $post)
            <div>
                #{{{ $id_post }}} |
                {{{ $post->title }}} |
                {{{ $post->date_created }}}
                <a href="/posts/reindex?id_post={{$id_post}}">Reindex</a>
                <a href="{{ \Config::get('app.url') }}/{{ $post->url }}">View</a>
                <a href="/posts/reject?id_post={{$id_post}}">Reject</a>
            </div>
        @endforeach
    @else
        <div class="">
            No posts in database
        </div>
    @endif
@stop
