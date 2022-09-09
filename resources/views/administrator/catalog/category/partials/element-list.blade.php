<li class="list-item justify-content-between" data-uuid="{{ $item->uuid }}">

    {{-- Объект для сортировки записей --}}
    <a href="javascript:void(0);" class="me-2" data-drag="true" title="{{ __('nested.action') }}">
        <i class="fas fa-sort"></i>
    </a>

    {{-- Основная информация --}}
    <div class="flex-fill ps-1">
        {{-- row 1 --}}
        <div class="d-flex align-items-start">
            {{-- icon --}}
            <span class="item-icon">
                <picture>
                    <source type="image/webp" srcset="{{ asset('administrator/icons/folder.webp') }}">
                    <img src="{{ asset('administrator/icons/folder.png') }}" alt="">
                </picture>
            </span>

            <div class="flex-fill flex-grow-1">
                {{-- row 1 --}}
                <div class="d flex">
                    <span class="link-edit me-2">
                        @if (!$item->isLeaf())
                            <a href="{{ route('admin.catalog.category.home', ['uuid' => $item->uuid]) }}">
                                {{ $item->name }}</a>
                            <small>
                                <i class="fas fa-long-arrow-alt-right ms-1"></i>
                            </small>
                        @else
                            <a href="{{ route('admin.catalog.category.edit', ['locale' => app()->getLocale(), 'uuid' => $item->uuid]) }}">
                                {{ $item->name }}</a>
                            <small>
                                <i class="fas fa-pencil-alt ms-1"></i>
                            </small>
                        @endif
                    </span>
                </div>

                {{-- row 2 --}}
                <div class="mb-0">
                    @include('administrator.resources.translation-item-variants', ['object' => $item])<br>

                    <a href="{{ route('admin.catalog.product.edit', ['locale' => app()->getLocale(), 'category_uuid' => $item->uuid]) }}" class="me-2 text-secondary">
                        <small>
                            <i class="fas fa-plus me-1 text-success"></i>{{ __('catalog.category.product_add') }}
                        </small></a>

                    <small>
                        @php($count_products = $item->products->count())

                        @if ($item->children->count())
                            @foreach ($item->children->pluck('products') as $products)
                                @php($count_products += $products->count())
                            @endforeach
                        @endif

                        <br>

                        @if ($count_products)
                            {{ trans_choice(__('catalog.category.products_choice', ['count' => $count_products]), $count_products) }}
                        @else
                            {{ __('catalog.category.no_products') }}
                        @endif
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 pl-0">
        <p class="mb-0">
            <small>
                <span class="text-secondary">{{ __('catalog.feature.title') }}:</span><br>
                @if ($item->features && $item->features->count())
                    {{ trans_choice(__('catalog.category.features_choice', ['count' => $item->features->count()]), $item->features->count()) }}
                @else
                    {{ __('catalog.category.no_features') }}
                @endif
            </small>
        </p>
    </div>

    <div class="col-md-2">
        <p class="mb-0">
            <small>
                <span class="text-secondary">{{ __('global.created_at') }}:</span><br>
                {{ $item->created_at }}
            </small>
        </p>
    </div>

    {{-- Удаление --}}
    <div class="d-flex col-md-1 flex-nowrap">
        <a href="{{ route('admin.catalog.category.edit', ['locale' => app()->getLocale(), 'uuid' => $item->uuid]) }}" class="btn btn-sm text-secondary">
            <i class="fas fa-pencil-alt"></i>
        </a>
        <small>
            @include('administrator.resources.delete-form', ['route_delete' => route('admin.catalog.category.delete')])
        </small>
    </div>
</li>
