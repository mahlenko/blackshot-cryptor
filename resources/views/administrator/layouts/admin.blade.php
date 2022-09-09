<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;600&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ mix('css/app.css', 'administrator') }}" rel="stylesheet">

    @stack('styles')
    @stack('scripts')
</head>
<body>
    <div id="app">
        <div class="sidebar-left">
            <x-administrator.navigation></x-administrator.navigation>
        </div>

        <div class="container-body">
            @stack('before_content')
            @yield('content')
            @stack('after_content')
        </div>
    </div>

    @include('administrator.partials.toasts')

    <!-- Scripts -->
    <script src="{{ mix('js/fontawesome.js', 'administrator') }}"></script>
    <script src="{{ mix('js/app.js', 'administrator') }}"></script>
    <script src="{{ mix('js/finder.js', 'administrator') }}"></script>

    <script>
        XMLHttpRequest.prototype.origOpen = XMLHttpRequest.prototype.open;
        XMLHttpRequest.prototype.open = function () {
            this.origOpen.apply(this, arguments);
            this.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
        };

        /**
         * Language
         * @type file: {delete_confirm: string}
         */
        let lang = {
            file : {
                delete_confirm: "{{ __('file.delete_confirm') }}"
            }
        }
    </script>
</body>
</html>
