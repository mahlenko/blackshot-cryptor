<li class="list-item"@if (empty($parent_uuid))data-uuid="{{ $node->uuid }}" @endif>
    @if (empty($parent_uuid))
        <a href="javascript:void(0);" data-drag="true" title="{{ __('nested.action') }}">
            <i class="fas fa-sort"></i>
        </a>
    @endif

    <div class="item-icon">
        @if ($count = $node->children->count())
            <img src="{{ asset('administrator/icons/folder.png') }}" alt="">
        @else
            <img src="{{ asset('administrator/icons/link.png') }}" alt="">
        @endif
    </div>

    <div class="flex-fill ps-1">
        <span class="link-edit">
        @if ($count && empty($parent_uuid))
            <a href="{{ route('admin.navigation.items.home', ['navigation_uuid' => $node->navigation->uuid, 'parent_id' => $node->uuid]) }}">{{ $node->name }}</a>
            <i class="fas fa-long-arrow-alt-right"></i>
        @else
            <a href="{{ route('admin.navigation.items.edit', ['navigation_uuid' => $node->navigation->uuid, 'uuid' => $node->uuid]) }}">{{ $node->name }}</a>
            <i class="fas fa-pencil-alt"></i>
        @endif
        </span>

        <br>
        <ul class="list-inline">
            <li class="list-inline-item">
                @if($node->is_active)
                    <small class="text-success">{{ __('navigation.items.is_active')[$node->is_active] }}</small>
                @else
                    <small class="text-danger">{{ __('navigation.items.is_active')[$node->is_active] }}</small>
                @endif
            </li>
            @if ($count)
                <li class="list-inline-item">
                    <small class="text-secondary">
                        {{ $count }}
                        {{ trans_choice('navigation.choice.pages', $count, []) }}
                    </small>
                </li>
            @endif
        </ul>
    </div>

    {{-- Meta теги --}}
    <div class="text-end">

    </div>

    {{-- Время публикации --}}
    <span>
        <small class="text-secondary">{{ __('navigation.columns.updated_at') }}:</small><br>
        {{ $item->updated_at }}
    </span>

    {{-- Время создания страницы --}}
    <span>
        <small class="text-secondary">{{ __('navigation.columns.created_at') }}:</small><br>
        {{ $item->created_at }}
    </span>

    {{-- Удаление --}}
    <div class="d-flex col-md-1 flex-nowrap">
        <a href="{{ route('admin.navigation.items.edit', ['navigation_uuid' => $node->navigation->uuid, 'uuid' => $node->uuid]) }}" class="btn btn-sm text-secondary">
            <i class="fas fa-pencil-alt"></i>
        </a>
        @include('administrator.resources.delete-form', ['route_delete' => route('admin.navigation.items.delete')])
    </div>
</li>
