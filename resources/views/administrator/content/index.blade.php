@extends('administrator.layouts.admin')

@section('content')
    {{-- Breadcrumbs --}}
    @if (\Diglactic\Breadcrumbs\Breadcrumbs::exists(\Illuminate\Support\Facades\Route::currentRouteName()))
        {{ \Diglactic\Breadcrumbs\Breadcrumbs::render(\Illuminate\Support\Facades\Route::currentRouteName(), $breadcrumbs_data ?? []) }}
    @endif
    {{-- End: Breadcrumbs --}}

    {{-- Header --}}
    @include('administrator.content.partials.index.header')

    {{-- Messages --}}
    @include('administrator.partials.messages')

    {{-- Object list --}}
    @if ($object_list && count($object_list))
        <ul class="list-group-table"@isset($routes['sortable']) data-sortable-url="{{ $routes['sortable'] }}@endif">
            @php
                if (empty($nested_view_name)) $nested_view_name = 'default';
            @endphp

            @foreach($object_list as $item)
                @include('administrator.content.nested-rows.' . $nested_view_name)
            @endforeach
        </ul>
    @else
        <p class="alert alert-warning">
            Objects not found. @if (isset($routes['create']))<a href="{{ $routes['create'] }}">Create first object</a>.@endif
        </p>
    @endif

    @if (isset($pagination) && $pagination)
    <div class="my-3">
        {!! $pagination !!}
    </div>
    @endif

@endsection
