<li class="list-item justify-content-between" data-uuid="{{ $feature->uuid }}">
    {{-- Объект для сортировки записей --}}
    <a href="javascript:void(0);" data-drag="true" title="{{ __('nested.action') }}">
        <i class="fas fa-sort"></i>
    </a>

    <div class="flex-fill">
        <img src="{{ asset('administrator/icons/file.png') }}" class="me-2" alt="" height="20">
        <span class="link-edit">
            <a href="{{ route('admin.catalog.feature.edit', ['locale' => app()->getLocale(), 'uuid' => $feature->uuid]) }}">
                {{ $feature->name }}
            </a>
            <i class="fas fa-pencil-alt"></i>
        </span>

        @include('administrator.resources.translation-item-variants', ['object' => $feature])

        <p class="mt-1 mb-0">
            <small>
                <i class="fas fa-crosshairs text-primary"></i>
                {{ __('catalog.feature.purposes')[$feature->purpose] }}
            </small>
            <br>

            <small class="text-secondary">{{ __('catalog.feature.columns.view_product.title') }}:</small>
            <span class="badge text-success">
                @if (key_exists($feature->view_product, __('catalog.feature.options')))
                    {{ __('catalog.feature.options')[$feature->view_product] }}
                @else
                    {{ $feature->view_product }}
                @endif
            </span>

            <br>
            <small class="text-secondary">{{ __('catalog.feature.columns.view_filter') }}:</small>
            @if (key_exists($feature->view_filter, __('catalog.feature.options')))
                <span class="badge text-success">
                    {{ __('catalog.feature.options')[$feature->view_filter] }}
                </span>
            @else
                <span class="badge text-secondary">
                    {{ $feature->view_filter ?? $feature->purpose !== 'organize_catalog' ? __('catalog.feature.hidden') : __('catalog.feature.use_template') }}
                </span>
            @endif
        </p>
    </div>

    <div class="col-md-3">
        <small>
            <span class="text-secondary">
                {{ __('catalog.feature.columns.group') }}:
            </span>
            @if ($feature->group)
                {{ $feature->group->name }}
            @else
                <strong>{{ __('catalog.feature.all_categories') }}</strong>
            @endif
        </small>
        <br>
        @if ($feature->products->count())
            <small>
                {{ trans_choice(__('catalog.feature.choice_used_product', ['count' => $feature->products->count()]), $feature->products->count()) }}
            </small>
        @else
            <small class="text-secondary">
                <i class="fas fa-exclamation-triangle text-warning"></i>
                {{ __('catalog.feature.unused_product') }}
            </small>
        @endif

{{--        @if ($group->features->count())--}}
{{--            <small class="text-secondary">{{ __('catalog.feature.title') }}:</small><br>--}}
{{--            <small>--}}
{{--                {{ $group->features->slice(0, 3)->pluck('name')->join(', ') }}--}}
{{--                @if ($group->features->count() > 3)--}}
{{--                    {{ trans_choice(__('catalog.feature.choice_and_more', ['count' => $group->features->count() - 3]), $group->features->count() - 3) }}--}}
{{--                @endif--}}
{{--            </small>--}}
{{--        @endif--}}
    </div>

    <div class="col-md-2">
        <p class="mb-0">
            <small>
                <span class="text-secondary">{{ __('global.created_at') }}:</span><br>
                {{ $feature->created_at }}
            </small>
        </p>
    </div>

    {{-- Удаление --}}
    <div class="d-flex col-md-2 justify-content-end">
        <a href="{{ route('admin.catalog.feature.edit', ['locale' => app()->getLocale(), 'uuid' => $feature->uuid]) }}" class="btn btn-sm text-secondary">
            <i class="fas fa-pencil-alt"></i>
        </a>
        @include('administrator.resources.delete-form', ['route_delete' => route('admin.catalog.feature.delete'), 'item' => $feature])
    </div>
</li>
