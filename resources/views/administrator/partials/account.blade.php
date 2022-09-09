<div class="account-dropdown me-3 me-md-0 mb-md-2 border-bottom pb-md-3">
    <div class="dropdown">
        <a
            href="javascript:void(0)"
            title="{{ \Illuminate\Support\Facades\Auth::user()->email }}"
            class="d-flex align-items-center dropdown-toggle text-secondary text-decoration-none"
            type="button"
            id="account"
            data-bs-toggle="dropdown"
            aria-expanded="false"
        >
            <img src="{{ \Illuminate\Support\Facades\Auth::user()->getAvatarUrl() }}" class="rounded" style="height: 50px;" alt="">

            <span class="d-none d-md-flex flex-column ms-2">
                <strong>{{ \Illuminate\Support\Str::limit(\Illuminate\Support\Facades\Auth::user()->name, 15) }}</strong>
                <small>
                    {{ \Illuminate\Support\Str::limit(\Illuminate\Support\Facades\Auth::user()->email, 17) }}
                </small>
            </span>
        </a>

        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-md-start" aria-labelledby="account">
            <li>
                <a class="dropdown-item" href="{{ route('home') }}" target="_blank">
                    <i class="fas fa-external-link-alt me-1"></i>
                    {{ __('global.to_website') }}
                </a>
            </li>
            <hr class="dropdown-divider">
            <li>
                <a class="dropdown-item" href="{{ route('admin.user.edit', ['id' => \Illuminate\Support\Facades\Auth::id()]) }}">
                    <i class="far fa-user me-1"></i>
                    {{ __('auth.profile') }}
                </a>
            </li>
            <li>
                <a
                    class="dropdown-item"
                    href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                >
                    <i class="fas fa-sign-out-alt me-1"></i>
                    {{ __('auth.logout') }}
                </a>
            </li>
        </ul>

        <form id="logout-form" action="{{ route('logout') }}" method="post" class="d-none">
            @csrf
        </form>
    </div>
</div>
