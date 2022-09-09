<form
    action="{{ route($routes['store']) }}"
    method="post"
    id="page_save"
    enctype="multipart/form-data"
    @if(\Illuminate\Support\Facades\Request::ajax())onsubmit="return window.ajaxSubmit(this)" @endif
>
    @csrf
    <input type="hidden" name="uuid" value="{{ $uuid }}">
    <input type="hidden" name="locale" value="{{ $locale ?? config('app.locale') }}">

    @if ($chunks_template)
        <div class="tab-content" id="nav-tabContent">
            @foreach($chunks_template as $idx => $chunk)
                <div class="tab-pane fade @if ($idx === 0)show active @endif" id="list-{{ $chunk['key'] }}" role="tabpanel" aria-labelledby="list-{{ $chunk['key'] }}-list">
                    @include($chunk['template'], $chunk['data'])
                </div>
            @endforeach
        </div>
    @endif
</form>
