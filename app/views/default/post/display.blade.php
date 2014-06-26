@extends('layouts.master')

@section('content')
<div class="_col _col_fixed2 _box _fullscreen bg">
    <div class="inner">
        <header class="full">
            <h2>{{{ $post->title }}}</h2>
        </header>
        <div class="galerie">
            <div class="big">
                <img src="{{{ \Custom\Media::url((array)$post->gallery['cover'], 'original') }}}" width="630" height="250" alt="{{{ $post->title }}}" class="" />
            </div>
            @if (!empty($post->gallery['media']))
                <div class="minis">
                    @foreach($post->gallery['media'] AS $media)
                        <img src="{{{ \Custom\Media::url((array)$media, '75x75') }}}" width="75" height="75" alt="{{{ $media->title }}}" class="" />
                    @endforeach
                </div>
            @endif
        </div>
        <div class="localisation">
            {{{ $post->country_name }}}
            @if(isset($post->admin1_id)) > {{{ $post->admin1_name }}}@endif
            @if(isset($post->admin2_id)) > {{{ $post->admin2_name }}}@endif
            > {{{ $post->city_name }}}
        </div>
        <div class="content">
            {{{ $post->content }}}
        </div>
    </div>
</div>
@stop
