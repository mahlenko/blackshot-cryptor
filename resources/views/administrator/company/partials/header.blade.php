<div class="d-flex flex-column flex-md-row align-items-start justify-content-between">
    <div>
        <h2>{{ __('company.title') }}</h2>
        <p class="text-secondary">{{ __('company.description') }}</p>
    </div>

    <a href="{{ route('admin.company.edit', ['locale' => app()->getLocale()]) }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i>
        {{ __('company.create') }}
    </a>
</div>
