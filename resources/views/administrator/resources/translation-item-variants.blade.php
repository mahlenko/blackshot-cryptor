@if (count(config('translatable.locales')) > 1)
    <strong><small>{{ __('admin.translate') }}:</small></strong>
    <ul class="d-inline-flex list-inline mx-1">
    @foreach (config('translatable.locales') as $locale)
        <li class="list-inline-item" @if (!$object->hasTranslation($locale)) style="opacity: .3" @endif>
            <img src="{{ asset('administrator/icons/flags/' . $locale .'.png') }}" title="{{ $locale }}">
        </li>
    @endforeach
    </ul>
@endif
