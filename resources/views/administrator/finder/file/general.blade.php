<table class="table">
    <tr>
        <td>
            <label for="name">{{ __('finder.columns.name') }}</label>
            @include('administrator.resources.translation-field', ['field' => 'name', 'object' => $object ?? null])
        </td>
        <td>
            <span class="input-group">
                <input id="name" type="text" class="form-control" name="name" value="{{ old('name', $object->onlyName() ?? null) }}">
                <span class="input-group-text" id="basic-addon2">.{{ $object->extension() }}</span>
            </span>
        </td>
    </tr>

    <tr>
        <td>
            <label for="alt">{{ __('finder.columns.alt') }}</label>
            @include('administrator.resources.translation-field', ['field' => 'alt', 'object' => $object ?? null])
        </td>
        <td>
            <input id="alt" type="text" class="form-control" name="alt" value="{{ old('alt', $object->translateOrNew($locale)->alt ?? null) }}">
            <small class="text-secondary">{{ __('finder.columns_description.alt') }}</small>
        </td>
    </tr>

    <tr>
        <td>
            <label for="title">{{ __('finder.columns.title') }}</label>
            @include('administrator.resources.translation-field', ['field' => 'title', 'object' => $object ?? null])
        </td>
        <td>
            <input id="title" type="text" class="form-control" name="title" value="{{ old('title', $object->translateOrNew($locale)->title ?? null) }}">
        </td>
    </tr>

    <tr>
        <td>
            <label for="description">{{ __('finder.columns.description') }}</label><br>
            <small class="text-secondary">{{ __('finder.columns_description.description') }}</small>
            @include('administrator.resources.translation-field', ['field' => 'description', 'object' => $object ?? null])
        </td>
        <td>
            <div class="form-floating">
                <textarea id="description" class="form-control" name="description" style="height: 100px" placeholder="">{{ old('description', $object->translateOrNew($locale)->description ?? null) }}</textarea>
                <label for="description">{{ __('finder.columns.description_placeholder') }}</label>
            </div>
        </td>
    </tr>

    <tr>
        <td>
            <label for="class">{{ __('finder.columns.class') }}</label>
        </td>
        <td>
            <input id="class" type="text" class="form-control" name="class" value="{{ old('class', $object->class ?? null) }}">
        </td>
    </tr>
</table>
