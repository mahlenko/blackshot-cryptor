@extends('administrator.layouts.admin')

@push('scripts')
    <script src="{{ mix('js/editor.js', 'administrator') }}"></script>
@endpush

@section('content')

    {{-- Breadcrumbs --}}
    @if (\Diglactic\Breadcrumbs\Breadcrumbs::exists(\Illuminate\Support\Facades\Route::currentRouteName()))
        {{ \Diglactic\Breadcrumbs\Breadcrumbs::render(\Illuminate\Support\Facades\Route::currentRouteName(), $breadcrumbs_data ?? []) }}
    @endif
    {{-- End: Breadcrumbs --}}

    {{-- Header --}}
    @include('administrator.content.partials.edit.header')

    {{-- Messages --}}
    @include('administrator.partials.messages')

    {{-- Content --}}
    <div class="card">
        {{-- Tabs navigation --}}
        @include('administrator.content.partials.edit.tabs')

        {{-- Content body --}}
        <div class="card-body text-start">
            @include('administrator.content.partials.edit.content')
        </div>
    </div>
@endsection
