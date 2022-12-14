<input type="hidden" name="navigation_uuid" value="{{ $navigation->uuid }}">

<table class="table">
    <tr>
        <td></td>
        <td>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="is_active" @if(old('is_active') || !isset($object) || (!old('is_active') && $object->is_active)) checked @endif id="active">
                <label class="form-check-label" for="active">{{ __('navigation.items.columns.is_active') }}</label>
            </div>
            @error('is_active')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>
    <tr>
        <td>
            <label for="name">{{ __('navigation.items.columns.text') }}</label>
            <span class="text-danger">*</span>
        </td>
        <td>
            <input required autofocus type="text" id="name" class="form-control" name="name" value="{{ old('name', $object ? $object->translateOrNew($locale)->name : null) }}">
            @error('name')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    <tr>
        <td>
            <label for="class_name">{{ __('navigation.items.columns.class_name') }}</label>
        </td>
        <td>
            <select onchange="return getObjectsType(this)" id="class_name" class="form-select">
                <option value="">{{ __('navigation.items.no_select') }}</option>
                @foreach($navigation_types as $class => $name)
                    <option value="{{ $class }}" @if (old('class_name', $object->meta->object_type ?? $default_class_name) == $class) selected @endif>{{ $name }}</option>
                @endforeach
            </select>
            @error('class_name')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    <tr id="meta" @if (!isset($object->meta->object_type))style="display: none;"@endif>
        <td>
            <label for="meta_uuid">{{ __('navigation.items.columns.meta_uuid') }}</label>
        </td>
        <td>
            <div class="form-floating">
                <select id="meta_uuid" name="meta_uuid" class="form-select" onchange="return changeUrl(this)">
                    <option value=""></option>
                    @foreach($object_list as $obj)
                        <option
                            value="{{ $obj->uuid }}"
                            data-url="{{ $obj->url }}"
                            {{ old('meta_uuid', $object->meta_uuid) == $obj->uuid ? 'selected' : null }}
                        >
                            {{ $obj->name }}
                        </option>
                    @endforeach
                </select>
                <label for="meta_uuid">{{ __('navigation.items.no_select') }}</label>
                @error('object_uuid')<span class="text-danger">{{ $message }}</span>@enderror
            </div>
        </td>
    </tr>

    <tr>
        <td></td>
        <td>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="generate_catalog" @if(old('generate_catalog') || !isset($object) || (!old('generate_catalog') && $object->generate_catalog)) checked @endif id="generate_catalog">
                <label class="form-check-label" for="generate_catalog">{{ __('navigation.items.columns.generate_catalog') }}</label>
            </div>
            @error('generate_catalog')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    <tr id="url" @if (isset($object->meta->object_type))style="display: none" @endif>
        <td>
            <label for="url">{{ __('navigation.items.columns.url') }}</label>
            <span class="text-danger">*</span>
            <br><small class="text-secondary">{{ __('navigation.items.descriptions.url') }}</small>
        </td>
        <td>
            <input type="text" class="form-control" id="url" name="url" required value="{{ old('url', $object->url ?? null) }}" placeholder='https://, "/"'>
        </td>
    </tr>

    @include('administrator.nested.form', ['parent_id' => $object->parent_id ?? $parent_id])
</table>

<script>
    /**
     * ?????????????? ???????????? ?????????????? ???????????????????? ????????
     * @param select
     */
    function getObjectsType(select)
    {
        $('#meta').find('select').find('option').remove()
        $('#meta').find('select').attr('disabled', true)
        $('input[name="url"]').val('')

        if ($(select).val() !== 'external') {
            window.Anita.default('<i class="fas fa-spinner fa-spin me-1"></i> Wait for the load...')

            $(select).attr('disabled', true)

            axios.post('{{ route('admin.navigation.items.objects') }}', {type: $(select).val()})
                .then(function(response) {
                    $(select).attr('disabled', false)

                    window.Anita.success('Successful loaded')

                    if (response.data) {
                        $('#meta').show()
                        $('#meta').find('select').append('<option></option>')

                        response.data.data.forEach(function(item) {
                            $('#meta').find('select')
                                .append('<option value="'+ item.uuid +'" data-url="'+ item.url +'">'+ item.name +'</option>');
                        })

                        $('#meta').find('select').attr('disabled', false).focus()
                        $('#url').hide();
                    } else {
                        window.Anita.error('Fail')
                    }
                })
                .catch(function (error) {
                    $(select).attr('disabled', false)
                    window.Anita.error(error.response.data.message)
                })
        } else {
            $('#meta').hide()
            $('#url').show();
            $('input[name="url"]').focus()
        }
    }

    function changeUrl(select)
    {
        $('input[name="url"]').val($(select).find(':selected').data('url')).focus()
    }
</script>
