<li class="list-item justify-content-between fake-control-groups" data-uuid="{{ $country->uuid }}">
    {{-- Объект для сортировки записей --}}
    <a href="javascript:void(0);" data-drag="true" title="{{ __('nested.action') }}">
        <i class="fas fa-sort"></i>
    </a>

    <div class="d-flex flex-fill align-items-start">
        <span class="link-edit">
            <a href="{{ route('admin.references.country.edit', ['uuid' => $country->uuid, 'locale' => $country->translate()->locale ?? config('translatable.locale')]) }}">{{ $country->name }}</a>
            <i class="fas fa-pencil-alt"></i>
        </span>

        @include('administrator.resources.translation-item-variants', ['object' => $country])
    </div>

    <div class="col-3 col-md-1">
        {{ $country->alpha2 }}
    </div>

    <div class="col-3 col-md-1">
        {{ $country->alpha3 }}
    </div>

    <div class="col-md-2 text-nowrap d-flex align-items-center justify-content-end">
        {{-- редактировать --}}
        <a href="{{ route('admin.references.country.edit', ['uuid' => $country->uuid, 'locale' => $country->translate()->locale ?? config('translatable.locale')]) }}" class="btn btn-sm text-secondary">
            <i class="fas fa-pencil-alt"></i>
        </a>

        {{-- Удалить товар --}}
        @include('administrator.resources.delete-form', ['route_delete' => route('admin.references.country.delete'), 'item' => $country])
    </div>
</li>
