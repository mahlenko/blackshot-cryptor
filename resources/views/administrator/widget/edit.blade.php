@extends('administrator.layouts.admin')
@section('content')
    {{-- Breadcrumbs --}}
    @include('administrator.resources.breadcrumbs', ['data' => ['widget' => $widget]])

    {{-- Content --}}
    <div class="d-flex align-items-start">
        <a
            class="btn btn-link text-secondary border me-2"
            href="{{ route('admin.widget.home') }}"
        >
            <i class="fas fa-arrow-left"></i>
        </a>
        <h2>{{ $widget->name ?? __('widget.create') }}</h2>
    </div>

    <hr>

    @include('administrator.partials.messages')

    <form action="{{ route('admin.widget.store') }}" method="post">
        @csrf
        <input type="hidden" name="uuid" value="{{ $widget->uuid ?? $uuid = \Ramsey\Uuid\Uuid::uuid4()->toString() }}">
        <input type="hidden" name="locale" value="{{ app()->getLocale() }}">

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
                    <button type="submit" class="btn btn-success">{{ __('global.save') }}</button>
                </div>
            </div>

            {{-- content --}}
            <div class="card-body">
                <div class="tab-content" id="tabs-content">
                    @foreach ($tabs as $tab)
                        @if (!in_array($tab['key'], ['variants', 'features']) || $product)
                            <div
                                class="tab-pane fade @if (!$loop->index)show active @endif"
                                id="tab-{{ $tab['key'] }}"
                                role="tabpanel"
                                aria-labelledby="tab-{{ $tab['key'] }}-content">
                                @include($tab['template'], $tab['data'] ?? [])
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </form>
@endsection
