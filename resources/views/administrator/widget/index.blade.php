@extends('administrator.layouts.admin')
@section('content')
    {{-- Breadcrumbs --}}
    @include('administrator.resources.breadcrumbs', ['data' => []])

    {{-- Заголовок --}}
    @include('administrator.widget.partials.header')
    <hr>

    @include('administrator.partials.messages')

    @if (isset($widgets) && $widgets->count())
        <ul class="list-group-table">
            @foreach($widgets as $item)
                @include('administrator.widget.partials.element-list')
            @endforeach
        </ul>
    @endif

    @if ($widgets->lastPage() > 1)
        <div class="my-3">
            {!! $widgets->links() !!}
        </div>
    @endif
@endsection
