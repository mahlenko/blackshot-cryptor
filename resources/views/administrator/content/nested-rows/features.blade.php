<li class="list-item" data-uuid="{{ $item->uuid }}">
    {{-- Объект для сортировки записей --}}
    @isset($routes['sortable'])
        <a href="javascript:void(0);" data-drag="true" title="{{ __('nested.action') }}">
            <i class="fas fa-sort"></i>
        </a>
    @endif

    {{-- Иконка элемента --}}
    <div class="item-icon">
        @if ($children_objects_count = $item->children->get()->toFlatTree()->count())
            <img src="{{ asset('administrator/icons/folder.png') }}" alt="">
        @else
            <img src="{{ asset('administrator/icons/file.png') }}" alt="">
        @endif
    </div>

    {{-- Основные данные по элементу --}}
    <div class="flex-fill ps-1">
        <span class="link-edit">
            @if ($children_objects_count)
                <a href="{{ route($routes['home'], ['uuid' => $item->uuid]) }}">{{ $item->name }}</a>
                <i class="fas fa-long-arrow-alt-right"></i>
            @else
                <a href="{{ route($routes['edit'], ['uuid' => $item->uuid, 'locale' => $item->translate()->locale]) }}">{{ $item->name }}</a>
                <i class="fas fa-pencil-alt"></i>
            @endif
        </span>

        <br>
        <small class="text-secondary">
            <i class="fas fa-crosshairs text-primary"></i>
            {{ $item->getPurposeTitle() }}
        </small>
    </div>


    {{-- Категории --}}
    <div class="w-25">
        <small class="text-secondary">{{ __('catalog.feature.categories') }}</small>
        <p>
            <small>
                @if ($item->categories && $item->categories->count())
{{--                    @dump($item->categories)--}}
                    @php($limit = 3)
                    @foreach ($item->categories as $index => $c)
                        @if($index < $limit)
                        {{ $c->category->name }}@if($index + 1 != $item->categories->count() && $index < $limit - 1), @endif
                        @endif
                    @endforeach

                    @if ($item->categories->count() > $limit)
                        &hellip;
                        {{ __('catalog.feature.and_more_categories', ['count' => $item->categories->count() - $limit]) }}
                        {{ trans_choice('catalog.feature.choice_category', $item->categories->count() - $limit) }}
                    @endif
                @else
                    {{ $item->category ?? __('catalog.category.all') }}
                @endif
            </small>
        </p>
    </div>

    {{-- Группа характеристик --}}
    <div>
        <small class="text-secondary">Группа характеристик</small>
        <p>
            <small>
                {{ $item->feature_group_uuid ? $item->group->name : __('catalog.feature.group.no_group') }}
            </small>
        </p>
    </div>


    {{-- Время создания --}}
    <span>
        <small class="text-secondary">{{ __('page.columns.date_create') }}:</small><br>
        {{ $item->created_at }}
    </span>

    {{-- Удаление --}}
    @if($children_objects_count)
        @php($message_delete = __('page.delete_category_confirm', ['name' => $item->name]))
    @else
        @php($message_delete = __('page.delete_confirm', ['name' => $item->name]))
    @endif

    <form action="{{ route($routes['delete']) }}" method="post" onsubmit="return confirm('{{ $message_delete }}')">
        @csrf
        <button name="uuid" value="{{ $item->uuid }}" class="btn btn-link text-danger">
            <i class="fas fa-trash-alt"></i>
        </button>
    </form>
</li>
