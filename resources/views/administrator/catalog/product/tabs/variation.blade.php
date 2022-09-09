<?php
    /* @var \App\Models\Catalog\Product $product */
?>

<div class="d-flex flex-column-reverse flex-md-row justify-content-between align-items-center mb-2">
    <div class="btn-group">
        <a href="{{ route('admin.catalog.variation.home', ['uuid' => $product->uuid]) }}" data-type="ajax" class="btn btn-primary">
            {{ __('catalog.variation.add') }}
        </a>
        @if ($product->group)
            <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="visually-hidden">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu">
                <li>
                    <a href="javascript:void(0);" class="dropdown-item" onclick="return disband.submit()">
                        {{ __('catalog.variation.disband') }}
                    </a>
                    @push('before_content')
                        <form action="{{ route('admin.catalog.variation.disband') }}" class="d-none" method="post" id="disband">
                            @csrf
                            <input type="hidden" name="uuid" value="{{ $product->group->uuid }}">
                        </form>
                    @endpush
                </li>
            </ul>
        @endif
    </div>

    <span>
        @if ($product->group)
            <strong>{{ __('catalog.variation.columns.code') }}:</strong>
            {{ $product->group->code }}
        @endif
    </span>
</div>

<hr>

@if ($product->group)
    <ul class="list-group-table">
        {{-- @var Product --}}
        @foreach ($product->group->products as $item)
            <li class="list-item justify-content-between fake-control-groups" data-uuid="{{ $item->product_uuid }}">
                <div class="d-flex w-50 align-items-start">
                    @if ($item->object->images->count())
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($item->object->images->first()->thumbnail()) }}" style="width: 80px" class="me-3" alt="{{ $item->object->images->first()->alt ?? null }}">
                    @else
                        <img src="{{ asset('images/no-photo.png') }}" style="width: 80px" class="me-3" alt="">
                    @endif

                    <span>
                        @if ($product->uuid == $item->object->uuid)
                            <strong>{{ $item->object->name }}</strong>
                        @else
                            <a href="{{ route('admin.catalog.product.edit', ['locale' => app()->getLocale(), 'uuid' => $item->object->uuid]) }}">
                                {{ $item->object->name }}
                            </a>
                        @endif

                        <ul class="list-inline">
                            <li class="list-inline-item">
                                <small class="text-secondary">
                                    {{ __('catalog.product.columns.product_code') }}: {{ $item->object->product_code }}
                                </small>
                            </li>

                            @if ($product->group->productParent->product_uuid == $item->object->uuid)
                                <li class="list-inline-item">
                                    @if($product->uuid == $item->object->uuid)
                                        <small class="badge bg-success rounded-circle p-1 me-1" title="{{ __('catalog.variation.this_is_product') }}"> </small>
                                    @endif
                                    <small class="badge bg-primary rounded-circle p-1 me-1"> </small>
                                    <small class="text-primary">{{ __('catalog.variation.primary') }}</small>
                                </li>
                            @elseif($product->uuid == $item->object->uuid)
                                <li class="list-inline-item">
                                    <small class="badge bg-success rounded-circle p-1 me-1" title="{{ __('catalog.variation.this_is_product') }}"> </small>
                                    <small class="text-success">{{ __('catalog.variation.this_is_product') }}</small>
                                </li>
                            @endif
                        </ul>
                    </span>
                </div>

                @foreach($product->variation_features as $feature)
                    <div class="col-md-2">
                        <small class="text-secondary">
                            <strong>{{ $feature->name }}</strong><br>
                        </small>
                        @php($feature = $item->object->features->where('feature_uuid', $feature->uuid)->first())
                        @php($feature_value_text = $feature->value ?? $feature->variant->name ?? null)

                        @if ($feature && $product->uuid != $item->object->uuid)
                        <select class="form-select form-select-sm no-select2" name="{{ $feature->feature_uuid }}" onchange="return updateFeature(this)">
                            @foreach($feature->feature->values as $value)
                                <option value="{{ $value->uuid }}" @if ($value->uuid == $feature->feature_variant_uuid)selected @endif>
                                    {{ $feature->feature->prefix }}
                                    {{ $value->name }}
                                    {{ $feature->feature->postfix }}
                                </option>
                            @endforeach
                        </select>
                        @else
                            <small>{{ $feature_value_text }}</small>
                        @endif
                    </div>
                @endforeach

                <small class="col-md-2 text-secondary" style="max-width: 130px">
                    <strong>{{ __('catalog.product.columns.price') }}</strong><br>
                    @if ($product->uuid != $item->object->uuid)
                        <input type="number" class="form-control form-control-sm" name="price" value="{{ $item->object->price }}" onchange="return updateParams(this)">
                    @else
                        <small>{{ $item->object->price }}</small>
                    @endif
                </small>

                <small class="col-md-2 text-secondary" style="max-width: 130px">
                    <strong>{{ __('catalog.product.columns.quantity') }}</strong><br>
                    @if ($product->uuid != $item->object->uuid)
                        <input type="number" class="form-control form-control-sm" name="quantity" value="{{ $item->object->quantity }}" onchange="return updateParams(this)">
                    @else
                        <small>{{ $item->object->quantity }}</small>
                    @endif
                </small>

                <div class="col-md-1">
                    @if ($product->group->productParent->product_uuid != $item->object->uuid)
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-h"></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0)" onclick="$('#remove_product_{{ $item->product_uuid }}').submit()">
                                        <i class="far fa-eye-slash me-1"></i>
                                        {{ __('catalog.variation.action.remove_by_group') }}
                                    </a>
                                    @push('before_content')
                                        <form action="{{ route('admin.catalog.variation.remove.product') }}" class="d-none" method="post" id="remove_product_{{ $item->product_uuid }}">
                                            @csrf
                                            <input type="hidden" name="uuid" value="{{ $item->product_uuid }}">
                                            <input type="hidden" name="group_uuid" value="{{ $product->group->uuid }}">
                                        </form>
                                    @endpush
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.catalog.product.edit', ['locale' => app()->getLocale(), 'uuid' => $item->product_uuid]) }}">
                                        <i class="fas fa-pencil-alt me-1"></i>
                                        {{ __('global.edit') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @endif
                </div>
            </li>
        @endforeach
    </ul>

@else
    <p class="text-secondary">
        Разрешить покупателям переключаться между похожими товарами через выбор желаемого варианта их общих характеристик.
    </p>
@endif
