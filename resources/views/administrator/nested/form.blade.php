{{--<tr>--}}
{{--    <td>--}}
{{--        <label for="nested_position">{{ __('nested.columns.action') }}</label>--}}
{{--    </td>--}}
{{--    <td>--}}
{{--        @if (!isset($nested_only_sortable) || !$nested_only_sortable)--}}
{{--        <div class="form-check form-check-inline">--}}
{{--            <input class="form-check-input" type="radio" name="nested[position]" value="append" id="nested_position_append" @if(old('nested.position') == 'append' || empty(old('nested.position')))checked @endif>--}}
{{--            <label class="form-check-label" for="nested_position_append">--}}
{{--                {{ __('nested.columns.append') }}--}}
{{--            </label>--}}
{{--        </div>--}}
{{--        @endif--}}

{{--        <div class="form-check form-check-inline">--}}
{{--            <input class="form-check-input" type="radio" name="nested[position]" value="before" id="nested_position_before" @if(old('nested.position') == 'before')checked @endif>--}}
{{--            <label class="form-check-label" for="nested_position_before">--}}
{{--                {{ __('nested.columns.before') }}--}}
{{--            </label>--}}
{{--        </div>--}}

{{--        <div class="form-check form-check-inline">--}}
{{--            <input class="form-check-input" type="radio" name="nested[position]" value="after" id="nested_position_after" @if(old('nested.position') == 'after')checked @endif>--}}
{{--            <label class="form-check-label" for="nested_position_after">--}}
{{--                {{ __('nested.columns.after') }}--}}
{{--            </label>--}}
{{--        </div>--}}

{{--        @error('nested.position')<span class="text-danger">{{ $message }}</span>@enderror--}}
{{--    </td>--}}
{{--</tr>--}}

<tr>
    <td>
        <label for="nested_parent_id">
            {{ __('nested.columns.object') }}
            <span class="text-danger">*</span>
        </label>

        <br><small class="text-secondary">{{ __('nested.descriptions.object') }}</small>
    </td>
    <td>
        <div class="form-floating">
            {{-- Позиция --}}
            <input type="hidden" name="nested[position]" value="append">
            @error('nested.position')<span class="text-danger">{{ $message }}</span>@enderror

            {{-- Объект --}}
            <select class="form-select no-select2 select2-description" name="nested[parent_id]" id="nested_parent_id" aria-label="Floating label select example">
                <option value="">{{ __('nested.no_select') }}</option>
                @if ($nested_nodes)
                    @foreach($nested_nodes as $item)
                        <option
                            value="{{ $item['value'] }}"
                            @if($item['eloquent']->ancestors)
                                data-select2-description-icon="far fa-folder"
                                data-select2-description="@if($item['eloquent']->ancestors->count() == 0)root @else {{ $item['eloquent']->ancestors->sortBy('_lft')->pluck('name')->join(' > ') }}@endif"
                            @endif
                            @if($item['disabled'])disabled @endif
                            @if(old('nested.parent_id', $node->parent_id ?? $parent->uuid ?? null) == $item['value'])selected @endif
                        >
                            {{ $item['name'] }}
                        </option>
                    @endforeach
                @endif
            </select>
            <label for="nested_parent_id">{{ __('nested.change_object') }}</label>
            @error('nested.parent_id')<span class="text-danger">{{ $message }}</span>@enderror
        </div>
    </td>
</tr>
