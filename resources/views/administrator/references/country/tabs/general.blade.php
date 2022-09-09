<table class="table">
    <tr>
        <td>
            <label for="name">{{ __('countries.columns.name') }}</label>
            <span class="text-danger">*</span>
            @include('administrator.resources.translation-field', ['field' => 'name', 'object' => $country ?? null])
        </td>
        <td>
            <input required id="name" type="text" class="form-control" name="name" autofocus value="{{ old('name', $country ? $country->translateOrNew($locale)->name : null) }}">
            @error('name')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    <tr>
        <td>
            <label for="alpha2">{{ __('countries.columns.alpha2') }}</label><br>
            <small class="text-secondary">
                {!! __('countries.descriptions.alpha2') !!}
            </small>
        </td>
        <td>
            <input id="alpha2" type="text" class="form-control" name="alpha2" autofocus value="{{ old('alpha2', $country ? $country->alpha2 : null) }}">
            @error('alpha2')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    <tr>
        <td>
            <label for="alpha3">{{ __('countries.columns.alpha3') }}</label>
        </td>
        <td>
            <input id="alpha3" type="text" class="form-control" name="alpha3" autofocus value="{{ old('alpha3', $country ? $country->alpha3 : null) }}">
            @error('alpha3')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>
</table>


