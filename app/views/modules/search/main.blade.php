@extends('layouts.master')

@section('content')

<div class="_col _col_fixed2 _box _fullscreen">
    <div class="inner">
        <ul>
        @foreach($posts AS $post)
            <li class="_c" style="margin: 10px 10px 0px;padding: 10px 0 10px 0;">
                <div class="galerie mini">
                    <div class="big">
                        <img src="/assets/img/test/thumbnail.jpg" alt="" class="" />
                    </div>
                    <div class="thumbnails">
                        <img src="/assets/img/test/thumbnail_1.jpg" alt="" />
                        <img src="/assets/img/test/thumbnail_2.jpg" alt="" />
                        <img src="/assets/img/test/thumbnail_3.jpg" alt="" />
                    </div>
                </div>
                {{{ $post->name }}} - {{{ $post->name_full }}}<br>
                {{{ $post->date_created }}} <br>
                {{{ $post->content }}}
            </li>
        @endforeach
        </ul>
    </div>
</div>
@stop
