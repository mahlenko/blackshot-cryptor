<div class="d-flex flex-column flex-md-row align-items-start justify-content-between">
    <div class="d-flex align-items-start">
        @if ($category && $category->parent_id)
            <a
                class="btn btn-link text-secondary border me-2"
                href="{{ route('admin.catalog.category.home', ['uuid' => $category->ancestors->sortByDesc('_lft')->first()->uuid ?? null]) }}"
            >
                <i class="fas fa-arrow-left"></i>
            </a>
        @endif

        <div>
            <h2>{{ $category->parent_id ? $category->name : __('catalog.category.title') }}</h2>
            @if ($category)
                <ul class="list-inline">
                    <li class="list-inline-item">
                        <a
                            href="{{ route('admin.catalog.category.edit', ['locale' => app()->getLocale(), 'uuid' => $category->uuid ?? null]) }}"
                            class="{{ $category->parent_id ? 'text-secondary' : 'text-success' }}">
                            <small>
                                @if ($category->parent_id)
                                    <i class="fas fa-pencil-alt me-2"></i>{{ __('global.edit') }}
                                @else
                                    <i class="fas fa-cog me-2"></i>{{ __('nested.root') }}
                                @endif
                            </small>
                        </a>
                    </li>

                    @if ($category->parent_id)
                    <li class="list-inline-item">
                        <small>
                            @include('administrator.resources.delete-form', ['route_delete' => route('admin.catalog.category.delete'), 'item' => $category, 'show_text' => true])
                        </small>
                    </li>
                    @endif

                    <li class="list-inline-item">
                        <small class="text-secondary">{{ __('meta.columns.robots') }}:</small>
                        @include('administrator.resources.meta-robots-badge', ['robots' => $category->meta->robots ?? null])
                    </li>

                    <li class="list-inline-item">
                        <small class="text-secondary">
                            {{ __('global.created_at') }}: {{ $category->created_at }}
                        </small>
                    </li>
            </ul>
            @endif
        </div>
    </div>

    <a href="{{ route('admin.catalog.category.edit', ['locale' => app()->getLocale(), 'parent_uuid' => $category->uuid ?? null]) }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i>
        {{ __('catalog.category.create') }}
    </a>
</div>
