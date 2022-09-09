<section class="section">
{{--    <div class="wrap">--}}
{{--        <div class="sec_heading sec_heading-center">--}}
{{--            @if ($root)--}}
{{--            <h2 class="sec_heading--h2">{{ $root->name }}</h2>--}}
{{--            <p class="sec_heading--subheading">{{ $root->description }}</p>--}}
{{--            @endif--}}
{{--        </div>--}}
{{--    </div>--}}

    @if ($items)
    <div class="wrap">
        {{-- Tabs --}}
        <div class="tabs">
            <div class="tabs--bar">
                @foreach($items as $page)
                    <div class="tabs--bar_item">
                        <div class="tabs--bar_item">
                            <div data-tabopen="{{ $page->uuid }}" class="tabs--btn">
                                <div class="tabs--progress" style="width: 0%;"></div>
                                <span class="tabs--btntext">
                                    {{ $page->name }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Tab contents --}}
        <div class="tabs--container">
            <div class="tabslider">
                @foreach($items as $page)
                    <div data-tab="{{ $page->uuid }}" class="tabslider--item">
                        {{-- mobile tab accordion --}}
                        <div data-tabopen="{{ $page->uuid }}" class="tabslider--header">
                            {{ $page->name }}
                            <svg class="tabslider--caret icon">
                                <use xlink:href="{{ asset('img/svg-sprite.svg#caret') }}"></use>
                            </svg>
                        </div>

                        @php($backdrop_image = $page->preview())
                        @if ($page->images->count() > 1)
                        @php($backdrop_image = $page->meta->is_active ? $page->images[1] : $page->preview())
                        @endif

                        {{-- tab content --}}
                        <div class="tabslider--content">
                            <div class="tabslider--inner">
                                <div class="tabslider--information">
                                    <h3 class="tabslider--heading">
                                        {{ $backdrop_image->title }}
                                    </h3>

                                    <p class="tabslider--text">
                                        {!! nl2br($backdrop_image->description) !!}
                                    </p>

                                    @if ($page->meta->is_active)
                                    <div class="tabslider--buttons">
                                        <a href="{{ $page->url() }}"
                                           class="btn btn-bordered">
                                            {{ __('website.more_detailed') }}
                                        </a>
                                    </div>
                                    @endif
                                </div>

                                <div class="tabslider--media">
                                    <picture>
                                        <source type="image/webp" srcset="{{ \Illuminate\Support\Facades\Storage::url($backdrop_image->webp()) }}">
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($backdrop_image->thumbnail()) }}" alt="" class="tabslider--image">
                                    </picture>

                                    {{-- include template page --}}
                                    @php($iterations = ['one', 'two', 'three'])
                                    @foreach(($page->meta->is_active ? $page->images->slice(2) : $page->images->slice(1)) as $image)
                                    <div class="tabslider--media_item tabslider--media_item-{{ $iterations[$loop->index] }}">
                                        <div class="tabslider--small">{{ $image->description }}</div>
                                        <div class="tabslider--block_heading">
                                            {{ $image->title }}

                                            @if($image->alt == '+')
                                                <svg class="icon icon-greencheck">
                                                    <use xlink:href="{{ asset('img/svg-sprite.svg#greencheck') }}"></use>
                                                </svg>
                                            @endif
                                        </div>

                                        <img src="{{ asset(\Illuminate\Support\Facades\Storage::url($image->fullName())) }}"
                                             alt="" class="{{ $image->class ?? 'tabslider--icon_1' }} icon">
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
    @endif
</section>
