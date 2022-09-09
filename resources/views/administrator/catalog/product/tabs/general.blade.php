<table class="table">
    <tr>
        <td>
            <label for="name">{{ __('catalog.product.columns.name') }}</label>
            <span class="text-danger">*</span>
            @include('administrator.resources.translation-field', ['field' => 'name', 'object' => $product ?? null])
        </td>
        <td>
            <input required id="name" type="text" class="form-control" name="name" autofocus value="{{ old('name', $product ? $product->translateOrNew($locale)->name : null) }}">
            @error('name')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    <tr>
        <td>
            <label for="categories">{{ __('catalog.product.columns.categories') }}</label>
            <span class="text-danger">*</span><br>
            <small class="text-secondary">{!! __('catalog.product.descriptions.categories') !!}</small>
        </td>
        <td>
            @if ((!isset($product->group) || !$product->group) || $product->group->productParent->product_uuid == $product->uuid)
            <p class="mb-1">
                <a href="{{ route('admin.catalog.category.categories') }}" id="categories" data-click="popup" data-type="ajax" class="btn btn-primary">
                    {{ __('catalog.feature.append_category') }}
                </a>
            </p>
            @endif
            @error('categories')<span class="text-danger">{{ $message }}</span>@enderror

            <ul id="category_selected_append" class="list-group-sortable sortable-primary" @if ((!isset($product->group) || !$product->group) || $product->group->productParent->product_uuid == $product->uuid)data-sortable-url="{{ route('admin.catalog.product.sortable.category', ['uuid' => $product->uuid ?? $uuid]) }}" @endif>
                @if ($old_category)
                    @foreach ($old_category as $item)
                        <li class="list-item">
                            <picture>
                                <source type="image/webp" srcset="{{ asset('administrator/icons/folder.webp') }}">
                                <img src="{{ asset('administrator/icons/folder.png') }}" class="me-1" alt="" height="16">
                            </picture>

                            <span class="name">{{ $item->name }}</span>
                            <small class="text-secondary">{{ $item->ancestors->sortBy('_lft')->pluck('name')->join(' / ') }}</small>
                            <input type="hidden" name="category[{{ $item->uuid }}]" value="{{ $item->uuid }}">
                        </li>
                    @endforeach
                @else
                    @if (isset($product))
                        @foreach ($product->categories as $item)
                            <li class="list-item" data-uuid="{{ $item->uuid }}">
                                @if ((!isset($product->group) || !$product->group) || $product->group->productParent->product_uuid == $product->uuid)
                                    <a href="javascript:void(0);" class="text-secondary pe-0" data-drag="true" title="{{ __('nested.action') }}">
                                        <i class="fas fa-sort"></i>
                                    </a>
                                @endif

                                <picture>
                                    <source type="image/webp" srcset="{{ asset('administrator/icons/folder.webp') }}">
                                    <img src="{{ asset('administrator/icons/folder.png') }}" class="mx-2" alt="" height="16">
                                </picture>

                                <span class="name">{{ $item->name }}</span>
                                <small class="text-secondary">{{ $item->ancestors->sortBy('_lft')->pluck('name')->join(' / ') }}</small>
                                <input type="hidden" name="category[{{ $item->uuid }}]" value="{{ $item->uuid }}">
                                @if (!$product->group || $product->group->productParent->product_uuid == $product->uuid)
                                    <a href="javascript:void(0)" onclick="return deleteCategory(this)" class="ms-2">
                                        <i class="far fa-trash-alt text-danger"></i>
                                    </a>
                                @endif
                            </li>
                        @endforeach
                    @endif
                @endif
            </ul>
        </td>
    </tr>

    <tr>
        <td>
            <label for="price">{{ __('catalog.product.columns.price') }}</label>
            <span class="text-danger">*</span>
        </td>
        <td>
            <input required id="price" type="number" class="form-control" name="price" value="{{ old('price', $product ? $product->price : 0) }}">
            @error('price')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    <tr>
        <td>
            <label for="description">{{ __('page.columns.description') }}</label><br>
            <small class="text-secondary ms-5">{{ __('page.column_descriptions.description') }}</small>
            @include('administrator.resources.translation-field', ['field' => 'description', 'object' => $product])
        </td>
        <td>
            <div class="form-floating">
                <textarea class="form-control" name="description" placeholder="{{ __('page.columns.description') }}" id="description" style="height: 100px">{{ old('description', $product ? $product->translateOrNew($locale)->description : null) }}</textarea>
                <label for="description">{{ __('page.columns.description') }}</label>
                @error('description')<span class="text-danger">{{ $message }}</span>@enderror
            </div>
        </td>
    </tr>

    <tr>
        <td>
            <label for="images">{{ __('catalog.product.columns.images') }}</label>
        </td>
        <td>
            @include('administrator.partials.image-list', ['images' => $product ? $product->images : null, 'object' => $product])

            <input class="form-control" type="file" name="images[]" id="images" accept="image/*" multiple>
            @error('files')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    <tr>
        <td colspan="2">
            <textarea name="body" id="body" cols="1" rows="1" class="editor">{{ old('body', $product ? $product->translateOrNew($locale)->body : null) }}</textarea>
        </td>
    </tr>

    <tr>
        <td>
            <label for="product_code">{{ __('catalog.product.columns.product_code') }}</label><br>
            <small class="text-secondary">{{ __('catalog.product.descriptions.product_code') }}</small>
        </td>
        <td>
            <input id="product_code" type="text" class="form-control" maxlength="64" name="product_code" value="{{ old('product_code', $product ? $product->product_code : null) }}">
            @error('product_code')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    <tr>
        <td>
            <label for="quantity">{{ __('catalog.product.columns.quantity') }}</label><br>
            <small class="text-secondary">{{ __('catalog.product.descriptions.quantity') }}</small>
        </td>
        <td>
            <input id="quantity" type="number" class="form-control" name="quantity" value="{{ old('quantity', $product ? $product->quantity : 0) }}">
            @error('quantity')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    <tr>
        <td>
            <label for="url">{{ __('catalog.product.columns.url') }}</label><br>
            <small class="text-secondary">{!! __('catalog.product.descriptions.url') !!}</small>
        </td>
        <td>
            <input id="url" type="url" class="form-control" name="url" value="{{ old('url', $product ? $product->url : '') }}">
            @error('url')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

{{--    <tr>--}}
{{--        <td>--}}
{{--            <label for="out_of_stock_action">{{ __('catalog.product.columns.out_of_stock_action') }}</label>--}}
{{--        </td>--}}
{{--        <td>--}}
{{--            <select name="out_of_stock_action" id="out_of_stock_action" class="form-select"></select>--}}
{{--            @error('out_of_stock_action')<span class="text-danger">{{ $message }}</span>@enderror--}}
{{--        </td>--}}
{{--    </tr>--}}

    <tr>
        <td colspan="2" class="text-secondary">
            <label for="weight">{{ __('catalog.product.separators.package') }}</label>
        </td>
    </tr>

    <tr>
        <td>
            <label for="weight">{{ __('catalog.product.columns.weight') }}</label><br>
            <small class="text-secondary">{{ __('catalog.product.descriptions.weight') }}</small>
        </td>
        <td>
            <input id="weight" type="number" class="form-control" name="weight" value="{{ old('weight', $product ? $product->weight : null) }}">
            @error('weight')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    <tr>
        <td>
            <label for="length">{{ __('catalog.product.columns.length') }}</label>
        </td>
        <td>
            <input id="length" type="number" class="form-control" name="length" value="{{ old('length', $product ? $product->length : 0) }}">
            @error('length')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    <tr>
        <td>
            <label for="width">{{ __('catalog.product.columns.width') }}</label>
        </td>
        <td>
            <input id="width" type="number" class="form-control" name="width" value="{{ old('width', $product ? $product->width : 0) }}">
            @error('width')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    <tr>
        <td>
            <label for="height">{{ __('catalog.product.columns.height') }}</label>
        </td>
        <td>
            <input id="height" type="number" class="form-control" name="height" value="{{ old('height', $product ? $product->height : 0) }}">
            @error('height')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    <tr>
        <td colspan="2" class="text-secondary">
            <label for="min_qty">{{ __('catalog.product.separators.order') }}</label>
        </td>
    </tr>

    <tr>
        <td>
            <label for="min_qty">{{ __('catalog.product.columns.min_qty') }}</label>
        </td>
        <td>
            <input id="min_qty" type="number" class="form-control" name="min_qty" value="{{ old('min_qty', $product ? $product->min_qty : 1) }}">
            @error('min_qty')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    <tr>
        <td>
            <label for="max_qty">{{ __('catalog.product.columns.max_qty') }}</label><br>
            <small class="text-secondary">{!! __('catalog.product.descriptions.nullable')  !!}</small>
        </td>
        <td>
            <input id="max_qty" type="number" class="form-control" name="max_qty" value="{{ old('max_qty', $product ? $product->max_qty : 0) }}">
            @error('max_qty')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    <tr>
        <td>
            <label for="step_qty">{{ __('catalog.product.columns.step_qty') }}</label>
        </td>
        <td>
            <input id="step_qty" type="number" class="form-control" name="step_qty" value="{{ old('step_qty', $product ? $product->step_qty : 1) }}">
            @error('step_qty')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    <tr>
        <td colspan="2" class="text-secondary">
            <label for="age_limit">{{ __('catalog.product.separators.age_limit') }}</label>
        </td>
    </tr>

    <tr>
        <td>
            <label for="age_limit">{{ __('catalog.product.columns.age_limit') }}</label><br>
            <small class="text-secondary">
                {{ __('catalog.product.descriptions.age_limit') }}
                {!! __('catalog.product.descriptions.nullable')  !!}
            </small>
        </td>
        <td>
            <input id="age_limit" type="number" class="form-control" name="age_limit" value="{{ old('age_limit', $product ? $product->age_limit : 0) }}">
            @error('age_limit')<span class="text-danger">{{ $message }}</span>@enderror

            <div class="form-check form-switch mt-1">
                <input type="hidden" name="age_verification" value="{{ old('age_verification', $object->age_verification ?? null) ? 1 : 0 }}">
                <input class="form-check-input" id="age_verification"  type="checkbox" name="age_verification" onchange="$('[name=\'age_verification\']').val($(this).is(':checked') ? 1 : 0)" {{ old('age_verification', $product->age_verification ?? null) ? 'checked' : null }}>
                <label class="form-check-label" for="age_verification">{{ __('catalog.product.columns.age_verification') }}</label>
            </div>
            @error('age_verification')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    <tr>
        <td colspan="2" class="text-secondary">
            <label for="views">{{ __('catalog.product.separators.extends') }}</label>
        </td>
    </tr>

    <tr>
        <td>
            <label for="popular">{{ __('catalog.product.columns.popular') }}</label><br>
            <small class="text-secondary">{{ __('catalog.product.descriptions.popular') }}</small>
        </td>
        <td>
            <input id="popular" type="number" class="form-control" name="popular" value="{{ old('popular', $product ? $product->popular : 0) }}">
            @error('popular')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>
</table>

<script>
    function deleteCategory(el)
    {
        if (confirm('<?php echo e(__('global.confirm.delete')); ?>')) {
            let $li = $(el).parents('li')
            let value = $li.find('input').val()

            $(el).parents('li').after('<input type="hidden" name="category[' + value + ']" value="">')
            $(el).parents('li').remove()
        }
    }
</script>

