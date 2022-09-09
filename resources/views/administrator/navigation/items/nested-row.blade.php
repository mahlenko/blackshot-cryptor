<li class="list-item"@if (empty($parent_uuid))data-uuid="{{ $node->uuid }}" @endif>
    @if (empty($parent_uuid))
        <a href="javascript:void(0);" data-drag="true" title="{{ __('nested.action') }}">
            <i class="fas fa-sort"></i>
        </a>
    @endif

    <div class="item-icon">
        @if ($node->isLeaf())
            <pictire>
                <source type="image/webp" srcset="{{ asset('administrator/icons/chain.webp') }}">
                <img src="{{ asset('administrator/icons/chain.png') }}" alt="">
            </pictire>
        @else
            <pictire>
                <source type="image/webp" srcset="{{ asset('administrator/icons/list.webp') }}">
                <img src="{{ asset('administrator/icons/list.png') }}" alt="">
            </pictire>
        @endif
    </div>

    <div class="flex-fill ps-1">
        <span class="link-edit">
        @if ($node->isLeaf())
            <a href="{{ route('admin.navigation.items.edit', ['uuid' => $navigation->uuid, 'locale' => app()->getLocale(), 'navigation_item' => $node->uuid]) }}">{{ $node->name }}</a>
            <i class="fas fa-pencil-alt"></i>
        @else
            <a href="{{ route('admin.navigation.items.home', ['uuid' => $navigation->uuid, 'parent_id' => $node->uuid]) }}">{{ $node->name }}</a>
            <i class="fas fa-long-arrow-alt-right"></i>
        @endif
        </span>

        <br>
        <ul class="list-inline">
            @if (count(config('translatable.locales')) > 1)
            <li class="list-inline-item">
                @include('administrator.resources.translation-item-variants', ['object' => $node])
            </li>
            @endif

            <li class="list-inline-item flex-fill">
                @if($node->is_active)
                    <small class="text-success">{{ __('navigation.items.is_active')[$node->is_active] }}</small>
                @else
                    <small class="text-danger">{{ __('navigation.items.is_active')[$node->is_active] }}</small>
                @endif
            </li>

            @if ($node->url && $node->meta && $meta_object = $meta_objects[$node->meta->object_type]->find($node->meta->object_id))
                <li class="list-inline-item">
                    <small class="text-secondary">
                        <i class="fas fa-link"></i>
                        <a href="{{ route('view', ['slug' => $node->url]) }}" class="text-secondary" target="_blank">
                            {{ $meta_object->name }}</a>

                    </small>
                    @if ($meta_object->ancestors->count() > 1)
                    <small class="px-1 text-secondary">
                        ({{ $meta_object->ancestors->reverse()->slice(1)->pluck('name')->join(' / ') }})
                    </small>
                    @endif
                </li>
            @endif
            @if (!$node->isLeaf())
                <li class="list-inline-item">
                    <small class="text-secondary">
{{--                        {{ $count = $node->descendantsOf($node)->count() }}--}}
                        {{ $count = $node->descendants->count() }}
                        {{ trans_choice('navigation.choice.pages', $count) }}
                    </small>
                </li>
            @endif
        </ul>

        @if ($node->generate_catalog || $node->generate_products)
            <ul class="list-unstyled">
                <li>
                    <small class="text-secondary">
                        <i class="fas fa-check-circle @if ($node->generate_catalog)text-primary @else text-secondary @endif"></i>
                        {{ __('navigation.generate.catalog', ['name' => $meta_object->name]) }}
                    </small>
                </li>

                <li>
                    <small class="text-secondary">
                        <i class="fas fa-check-circle @if ($node->generate_products)text-primary @else text-secondary @endif"></i>
                        {{ __('navigation.generate.products', ['name' => $meta_object->name]) }}
                    </small>
                </li>
            </ul>
        @endif
    </div>

    {{-- Время создания страницы --}}
    <span class="col-md-2">
        <small class="text-secondary">{{ __('navigation.columns.created_at') }}:</small><br>
        <small>{{ $item->created_at }}</small>
    </span>

    {{-- Удалить и редактировать --}}
    <span class="d-flex justify-content-end">
        <a href="{{ route('admin.navigation.items.edit', ['uuid' => $navigation->uuid, 'locale' => app()->getLocale(), 'navigation_item' => $node->uuid]) }}" class="btn btn-sm text-secondary">
            <i class="fas fa-pencil-alt"></i>
        </a>

        {{-- Удаление --}}
        @include('administrator.resources.delete-form', ['route_delete' => route('admin.navigation.items.delete')])
    </span>
</li>
