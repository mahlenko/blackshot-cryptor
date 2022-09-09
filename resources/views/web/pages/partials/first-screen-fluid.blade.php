<div class="first_screen first_screen-main">
    <div class="first_screen--inner">
        <div class="first_screen--content">
            <h1 class="first_screen--heading">
                {!! $page->heading_h1() !!}
            </h1>
            <div class="first_screen--text">
                <p>{{ $page->description }}</p>
            </div>
            @if(!\Illuminate\Support\Str::contains(\Illuminate\Support\Facades\URL::current(), '/blog'))
            <div class="first_screen--buttons">
                <button class="btn btn-mobile_fullwidth" data-modal-open="modalOrder">
                    {{ __('website.leave_a_request') }}
                </button>
            </div>
            @endif
        </div>
    </div>
    @if($page->preview())
    <picture>
        <source type="image/webp" srcset="{{ \Illuminate\Support\Facades\Storage::url($page->preview()->webp()) }}">
        <img src="{{ \Illuminate\Support\Facades\Storage::url($page->preview()->fullName()) }}" alt="" class="first_screen__img">
    </picture>
    @endif
</div>
