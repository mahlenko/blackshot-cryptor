<table class="table">
    <tr>
        <td></td>
        <td>
            <div class="form-check form-switch">
                <input class="form-check-input" id="is_active" type="checkbox" name="is_active" @if(old('is_active') || !isset($feature) || (!old('is_active') && $feature->is_active)) checked @endif>
                <label class="form-check-label" for="is_active">{{ __('catalog.feature.columns.is_active') }}</label>
            </div>
            @error('is_active')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    <tr>
        <td>
            <label for="name">
                {{ __('catalog.feature.columns.name') }}
                <span class="text-danger">*</span>
            </label>
            @include('administrator.resources.translation-field', ['field' => 'name', 'object' => $feature ?? null])
        </td>
        <td>
            <input required id="name" name="name" type="text" class="form-control" value="{{ old('name', $feature ? $feature->translateOrNew($locale)->name : null) }}">
            @error('name')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    <tr>
        <td>
            @php($default_purpose = array_key_first($purpose_list))
            <label for="{{ $default_purpose }}">
                {{ __('catalog.feature.columns.purpose') }}
                <span class="text-danger">*</span>
            </label>
            <br>
            <small class="text-secondary">
                {{ __('catalog.feature.columns.purpose_description') }}
            </small>
        </td>
        <td>
            <div class="d-flex flex-column flex-md-row">
                <div class="w-50">
                    @foreach($purpose_list as $value => $variant)
                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="purpose"
                                id="{{ $value }}"
                                value="{{ $value }}"
                                data-description="{{ $variant['description'] }}"
                                onchange="return getPurposeData(this)"
                                @if (($feature && $feature->purpose == $value) || old('view_product') == $value || (!$feature && $value == $default_purpose)) checked @endif
                            >
                            <label class="form-check-label text-nowrap" for="{{ $value }}">
                                {{ $variant['title'] }}
                            </label>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex align-content-start px-5 flex-fill">
                    <i class="fas fa-info-circle pe-2 text-info"></i>
                    <small id="purpose-description" class="text-secondary"></small>
                </div>
            </div>
            @error('purpose')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    <tr>
        <td>
            <label for="group">
                {{ __('catalog.feature.columns.group') }}
            </label>
        </td>
        <td>
            <select name="feature_group_uuid" id="group" class="form-select">
                <option value="">{{ __('catalog.feature.without_group') }}</option>
                @if ($feature_groups && count($feature_groups))
                    @foreach($feature_groups as $group)
                        <option value="{{ $group->uuid }}" @if(old('feature_group_uuid') == $group->uuid || ($feature && $feature->feature_group_uuid == $group->uuid)) selected @endif>{{ $group->name }}</option>
                    @endforeach
                @endif
            </select>
            @error('feature_group_uuid')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    <tr>
        <td>
            <label for="view_product">
                {{ __('catalog.feature.columns.view_product.title') }}
                <span class="text-danger">*</span>
            </label>
        </td>
        <td>
            <select name="view_product" id="view_product" class="form-select" onchange="return getViewFilter(this.value)" data-value="{{ old('view_product', $feature->view_product ?? null) }}" disabled></select>
        </td>
    </tr>

    <tr>
        <td>
            <label for="view_filter">
                {{ __('catalog.feature.columns.view_filter') }}
                <span class="text-danger">*</span>
            </label>
        </td>
        <td>
            <select name="view_filter" id="view_filter" class="form-select" onchange="return createVariant()" data-value="{{ old('view_filter', $feature->view_filter ?? null) }}" disabled></select>
        </td>
    </tr>

    @include('administrator.nested.form', ['nested_only_sortable' => true])

    <tr>
        <td colspan="2">
            @error('description')<span class="text-danger">{{ $message }}</span>@enderror
            <textarea name="description" class="editor" style="visibility: hidden">{{old('description', $feature ? $feature->translateOrNew($locale)->description : null)}}</textarea>
        </td>
    </tr>

    <tr>
        <td></td>
        <td>
            <div class="form-check form-switch">
                <input class="form-check-input" id="is_show_feature" type="checkbox" name="is_show_feature" @if(old('is_show_feature') || (isset($feature) && $feature->is_show_feature) || !$feature) checked @endif>
                <label class="form-check-label" for="is_show_feature">{{ __('catalog.feature.columns.is_show_feature') }}</label>
            </div>
            @error('is_show_feature')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    <tr>
        <td></td>
        <td>
            <div class="form-check form-switch">
                <input class="form-check-input" id="is_show_description" type="checkbox" name="is_show_description" @if(old('is_show_description') || (isset($feature) && $feature->is_show_description)) checked @endif>
                <label class="form-check-label" for="is_show_description">{{ __('catalog.feature.columns.is_show_description') }}</label>
            </div>
            @error('is_show_description')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    <tr>
        <td>
            <label for="prefix">
                {{ __('catalog.feature.columns.prefix') }}
            </label>
            <br>
            <small class="text-secondary">
                {{ __('catalog.feature.columns.prefix_description') }}
            </small>

            @include('administrator.resources.translation-field', ['field' => 'prefix', 'object' => $feature ?? null])
        </td>
        <td>
            <input id="prefix" name="prefix" type="text" class="form-control" value="{{ old('prefix', $feature ? $feature->prefix : null) }}">
        </td>
    </tr>

    <tr>
        <td>
            <label for="postfix">
                {{ __('catalog.feature.columns.postfix') }}
            </label>
            <br>
            <small class="text-secondary">
                {{ __('catalog.feature.columns.postfix_description') }}
            </small>

            @include('administrator.resources.translation-field', ['field' => 'postfix', 'object' => $feature ?? null])
        </td>
        <td>
            <input id="postfix" name="postfix" type="text" class="form-control" value="{{ old('postfix', $feature ? $feature->postfix : null) }}">
        </td>
    </tr>
</table>

<script>
    let object = @json($feature);
    let _last_hidden_fields = null;

    document.addEventListener("DOMContentLoaded", () => {
        /* покажем по-умолчанию описание цели и выполним остальные тригеры */
        let purposes_radio = document.getElementsByName('purpose');
        purposes_radio.forEach(function(radio){
            if (radio.checked) {
                getPurposeData(radio)
            }
        })
    })

    /**
     * Выбор цели
     * @param el
     */
    function getPurposeData(el)
    {
        document.getElementById('purpose-description').textContent = el.dataset.description

        let purpose = el.value

        /* обновим табы */
        updateTabs(purpose)

        /* обновим форму */
        updateForm(purpose)
    }

    /**
     * Обновить доступные вкладки
     * @param value
     * @param remove_tab Удалить дополнительный таб
     */
    function updateTabs(value, remove_tab)
    {
        let showTabs = {
            'find_product' : ['general', 'variants', 'categories'],
            'group_products' : ['general', 'variants', 'categories'],
            'group_variants' : ['general', 'variants', 'categories'],
            'organize_catalog' : ['general', 'variants', 'categories', 'meta'],
            'describe' : ['general', 'categories'],
        }

        if (remove_tab) {
            let tabs = JSON.parse(JSON.stringify(showTabs[value]))
            if (tabs.indexOf(remove_tab) >= 0) {
                tabs[tabs.indexOf(remove_tab)] = undefined
            }
            return hiddenTabs(tabs, false)
        }

        hiddenTabs(showTabs[value], false)
    }

    /**
     * @param keys array
     * @param hidden boolean
     **/
    function hiddenTabs(keys, hidden)
    {
        let headerTabs = document.getElementsByClassName('card-header-tabs')
        let tabList = headerTabs[0].querySelectorAll('.nav-link')

        tabList.forEach(function(tab) {
            if (keys.includes(tab.attributes["aria-controls"].value.toLowerCase())) {
                tab.parentElement.hidden = hidden
            } else {
                tab.parentElement.hidden = !hidden
            }
        })
    }

    /**
     * Обновить поля формы
     * @param value
     */
    function updateForm(value)
    {
        let hideFields = {
            'find_product' : ['slug', 'icon'],
            'group_products' : ['slug', 'icon'],
            'group_variants' : ['slug', 'icon'],
            'organize_catalog' : [],
            'describe' : ['slug', 'icon'],
        }

        let $body = $('.card-body')
        let form = $body.parents('form')[0]
        let hide_fields = hideFields[value]

        /* обновим элементы формы если отличается их список от предыдущего обновлеия */
        if (_last_hidden_fields !== hideFields) {
            /* покажем все спрятанные строки */
            Array.from(form.elements).forEach(function (field) {
                let field_row = $(field).parents('tr')[0]
                if (field_row) {
                    field_row.hidden = false
                }
            })

            /* спрячем нужные строки */
            if (hide_fields) {
                hide_fields.forEach(function (name) {
                    let field = form.querySelector('[name="' + name + '"]')
                    if (field) {
                        let field_row = $(field).parents('tr')[0]
                        if (field_row) {
                            field.value = null;
                            field_row.hidden = true
                        }
                    }
                })
            }

            _last_hidden_fields = hide_fields
        }

        /* получим данные для вариантов отображения */
        return getViewProduct(value)
    }

    /**
     * Варианты отображения в товаре
     **/
    function getViewProduct(value)
    {
        let $body = $('.card-body')
        let form = $body.parents('form')[0]
        let dropdown = form.querySelector('select[name="view_product"]')
        dropdown.disabled = true
        submitFormBtn.disabled = true

        axios.post('{{ route('admin.catalog.feature.json.product') }}', { value })
            .then(function(response) {
                updateOptionsDropdown(dropdown, response.data)
                return getViewFilter(response.data[0].value)
            })
            .catch(function(error) {
                // alert(error.response.data.message)
                console.log(Promise.reject(error))
            })
    }

    /**
     * Варианты отображения в фильтре
     **/
    function getViewFilter(value)
    {
        let $body = $('.card-body')
        let form = $body.parents('form')[0]
        let dropdown = form.querySelector('select[name="view_filter"]')
        let purpose = $(form).find('input[name="purpose"]:checked').val()

        dropdown.disabled = true
        submitFormBtn.disabled = true

        if (value === 'checkbox') {
            updateTabs(purpose, 'variants')
        } else {
            updateTabs(purpose)
        }

        axios.post('{{ route('admin.catalog.feature.json.filter') }}', { value, purpose })
            .then(function(response) {
                updateOptionsDropdown(dropdown, response.data)
                createVariant()
            })
            .catch(function(error) {
                console.log(Promise.reject(error))
            })
    }

    /**
     * Обновить варианты выпадающего списка
     **/
    function updateOptionsDropdown(dropdown, options)
    {
        /* очистим от предыдущих значений */
        dropdown.options.length = 0

        /* заполним новыми значениями */
        Object.keys(options).map(function(key) {
            let item = options[key]
            /* создаем элемент option */
            let option = document.createElement('option')
            option.value = item.value
            option.innerText = item.name

            /* добавим элемент в список */
            dropdown.appendChild(option)
        })

        /* разблокируем поле */
        if (dropdown.options.length) {
            dropdown.disabled = false

            /* выберем значение по-умолчанию если есть */
            if (dropdown.dataset.value) {
                dropdown.value = dropdown.dataset.value
                $(dropdown).removeAttr('data-value')
            }
        } else {
            /* заблокируем поле */
            dropdown.disabled = true
        }

        submitFormBtn.disabled = false
    }
</script>
