<li class="list-item justify-content-between fake-control-groups" data-uuid="{{ $item->uuid }}">
    <div class="flex-fill">
        <div class="d-flex align-items-start">
            {{-- Иконка --}}
            <div class="item-icon">
                @if(ltrim($item->type, '\\') == \App\Http\Controllers\Administrator\Widget\Services\HtmlBlockService::class)
                    <picture>
                        <source type="image/webp" srcset="{{ asset('administrator/icons/html.webp') }}">
                        <img src="{{ asset('administrator/icons/html.png') }}" alt="">
                    </picture>
                @elseif(ltrim($item->type, '\\') == \App\Http\Controllers\Administrator\Widget\Services\CompanyService::class)
                    <picture>
                        <source type="image/webp" srcset="{{ asset('administrator/icons/company.webp') }}">
                        <img src="{{ asset('administrator/icons/company.png') }}" alt="">
                    </picture>
                @else
                    <picture>
                        <source type="image/webp" srcset="{{ asset('administrator/icons/file.webp') }}">
                        <img src="{{ asset('administrator/icons/file.png') }}" alt="">
                    </picture>
                @endif
            </div>

            {{-- Основные данные --}}
            <div>
                <a href="{{ route('admin.widget.edit', ['uuid' => $item->uuid]) }}">
                    {{ $item->name }}</a>

                <span class="link-edit me-2">
                    <small>
                        <i class="fas fa-pencil-alt ms-1"></i>
                    </small>
                </span>

                <ul class="list-inline text-secondary">
                    <li class="list-inline-item">
                        <small>
                            {{ __('Тип') }}:
                            {{ $item->type::name() }}
                        </small>
                    </li>
                    <li class="list-inline-item">
                        <small>
                            <i class="fas fa-palette text-warning"></i>
                            {{ $item->template }}
                        </small>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-1 text-nowrap d-flex align-items-center">
        {{-- Редактировать --}}
        <a href="{{ route('admin.widget.edit', ['uuid' => $item->uuid]) }}" class="btn btn-sm text-secondary">
            <i class="fas fa-pencil-alt"></i>
        </a>

        {{-- Удалить --}}
        @include('administrator.resources.delete-form', ['route_delete' => route('admin.widget.delete'), 'item' => $item])
    </div>
</li>
