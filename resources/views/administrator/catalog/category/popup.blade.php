@extends('administrator.layouts.popup')

@section('content')
    @if ($object_list && count($object_list))
        <table class="table table-sm table-hover" id="categoryList">
            @foreach($object_list as $index => $object)
                <tr>
                    <td class="pe-0" style="width: 24px">
                        <div class="form-check">
                            <input class="form-check-input me-0" type="checkbox" data-name="{{ $object['name'] }}" data-slug="{{ $object['eloquent']->ancestors->sortBy('_lft')->pluck('name')->join(' / ') }}" value="{{ $object['value'] }}" id="{{ $object['value'] }}">
                        </div>
                    </td>

                    <td @if ($object['level0'])style="padding-left: {{ $object['level0'] }}rem;"@endif>
                        <label class="d-flex form-check-label w-100" for="{{ $object['value'] }}">
                            @if (isset($object_list[$index + 1]) && $object_list[$index + 1]['level0'] > $object['level0'])
                                <img src="{{ asset('administrator/icons/folder.png') }}" class="me-2" alt="" height="16">
                            @else
                                <img src="{{ asset('administrator/icons/file.png') }}" class="me-2" alt="" height="16">
                            @endif
                            {{ $object['name'] }}
                        </label>
                    </td>
                </tr>
            @endforeach
        </table>

        <p>
            <a href="javascript:void(0)" class="btn btn-primary" onclick="return selected()">{{ $action_text }}</a>
        </p>
    @else
    @endif

    <script>
        category_inputs = document
            .getElementById('category_selected_append')
            .getElementsByTagName('input')

        if (category_inputs.length) {
            Array.from(category_inputs).forEach((input) => {
                let checkbox = document
                    .getElementById('categoryList')
                    .querySelector('input[value="'+ input.value +'"]')

                if (checkbox) {
                    if (!checkbox.checked) checkbox.checked = true
                    if (!checkbox.disabled) checkbox.disabled = true
                }
            })
        }

        /**
         * Выбор полей и вставка их в форму
         */
        function selected()
        {
            let category_list = document.getElementById('category_selected_append')
            Array.from(categoryList.getElementsByTagName('input')).forEach((checkbox) => {
                if (checkbox.checked && !checkbox.disabled) {
                    /* добавляем выбранные элементы в форму */
                    let li = document.createElement('li')
                    li.classList.add('list-item')

                    let name_tag = document.createElement('span')
                    name_tag.classList.add('name')
                    name_tag.textContent = checkbox.dataset.name

                    let icon = document.createElement('img')
                    icon.src = "{{ asset('administrator/icons/folder.png') }}"
                    icon.classList.add('me-1')
                    icon.style.height = '16px'
                    icon.alt = ''

                    let slug_tag = document.createElement('small')
                    slug_tag.classList.add('text-secondary')
                    slug_tag.textContent = checkbox.dataset.slug

                    let input = document.createElement('input')
                    input.type = 'hidden'
                    input.name = 'category['+ checkbox.value +']'
                    input.value = checkbox.value

                    let link_delete = document.createElement('a')
                    link_delete.href = 'javascript:void(0)'
                    link_delete.innerHTML = '<i class="far fa-trash-alt text-danger"></i>'
                    link_delete.onclick = () => { return deleteCategory(link_delete) }

                    li.appendChild(icon)
                    li.appendChild(name_tag)
                    li.appendChild(slug_tag)
                    li.appendChild(input)
                    li.appendChild(link_delete)
                    category_list.appendChild(li)
                }
            })

            $.magnificPopup.instance.close()
        }
    </script>
@endsection
