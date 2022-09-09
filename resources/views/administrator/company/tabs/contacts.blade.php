<div class="alert alert-info d-flex">
    <i class="fas fa-info-circle mt-1 me-2"></i>
    <p class="mb-0">
        {{ __('company.info') }}
    </p>
</div>

<table class="table">
    <tr>
        <td>
            <label for="">{{ __('company.columns.headers.addresses') }}</label>
            @include('administrator.resources.translation-field', ['field' => 'value', 'object' => $company->address ?? null])
        </td>
        <td>
            <textarea maxlength="255" name="address" class="form-control">{{ old('address', $company && $company->address ? $company->address->translateOrNew($locale)->value : null) }}</textarea>
        </td>
    </tr>

    <tr>
        <td>
            <label for="phones">{{ __('company.columns.headers.phones') }}</label>
        </td>
        <td>
            <a href="javascript:void(0)" class="link-ajax" onclick="return addPhone('#phoneList')">[ + ] {{ __('company.create_element.phone') }}</a>
            <ul class="list-group-sortable sortable-primary" id="phoneList">
                @if (isset($company->phones) && $company->phones->count())
                    @foreach($company->phones as $phone)
                        <li class="input-group my-1">
                            <input type="text" name="phone[{{ $phone->uuid }}][number]" placeholder="{{ __('company.columns.phone.number') }}" value="{{ $phone->number }}" class="form-control">
                            <input type="text" name="phone[{{ $phone->uuid }}][description]" placeholder="{{ __('company.columns.phone.description') }}" value="{{ $phone->translateOrNew($locale)->description }}" class="form-control">
                        </li>
                    @endforeach
                @endif
            </ul>
        </td>
    </tr>

    <tr>
        <td>
            <label for="">{{ __('company.columns.headers.emails') }}</label>
        </td>
        <td>
            <a href="javascript:void(0)" class="link-ajax" onclick="return addEmail('#emailList')">[ + ] {{ __('company.create_element.email') }}</a>
            <ul class="list-group-sortable sortable-primary" id="emailList">
                @if (isset($company->emails) && $company->emails->count())
                    @foreach($company->emails as $email)
                        <li class="input-group my-1">
                            <input type="text" name="email[{{ $email->uuid }}][value]" placeholder="{{ __('company.columns.email.value') }}" value="{{ $email->value }}" class="form-control">
                            <input type="text" name="email[{{ $email->uuid }}][description]" placeholder="{{ __('company.columns.email.description') }}" value="{{ $email->translateOrNew($locale)->description }}" class="form-control">
                        </li>
                    @endforeach
                @endif
            </ul>
        </td>
    </tr>

    <tr>
        <td>
            <label for="">{{ __('company.columns.headers.websites') }}</label>
        </td>
        <td>
            <a href="javascript:void(0)" class="link-ajax" onclick="return addWebsite('#websiteList')">[ + ] {{ __('company.create_element.website') }}</a>
            <ul class="list-group-sortable sortable-primary" id="websiteList">
                @if (isset($company->websites) && $company->websites->count())
                    @foreach($company->websites as $website)
                        <li class="input-group my-1">
                            <input type="url" name="website[{{ $website->uuid }}][value]" value="{{ $website->value }}" class="form-control">
                        </li>
                    @endforeach
                @endif
            </ul>
        </td>
    </tr>

    <tr>
        <td>
            <label for="">{{ __('company.columns.headers.timework') }}</label>
            @include('administrator.resources.translation-field', ['field' => 'value', 'object' => $company->timework ?? null])
        </td>
        <td>
            <textarea maxlength="255" name="timework" class="form-control">{{ old('timework', $company && $company->timework ? $company->timework->translateOrNew($locale)->value : null) }}</textarea>
        </td>
    </tr>
</table>

<script>
    /**
     * Добавить номер телефона
     **/
    function addPhone(elementAppend)
    {
        let uuid_element = uuid4();

        let fields = createElementArray([
            {
                tag: 'input',
                attrs: {
                    type: 'tel',
                    name: 'phone[{uuid}][number]',
                    class: 'form-control',
                    placeholder: '{{ __('company.columns.phone.number') }}'
                }
            },
            {
                tag: 'input',
                attrs: {
                    type: 'text',
                    name: 'phone[{uuid}][description]',
                    class: 'form-control',
                    placeholder: '{{ __('company.columns.phone.description') }}'
                }
            },
        ], {uuid: uuid_element})

        let $li = createElement('li', {class: 'input-group my-1'}, {uuid: uuid_element})
        $li.append(fields)

        $(elementAppend).append($li)
    }

    /**
     *
     **/
    function addEmail(elementAppend)
    {
        let uuid_element = uuid4();

        let fields = createElementArray([
            {
                tag: 'input',
                attrs: {
                    type: 'email',
                    name: 'email[{uuid}][value]',
                    class: 'form-control',
                    placeholder: '{{ __('company.columns.email.value') }}'
                }
            },
            {
                tag: 'input',
                attrs: {
                    type: 'text',
                    name: 'email[{uuid}][description]',
                    class: 'form-control',
                    placeholder: '{{ __('company.columns.email.description') }}'
                }
            },
        ], {uuid: uuid_element})

        let $li = createElement('li', {class: 'input-group my-1'}, {uuid: uuid_element})
        $li.append(fields)

        $(elementAppend).append($li)
    }

    /**
     *
     **/
    function addWebsite(elementAppend)
    {
        let uuid_element = uuid4();

        let fields = createElementArray([
            {
                tag: 'input',
                attrs: {
                    type: 'url',
                    name: 'website[{uuid}][value]',
                    class: 'form-control',
                    placeholder: '{{ __('company.columns.website') }}'
                }
            },
        ], {uuid: uuid_element})

        let $li = createElement('li', {class: 'input-group my-1'}, {uuid: uuid_element})
        $li.append(fields)

        $(elementAppend).append($li)
    }

    /**
     *
     * @param elementAppend
     * @param fields
     * @param data
     */
    function createElementArray(fields, data)
    {
        /*  */
        let items = []

        /*  */
        fields.forEach((field) => {
            /* Атрибуты элемента */
            if (field.attrs && data) {
                Object.keys(field.attrs).forEach((key) => {
                    field.attrs[key] = dataReplace(field.attrs[key], data)
                })
            }

            /* Data-* */
            if (field.data && data) {
                Object.keys(field.data).forEach((key) => {
                    field.data[key] = dataReplace(field.data[key], data)
                })
            }

            items.push(createElement(field.tag, field.attrs ?? null, field.data ?? null))
        })

        return items;
    }

    /**
     * Создаст элемент
     * @param tag
     * @param attrs
     * @param data
     */
    function createElement(tag, attrs, data)
    {
        let $element = $('<'+ tag +'/>')

        if (attrs) {
            Object.keys(attrs).forEach((attr) => {
                $element.attr(attr, attrs[attr])
            })
        }

        if (data) {
            Object.keys(data).forEach((key) => {
                $element.attr('data-' + key, data[key])
            })
        }

        return $element
    }

    /**
     * Подставит данные вместо шаблонов
     * @param str
     * @param data
     * @returns {*}
     */
    function dataReplace(str, data)
    {
        Object.keys(data).forEach((key) => {
            if (str.indexOf('{'+ key +'}') >= 0) {
                let regExp = RegExp('{'+ key +'}', 'gi')
                str = str.replace(regExp, data[key])
            }
        })

        return str;
    }

</script>
