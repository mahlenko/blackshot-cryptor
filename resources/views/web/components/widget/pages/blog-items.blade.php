@php($root = \App\Models\Page\Page::find($widget->parameters->item)->translateOrDefault(app()->getLocale()))

<section class="art" id="art">
    <div class="wrap">
        <div class="art__flex">
            <div class="sec_heading sec_heading-center">
                <h2 class="sec_heading--h2">{{ __($root->name) }}</h2>
                <p class="sec_heading--subheading">{{ __('website.last_article_description') }}</p>
            </div>
            <a href="{{ App\Helpers\Url::route_locale('view', ['slug' => '/blog'], app()->getLocale()) }}" class="btn btn-bordered">
                {{ __('website.all_article') }}
            </a>
        </div>

        <div class="art__slider">
            @foreach($items->chunk(4) as $chunk)
                <div class="projects__box">
                    @foreach($chunk as $item)
                        @php($news = $item->descendants->first())

                        @if ($news)
                        <a href="{{ route('view', ['slug' => $news->meta->url]) }}" class="projects__item">
                            @if ($news->preview())
                            <picture>
                                <source type="image/webp" srcset="{{ \Illuminate\Support\Facades\Storage::url($news->preview()->webp(false)) }}">
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($news->preview()->thumbnail()) }}" alt="{{ $news->preview()->alt }}">
                            </picture>
                            @endif
                            <div>
                                <p>{{ $news->translateOrDefault(app()->getLocale())->name }}</p>
                            </div>
                        </a>
                        @endif
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
</section>
