<table class="table">
    <tr>
        <td>
            <label for="name">
                {{ __('company.columns.name') }}
                <span class="text-danger">*</span>
            </label><br>
            <small class="text-secondary">
                {{ __('company.column_descriptions.name') }}
            </small>

            @include('administrator.resources.translation-field', ['field' => 'name', 'object' => $company ?? null])
        </td>
        <td>
            <input required autofocus type="text" class="form-control" id="name" name="name" value="{{ old('name', $company ? $company->translateOrNew($locale)->name : null) }}">
            @error('name')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

{{--    @include('administrator.resources.field-slug', ['object' => $company, 'uuid' => $company->uuid ?? $uuid, 'prefix' => route('company')])--}}

    <tr>
        <td>
            <label for="type">
                {{ __('company.columns.type') }}
                <span class="text-danger">*</span>
            </label>
        </td>
        <td>
            <select required name="type" id="type" class="form-select">
                <option value=""></option>
                @foreach($types as $value => $type)
                    <option value="{{ $value }}" {{ $value != 'office' ? 'disabled' : null }} {{ old('type', $company->type ?? 'office') == $value ? 'selected' : null }}>{{ $type }}</option>
                @endforeach
            </select>
            @error('type')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    <tr>
        <td>
            <label>{{ __('company.columns.image') }}</label>
        </td>
        <td>
            @include('administrator.partials.image-info', ['image' => $company->image ?? null])

            <input class="form-control" type="file" name="image" id="image" accept="image/*">
            @error('image')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    <tr>
        <td>
            <label for="description">{{ __('company.columns.description') }}</label><br>
            <small class="text-secondary ms-5">{{ __('company.column_descriptions.description') }}</small>
            @include('administrator.resources.translation-field', ['field' => 'description', 'object' => $company ?? null])
        </td>
        <td>
            <div class="form-floating">
                <textarea class="form-control" name="description" placeholder="{{ __('company.columns.description') }}" id="description" style="height: 100px">{{ old('description', $company ? $company->translateOrNew($locale)->description : null) }}</textarea>
                <label for="description">{{ __('company.columns.description') }}</label>
                @error('description')<span class="text-danger">{{ $message }}</span>@enderror
            </div>
        </td>
    </tr>

{{--    @include('administrator.nested.form', ['node' => $company])--}}

    <tr>
        <td colspan="2">
            @error('body')<span class="text-danger">{{ $message }}</span>@enderror
            <textarea name="body" class="editor" style="visibility: hidden">{{old('body', $company ? $company->translateOrNew($locale)->body : null)}}</textarea>
        </td>
    </tr>

</table>
