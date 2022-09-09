@if (isset($locale) && isset($data) && count(config('translatable.locales')) > 1)
<div class="btn-group btn-group-sm">
    <button class="btn btn-link border text-secondary dropdown-toggle text-decoration-none" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <img src="{{ asset('administrator/icons/flags/' . $locale .'.png') }}" class="me-2" alt="">{{ $locale }}
    </button>

    <ul class="dropdown-menu dropdown-menu-end">
        @foreach(config('translatable.locales') as $locale_code)
            <li>
                <a
                    class="dropdown-item
                    @if($locale == $locale_code)active @endif"
                    href="{{ route(\Illuminate\Support\Facades\Route::currentRouteName(), array_merge($data, ['locale' => $locale_code])) }}"
                    @if(\Illuminate\Support\Facades\Request::ajax())
                        data-click="popup"
                        data-type="ajax"
                    @endif
                >
                    <img src="{{ asset('administrator/icons/flags/' . $locale_code .'.png') }}" alt="">
                    {{ $locale_code }}
                </a>
            </li>
        @endforeach
    </ul>
</div>
@endif
