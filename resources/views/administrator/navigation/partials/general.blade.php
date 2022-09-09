<table class="table">
    {{-- Название меню --}}
    <tr>
        <td>
            <label for="name">{{ __('navigation.columns.name') }}</label>
            <span class="text-danger">*</span><br>
            <small class="text-secondary">{{ __('navigation.descriptions.name') }}</small>
        </td>
        <td>
            <input name="name" type="text" id="name" class="form-control" required value="{{ old('name', $object->name ?? null) }}">
            @error('name')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    {{-- Ключ доступа к меню --}}
    <tr>
        <td>
            <label for="key">{{ __('navigation.columns.key') }}</label>
            <span class="text-primary">*</span><br>
            <small class="text-secondary">{{ __('navigation.descriptions.key') }}</small>
        </td>
        <td>
            <input name="key" type="text" id="key" class="form-control" value="{{ old('key', $object->key ?? null) }}">
            @error('key')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    {{-- Ключ доступа к меню --}}
    <tr>
        <td>
            <label for="description">{{ __('navigation.columns.description') }}</label>
        </td>
        <td>
            <input name="description" type="text" id="description" class="form-control" value="{{ old('description', $object->description ?? null) }}">
            @error('description')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    {{-- Кэширование меню --}}
    <tr>
        <td></td>
        <td>
            <div class="form-check">
                @php($cached = old('cached', $object->cached ?? false))
                <input class="form-check-input" type="checkbox" name="cached" value="1" {{ $cached ? 'checked' : '' }} id="cached">
                <label class="form-check-label" for="cached">
                    {{ __('navigation.columns.cached') }}
                </label>
            </div>
            @error('cached')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    {{-- Шаблон меню --}}
    <tr>
        <td>
            <label for="template">{{ __('navigation.columns.template') }}</label>
        </td>
        <td>
            <select name="template" id="template" class="form-select">
                @foreach($templates as $template)
                    <option value="{{ $template }}" @if(old('template') == $template || ($object && $object->template == $template))selected @endif>{{ \Illuminate\Support\Str::ucfirst($template) }}</option>
                @endforeach
            </select>
            @error('template')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>
</table>
