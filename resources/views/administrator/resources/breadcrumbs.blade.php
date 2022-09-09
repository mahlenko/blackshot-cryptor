{{-- Breadcrumbs --}}
@if (\Diglactic\Breadcrumbs\Breadcrumbs::exists(\Illuminate\Support\Facades\Route::currentRouteName()))
    {{ \Diglactic\Breadcrumbs\Breadcrumbs::render(\Illuminate\Support\Facades\Route::currentRouteName(), $data ?? null) }}
@endif
{{-- End: Breadcrumbs --}}
