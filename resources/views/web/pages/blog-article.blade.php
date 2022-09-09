@php
    $blog_page = \App\Models\Meta::where('slug', 'blog')->first()->object()->first();
    $blog_page->meta = $page->meta;
@endphp

@extends('web.layouts.default', ['first_screen' => 'first-screen-fluid', 'page' => $blog_page])
@section('content')
    <section class="project-page project-blog" id="project-page">
        <div class="wrap">
            <div class="project-page__box">
                <div class="project-page__left">
                    <h2 class="sec_heading--h2">
                        {{ $page->heading_h1() }}
                    </h2>

                    <div class="blog-head">
                        @if ($page->preview())
                        <picture>
                            <source type="image/webp" srcset="{{ \Illuminate\Support\Facades\Storage::url($page->preview()->webp()) }}">
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($page->preview()->fullName()) }}" alt="" class="blog-pic">
                        </picture>
                        @endif

                        <p class="blog-data">
                            {{ (new DateTimeImmutable($page->created_at))->format('d.m.Y') }}
                        </p>
                    </div>

                    <div class="smart__text">
                        {!! $page->translateOrDefault(app()->getLocale())->body !!}
                    </div>
                </div>

                <div class="project-page__right">
                    <div class="sticky_wrapp">
                        <div class="sticky">
                            <p class="name">{{ __('website.other_article') }}</p>
                            <div class="projects__box">
                                @foreach($blog_page->descendants->sortByDesc('_lft')->where('uuid', '<>', $page->uuid)->take(2) as $child)
                                <a href="{{ route('view', $child->meta->url) }}" class="projects__item">
                                    @if ($child->preview())
                                        <picture>
                                            <source type="image/webp" srcset="{{ \Illuminate\Support\Facades\Storage::url($child->preview()->webp()) }}">
                                            <img src="{{ \Illuminate\Support\Facades\Storage::url($child->preview()) }}" alt="{{ $child->preview()->alt }}">
                                        </picture>
                                    @endif
                                    <div><p>Сайт на wordpress</p></div>
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
