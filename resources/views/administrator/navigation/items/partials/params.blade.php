<table class="table">
    <tr>
        <td>
            <label for="params.title">{{ __('navigation.params.columns.title') }}</label>
            @include('administrator.resources.translation-field', ['field' => 'title', 'object' => $object->params ?? null])
        </td>
        <td>
            <input
                type="text"
                id="params.title"
                name="params[title]"
                value="{{ old('params.title', $object ? $object->params->translateOrNew($locale)->title : null) }}"
                class="form-control"
            >
        </td>
    </tr>

    <tr>
        <td>
            <label>{{ __('navigation.params.columns.icon') }}</label>
            @include('administrator.resources.translation-field', ['field' => 'icon', 'object' => $object->params ?? null])
        </td>
        <td>
            @include('administrator.partials.image-info', ['image' => $object->params->icon ?? null])

            <input
                type="file"
                id="params.icon"
                name="params[icon]"
                class="form-control"
            >
        </td>
    </tr>

    <tr>
        <td>
            <label for="params.target">{{ __('navigation.params.columns.target') }}</label>
        </td>
        <td>
            <select name="params[target]" id="params.target" class="form-select">
                <option value="_self" @if (old('params.target', $object ? $object->params->target : null) == '_self') selected @endif>{{ __('navigation.params.target.self') }}</option>
                <option value="_blank" @if (old('params.target', $object ? $object->params->target : null) == '_blank') selected @endif>{{ __('navigation.params.target.blank') }}</option>
            </select>
        </td>
    </tr>

    <tr>
        <td>
            <label for="params.css">{{ __('navigation.params.columns.css') }}</label>
            @include('administrator.resources.translation-field', ['field' => 'css', 'object' => $object->params ?? null])
        </td>
        <td>
            <input
                type="text"
                id="params.css"
                name="params[css]"
                value="{{ old('params.css', $object->params->css ?? null) }}"
                class="form-control"
            >
        </td>
    </tr>

    <tr>
        <td>
            <label for="params.style">{{ __('navigation.params.columns.style') }}</label>
            @include('administrator.resources.translation-field', ['field' => 'style', 'object' => $object->params ?? null])
        </td>
        <td>
            <input
                type="text"
                id="params.style"
                name="params[style]"
                value="{{ old('params.style', $object->params->style ?? null) }}"
                class="form-control"
            >
        </td>
    </tr>

{{--    <tr>--}}
{{--        <td>--}}
{{--            <label for="params.iconCss">{{ __('navigation.params.columns.iconCss') }}</label>--}}
{{--            @include('administrator.resources.translation-field', ['field' => 'iconCss', 'object' => $object->params ?? null])--}}
{{--        </td>--}}
{{--        <td>--}}
{{--            <input--}}
{{--                type="text"--}}
{{--                id="params.iconCss"--}}
{{--                name="params[iconCss]"--}}
{{--                value="{{ old('params.iconCss', $object->params->iconCss ?? null) }}"--}}
{{--                class="form-control"--}}
{{--            >--}}
{{--        </td>--}}
{{--    </tr>--}}

{{--    <tr>--}}
{{--        <td>--}}
{{--            <label for="params.iconAlt">{{ __('navigation.params.columns.iconAlt') }}</label>--}}
{{--            @include('administrator.resources.translation-field', ['field' => 'iconAlt', 'object' => $object->params ?? null])--}}
{{--        </td>--}}
{{--        <td>--}}
{{--            <input--}}
{{--                type="text"--}}
{{--                id="params.iconAlt"--}}
{{--                name="params[iconAlt]"--}}
{{--                value="{{ old('params.iconAlt', $object ? $object->params->translateOrNew($locale)->iconAlt : null) }}"--}}
{{--                class="form-control"--}}
{{--            >--}}
{{--        </td>--}}
{{--    </tr>--}}
</table>
