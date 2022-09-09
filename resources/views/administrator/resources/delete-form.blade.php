@if (isset($route_delete) && $route_delete)
    <form action="{{ $route_delete }}" method="post" id="delete_{{ $item->uuid }}" onsubmit="return confirm('{{ $message_confirm ?? __('global.confirm.delete_force') }}')">
        @csrf
        <button type="submit" class="btn btn-sm text-danger" name="uuid" value="{{ $item->uuid }}">
            @if(isset($show_text) && $show_text)
                <i class="fas fa-trash-alt me-2"></i>{{ __('global.delete') }}
            @else
                <i class="fas fa-trash-alt"></i>
            @endif
        </button>
    </form>
@else
    <p class="text-secondary">
        Не указан $route_delete.
    </p>
@endif
