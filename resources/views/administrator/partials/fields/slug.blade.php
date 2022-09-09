<tr>
    <td>
        <label for="slug">{{ __('fields.slug') }}</label>
        <i class="fas fa-info-circle text-info" title="{{ __('fields.titles.slug') }}"></i><br>
        <small class="text-secondary">{{ __('fields.label_description.slug') }}</small>
    </td>
    <td>
        <div class="input-group">
            @if(isset($object) && $object->slug)
                <span class="input-group-text">
                    <a href="javascript:void(0);" onclick="return readonly(this, 'slug')">
                        <i class="fas fa-lock text-success" title="{{ __('fields.titles.unlock') }}"></i>
                    </a>
                </span>
            @endif
            <div class="form-floating flex-fill">
                <input
                    id="slug"
                    type="text"
                    class="form-control"
                    name="slug"
                    data-parent="@if (isset($parent_slug_text) && $parent_slug_text) {{ $parent_slug_text }} @elseif($object){{ $object->getSlugCategory() }}@elseif(isset($parent) && $parent){{ $parent->getSlugCategory() }}@endif"
                    onkeyup="return duplicate(this, 'slugFieldPreview');"
                    placeholder="{{ __('fields.slug') }}"
                    value="{{ old('slug', $object->slug ?? null) }}"
                    @if(isset($object) && $object->slug)readonly @endif
                >
                <label for="slug">{{ __('fields.slug') }}</label>
            </div>
        </div>
        <small id="slugFieldPreview" class="text-decoration-underline"></small>
        @error('slug')<span class="text-danger">{{ $message }}</span>@enderror
    </td>
</tr>

<script>
    function duplicate(field, element_id)
    {
        let element = document.getElementById(element_id)
        let domain = document.location.protocol +'//'+ document.location.hostname
        let parent = field.dataset.parent ?? ''

        let protocol_security = document.location.protocol === 'https:'
        let protocol_class = protocol_security ? 'text-success' : 'text-secondary'
        let protocol_icon = protocol_security
            ? '<i class="fas fa-lock me-1 text-success"></i>'
            : '<i class="fas fa-unlock me-1 text-danger"></i>'

        if (!element.classList.contains(protocol_class)) {
            element.classList.add(protocol_class)
        }

        element.innerHTML = protocol_icon + domain + parent +'/'+ field.value
    }

    function readonly(link, field)
    {
        field = document.getElementById(field)
        let icon = link.getElementsByClassName('fas')[0]

        if (field.attributes.readonly) {
            field.removeAttribute('readonly')
            field.focus()
            icon.classList.remove('text-success')
            icon.classList.add('text-secondary')
        } else {
            field.setAttribute('readonly', true)
            icon.classList.remove('text-secondary')
            icon.classList.add('text-success')
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        duplicate(document.getElementById('slug'), 'slugFieldPreview')
    })
</script>
