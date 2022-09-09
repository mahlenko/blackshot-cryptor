<li class="list-item justify-content-between" data-uuid="{{ $group->uuid }}">
    {{-- Объект для сортировки записей --}}
    <a href="javascript:void(0);" data-drag="true" title="{{ __('nested.action') }}">
        <i class="fas fa-sort"></i>
    </a>

    <div class="flex-fill">
        <img src="{{ asset('administrator/icons/file.png') }}" class="me-2" alt="" height="20">
        <span class="link-edit">
            <a href="{{ route('admin.catalog.feature.group.edit', ['locale' => app()->getLocale(), 'uuid' => $group->uuid]) }}">
                {{ $group->name }}
            </a>
            <i class="fas fa-pencil-alt"></i>
        </span>

        @include('administrator.resources.translation-item-variants', ['object' => $group])

        <p>
            <small>
                @if ($group->features->count())
                    {{ trans_choice(__('catalog.feature.group.features_choice', ['count' => $group->features->count()]), $group->features->count()) }}
                @else
                    {{ __('catalog.feature.group.no_features') }}
                @endif
            </small>
        </p>
    </div>

    <div class="col-md-3">
        @if ($group->features->count())
            <small class="text-secondary">{{ __('catalog.feature.title') }}:</small><br>
            <small>
                {{ $group->features->slice(0, 3)->pluck('name')->join(', ') }}
                @if ($group->features->count() > 3)
                    {{ trans_choice(__('catalog.feature.choice_and_more', ['count' => $group->features->count() - 3]), $group->features->count() - 3) }}
                @endif
            </small>
        @endif
    </div>

    <div class="col-md-2">
        <p>
            <small>
                <span class="text-secondary">{{ __('global.created_at') }}:</span><br>
                {{ $group->created_at }}
            </small>
        </p>
    </div>

    {{-- Удаление --}}
    <div class="d-flex col-md-2 justify-content-end">
        <a href="{{ route('admin.catalog.feature.group.edit', ['locale' => app()->getLocale(), 'uuid' => $group->uuid]) }}" class="btn btn-sm text-secondary">
            <i class="fas fa-pencil-alt"></i>
        </a>
        @include('administrator.resources.delete-form', ['route_delete' => route('admin.catalog.feature.group.delete'), 'item' => $group])
    </div>
</li>
