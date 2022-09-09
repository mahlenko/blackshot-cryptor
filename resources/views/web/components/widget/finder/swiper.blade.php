<section class="tech" id="tech">
    <div class="wrap">
        <div class="sec_heading sec_heading-center">
            <h2 class="sec_heading--h2">{{ $widget->parameters->title }}</h2>
            <p class="sec_heading--subheading">{{ $widget->parameters->description }}</p>
        </div>
        <div class="tech__slider">
            @foreach($items as $item)
                {{-- image --}}
                @if ($item->mimeType == 'image/svg+xml')
                    <span class="icon icon-swiper">
                        {!! \Illuminate\Support\Facades\Storage::get($item->fullName()) !!}
                    </span>
                @else
                    <picture>
                        <source type="image/webp" srcset="{{ \Illuminate\Support\Facades\Storage::url($item->webp(false)) }}">
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($item->thumbnail()) }}" class="icon_box--icon icon" alt="{{ $item->alt }}">
                    </picture>
                @endif
            @endforeach
        </div>
    </div>
</section>
