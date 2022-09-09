<li class="list-item" data-uuid="{{ $item->uuid }}">
    {{-- Объект для сортировки записей --}}
    @isset($routes['sortable'])
        <a href="javascript:void(0);" data-drag="true" title="{{ __('nested.action') }}">
            <i class="fas fa-sort"></i>
        </a>
    @endif

    {{-- Иконка элемента --}}
    <div class="item-icon">
        <picture>
            <source type="image/webp" srcset="{{ asset('administrator/icons/navigation.webp') }}">
            <img src="{{ asset('administrator/icons/navigation.png') }}" alt="">
        </picture>
    </div>

    {{-- Основные данные по элементу --}}
    <div class="flex-fill ps-1">
        <span class="link-edit">
            <a href="{{ route('admin.navigation.items.home', ['uuid' => $item->uuid]) }}">
                {{ $item->name }}</a>
            <i class="fas fa-long-arrow-alt-right"></i>
        </span>

        <br>
        {{-- Дополнительная информация по меню: ключ, комментарий --}}
        <ul class="list-inline">
            {{-- ключ доступа к меню --}}
            <li class="list-inline-item">
                <small class="text-success" title="{{ __('navigation.columns.key') }}">
                    <i class="fas fa-key"></i>
                    &lt;x-navigation key="{{ $item->key }}"&gt;
                </small>
            </li>

            {{-- комментарий --}}
            @if ($item->description)
                <li class="list-inline-item">
                    <small class="text-secondary">
                        <i class="far fa-comment"></i>
                        {{ \Illuminate\Support\Str::limit($item->description, 50) }}
                    </small>
                </li>
            @endif
        </ul>
    </div>

    {{-- Время создания --}}
    <span class="col-md-2 text-nowrap">
        <small class="text-secondary">{{ __('page.columns.date_create') }}:</small><br>
        <small>{{ $item->created_at }}</small>
    </span>

    {{-- Удаление --}}
    <div class="d-flex col-md-1 flex-nowrap">
        <a href="{{ route('admin.navigation.items.home', ['uuid' => $item->uuid]) }}" class="btn btn-sm text-secondary">
            <i class="fas fa-pencil-alt"></i>
        </a>
        @include('administrator.resources.delete-form', ['route_delete' => route('admin.navigation.delete')])
    </div>
</li>
