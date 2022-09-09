<div class="card">
    <div class="card-header">
        {{ __('catalog.title') }}
    </div>

    <ol class="list-group list-group-flush">
        <li class="list-group-item d-flex justify-content-between align-items-start">
            <div class="ms-2 me-auto">
                <a href="{{ route('admin.catalog.category.home') }}">{{ __('catalog.category.title') }}</a>
            </div>
            <small>{{ $categories }}</small>
        </li>

        <li class="list-group-item d-flex justify-content-between align-items-start">
            <div class="ms-2 me-auto">
                <a href="{{ route('admin.catalog.product.home') }}">{{ __('catalog.product.title') }}</a>
            </div>
            <small>{{ $products }}</small>
        </li>
    </ol>
</div>
