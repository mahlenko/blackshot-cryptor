@extends('administrator.layouts.admin')
@section('content')
    {{-- Breadcrumbs --}}
    @include('administrator.resources.breadcrumbs')

    {{-- Заголовок --}}
    @include('administrator.catalog.feature.partials.header')
    <hr>

    @include('administrator.partials.messages')

    @if (isset($features) && $features->count())
        <ul class="list-group-table" data-sortable-url="{{ route('admin.catalog.feature.sortable') }}">
            @foreach($features as $feature)
                @include('administrator.catalog.feature.partials.element-list')
            @endforeach
        </ul>
    @endif

    @if ($features->lastPage() > 1)
        <div class="my-3">
            {!! $features->links() !!}
        </div>
    @endif
@endsection
