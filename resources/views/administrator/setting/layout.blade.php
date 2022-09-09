@extends('administrator.layouts.admin')
@section('content')
    {{-- Breadcrumbs --}}
    @include('administrator.resources.breadcrumbs', ['data' => ['locale' => $locale ?? app()->getLocale()]])

    {{-- Заголовок --}}
    @include('administrator.setting.partials.header')
    <hr>

    @if (count(config('translatable.locales')) > 1)
    <div class="alert alert-info d-flex">
        <i class="fas fa-info-circle mt-1 me-2"></i>
        <p class="mb-0">
            {!! __('settings.info') !!}
        </p>
    </div>
    @endif

    @include('administrator.partials.messages')

    <div class="card">
        {{-- tabs --}}
        <div class="card-header d-flex justify-content-between align-items-start">
            <ul class="nav nav-tabs card-header-tabs" role="tablist">
                <li class="nav-item">
                    <a href="{{ route('admin.setting.website.home', ['locale' => app()->getLocale()]) }}"
                       class="nav-link list-group-item-action {{ \Illuminate\Support\Facades\Request::route()->getName() == 'admin.setting.website.home' ? 'active' : null }}">
                        <i class="fas fa-globe me-1"></i>
                        {{ __('settings/website.title') }}
                    </a>
                </li>
            </ul>

            <div class="btn-group btn-group-sm">
                @include('administrator.resources.switcher-locale', ['locale' => $locale, 'data' => []])
                <button type="submit" class="btn btn-success" form="settingForm">{{ __('global.save') }}</button>
            </div>
        </div>

        {{-- content --}}
        <div class="card-body">
            <div class="tab-content" id="tabs-content">
                @yield('body')
            </div>
        </div>
    </div>
@endsection
