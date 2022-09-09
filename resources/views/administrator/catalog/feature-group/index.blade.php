@extends('administrator.layouts.admin')
@section('content')
    {{-- Breadcrumbs --}}
    @include('administrator.resources.breadcrumbs')

    {{-- Заголовок --}}
    @include('administrator.catalog.feature-group.partials.header')
    <hr>

    @include('administrator.partials.messages')

    @if (isset($groups) && $groups->count())
        <ul class="list-group-table" data-sortable-url="{{ route('admin.catalog.feature.group.sortable') }}">
            @foreach($groups as $group)
                @include('administrator.catalog.feature-group.partials.element-list')
            @endforeach
        </ul>
    @endif

    @if ($groups->lastPage() > 1)
        <div class="my-3">
            {!! $groups->links() !!}
        </div>
    @endif
@endsection
