@extends('layouts.master')

@section('content')

<div class="_col _col_fixed2 _box _fullscreen">
    <div class="inner">
        <ul>
        @foreach($posts AS $post)
            <li class="_c" style="margin: 10px 10px 0px;padding: 10px 0 10px 0;">
                <div class="galerie mini">
                    <div class="big">
                        <img src="{{{ $post['cover']['url'] }}}" width="200" alt="" class="" />
                    </div>
                </div>
            </li>
        @endforeach
        </ul>
    </div>
</div>
@stop
