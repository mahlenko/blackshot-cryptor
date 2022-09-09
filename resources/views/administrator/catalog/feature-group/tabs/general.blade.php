<table class="table">
    <tr>
        <td></td>
        <td>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" @if(old('is_active') || !isset($group) || (!old('is_active') && $group->is_active)) checked @endif id="active">
                <label class="form-check-label" for="active">{{ __('catalog.feature.group.columns.is_active') }}</label>
            </div>
            @error('is_active')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    <tr>
        <td>
            <label for="name">
                {{ __('catalog.feature.group.columns.name') }}
                <span class="text-danger">*</span>
            </label>
            @include('administrator.resources.translation-field', ['field' => 'name', 'object' => $group ?? null])
        </td>
        <td>
            <input class="form-control" autofocus id="name" type="text" name="name" value="{{ old('name', $group ? $group->translateOrNew($locale)->name : null) }}" required>
        </td>
    </tr>

    @include('administrator.nested.form', ['nested_only_sortable' => true])

    <tr>
        <td colspan="2">
            @error('body')<span class="text-danger">{{ $message }}</span>@enderror
            <textarea name="body" class="editor" style="visibility: hidden">{{old('body', $group ? $group->translateOrNew($locale)->body : null)}}</textarea>
        </td>
    </tr>
</table>
