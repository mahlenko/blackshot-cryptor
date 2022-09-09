@extends('web.layouts.default', ['first_screen' => 'first-screen-fluid'])
@section('content')
    @if (isset($children_categories) && !$children_categories->count() && \Illuminate\Support\Str::contains(\Illuminate\Support\Facades\URL::current(), 'blog'))
        @php($children_categories = $page->parent->children)
    @endif

    @if(isset($children_categories) && $children_categories)
        <section class="section section-blog">
            <div class="wrap">
                <div class="tabs-imitation">
                    <a href="{{ route('view', 'blog') }}" class="tab {{ $page->meta->url == 'blog' ? 'active' : null }}">Все</a>

                    @foreach($children_categories as $category)
                        <a href="{{ route('view', $category->meta->url) }}" class="tab {{ $page->uuid == $category->uuid ? 'active' : null }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {!! $page->translateOrDefault(app()->getLocale())->body !!}

    @if(isset($children) && $children)
        <section class="wrap">
            <div class="projects__box">
                @foreach($children as $child)
                    <a href="{{ route('view', $child->meta->url) }}" class="projects__item">
                        @if ($child->preview())
                            <picture>
                                <source type="image/webp" srcset="{{ \Illuminate\Support\Facades\Storage::url($child->preview()->webp()) }}">
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($child->preview()->thumbnail()) }}" alt="">
                            </picture>
                        @endif
                        <div>
                            <p>
                                {{ $child->translateOrDefault(app()->getLocale())->name }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
@endsection
