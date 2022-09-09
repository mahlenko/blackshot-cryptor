<li class="list-item justify-content-between fake-control-groups" data-uuid="{{ $product->uuid }}">
    {{-- Объект для сортировки записей --}}
    <a href="javascript:void(0);" data-drag="true" title="{{ __('nested.action') }}">
        <i class="fas fa-sort"></i>
    </a>

    <div class="flex-fill">
        <div class="d-flex align-items-start">
            {{-- Иконка элемента --}}
            @if ($product->preview())
                <a
                    href="{{ \Illuminate\Support\Facades\Storage::url($product->preview()->fullName()) }}"
                    data-type="image"
                    class="item-icon cover-image"
                    title="{{ $product->name }} {{ $product->preview()->name }}"
                >
                    <picture>
                        <source type="image/webp" srcset="{{ \Illuminate\Support\Facades\Storage::url($product->preview()->webp()) }}">
                        <img
                            src="{{ \Illuminate\Support\Facades\Storage::url($product->preview()->thumbnail()) }}"
                            class="rounded" alt="">
                    </picture>
                </a>
            @endif

            <div>
                <div class="d-flex align-items-start">
                    <span class="link-edit me-3">
                        <a href="{{ route('admin.catalog.product.edit', ['locale' => app()->getLocale(), 'uuid' => $product->uuid]) }}" title="{{ $product->name }}">
                            {{ $product->name }}
                        </a>
                        <i class="fas fa-pencil-alt"></i>
                    </span>
                </div>

                <ul class="list-inline">
                    <li class="list-inline-item me-3">
                        @include('administrator.resources.translation-item-variants', ['object' => $product])
                    </li>

                    <li class="list-inline-item">
                        <i class="far fa-folder text-warning"></i>
                        <strong>
                            <small>{{ __('catalog.category.title') }}:</small>
                        </strong>
                        <a href="{{ route('admin.catalog.product.home', ['category_uuid' => $product->categories->first()->uuid]) }}">
                            <small>
                                {{ $product->categories->first()->name }}
                            </small>
                        </a>
                    </li>

{{--                    <li class="list-inline-item">--}}
{{--                        <i class="fas fa-barcode" title="{{ __('catalog.product.columns.product_code') }}"></i>--}}
{{--                        <strong>--}}
{{--                            <small>{{ __('catalog.product.columns.product_code') }}:</small>--}}
{{--                        </strong>--}}
{{--                        <small class="text-secondary" title="{{ __('catalog.product.columns.product_code') }}">--}}
{{--                            {{ $product->product_code }}--}}
{{--                        </small>--}}
{{--                    </li>--}}

                    @if ($product->group)
                    <li class="list-inline-item">
                        <small>
                            <strong>{{ __('catalog.variation.columns.code') }}:</strong>
                            {{ $product->group->code }}
                        </small>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-2" style="max-width: 130px">
        <small>
            <label for="price_{{ $product->uuid }}" class="mx-1">
                <span class="text-secondary">{{ __('catalog.product.columns.price') }}:</span>
            </label><br>
            <input type="number" id="price_{{ $product->uuid }}" name="price" onchange="return updateParams(this)" class="form-control form-control-sm" value="{{ $product->price }}">
        </small>
    </div>

    <div class="col-md-2" style="max-width: 130px">
        <small>
            <label for="quantity_{{ $product->uuid }}" class="mx-1">
                <span class="text-secondary">{{ __('catalog.product.columns.quantity') }}:</span>
            </label><br>
            <input type="text" id="quantity_{{ $product->uuid }}" name="quantity" onchange="return updateParams(this)" class="form-control form-control-sm" value="{{ $product->quantity }}">
        </small>
    </div>

{{--    <div class="col-6 col-md-1 d-flex align-items-start">--}}
{{--        <small>--}}
{{--            <label class="form-check-label" for="is_active_{{ $product->uuid }}">--}}
{{--                <span class="text-secondary">{{ __('catalog.product.columns.status') }}:</span>--}}
{{--            </label>--}}
{{--            <br>--}}
{{--            <div class="form-check form-switch">--}}
{{--                <input--}}
{{--                    class="form-check-input"--}}
{{--                    type="checkbox"--}}
{{--                    name="is_active"--}}
{{--                    value="1"--}}
{{--                    id="is_active_{{ $product->uuid }}"--}}
{{--                    @if($product->is_active)checked @endif--}}
{{--                >--}}
{{--            </div>--}}
{{--        </small>--}}
{{--    </div>--}}

    <div class="col-md-2 text-nowrap d-flex align-items-center justify-content-end">
        {{-- Сделает копию товара --}}
        <form action="{{ route('admin.catalog.product.copy') }}" method="post">
            @csrf
            <button name="uuid" value="{{ $product->uuid }}" class="btn btn-link text-secondary">
                <i class="far fa-copy"></i>
            </button>
        </form>

        {{-- редактировать --}}
        <a href="{{ route('admin.catalog.product.edit', ['locale' => app()->getLocale(), 'uuid' => $product->uuid]) }}" class="btn btn-sm text-secondary">
            <i class="fas fa-pencil-alt"></i>
        </a>

        {{-- Удалить товар --}}
        @include('administrator.resources.delete-form', ['route_delete' => route('admin.catalog.product.delete'), 'item' => $product])
    </div>
</li>
