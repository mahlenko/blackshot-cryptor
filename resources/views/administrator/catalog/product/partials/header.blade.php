<div class="d-flex flex-column flex-md-row align-items-start justify-content-between">
    <h2>{{ __('catalog.product.title') }}</h2>

    <a href="{{ route('admin.catalog.product.edit', ['locale' => app()->getLocale()]) }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i>
        {{ __('catalog.product.create') }}
    </a>
</div>
