@extends('administrator.layouts.admin')
@section('content')
    {{-- Breadcrumbs --}}
    @include('administrator.resources.breadcrumbs')

    {{-- Заголовок --}}
    @include('administrator.company.partials.header')
    <hr>

    @include('administrator.partials.messages')

    @if (isset($companies) && $companies->count())
        <ul class="list-group-table" data-sortable-url="{{ route('admin.company.sortable') }}">
            @foreach($companies as $company)
                @include('administrator.company.partials.element-list')
            @endforeach
        </ul>
    @endif

    @if (isset($companies) && $companies->lastPage() > 1)
        <div class="my-3">
            {!! $companies->links() !!}
        </div>
    @endif
@endsection
