@extends('layouts.master')

@section('content')
<div class="_col _col_fixed2 _box _fullscreen bg">
    <div class="inner">
        <header class="full">
            <h2>{{{ $post->title }}}</h2>
        </header>
        <div class="galerie">
            <div class="big">
                <img src="{{{ \Custom\Media::url($post->gallery['cover'], 'original') }}}" width="630" height="250" alt="{{{ $post->title }}}" class="" />
            </div>
        </div>
        <div class="content">
            {{{ $post->content }}}
        </div>
    </div>
</div>
@stop
