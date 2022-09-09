@extends('administrator.layouts.admin')
@section('content')
    {{-- Breadcrumbs --}}
    @include('administrator.resources.breadcrumbs', ['data' => ['category' => $category]])

    {{-- Заголовок --}}
    @include('administrator.catalog.category.partials.header')
    <hr>

    @include('administrator.partials.messages')

    @if (isset($category_list) && $category_list->count())
    <ul class="list-group-table" data-sortable-url="{{ route('admin.catalog.category.sortable') }}">
        @foreach($category_list as $item)
            @include('administrator.catalog.category.partials.element-list')
        @endforeach
    </ul>
    @endif

    @if ($category_list->lastPage() > 1)
        <div class="my-3">
            {!! $category_list->links() !!}
        </div>
    @endif
@endsection
