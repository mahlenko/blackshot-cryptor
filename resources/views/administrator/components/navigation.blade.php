<nav class="navbar navbar-expand-md navbar-light h-100">
    <div class="container-fluid d-flex flex-column align-items-start h-100">
        <div class="d-flex w-100 align-items-center align-items-md-start justify-content-between flex-md-column">
            <a class="navbar-brand text-primary" href="{{ route('admin.home') }}">{{ env('APP_NAME') }}</a>

            <div class="d-flex mt-md-2 mb-md-3">
                @include('administrator.partials.account')
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </div>

        {{--  --}}
        <div class="collapse navbar-collapse align-items-start" id="navbarNav">
            <ul class="navbar-nav d-flex flex-column">
                @foreach($navigations as $item)
                    @php($_route = $item['route_name'])
                    @if ($item['route_name'] != 'admin.home')
                        @php($_route = $_route = \Illuminate\Support\Str::replaceLast('.home', '.*', $item['route_name']))
                    @endif

                    <li class="nav-item">
                        @if (isset($item['children']) && count($item['children']))
                            @php($_route = implode('.', array_slice(explode('.', $item['route_name']), 0, 2)) . '.*')
                            @include('administrator.components.partials.dropdown')
                        @else
                            @include('administrator.components.partials.single')
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>

        <p>
            <small>
                Laravel v{{ app()->version() }}
            </small>
        </p>

    </div>
</nav>
