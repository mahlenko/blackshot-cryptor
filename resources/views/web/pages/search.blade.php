@extends('web.layouts.default')

@section('content')
    <div class="container">
        @include('web.pages.components.title')

        <form action="{{ route('search') }}" method="GET" class="mb-4">
            <label>
                <input type="search"
                       class="control w-100"
                       name="q"
                       placeholder="Искать на сайте..."
                       value="{{ $q }}"
                >
            </label>
        </form>
    </div>

    @if ($items)
        <div class="search">
            <div class="container">
                @foreach($items as $item)
                    <div class="item p-3 mb-3 border">
                        <p class="mb-2">
                            {{ $loop->iteration }}.
                            <a class="link" href="{{ route('view', $item->meta->url) }}" target="_blank">
                                <strong>{{ $item->name }}</strong>
                            </a><br>

                            <a href="{{ route('view', $item->meta->url) }}" class="link-url" target="_blank">
                                <small>{{ route('view', $item->meta->url) }}</small>
                            </a>
                        </p>

                        <div class="d-flex flex-column flex-md-row">
                            @if ($item->preview() && $item->preview()->mimeType !== 'image/svg+xml')
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($item->preview()->thumbnail()) }}" class="col-md-2 me-3" alt="">
                            @endif

                            <p class="mb-0">
                                {!! \Illuminate\Support\Str::limit(strip_tags($item->body) ?? $item->description, 200) !!}
                            </p>
                        </div>
                    </div>
                @endforeach

                <div class="d-flex justify-content-center py-4">
                    {!! $items->appends(['q' => $q])->links() !!}
                </div>
            </div>
        </div>
    @endif

@endsection
