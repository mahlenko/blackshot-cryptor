<div class="d-flex flex-column flex-md-row align-items-start justify-content-between">
    <div>
        <h2>{{ __('widget.title') }}</h2>
        <p class="text-secondary">{{ __('widget.description') }}</p>
    </div>

    <a href="{{ route('admin.widget.edit', ['locale' => app()->getLocale()]) }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i>
        {{ __('widget.create') }}
    </a>
</div>
