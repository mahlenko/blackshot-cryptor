<table class="table">
    <input type="hidden" name="feature_uuid" value="{{ $object->feature_uuid }}">

    <tr>
        <td>
            <label for="name">{{ __('catalog.feature.variant.columns.name') }}</label>
        </td>
        <td>
            <input id="name" class="form-control" type="text" name="name" required value="{{ old('name', $object ? $object->translateOrNew($locale)->name : null) }}">
            @error('name')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    <tr>
        <td>
            <label>{{ __('catalog.feature.variant.columns.preview') }}</label>
        </td>
        <td>
            @include('administrator.partials.image-info', ['image' => $object->icon ?? null])

            <input class="form-control" type="file" name="icon" id="icon">
            @error('icon')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    <tr>
        <td>
            <label for="description">{{ __('catalog.feature.variant.columns.description') }}</label><br>
            <small class="text-secondary d-block ms-5">{{ __('catalog.feature.variant.columns.description_description') }}</small>
        </td>
        <td>
            <div class="form-floating">
                <textarea class="form-control" name="description" placeholder="{{ __('catalog.feature.variant.columns.description') }}" id="description" style="height: 100px">{{ old('description', $object ? $object->translateOrNew($locale)->description : null) }}</textarea>
                <label for="description">{{ __('catalog.feature.variant.columns.description') }}</label>
                @error('description')<span class="text-danger">{{ $message }}</span>@enderror
            </div>
        </td>
    </tr>

    <tr>
        <td>
            <label for="url">{{ __('catalog.feature.variant.columns.url') }}</label><br>
            <small class="text-secondary">{{ __('catalog.feature.variant.columns.url_description') }}</small>
        </td>
        <td>
            <input id="url" class="form-control" type="text" name="url" placeholder="https://" value="{{ old('url', $object ? $object->url : null) }}">
            @error('name')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    <tr>
        <td colspan="2">
            @error('body')<span class="text-danger">{{ $message }}</span>@enderror
            <textarea name="body" class="editor" style="visibility: hidden">{{old('description', $object ? $object->translateOrNew($locale)->body : null)}}</textarea>
        </td>
    </tr>
</table>
