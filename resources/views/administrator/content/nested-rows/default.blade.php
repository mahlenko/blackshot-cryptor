<li class="list-item" data-uuid="{{ $item->uuid }}">
    {{-- Объект для сортировки записей --}}
    @isset($routes['sortable'])
        <a href="javascript:void(0);" data-drag="true" title="{{ __('nested.action') }}">
            <i class="fas fa-sort"></i>
        </a>
    @endif

    {{-- Иконка элемента --}}
    <div class="item-icon">
        @if ($item->isLeaf())
            <picture>
                <source type="image/webp" srcset="{{ asset('administrator/icons/file.webp') }}">
                <img src="{{ asset('administrator/icons/file.png') }}" alt="">
            </picture>
        @else
            <picture>
                <source type="image/webp" srcset="{{ asset('administrator/icons/folder.webp') }}">
                <img src="{{ asset('administrator/icons/folder.png') }}" alt="">
            </picture>
        @endif
    </div>

    {{-- Основные данные по элементу --}}
    <div class="flex-fill ps-1">
        @if (isset($item->meta->is_active))
            <small class="badge-dot {{ $item->meta->is_active ? 'bg-success' : 'bg-danger' }}" title="{{ $item->meta->is_active ? __('global.active') : __('global.unactive') }}"></small>
        @endif
        <span class="link-edit">
            @if ($item->isLeaf())
                <a href="{{ route($routes['edit'], ['uuid' => $item->uuid, 'locale' => app()->getLocale()]) }}">{{ $item->name }}</a>
                <small><i class="fas fa-pencil-alt ms-1"></i></small>
            @else
                <a href="{{ route($routes['home'], ['uuid' => $item->uuid]) }}">{{ $item->name }}</a>
                <small><i class="fas fa-long-arrow-alt-right ms-1"></i></small>
            @endif
        </span>

        <br>
        <ul class="list-inline">
            @if (count(config('translatable.locales')) > 1)
            <li class="list-inline-item">
                @include('administrator.resources.translation-item-variants', ['object' => $item])
            </li>
            @endif

            <li class="list-inline-item">
                <strong>
                    <small>{{ __('meta.columns.link') }}:</small>
                </strong>
                <a href="{{ route('view', $item->meta->url) }}" target="_blank" class="text-secondary">
                    <small>
                        /{{ \Illuminate\Support\Str::limit($item->meta->url, 30) }}
                    </small>
                </a>
            </li>

            <li class="list-inline-item">
                <strong>
                    <small>Robots:</small>
                </strong>
                @foreach(explode(', ', $item->meta->robots) as $val)
                    <span class="badge bg-light @if(in_array($val, ['all', 'index', 'follow']))text-success @else text-danger @endif">{{ $val }}</span>
                @endforeach
            </li>
        </ul>
    </div>

    {{-- Meta теги --}}
    @if ($item->isLeaf() && isset($item->meta))

    @else
        @isset ($trans_choice)
            <small class="text-secondary">
                {{ $children_objects_count }}
                {{ trans_choice($trans_choice, $children_objects_count) }}
            </small>
        @endif
    @endif

    @if (isset($item->meta->publish_at))
        {{-- Время публикации --}}
        <span class="col-md-2 text-nowrap">
            <small class="text-secondary">{{ __('page.columns.publish_at') }}:</small><br>
            <small>{{ $item->meta->publish_at }}</small>
        </span>
    @else
        {{-- Время создания --}}
        <span class="col-md-2 text-nowrap px-2">
            <small class="text-secondary">{{ __('page.columns.date_create') }}:</small><br>
            <small>{{ $item->created_at }}</small>
        </span>
    @endif

    <span class="d-flex col-md-1 justify-content-end">
        <a href="{{ route($routes['edit'], ['uuid' => $item->uuid, 'locale' => app()->getLocale()]) }}" class="btn btn-sm text-secondary">
            <i class="fas fa-pencil-alt"></i>
        </a>

        {{-- Удаление --}}
        @include('administrator.resources.delete-form', ['route_delete' => route($routes['delete'])])
    </span>
</li>
