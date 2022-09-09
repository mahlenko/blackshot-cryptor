@if($withoutCategories && $withoutCategories->count())
    <div class="accordion" id="features_accordion">
        @foreach($withoutCategories as $feature_group)
            <div class="accordion-item">
                {{-- header --}}
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button @if($loop->index)collapsed @endif" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_{{ $loop->index }}" aria-expanded="true" aria-controls="collapse_{{ $loop->index }}">
                        <strong>{{ $feature_group->first()->group->name ?? __('catalog.feature.group.no_group') }}</strong>
                    </button>
                </h2>

                {{-- body --}}
                <div id="collapse_{{ $loop->index }}" class="accordion-collapse collapse @if(!$loop->index)show @endif" aria-labelledby="heading_{{ $loop->index }}" data-bs-parent="#features_accordion">
                    <div class="accordion-body">
                        <table class="table table-borderless">
                            @foreach($feature_group as $feature)
                                <tr>
                                    <td>
                                        <label for="feature_{{ $feature->uuid }}">{{ $feature->name }}</label>
                                        @if(in_array($feature->purpose, ['group_products', 'group_variants']))
                                            <br><small class="text-secondary">
                                                <i class="fas fa-crosshairs text-primary"></i>
                                                {{ (new \App\Repositories\FeatureRepository())->getPurposes()[$feature->purpose]['title'] }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        @php($product_feature_item = $product->features->where('feature_uuid', $feature->uuid)->pluck('feature_variant_uuid'))

                                        @if ($feature->purpose !== 'describe')
                                            <div class="input-group">
                                                @if ($feature->prefix)
                                                    <span class="input-group-text">{{ $feature->prefix }}</span>
                                                @endif

                                                @if ($feature->view_product !== 'checkbox' && $feature->values && $feature->values->count())
                                                    @if (!$group || $group && !in_array($feature->uuid, $group->features->pluck('feature_uuid')->toArray()))
                                                    <select @if($feature->view_product == 'checkbox_group')multiple name="features[{{ $feature->uuid }}][]" @else name="features[{{ $feature->uuid }}]" @endif id="feature_{{ $feature->uuid }}" class="form-select">
                                                        <option value="">{{ __('nested.no_select') }}</option>
                                                        @foreach ($feature->values as $variant)
                                                            <option
                                                                value="{{ $variant->uuid }}"
                                                                @if ($feature->view_filter == 'color')data-color="{{ $variant->color }}"@endif
                                                                @if ($feature->purpose == 'organize_catalog' && $variant->icon)data-icon="{{ \Illuminate\Support\Facades\Storage::url($variant->icon->thumbnail()) }}"@endif
                                                                @if ($product_feature_item && $product_feature_item->contains($variant->uuid))selected @endif
                                                            >
                                                                {{ $variant->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @elseif ($product_feature_item)
                                                        {{ $product_feature_item->variant->name }}
                                                    @endif
                                                @else
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" @if($product_feature_item && intval($product_feature_item->value))checked @endif value="1" id="feature_{{ $feature->uuid }}" onchange="return changeCheckbox(this)">
                                                        <input type="hidden" name="features[{{ $feature->uuid }}]" value="{{ $product_feature_item->value ?? null }}" id="feature_{{ $feature->uuid }}_input">
                                                        <label class="form-check-label" for="feature_{{ $feature->uuid }}"></label>
                                                    </div>
                                                @endif

                                                @if ($feature->postfix)
                                                    <span class="input-group-text">{{ $feature->postfix }}</span>
                                                @endif
                                            </div>
                                        @else
                                            {{-- только для дополнительной информации --}}
                                            <input
                                                id="feature_{{ $feature->uuid }}"
                                                type="text"
                                                class="form-control"
                                                name="features[{{ $feature->uuid }}]"
                                                @if ($product_feature_item || old('features['. $feature->uuid .']'))
                                                value="{{ old('features[$feature->uuid]') ?? $product_feature_item->translateOrNew($locale)->value }}"
                                                @endif
                                            >
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

<script>
    function changeCheckbox(checkbox)
    {
        let value = ''
        if ($(checkbox).is(':checked')) {
            value = checkbox.value
        }

        $('#' + $(checkbox).attr('id') + '_input').val(value)
    }
</script>

