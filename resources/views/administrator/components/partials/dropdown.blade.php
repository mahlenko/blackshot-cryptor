<li class="nav-item dropdown dropend">
    <a class="nav-link text-nowrap dropdown-toggle {{ \Illuminate\Support\Facades\Request::routeIs($_route) ? 'active' : null }}" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <span class="nav-icon">
            {!! $item['icon'] !!}
        </span>
        {{ $item['text'] }}
    </a>
    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
        @foreach($item['children'] as $child)
            @if ($child['title'])
                <li><h6 class="dropdown-header">{{ $child['title'] }}</h6></li>
            @endif
            @foreach($child['items'] as $i)
                <li>
                    <a href="{{ route($i['route_name']) }}" class="dropdown-item {{ \Illuminate\Support\Facades\Request::routeIs($i['route_name']) ? 'active' : null }}">
                        @if (!empty($i['icon']))<span class="dropdown-icon">{!! $i['icon'] !!}</span>@endif
                        {{ $i['text'] }}
                    </a>
                </li>
                @if ($loop->last && !$loop->parent->last)
                    <li><hr class="dropdown-divider"></li>
                @endif
            @endforeach
        @endforeach
    </ul>
</li>
