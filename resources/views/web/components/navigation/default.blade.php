{{-- @dump($navigation) --}}
<nav {{ $attributes }}>
    <div class="main_menu--close_button">
        <svg class="main_menu--close_icon icon"><use xlink:href="img/svg-sprite.svg#cross"></use></svg>
    </div>

    @foreach($navigation->items as $item)
    <div class="main_menu--item main_submenu">
        <!-- .main_submenu стили для подменю -->
        <a href="{{ $item->children ? 'javascript:void(0);' : $item->url }}" class="main_menu--lnk main_submenu--btn">
            {{ $item->text }}
            @if (isset($item->children))
                <svg class="main_submenu--icon icon">
                    <use xlink:href="{{ asset('img/svg-sprite.svg#caret') }}"></use>
                </svg>
            @endif
        </a>

        <!-- Подменю раскрывается при наведении только в разрешениях более 1024px -->
        @if ($item->children)
        <div class="main_submenu--list">
            @foreach($item->children as $child)
                @include($child->template ?? 'web.components.navigation.items.default', ['child' => $child])
            @endforeach
        </div>
        @endif
    </div>
    @endforeach
</nav>
