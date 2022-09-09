<div class="card">
    <div class="card-header">
        {{ __('page.title') }}
    </div>
    <ol class="list-group list-group-flush">
        <li class="list-group-item d-flex justify-content-between align-items-start">
            <div class="ms-2 me-auto">
                <a href="{{ route('admin.page.home') }}">{{ __('page.title') }}</a>
            </div>
            <small>{{ $pages }}</small>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-start">
            <div class="ms-2 me-auto">
                <span>{{ __('page.columns.views') }}</span>
            </div>
            <small>{{ $pages_views }}</small>
        </li>
    </ol>
</div>
