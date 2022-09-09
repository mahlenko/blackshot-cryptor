<input type="hidden" name="navigation_uuid" value="{{ $navigation->uuid }}">

<table class="table">
    <tr>
        <td>
            <label for="name">{{ __('navigation.items.columns.text') }}</label>
            <span class="text-danger">*</span>
            @include('administrator.resources.translation-field', ['field' => 'name', 'object' => $object ?? null])
        </td>
        <td>
            <input required autofocus type="text" id="name" class="form-control" name="name" value="{{ old('name', $object ? $object->translateOrNew($locale)->name : null) }}">
            @error('name')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    @if(!isset($object->meta->object_type))
    <tr>
        <td>
            <label for="class_name">{{ __('navigation.items.columns.class_name') }}</label>
            <span class="text-danger">*</span>
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
    @endif

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

    <tr>
        <td>
            <label for="template">
                {{ __('navigation.items.columns.template') }}
            </label>
        </td>
        <td>
            <select name="template" id="template" class="form-select">
                @foreach($templates as $value => $label)
                <option value="{{ $value }}" @if((isset($object) && $object->template == $value) || old('template') == $value || (!isset($object) && $value == 'web.components.navigation.items.default'))selected @endif>{{ \Illuminate\Support\Str::ucfirst($label) }}</option>
                @endforeach
            </select>
        </td>
    </tr>

    <tr id="generate_catalog_row" @if (!$object || !$object->meta || !in_array($object->meta->object_type, \App\Models\Navigation\NavigationItem::SHOW_GENERATE_CHILDREN)) style="display: none" @endif>
        <td></td>
        <td>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="generate_catalog" @if(old('generate_catalog') || (isset($object) && !old('generate_catalog') && $object->generate_catalog)) checked @endif id="generate_catalog">
                <label class="form-check-label" for="generate_catalog">
                    {{ __('navigation.items.columns.generate_catalog') }}
                    <br><small class="text-secondary">{{ __('navigation.items.descriptions.generate_catalog') }}</small>
                </label>
            </div>
            @error('generate_catalog')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    <tr id="generate_products_row" @if (!$object || !$object->meta || $object->meta->object_type != \App\Models\Catalog\Category::class) style="display: none" @endif>
        <td></td>
        <td>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="generate_products" @if(old('generate_products') || (isset($object) && !old('generate_products') && $object->generate_products)) checked @endif id="generate_products">
                <label class="form-check-label" for="generate_products">
                    {{ __('navigation.items.columns.generate_products') }}
                    <br><small class="text-secondary">{{ __('navigation.items.descriptions.generate_products') }}</small>
                </label>
            </div>
            @error('generate_catalog')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

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

</table>

<script>
    /**
     * Получит список страниц выбранного типа
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
                            if (item.url == null) item.url = '/';
                            $('#meta').find('select')
                                .append('<option value="'+ item.uuid +'" data-url="'+ item.url +'">'+ item.name +'</option>');
                        })

                        $('#meta').find('select').attr('disabled', false).focus()
                        $('#url').hide();

                        if (response.data.show_generate.children) {
                            $('#generate_catalog_row').show()
                        } else {
                            $('#generate_catalog_row').hide().find(':checkbox').prop('checked', false)
                        }

                        if (response.data.show_generate.products) {
                            $('#generate_products_row').show()
                        } else {
                            $('#generate_products_row').hide().find(':checkbox').prop('checked', false)
                        }

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

            $('#generate_catalog_row, #generate_products_row').hide().find(':checkbox').prop('checked', false)
        }
    }

    function changeUrl(select)
    {
        $('input[name="url"]').val($(select).find(':selected').data('url')).focus()
    }
</script>
