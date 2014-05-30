@extends('layouts.master')

@section('content')

<div class="_col _col_fixed2 _box _fullscreen">
    <div class="inner">
        <ul>
        @foreach($posts AS $post)
            <li class="_c" style="margin: 10px 10px 0px;padding: 10px 0 10px 0;">
                <div class="galerie mini">
                    @if ($post->gallery['count'] > 0)
                        <div class="big">
                            <img src="{{{ Custom\Media::url($post->gallery['cover'], "original") }}}" width="200" alt="" class="" />
                        </div>
                        @if ($post->gallery['count'] > 1)
                        <div class="thumbnails">
                            @foreach($post->gallery['media'] AS $media)
                                <img src="{{{ Custom\Media::url($media, "original") }}}" alt="" height="50" />
                            @endforeach
                        </div>
                        @endif
                    @else
                        <div class="big">
                            <img src="http://media.pfe.dev/404-original-0-not-found.jpg" width="200" alt="" class="" />
                        </div>
                    @endif
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
