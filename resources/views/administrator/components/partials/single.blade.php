<li class="nav-item">
    <a class="nav-link text-nowrap {{ \Illuminate\Support\Facades\Request::routeIs($_route) ? 'active' : null }}" aria-current="page" href="{{ route($item['route_name'], $item['route_data'] ?? []) }}">
        <span class="nav-icon">
            {!! $item['icon'] !!}
        </span>
        {{ $item['text'] }}
    </a>
</li>
