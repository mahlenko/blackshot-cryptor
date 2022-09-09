@extends('administrator.layouts.admin')
@section('content')
    {{-- Breadcrumbs --}}
    @include('administrator.resources.breadcrumbs')

    {{-- Заголовок --}}
    @include('administrator.catalog.product.partials.header')

    <form method="get" class="mt-2">
        <div class="form-awesome-control">
            <label for="name" class="label-awesome">
                <i class="fas fa-search"></i>
            </label>
            <input type="search"
                   id="name"
                   name="name"
                   class="form-control font-awesome-icon"
                   placeholder="Поиск по названию товара..."
                   onchange="return this.parentNode.parentNode.submit()"
                   value="{{ \Illuminate\Support\Facades\Request::input('name') }}"
            >
        </div>
    </form>
    <hr>

    @include('administrator.partials.messages')

    @if (isset($products) && $products->count())
        <ul class="list-group-table" data-sortable-url="{{ route('admin.catalog.feature.sortable') }}">
            @foreach($products as $product)
                @include('administrator.catalog.product.partials.element-list')
            @endforeach
        </ul>
    @else
        <p class="alert alert-warning">
            @if (\Illuminate\Support\Facades\Request::has('name'))
                Товаров в названии которых есть
                "{{ \Illuminate\Support\Facades\Request::input('name') }}"
                не найдено.
            @else
                Товаров еще нет в каталоге.
            @endif
        </p>
    @endif

    @if ($products->lastPage() > 1)
        <div class="my-3">
            {!! $products->links() !!}
        </div>
    @endif
@endsection
