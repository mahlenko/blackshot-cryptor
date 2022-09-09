<p>
    <button class="btn btn-primary" type="button" onclick="return createVariant()">
        {{ __('catalog.feature.variant.create') }}
    </button>
</p>

<p class="text-secondary">{{ __('catalog.feature.variant.info') }}</p>


{{-- Шаблон для полей цвета --}}
<template id="variants_template">
    <li class="list-item">
        <div class="input-group">
            <span class="input-group-text bg-white text-decoration-none" data-drag="true">
                <i class="fas fa-bars me-1"></i>
            </span>
            <input type="hidden" name="feature_variants[{uuid4}][color]" class="form-control form-control-color bg-light" value="#ffffff">
            <input type="text" name="feature_variants[{uuid4}][name]" class="form-control" placeholder="{{ __('catalog.feature.variant.value') }}">
        </div>
    </li>
</template>

<ul class="list-group-table" id="feature_variants" data-sortable-url="{{ route('admin.catalog.feature.sortable.variants') }}">
    {{-- список значений при ошибке в форме --}}
    @if(old('feature_variants'))
        @foreach(old('feature_variants') as $id => $variant)
            <li data-uuid="{{ $id }}" class="list-item">
                <div class="input-group">
                    <span class="input-group-text bg-white text-decoration-none" data-drag="true">
                        <i class="fas fa-bars me-1"></i>
                    </span>
                    <input type="@if(old('view_filter') === 'color')color@else hidden @endif" name="feature_variants[{{ $id }}][color]" class="form-control form-control-color bg-light" value="{{ $variant['color'] }}">
                    <input type="text" name="feature_variants[{{ $id }}][name]" class="form-control" placeholder="{{ __('catalog.feature.variant.value') }}" value="{{ $variant['name'] }}">
                    <a href="#" class="input-group-text bg-white text-decoration-none" onclick="return deleteVariant(this)">
                        <i class="fas fa-trash text-danger"></i>
                    </a>
                </div>
            </li>
        @endforeach
    {{-- список сохраненных значений характиристики --}}
    @elseif ($feature && $feature->values->count())
        @foreach($feature->values->sortBy('_lft') as $value)
            <li data-uuid="{{ $value->uuid }}" class="list-item">
                <div class="input-group">
                    <span class="input-group-text bg-white text-decoration-none" data-drag="true">
                        <i class="fas fa-bars me-1"></i>
                    </span>
                    @if($feature->purpose == 'organize_catalog')
                    <span class="input-group-text bg-white">
                        @if ($value->icon)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($value->icon->thumbnail()) }}" alt="{{ $value->name }}" height="24">
                        @else
                            <i class="fas fa-camera-retro"></i>
                        @endif
                    </span>
                    @endif

                    <input @if($feature->view_filter === 'color')type="color"@else type="hidden" @endif name="feature_variants[{{ $value->uuid }}][color]" class="form-control form-control-color bg-light" value="{{ $value->color }}">
                    <input type="text" name="feature_variants[{{ $value->uuid }}][name]" class="form-control" placeholder="{{ $locale != app()->getLocale() ? $value->name : __('catalog.feature.variant.value') }}" value="{{ $value->translateOrNew($locale)->name }}">

                    @if($feature->purpose === 'organize_catalog')
                        <a href="{{ route('admin.catalog.feature.page.edit', ['locale' => app()->getLocale(), 'uuid' => $value->uuid]) }}" class="input-group-text bg-white text-decoration-none" data-click="popup" data-type="ajax">
                            <i class="fas fa-cog"></i>
                        </a>
                    @endif

                    <a href="#" class="input-group-text bg-white text-decoration-none" onclick="return deleteVariant(this)">
                        <i class="fas fa-trash-alt text-danger"></i>
                    </a>
                </div>
            </li>
        @endforeach
    @endif
</ul>

<script>
    /**
     * Удалить вариант со значением
     */
    function deleteVariant(el)
    {
        if (confirm('{{ __('global.confirm.delete') }}')) {
            el.previousElementSibling.value = null
            $(el).parents('li').before('<input type="hidden" name="'+ $(el).parents('li').find('input[type=text]').attr('name') +'">')
            $(el).parents('li').remove()
        }
    }

    /**
     * Создаст новый вариант в таблице
     */
    function createVariant()
    {
        /* получим значение вида отображения полей в фильтре */
        // let view_filter = document.getElementsByName('view_filter').item(0).selectedOptions.item(0).value.trim()
        let view_filter = document.getElementsByName('view_filter').item(0).value.trim()

        /* элемент в который вставляем новые поля */
        let tableVariants = document.getElementById('feature_variants')

        /* шаблон */
        let variants_template = document.getElementById('variants_template')

        /* проверим заполнен ли последний вариант */
        let append = true
        if (tableVariants.children.length) {
            let last_row = tableVariants.children[tableVariants.children.length - 1];
            if (last_row.querySelector('input[type=text]').value.trim() === '') {
                append = false
                last_row.querySelector('input[type=text]').focus()
            }
        }

        /* вставляем новую строку таблицы */
        if (append) {
            let data = variants_template.content.cloneNode(true)
            tableVariants.append(data)

            /* получим новую строку таблицы */
            let createTemplateRow = tableVariants.getElementsByTagName('li')

            /* генерация uuid4 */
            let uuid = uuid4();

            /* замена в названиях полей шаблона - {uuid4}, на значение uuid4 */
            createTemplateRow[createTemplateRow.length - 1].querySelectorAll('input').forEach(function (field) {
                field.name = field.name.replaceAll(/\{uuid4\}/g, uuid);
                if (field.getAttribute('type') === 'hidden' && view_filter === 'color') {
                    field.setAttribute('type', 'color')
                }
                field.focus()
            })
        }

        if (tableVariants.children) {
            Array.from(tableVariants.children).forEach((row) => {
                let field_color = row.querySelectorAll('input')[0]
                let field_value = row.querySelectorAll('input')[1]

                switch(view_filter) {
                    case 'checkbox':
                        field_color.setAttribute('type', 'hidden')
                        if (field_value.dataset.value && field_value.value === field_value.dataset.value.replace(/[^0-9\.]/g, '')) {
                            field_value.value = field_value.dataset.value
                            field_value.dataset.value = null
                        }
                        break
                    case 'slider':
                        field_color.setAttribute('type', 'hidden')
                        if (field_value.value !== '') {
                            field_value.dataset.value = field_value.value
                            field_value.value = field_value.value.replace(/[^0-9\.]/g, '')
                        }
                        break
                    case 'color':
                        field_color.setAttribute('type', 'color')
                        if (field_value.dataset.value && field_value.value === field_value.dataset.value.replace(/[^0-9\.]/g, '')) {
                            field_value.value = field_value.dataset.value
                            field_value.dataset.value = null
                        }
                        break
                }
            })
        }
    }

    function uuid4() {
        return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
            (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
        );
    }
</script>
