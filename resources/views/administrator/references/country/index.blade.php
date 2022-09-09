@extends('administrator.layouts.admin')
@section('content')
    {{-- Breadcrumbs --}}
    @include('administrator.resources.breadcrumbs')

    {{-- Заголовок --}}
    @include('administrator.references.country.partials.header')
    <hr>

    @include('administrator.partials.messages')

    @if (isset($countries) && $countries->count())
        <ul class="list-group-table" data-sortable-url="{{ route('admin.references.country.sortable') }}">
            @foreach($countries as $country)
                @include('administrator.references.country.partials.element-list')
            @endforeach
        </ul>
    @endif

    @if ($countries->lastPage() > 1)
        <div class="my-3">
            {!! $countries->links() !!}
        </div>
    @endif
@endsection
