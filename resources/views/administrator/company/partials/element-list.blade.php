<li class="list-item justify-content-between fake-control-groups" data-uuid="{{ $company->uuid }}">
    {{-- Объект для сортировки записей --}}
    <a href="javascript:void(0);" data-drag="true" title="{{ __('nested.action') }}">
        <i class="fas fa-sort"></i>
    </a>

    <div class="flex-fill">
        <div class="d-flex align-items-start">
            {{-- Иконка элемента --}}
            @if (isset($company->image) && $company->image)
                <a
                    href="{{ \Illuminate\Support\Facades\Storage::url($company->image->fullName()) }}"
                    data-type="image"
                    class="item-icon"
                    style="width: 50px;"
                    title="{{ $company->name }} {{ $company->image->name }}"
                >
                    @if ($company->image->mimeType === 'image/svg+xml')
                        <span class="rounded p-0" style="width: 50px;">
                            {!! \Illuminate\Support\Facades\Storage::get($company->image->fullName()) !!}
                        </span>
                    @else
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($company->image->thumbnail()) }}" class="rounded" alt="">
                    @endif
                </a>
            @endif

            <div>
                <div class="d-flex align-items-start">
                    <span class="link-edit me-2">
                        <a href="{{ route('admin.company.edit', ['locale' => app()->getLocale(), 'uuid' => $company->uuid]) }}" title="{{ $company->name }}">
                            {{ $company->name }}
                        </a>
                        <i class="fas fa-pencil-alt"></i>
                    </span>

                    @include('administrator.resources.translation-item-variants', ['object' => $company])
                </div>

                <ul class="list-inline text-secondary">
                    <li class="list-inline-item">
                        <small>
                            {{ __('company.columns.type') }}:
                            {{ \App\Repositories\CompanyRepository::types()[$company->type] }}
                        </small>
                    </li>
                    <li class="list-inline-item">
                        <small>{{ $company->address->value ?? '---' }}</small>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-1 text-nowrap d-flex align-items-center">
        {{-- Редактировать --}}
        <a href="{{ route('admin.company.edit', ['locale' => app()->getLocale(), 'uuid' => $company->uuid]) }}"
           class="btn btn-sm text-secondary">
            <i class="fas fa-pencil-alt"></i>
        </a>

        {{-- Удалить --}}
        @include('administrator.resources.delete-form', ['route_delete' => route('admin.company.delete'), 'item' => $company])
    </div>
</li>
