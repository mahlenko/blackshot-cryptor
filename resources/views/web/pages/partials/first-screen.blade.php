<div class="first_screen first_screen-smart">
    <div class="first_screen--inner first_screen--angle-type">
        <div class="first_screen--content">
            <h1 class="first_screen--heading">
                {!! $page->heading_h1() !!}
            </h1>
            <div class="first_screen--text">
                {!! nl2br($page->description) !!}
            </div>

            <div class="first_screen--buttons">
                <button class="btn btn-mobile_fullwidth" data-modal-open="modalOrder">
                    {{ __('website.leave_a_request') }}
                </button>
            </div>

        </div>
        <div class="first_screen--media media_block">
            <div class="media_block--inner">
                <div class="media_block--content">
                    {{-- images_slider --}}
                    <div class="media_block--images_slider images_slider">
                        @if ($page->preview() && $webp = $page->preview()->webp())
                            <picture>
                                <source type="image/webp" srcset="{{ \Illuminate\Support\Facades\Storage::url($webp) }}">
                                <img
                                    class="images_slider--image"
                                    src="{{ \Illuminate\Support\Facades\Storage::url($page->preview()->fullName()) }}"
                                    alt="{{ $page->preview()->alt }}">
                            </picture>
                        @endif
                    </div>
                </div>
            </div>

            @if (\Illuminate\Support\Facades\Route::current()->uri === '/')
            <div class="media_block--elements">
                <span class="media_block--button">
                    <svg class="media_block--icon icon"><use xlink:href="img/svg-sprite.svg#play"></use></svg>
                </span>
            </div>
            @endif
        </div>
    </div>
</div>
