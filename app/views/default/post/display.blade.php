@extends('layouts.master')

@section('content')
<div id="post_display" class="_col _col_fixed2 _box _fullscreen bg _c">
    <div class="inner">
        <header class="full">
            <h2>{{{ $post->title }}}</h2>
        </header>
        <div class="cover">
            <div class="galerie">
                <div class="big">
                    <img src="{{{ \Custom\Media::url((array)$post->gallery['cover'], 'cover_post') }}}" width="630" height="250" alt="{{{ $post->title }}}" class="" />
                </div>
                @if (!empty($post->gallery['media']))
                    <div class="minis" data-count="{{ $post->gallery['count'] }}">
                        @foreach($post->gallery['media'] AS $media)
                            <img src="{{{ \Custom\Media::url((array)$media, '75x75') }}}" width="75" height="75" alt="{{{ $media->title }}}" class="" />
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="inner">
                <div class="localisation">
                    {{{ $post->country_name }}}
                    @if(isset($post->admin1_id)) > {{{ $post->admin1_name }}}@endif
                    @if(isset($post->admin2_id)) > {{{ $post->admin2_name }}}@endif
                    > {{{ $post->city_name }}}
                </div>
                <div class="details_quick">
                    <ul>
                        <li>
                            <i class="_16 i_post_surface"></i>
                            <span>{{ $post->surface_living }} m²</span>
                        </li>
                        <li>
                            <i class="_16 i_post_price"></i>
                            <span>{{ $post->price }} € {{ \Custom\Post\Price::$type[$post->price_type] }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <span class="_c"></span>
        <div class="content">
            {{{ $post->content }}}
        </div>
    </div>
</div>
@stop
