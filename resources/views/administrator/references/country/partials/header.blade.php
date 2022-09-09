<div class="d-flex flex-column flex-md-row align-items-start justify-content-between">
    <h2>{{ __('countries.title') }}</h2>

    <a href="{{ route('admin.references.country.edit', ['locale' => app()->getLocale()]) }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i>
        {{ __('countries.create') }}
    </a>
</div>
