@extends('administrator.layouts.admin')

@section('content')
    {{-- Breadcrumbs --}}
    @if (\Diglactic\Breadcrumbs\Breadcrumbs::exists(\Illuminate\Support\Facades\Route::currentRouteName()))
        {{ \Diglactic\Breadcrumbs\Breadcrumbs::render(\Illuminate\Support\Facades\Route::currentRouteName(), $breadcrumbs_data ?? []) }}
    @endif
    {{-- End: Breadcrumbs --}}

    {{-- Header --}}
    @include('administrator.navigation.items.partials.header')

    {{-- Messages --}}
    @include('administrator.partials.messages')

    {{-- Список ссылок меню --}}
    <ul class="list-group-table" data-sortable-url="{{ route('admin.navigation.items.sortable') }}">
        {{-- Элементы меню --}}
        @foreach($object_list as $item)
            @include('administrator.navigation.items.nested-row', ['node' => $item])
        @endforeach
    </ul>
@endsection
