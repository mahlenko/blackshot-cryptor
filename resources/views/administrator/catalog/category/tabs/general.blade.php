<table class="table">
    <tr>
        <td>
            <label for="name">{{ __('catalog.category.columns.name') }}</label>
            <span class="text-danger">*</span>
            @include('administrator.resources.translation-field', ['field' => 'name', 'object' => $category ?? null])
        </td>
        <td>
            <input id="name" name="name" required type="text" class="form-control" value="{{ old('name', $category ? $category->translateOrNew($locale)->name : null) }}">
        </td>
    </tr>

    <tr>
        <td>
            <label>{{ __('catalog.category.columns.preview') }}</label>
        </td>
        <td>
            @include('administrator.partials.image-info', ['image' => $category->icon ?? null])

            <input class="form-control" type="file" name="icon" id="icon" accept="image/*">
            @error('icon')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    <tr>
        <td>
            <label for="description">{{ __('catalog.category.columns.description') }}</label><br>
            <small class="text-secondary ms-5">{{ __('catalog.category.column_descriptions.description') }}</small>
            @include('administrator.resources.translation-field', ['field' => 'description', 'object' => $category ?? null])
        </td>
        <td>
            <div class="form-floating">
                <textarea class="form-control" name="description" placeholder="{{ __('catalog.category.columns.description') }}" id="description" style="height: 100px">{{ old('description', $category ? $category->translateOrNew($locale)->description : null) }}</textarea>
                <label for="description">{{ __('catalog.category.columns.description') }}</label>
                @error('description')<span class="text-danger">{{ $message }}</span>@enderror
            </div>
        </td>
    </tr>

    @include('administrator.nested.form', ['parent' => $parent_category ? $parent_category : $category->parent ?? null])

    <tr>
        <td colspan="2">
            <textarea name="body" class="editor" cols="30" rows="10">{{ old('body', $category ? $category->translateOrNew($locale)->body : null) }}</textarea>
        </td>
    </tr>
</table>
