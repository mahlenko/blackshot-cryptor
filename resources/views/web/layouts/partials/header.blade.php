@php($show_screen = !(\Illuminate\Support\Facades\Route::is('login') || \Illuminate\Support\Facades\Route::is('register')))

<header class="section">
    <div class="wrap {{ $show_screen && !isset($first_screen) ? 'wrap-full_height' : null }}">
        @include('web.pages.partials.header')

        {{-- Landing first screen --}}
        @if($show_screen && isset($page))
            @include('web.pages.partials.' . ($first_screen ?? 'first-screen'))
        @endif
    </div>
</header>
