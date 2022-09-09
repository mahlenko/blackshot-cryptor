@extends('administrator.layouts.admin')
@section('content')
    {{-- Breadcrumbs --}}
    @if (\Diglactic\Breadcrumbs\Breadcrumbs::exists(\Illuminate\Support\Facades\Route::currentRouteName()))
        {{ \Diglactic\Breadcrumbs\Breadcrumbs::render(\Illuminate\Support\Facades\Route::currentRouteName(), $breadcrumbs_data ?? []) }}
    @endif
    {{-- End: Breadcrumbs --}}

    @include('administrator.partials.messages')
    @include('administrator.finder.iframe')
@endsection
