@if (isset($similar) && $similar->count())
    <form action="{{ route('admin.catalog.variation.add') }}" method="post" onsubmit="return ajaxSubmit(this);">
        @csrf
        <input type="hidden" name="product_uuid" value="{{ $product->uuid }}">
        <input type="hidden" name="group_uuid" value="{{ $product->group->uuid ?? \Ramsey\Uuid\Uuid::uuid4()->toString() }}">

        <p>
            <button class="btn btn-primary">
                {{ __('catalog.variation.add') }}
            </button>
        </p>

        <ul class="list-group-table">
        @foreach($similar as $item)
            <li class="list-item">
                <div class="form-check" style="width: 20px;">
                    <input class="form-check-input" type="checkbox" name="combination[]" value="{{ $item->uuid }}">
                </div>

                @if ($item->preview())
                <img
                    src="{{ \Illuminate\Support\Facades\Storage::url($item->preview()->thumbnail()) }}"
                    class="rounded"
                    style="width: 90px;"
                    alt="{{ $item->preview()->alt }}"
                >
                @endif

                <div>
                    <p>
                        <a href="{{ route('admin.catalog.product.edit', ['locale' => app()->getLocale(), 'uuid' => $item->uuid]) }}" target="_blank">
                            {{ $item->name }}
                        </a>
                        <br>
                        <small class="text-secondary">
                            {{ __('catalog.product.columns.product_code') }}: {{ $item->product_code }}
                        </small>
                    </p>

                    <ul class="list-inline">
                        @foreach($item->variation_features as $feature)
                            <li class="list-inline-item">
                                <small class="text-secondary">
                                    <strong>{{ $feature->name }}</strong>:
                                    {{ $feature->prefix ?? null }}
                                    {{ $item->features->where('feature_uuid', $feature->uuid)->first()->value ?? $item->features->where('feature_uuid', $feature->uuid)->first()->variant->name }}
                                    {{ $feature->postfix ?? null }}
                                </small>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </li>
        @endforeach
    </ul>
    </form>
@else
    <div class="alert alert-light">
        {!! __('catalog.variation.no_similar_products') !!}
    </div>
@endif
