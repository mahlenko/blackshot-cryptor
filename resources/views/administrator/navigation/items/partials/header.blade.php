<div class="d-flex align-items-start justify-content-between border-bottom">
    <div class="d-flex">
        <img src="{{ asset('administrator/icons/folder.png') }}" class="me-2" alt="" height="32">
        <div>
            <h2 class="mb-1">{{ $navigation->name }}</h2>

            @if ($navigation->description)
                <p class="mb-1">{{ $navigation->description }}</p>
            @endif

            {{-- Управление меню --}}
            <ul class="list-inline">
                <li class="list-inline-item">
                    <a href="{{ route('admin.navigation.edit', ['uuid' => $navigation->uuid]) }}" class="text-secondary">
                        <small>
                            <i class="fas fa-pencil-alt me-2"></i>{{ __('navigation.edit') }}
                        </small>
                    </a>
                </li>

                <li class="list-inline-item">
                    <form action="{{ route('admin.navigation.delete') }}" method="post" onsubmit="return confirm('{{ __('navigation.delete_confirm', ['name' => $navigation->name]) }}')">
                        @csrf
                        <button name="uuid" value="{{ $navigation->uuid }}" class="btn btn-sm btn-link text-danger">
                            <i class="fas fa-trash-alt me-1"></i>{{ __('navigation.delete') }}
                        </button>
                    </form>
                </li>

                @if ($navigation->key)
                <li class="list-inline-item">
                    <small class="text-success" title="{{ __('navigation.columns.key') }}">
                        <i class="fas fa-key"></i>
                        {{ $navigation->key }}
                    </small>
                </li>
                @endif

                <li class="list-inline-item">
                    <small class="text-secondary">
                        {{ $navigation->created_at }}
                    </small>
                </li>
            </ul>
        </div>
    </div>

    <a href="{{ route('admin.navigation.items.edit', ['uuid' => $navigation->uuid, 'locale' => app()->getLocale(), 'parent_id' => \Illuminate\Support\Facades\Request::input('parent_id')]) }}" class="btn btn-primary">
        <i class="far fa-file-alt me-1"></i>
        {{ __('navigation.items.add') }}
    </a>
</div>
