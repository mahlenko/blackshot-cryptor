<table class="table">
    <tr>
        <td>
            <label for="name">
                {{ __('page.columns.name') }}
                <span class="text-danger">*</span>
            </label>

            @include('administrator.resources.translation-field', ['field' => 'name', 'object' => $object])
        </td>
        <td>
            <input id="name" type="text" name="name" class="form-control" autofocus required value="{{ old('name', $object ? $object->translateOrDefault($locale)->name : null) }}">
            @error('name')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    {{--@if ($object && $object->children->count())
        <tr>
            <td></td>
            <td>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="view_as_catalog" name="view_as_catalog" value="1" @if(old('view_as_catalog') || !isset($object) || (!old('view_as_catalog') && $object->view_as_catalog)) checked @endif>
                    <label class="form-check-label" for="view_as_catalog">{{ __('page.columns.view_as_catalog') }}</label>
                </div>
                <p class="mb-0">
                    <small class="text-secondary">
                        {!! __('page.descriptions.view_as_catalog') !!}
                    </small>
                </p>
                @error('view_as_catalog')<span class="text-danger">{{ $message }}</span>@enderror
            </td>
        </tr>
    @endif--}}

    <tr>
        <td>
            <label>{{ __('page.columns.preview') }}</label>
            @include('administrator.resources.translation-field', ['field' => 'preview', 'object' => $object])
        </td>
        <td>
            @include('administrator.partials.image-list', ['images' => $object ? $object->images : null])

            <input class="form-control" type="file" name="preview[]" id="preview" multiple accept="image/*">
            @error('preview')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    <tr>
        <td>
            <label for="description">{{ __('page.columns.description') }}</label><br>
            <small class="text-secondary ms-5">{{ __('page.column_descriptions.description') }}</small>
            @include('administrator.resources.translation-field', ['field' => 'description', 'object' => $object])
        </td>
        <td>
            <div class="form-floating">
                <textarea class="form-control" name="description" placeholder="{{ __('page.columns.description') }}" id="description" style="height: 100px">{{ old('description', $object ? $object->translateOrNew($locale)->description : null) }}</textarea>
                <label for="description">{{ __('page.columns.description') }}</label>
                @error('description')<span class="text-danger">{{ $message }}</span>@enderror
            </div>
        </td>
    </tr>

    @include('administrator.nested.form', ['node' => $object])

    <tr>
        <td colspan="2">
            @error('body')<span class="text-danger">{{ $message }}</span>@enderror
            <textarea name="body" class="editor" style="visibility: hidden">{{old('body', $object ? $object->translateOrNew($locale)->body : null)}}</textarea>
        </td>
    </tr>

{{--    <tr>--}}
{{--        <td>--}}
{{--            <label for="template">{{ __('page.columns.template') }}</label>--}}
{{--            <span class="text-danger">*</span>--}}
{{--        </td>--}}
{{--        <td>--}}
{{--            <select name="template" id="template" class="form-select">--}}
{{--                @foreach($templates as $template)--}}
{{--                    <option value="{{ $template }}" @if(old('template') == $template || (isset($object) && $object->template == $template))selected @endif>--}}
{{--                        {{ \Illuminate\Support\Str::ucfirst($template) }}--}}
{{--                    </option>--}}
{{--                @endforeach--}}
{{--            </select>--}}
{{--            @error('template')<span class="text-danger">{{ $message }}</span>@enderror--}}
{{--        </td>--}}
{{--    </tr>--}}
</table>
