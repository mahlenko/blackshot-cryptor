<div class="d-flex flex-column flex-md-row align-items-start justify-content-between">
    <h2>{{ $category->name ?? __('catalog.feature.title') }}</h2>

    <a href="{{ route('admin.catalog.feature.edit', ['locale' => app()->getLocale()]) }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i>
        {{ __('catalog.feature.create') }}
    </a>
</div>
