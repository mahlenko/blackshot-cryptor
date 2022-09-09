@extends('administrator.layouts.admin')
@section('content')
    {{-- Breadcrumbs --}}
    @include('administrator.resources.breadcrumbs', ['data' => ['locale' => $locale, 'feature' => $feature]])

    {{-- Content --}}
    <div class="d-flex align-items-start">
        <a
            class="btn btn-link text-secondary border me-2"
            href="{{ route('admin.catalog.feature.home') }}"
        >
            <i class="fas fa-arrow-left"></i>
        </a>
        <h2>{{ $feature->name ?? __('catalog.feature.create') }}</h2>
    </div>

    <hr>

    @include('administrator.partials.messages')

    <form action="{{ route('admin.catalog.feature.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="uuid" value="{{ $feature->uuid ?? $uuid = \Ramsey\Uuid\Uuid::uuid4()->toString() }}">
        <input type="hidden" name="locale" value="{{ $locale }}">

        <div class="card">
            {{-- tabs --}}
            <div class="card-header d-flex justify-content-between align-items-start">
                <ul class="nav nav-tabs card-header-tabs" role="tablist">
                @foreach ($tabs as $tab)
                    <li class="nav-item">
                        <a
                            href="#tab-{{ $tab['key'] }}"
                            class="nav-link list-group-item-action @if (!$loop->index)active @endif"
                            id="tab-{{ $tab['key'] }}-content"
                            data-bs-toggle="list"
                            role="tab"
                            aria-controls="{{ $tab['key'] }}">
                            {!! $tab['name'] !!}
                        </a>
                    </li>
                @endforeach
                </ul>

                <div class="btn-group btn-group-sm">
                    @if ($feature)
                        @include('administrator.resources.switcher-locale', ['locale' => $locale, 'data' => ['uuid' => $feature->uuid]])
                    @endif
                    <button type="submit" id="submitFormBtn" class="btn btn-success">{{ __('global.save') }}</button>
                </div>
            </div>

            {{-- content --}}
            <div class="card-body">
                <div class="tab-content" id="tabs-content">
                    @foreach ($tabs as $tab)
                        <div
                            class="tab-pane fade @if (!$loop->index)show active @endif"
                            id="tab-{{ $tab['key'] }}"
                            role="tabpanel"
                            aria-labelledby="tab-{{ $tab['key'] }}-content">
                            @include($tab['template'], $tab['data'] ?? [])
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </form>
@endsection
