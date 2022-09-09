@extends('web.layouts.default', ['first_screen' => 'first-screen-fluid'])
@section('content')

    {!! $page->translateOrDefault(app()->getLocale())->body !!}

    @if($products)
        <section class="wrap">
            <div class="projects__box">
                @foreach($products as $product)
                    <a href="{{ route('view', $product->meta->url) }}" class="projects__item">
                        @if ($product->preview())
                            <picture>
                                <source type="image/webp" srcset="{{ \Illuminate\Support\Facades\Storage::url($product->preview()->webp()) }}">
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($product->preview()->thumbnail()) }}" alt="">
                            </picture>
                        @endif
                        <div>
                            <p>
                                {{ $product->translateOrDefault(app()->getLocale())->name }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
@endsection
