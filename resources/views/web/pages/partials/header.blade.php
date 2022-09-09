<div class="header header-main">
    <div class="header--wrapper">
        {{-- Menu burger --}}
        <div class="menu_burger">
            <svg class="menu_burger--icon icon"><use xlink:href="{{ asset('img/svg-sprite.svg#burger') }}"></use></svg>
        </div>

        {{-- Logo --}}
        <div class="header--logo">
            <a href="{{ route('view', '/') }}" class="main_logo">
                {!! \Illuminate\Support\Facades\Storage::get($settings['logotype']->fullName()) !!}
            </a>
        </div>

        {{-- Main menu --}}
        <div class="header--menu">
            <div class="header--menu_wrapper">
                <x-navigation key="general" class="main_menu" />
            </div>
        </div>

        {{-- Lang --}}
        <div class="header--lang">
            <div class="lang_select">
                <div class="lang_select--item lang_select--item-selected">
                    <img class="lang_select--flag" src="{{ asset('img/flags/'. (app()->getLocale() == 'cn' ? 'ch' : app()->getLocale()) .'.svg')}}" alt="">
                    {{ app()->getLocale() }}
                    <svg class="lang_select--icon icon">
                        <use xlink:href="{{ asset('img/svg-sprite.svg#caret') }}"></use>
                    </svg>
                </div>

                <div class="lang_select--list">
                    @foreach(config('translatable.domain_locales') as $lang => $url)
                        @if ($lang !== app()->getLocale())
                            <div class="lang_select--item">
                                <a href="{{ $url }}" class="lang_select--link">
                                    <img class="lang_select--flag" src="{{ asset('img/flags/'. ($lang == 'cn' ? 'ch' : $lang) .'.svg') }}" alt="">
                                    {{ $lang }}
                                </a>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
