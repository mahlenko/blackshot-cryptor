{{-- Заголовок страницы со списком записей --}}
<div class="d-flex align-items-start justify-content-between">
    @if ($object)
        <div class="d-flex">
            {{-- Иконка --}}
            @if (($object->parent_id && $object instanceof \App\Models\Page\Page) || !$object instanceof \App\Models\Page\Page)
                <picture>
                    <source type="image/webp" srcset="{{ asset('administrator/icons/folder.webp') }}">
                    <img src="{{ asset('administrator/icons/folder.png') }}" class="me-2" alt="" height="32">
                </picture>
            @endif
            <span>
                {{-- Название категории --}}
                <h2>
                    @if (($object->parent_id && $object instanceof \App\Models\Page\Page) || !$object instanceof \App\Models\Page\Page)
                        {{ \Illuminate\Support\Str::limit(\Illuminate\Support\Str::ucfirst($object->name), 25) }}
                    @else
                        {{ __('page.title') }}
                    @endif
                </h2>

                {{-- Управление страницей --}}
                <ul class="list-inline mb-0">
                    <li class="list-inline-item">
                        <a
                            href="{{ route($routes['edit'], ['locale' => app()->getLocale(), 'uuid' => $object->uuid]) }}"
                            class="@if (($object->parent_id && $object instanceof \App\Models\Page\Page) || !$object instanceof \App\Models\Page\Page)text-secondary @else text-success @endif"
                        >
                            <small>
                                @if (($object->parent_id && $object instanceof \App\Models\Page\Page) || !$object instanceof \App\Models\Page\Page)
                                    <i class="fas fa-pencil-alt me-2"></i>{{ __('page.edit_category') }}
                                @else
                                    <i class="fas fa-cog me-2"></i>{{ __('nested.root') }}
                                @endif
                            </small>
                        </a>
                    </li>

                    @if (($object->parent_id && $object instanceof \App\Models\Page\Page) || !$object instanceof \App\Models\Page\Page)
                    <li class="list-inline-item">
                        <form action="{{ route($routes['delete']) }}" method="post" onsubmit="return confirm('{{ __('catalog.category.delete_category_confirm', ['name' => $object->name]) }}')">
                            @csrf
                            <button name="uuid" value="{{ $object->uuid }}" class="btn btn-sm btn-link text-danger">
                                <i class="fas fa-trash-alt me-1"></i>{{ __('page.delete_category') }}
                            </button>
                        </form>
                    </li>
                    @endif

                    <li class="list-inline-item">
                        <small class="text-success" title="{{ __('meta.descriptions.robots') }}">
                            @foreach(explode(',', $object->meta->robots) as $label)
                                <span class="badge bg-light text-success">{{ $label }}</span>
                            @endforeach
                        </small>
                    </li>

                    <li class="list-inline-item">
                        <small class="text-secondary">
                            {{ $object->created_at }}
                        </small>
                    </li>
                </ul>
            </span>
        </div>
    @else
        <h2>{{ \Illuminate\Support\Str::ucfirst($header['title']) }}</h2>
    @endif

    @if (isset($routes['create']) && $header['create'])
        <a href="{{ $routes['create'] }}" class="btn btn-primary">
            <i class="far fa-file-alt me-1"></i>
            {{ $header['create'] }}
        </a>
    @endif
</div>

<hr>
