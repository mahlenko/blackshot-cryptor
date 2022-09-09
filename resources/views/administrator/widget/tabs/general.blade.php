<table class="table">
    @if ($widget)
        <tr class="table-light">
            <td colspan="2">
                <ul>
                    <li>
                        <small class="text-secondary">
                            {!! __('widget.columns_description.editor') !!}
                        </small>
                    </li>
                    <li>
                        <small class="text-secondary">
                            {{ __('widget.columns_description.html_code') }}<br>
                            <span class="text-success">&lt;x-widget uuid="{{ $widget->uuid }}"/&gt;</span>
                        </small>
                    </li>
                </ul>
            </td>
        </tr>
    @endif

    <tr>
        <td>
            <label for="name">{{ __('widget.columns.name') }}</label>
            <span class="text-danger">*</span>
        </td>
        <td>
            <input type="text" id="name" name="name" value="{{ old('name', $widget->name ?? null) }}" required autofocus class="form-control">
            @error('name')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    @if (!$widget)
    <tr>
        <td>
            <label for="type">{{ __('widget.columns.type') }}</label>
            <span class="text-danger">*</span>
        </td>
        <td>
            <select name="type" id="type" class="form-select" required>
                <option value=""></option>
                @foreach($types as $type)
                    <option value="{{ $type['value'] }}" @if(old('type', $widget->type ?? null) == $type['value'])selected @endif>
                        {{ $type['text'] }}
                    </option>
                @endforeach
            </select>
            @error('name')<span class="text-danger">{{ $message }}</span>@enderror

            <p class="my-2">
                <small class="text-secondary">
                    {{ __('widget.columns_description.type') }}
                </small>
            </p>
        </td>
    </tr>
    @else
        <input type="hidden" name="type" readonly value="{{ $widget->type }}">
    @endif

    @if (isset($widget))
        @if(!empty($widget->type::templateFolder()))
        <tr>
            <td>
                <label for="template">{{ __('widget.columns.template') }}</label>
                <span class="text-danger">*</span>
            </td>
            <td>
                <select name="template" id="template" class="form-select" required>
                    <option value=""></option>
                    @foreach($templates as $template)
                        <option value="{{ $template }}" @if(old('type', $widget->template ?? 'default') == $template)selected @endif>
                            {{ \Illuminate\Support\Str::ucfirst($template) }}
                        </option>
                    @endforeach
                </select>
                @error('name')<span class="text-danger">{{ $message }}</span>@enderror
            </td>
        </tr>
        @endif

        @foreach ($widget->getParams() as $field)
            @php($field_name = preg_replace('/[^a-z0-9_]/', '', $field['name']))

            <tr class="table-light">
                <td>
                    @if (empty($widget->parameters->{$field_name}) && isset($field['args']['required']) && $field['args']['required'])
                        <span>⚠️&nbsp;</span>
                    @endif

                    <label for="{{ $field['name'] }}">{{ $field['label'] }}</label>
                    @if (isset($field['args']['required']) && $field['args']['required'])
                        <span class="text-danger">*</span>
                    @endif

                    @if (isset($field['description']))
                        <br>
                        <small class="text-secondary">
                            {!! $field['description'] !!}
                        </small>
                    @endif
                </td>
                <td>
                    @if ($field['type'] === 'dropdown')
                        <select name="{{ $field['name'] }}" id="{{ $field['name'] }}" class="form-select no-select2 select2-description" @isset($field['args']) @foreach($field['args'] as $key => $value){{ $key }}="{{ $value }}" @endforeach @endif>
                            @foreach($field['options'] as $item)
                                @isset($item['value'])
                                    <option @isset($item['description'])data-select2-description="{{ $item['description'] }}" @endif value="{{ $item['value'] }}" @isset ($widget->parameters->{$field_name})@if(is_array($widget->parameters->{$field_name})){{ in_array($item['value'], $widget->parameters->{$field_name}) ? 'selected' : null }}@else {{ $widget->parameters->{$field_name} == $item['value'] ? 'selected' : null }} @endif @endif>
                                        {{ $item['label'] }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    @elseif ($field['type'] === 'code')
                        @include('administrator.widget.fields.code', ['field' => $field, 'value' => $widget->parameters->{$field_name} ?? null])
                    @elseif ($field['type'] === 'textarea')
                        <textarea name="{{ $field_name }}"
                                  id="{{ $field_name }}"
                                  class="form-control"
                                @isset($field['args']) @foreach($field['args'] as $key => $value){{ $key }}="{{ $value }}" @endforeach @endif
                        >{{ $widget->parameters->{$field_name} ?? null }}</textarea>
                    @else
                        <input type="{{ $field['type'] }}"
                               id="{{ $field_name }}"
                               class="form-control"
                               name="{{ $field_name }}"
                               value="{{ old($field_name, $widget->parameters->{$field_name} ?? $field['default'] ?? null) }}"
                               @isset($field['args']) @foreach($field['args'] as $key => $value){{ $key }}="{{ $value }}" @endforeach @endif />
                    @endif
                </td>
            </tr>
        @endforeach
    @endif

</table>

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ace.js" integrity="sha512-GZ1RIgZaSc8rnco/8CXfRdCpDxRCphenIiZ2ztLy3XQfCbQUSCuk8IudvNHxkRA3oUg6q0qejgN/qqyG1duv5Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/mode-php_laravel_blade.min.js" integrity="sha512-zrOCvRD3xuYDyh+rr4yrK2xXbPpsU2BdUbRWoLpUCipO0Oyvx3VOaAtAncKBtqDfyI4wvB/yFeI0clxdIWFQ1A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endpush
