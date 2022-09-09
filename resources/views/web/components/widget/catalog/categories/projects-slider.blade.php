@php($root = App\Models\Catalog\Category::whereIn('uuid', $widget->parameters->items)->first()->translateOrDefault(app()->getLocale()))

<section class="section section-smart">
    <div class="wrap">
        <div class="sec_heading sec_heading-center">
            <h2 class="sec_heading--h2">{{ $root->name }}</h2>
            <p class="sec_heading--subheading">{!! nl2br($root->description) !!}</p>
        </div>
    </div>

    <div class="wrap">
        <div class="project-slider">
            @foreach($items as $product)
                <a href="{{ route('view', $product->meta->url) }}" class="projects__item">
                    <picture>
                        <source type="image/webp" srcset="{{ \Illuminate\Support\Facades\Storage::url($product->preview()->webp()) }}">
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($product->preview()->fullName()) }}" alt="">
                    </picture>

                    <div>
                        <h4>{{ $product->translateOrDefault(app()->getLocale())->name }}</h4>
                        <p>{!! nl2br($product->translateOrDefault(app()->getLocale())->description) !!}</p>
                    </div>
                </a>
            @endforeach
        </div>

        <a href="{{ route('view', \App\Models\Catalog\Category::find($widget->getParametersAttribute()->items[0])->meta->url) }}"
           class="btn btn-bordered project-btn">
            {{ __('website.all_projects') }}
        </a>
    </div>
</section>
