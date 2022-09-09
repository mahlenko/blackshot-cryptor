<div class="main_submeny--subitem {{ $child->children ? 'main_submenu' : null }}">
    <a href="{{ $child->url }}" class="main_submenu--lnk">
        {{ $child->text }}
        @if ($child->children && $child->children->count())
        <svg class="icon main_submenu--icon main_submenu--icon-small">
            <use xlink:href="{{ asset('img/svg-sprite.svg#caret') }}"></use>
        </svg>
        @endif
    </a>

    @if ($child->children && $child->children->count())
        <div class="main_submenu--list">
        @foreach($child->children as $ch_item)
            <div class="main_submenu--subitem">
                <a href="{{ route('view', $ch_item->url) }}" class="main_submenu--lnk">
                    {{ $ch_item->text }}
                </a>
            </div>
        @endforeach
        </div>
    @endif
</div>
