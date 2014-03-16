@if (!empty(Session::get('flash.notice')))
    <div id="_notices">
    @if (Session::has('flash.notice.error'))
        <div class="msg _full _error">
            {{ Session::get('flash.notice.error') }}
        </div>
    @elseif (Session::has('flash.notice.success'))
        <div class="msg _full _success">
            {{ Session::get('flash.notice.success') }}
        </div>
    @else
        <div class="msg _full _info">
            {{ Session::get('flash.notice.info') }}
        </div>
    @endif
    </div>
@endif
