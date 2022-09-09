@php($first_screen = 'first-screen-fluid')
@extends('web.layouts.default')

@section('content')
<section class="project-page" id="project-page">
    <div class="wrap">
        <div class="project-page__box">
            <div class="project-page__left">
                <h2 class="sec_heading--h2">
                    {{ $page->name }}
                </h2>

                <div class="contacts__info">
                    <div class="contacts__flex">
                        <div class="contacts__item">
                            <p>{{ __('website.website') }}:</p>
                            <a href="{{ $page->url }}" rel="nofollow" target="_blank">
                                {{ $page->url }}
                            </a>
                        </div>

                        <div class="contacts__item">
{{--                            <p>Время разработки:</p>--}}
{{--                            <span>1 месяц.</span>--}}
                        </div>
                    </div>
                </div>

                <div class="smart__text">
                    {!! $page->body !!}
                    <a href="#" class="mob-link mob-link1">
                        Читать больше
                        <svg class="icon">
                            <use xlink:href="img/svg-sprite.svg#arr"></use>
                        </svg>
                    </a>
                </div>

                <div class="why-box">
                    @foreach($page->features as $feature)
                    <div class="why-box__item">
                        <p>{{ $feature->name }}:</p>

                        <ul>
                            @foreach($feature->values as $value)
                                <li>{{ $value->variant->name }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endforeach
                </div>

                <div class="project-page__screens">
                    <p>{{ __('website.screenshots') }}</p>
                    @foreach($page->images as $image)
                        <picture>
                            <source type="image/webp" srcset="{{ \Illuminate\Support\Facades\Storage::url($image->webp()) }}">
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($image->fullName()) }}" alt="{{ $image->alt }}">
                        </picture>
                    @endforeach
                </div>

            </div>

            @if ($other)
            <div class="project-page__right">
                <div class="sticky_wrapp">
                    <div class="sticky">
                        <p class="name">
                            {{ __('website.other_projects') }}
                        </p>
                        <div class="projects__box">
                            @foreach($other as $project)
                            <a href="{{ route('view', $project->meta->url) }}" class="projects__item">
                                <picture>
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($project->preview()->thumbnail()) }}" alt="">
                                </picture>
                                <div>
                                    <p>{{ $project->translateOrDefault(app()->getLocale())->name }}</p>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
@endsection
