@php($root = \App\Models\Page\Page::find($widget->parameters->item)->translateOrDefault(app()->getLocale()))

<section class="section arc_block arc_block-bottom">
    <div class="wrap">
        <div class="sec_heading sec_heading-center">
            <h2 class="sec_heading--h2">{{ __($root->name) }}</h2>
            <p class="sec_heading--subheading">{{ __($root->description) }}</p>
        </div>
    </div>

    <div class="wrap">
        <div class="rowblk">
            @foreach($items as $item)
                <div class="rowblk--item rowblk--item-col4">
                    <div class="rowblk--inner">
                        <div class="icon_box">
                            <svg class="icon_box--icon icon">
                                <use xlink:href="{{ asset('img/svg-sprite.svg#icon' . $loop->iteration) }}"></use>
                            </svg>
                            <div class="icon_box--header">{{ $item->translate(app()->getLocale())->name }}</div>
                            <div class="icon_box--text">
                                {{ $item->translate(app()->getLocale())->description }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
