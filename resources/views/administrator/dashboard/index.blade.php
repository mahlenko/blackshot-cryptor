@extends('administrator.layouts.admin')
@section('content')

    <div class="dashboard">
        {{-- Breadcrumbs --}}
        @include('administrator.resources.breadcrumbs')

        <h1>{{ __('dashboard.welcome') }}</h1>

        <div class="mt-4">
{{--            @include('administrator.dashboard.partials.statistic')--}}
        </div>

        {{-- right --}}
        <div class="d-flex align-items-start mt-4">
            <div class="col me-3">
                @include('administrator.dashboard.partials.cards.catalog')
            </div>
            <div class="col me-3">
                @include('administrator.dashboard.partials.cards.pages')
            </div>
            <div class="col me-3">
                @include('administrator.dashboard.partials.cards.files')
            </div>
            <div class="col">
                @include('administrator.dashboard.partials.cards.users')
            </div>
        </div>

    </div>

@endsection
