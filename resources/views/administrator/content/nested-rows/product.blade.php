<li class="list-item" data-uuid="{{ $item->uuid }}">
    {{-- Объект для сортировки записей --}}
    @isset($routes['sortable'])
        <a href="javascript:void(0);" data-drag="true" title="{{ __('nested.action') }}">
            <i class="fas fa-sort"></i>
        </a>
    @endif

    {{-- Иконка элемента --}}
    @if (isset($item->images) && $item->images->count())
    <a
        href="{{ \Illuminate\Support\Facades\Storage::url($item->images->first()->fullName()) }}"
        data-type="image"
        class="item-icon"
        style="width: 50px;"
        title="{{ $item->name }} {{ $item->images->first()->name }}"
    >
        <img src="{{ \Illuminate\Support\Facades\Storage::url($item->images->first()->thumbnail()) }}" class="rounded" alt="">
    </a>
    @endif

    {{-- Основные данные по элементу --}}
    <div class="flex-fill ps-1">
        <span class="link-edit">
            <a href="{{ route($routes['edit'], ['uuid' => $item->uuid, 'locale' => $item->translate()->locale]) }}">{{ $item->name }}</a>
            <i class="fas fa-pencil-alt"></i>
        </span>

        <br>
        <small class="text-secondary">{{ $item->categories->pluck('name')->join(', ') }}</small>
    </div>

    {{-- Meta теги --}}
    @if (isset($item->meta))
        <div class="text-end" title="{{ __('meta.descriptions.robots') }}">
            @foreach(explode(', ', $item->meta->robots) as $val)
                <span class="badge bg-light @if(in_array($val, ['all', 'index', 'follow']))text-success @else text-danger @endif">{{ $val }}</span>
            @endforeach

            @if ($item->meta->title)
                <br><i class="fas fa-chart-line text-success ms-1"></i>
                <small class="text-secondary" title="{{ __('meta.columns.title') }}">
                    {{ \Illuminate\Support\Str::limit($item->meta->title, 30) }}
                </small>
            @endif
        </div>
    @else
        @isset ($trans_choice)
            <small class="text-secondary">
                {{ $children_objects_count }}
                {{ trans_choice($trans_choice, $children_objects_count) }}
            </small>
        @endif
    @endif

    {{-- Время создания --}}
    <span>
        <small class="text-secondary">{{ __('page.columns.date_create') }}:</small><br>
        {{ $item->created_at }}
    </span>

    {{-- Действия --}}
    <div class="d-flex flex-nowrap">
        {{-- Сделает копию товара --}}
        <form action="{{ route('admin.catalog.product.copy') }}" method="post">
            @csrf
            <button name="uuid" value="{{ $item->uuid }}" class="btn btn-link text-secondary">
                <i class="far fa-copy"></i>
            </button>
        </form>

        {{-- Удалит товар --}}
        <form action="{{ route($routes['delete']) }}" method="post" onsubmit="return confirm('{{ __('page.delete_confirm', ['name' => $item->name]) }}')">
            @csrf
            <button name="uuid" value="{{ $item->uuid }}" class="btn btn-link text-danger">
                <i class="fas fa-trash-alt"></i>
            </button>
        </form>
    </div>
</li>
