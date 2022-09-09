<section class="section arc_block arc_block-bottom">
    <div class="wrap">
        <div class="sec_heading sec_heading-center">
            <h2 class="sec_heading--h2">{{ $widget->parameters->title }}</h2>
            <p class="sec_heading--subheading">{{ $widget->parameters->description }}</p>
        </div>
    </div>

    <div class="wrap">
        <div class="rowblk">
            @foreach($items as $item)
                <div class="rowblk--item rowblk--item-col4">
                    <div class="rowblk--inner">
                        <div class="icon_box">
                            {{-- image --}}
                            @if ($item->mimeType == 'image/svg+xml')
                                <span class="icon_box--icon icon">
                                    {!! \Illuminate\Support\Facades\Storage::get($item->fullName()) !!}
                                </span>
                            @else
                                <picture>
                                    <source type="image/webp" srcset="{{ \Illuminate\Support\Facades\Storage::url($item->webp(false)) }}">
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($item->thumbnail()) }}" class="icon_box--icon icon" alt="{{ $item->alt }}">
                                </picture>
                            @endif

                            {{-- title, description --}}
                            <div class="icon_box--header">
                                {{ $item->title }}
                            </div>
                            <div class="icon_box--text">
                                {{ $item->description }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

