@extends('administrator.layouts.admin')

@section('content')
    {{-- Breadcrumbs --}}
    {!! \Diglactic\Breadcrumbs\Breadcrumbs::render(config('anita.dashboard.prefix') . '.navigation.items.edit', ['navigation' => $navigation, 'parent_id' => $item->parent_id ?? null, 'item' => $item ?? null]) !!}

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-start mb-3">
        <span class="d-flex">
            <pictire>
                <source type="image/webp" srcset="{{ asset('administrator/icons/folder.webp') }}">
                <img src="{{ asset('administrator/icons/folder.png') }}" class="me-2" height="32" alt="">
            </pictire>
            <h2 class="me-5">@if ($is_create){{ __('navigation.add_item') }}@else{{ $navigation->name }}@endif</h2>
        </span>

        <div class="d-flex">
            <button type="submit" class="btn btn-success" form="page_save">
                <i class="fas fa-save"></i>sd
                {{ __('navigation.items.save') }}
            </button>
        </div>
    </div>

    <div class="card text-center">
        {{-- Navigation chunks --}}
        <div class="card-header">
            @if ($chunks_template)
                <ul class="nav nav-tabs card-header-tabs" role="tablist">
                    @foreach($chunks_template as $idx => $chunk)
                        <li class="nav-item">
                            <a class="nav-link list-group-item-action @if ($idx === 0)active @endif" id="list-{{ $chunk['key'] }}-list" data-bs-toggle="list" href="#list-{{ $chunk['key'] }}" role="tab" aria-controls="{{ $chunk['key'] }}">
                                {!! $chunk['icon'] ?? '' !!}{{ $chunk['name'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- Edit form data --}}
        <div class="card-body text-start">
            <form action="{{ route('admin.navigation.items.save') }}" method="post" id="page_save" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="uuid" value="{{ $uuid }}">
            <input type="hidden" name="navigation_uuid" value="{{ $navigation->uuid }}">

            @if ($chunks_template)
                <div class="tab-content" id="nav-tabContent">
                    @foreach($chunks_template as $idx => $chunk)
                        <div class="tab-pane fade @if ($idx === 0)show active @endif" id="list-{{ $chunk['key'] }}" role="tabpanel" aria-labelledby="list-{{ $chunk['key'] }}-list">
                            @include($chunk['template'], $chunk['data'])
                        </div>
                    @endforeach
                </div>
            @endif
        </form>
        </div>
    </div>
@endsection
