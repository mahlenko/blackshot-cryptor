<div class="card">
    <div class="card-header">
        {{ __('finder.title') }}
    </div>
    <ol class="list-group list-group-flush">
        <li class="list-group-item d-flex justify-content-between align-items-start">
            <div class="ms-2 me-auto">
                <a href="{{ route('admin.finder.home') }}">{{ __('finder.title') }}</a>
            </div>
            <small>{{ $files }}</small>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-start">
            <div class="ms-2 me-auto">
                <span>{{ __('finder.downloaded') }}</span>
            </div>
            <small>{{ $downloads }}</small>
        </li>
    </ol>
</div>
