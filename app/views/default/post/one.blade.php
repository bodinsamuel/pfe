<div class="galerie mini _col _col45">
    <div class="big">
        <a href="{{ $post['url'] or "/post/" . $id_post }}/"><img src="{{{ \Custom\Media::url($post['cover'], '250x175') }}}" width="250" alt="" class="" /></a>
    </div>
</div>
<div class="content _col _col5">
    <h3><a href="{{ $post['url'] or "/post/" . $id_post }}/">{{ \Custom\Post::make_title($post['id_property_type'], $post['details']['surface_living']) }}</a></h3>
    {{{ $post['content'] }}}
</div>
