<li class="list-item" data-uuid="{{ $item->uuid }}">
    {{-- Объект для сортировки записей --}}
    @isset($routes['sortable'])
        <a href="javascript:void(0);" data-drag="true" title="{{ __('nested.action') }}">
            <i class="fas fa-sort"></i>
        </a>
    @endif

    {{-- Аватар --}}
    <div class="item-icon" style="width: 50px;">
        <a
            class="item-icon"
            @if (isset($item->avatar))
            href="{{ \Illuminate\Support\Facades\Storage::url($item->avatar->fullName()) }}"
            data-type="image"
            @endif
            style="width: 50px;"
        >
            <img src="{{ $item->getAvatarUrl() }}" class="rounded" alt="">
        </a>
    </div>

    {{-- Основные данные по элементу --}}
    <div class="flex-fill ps-1">
        <span class="link-edit">
            <a href="{{ route($routes['edit'], ['id' => $item->id, 'locale' => app()->getLocale()]) }}">{{ $item->name }}</a>
            <i class="fas fa-pencil-alt"></i>
        </span>

        <br>
        <span class="text-secondary">
            {{ $item->email }}
        </span>
    </div>

    <div class="col-md-2">
        {{ __('user.columns.role') }}:<br>
        @if ($item->isAdmin())
            <span class="text-success">{{ __('user.roles')[$item->role] }}</span>
        @else
            <span class="text-secondary">{{ __('user.roles')[$item->role] }}</span>
        @endif
    </div>

    {{-- Время создания --}}
    <span class="col-md-2">
        <small class="text-secondary">{{ __('user.columns.created_at') }}:</small><br>
        {{ $item->created_at }}
    </span>

    {{-- Удаление --}}
    <div class="col-md-1">
        @if (\Illuminate\Support\Facades\Auth::user()->created_at < $item->created_at)
        <form action="{{ route($routes['delete']) }}" method="post" onsubmit="return confirm('{{ __('page.delete_confirm', ['name' => $item->name]) }}')">
            @csrf
            <button name="id" value="{{ $item->id }}" class="btn btn-link text-danger">
                <i class="fas fa-trash-alt"></i>
            </button>
        </form>
        @endif
    </div>
</li>
