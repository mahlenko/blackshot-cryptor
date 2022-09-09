<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {!! isset($page->meta) ? $page->meta->generate() : null !!}

    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('css/slick-theme.css') }}">
    <link rel="stylesheet" href="{{ mix('css/bundle.css') }}">
</head>
<body>
    <div class="page">
        @include('web.layouts.partials.header')

        <main>
            @yield('content')
            @include('web.layouts.partials.feedback-form')
        </main>

        @include('web.layouts.partials.footer')
        @include('web.layouts.partials.modals')
    </div>


    <script src="{{ asset('js/all.js') }}"></script>
    <script src="{{ mix('js/bundle.js') }}"></script>
</body>
</html>
