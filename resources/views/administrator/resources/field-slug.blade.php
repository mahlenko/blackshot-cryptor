<tr>
    <td>
        <label for="slug">{{ __('fields.slug') }}</label>
        <i class="fas fa-info-circle text-info" title="{{ __('fields.titles.slug') }}"></i><br>
        <small class="text-secondary">{{ __('fields.label_description.slug') }}</small>
    </td>
    <td>
        <div class="input-group">
            @if(isset($object) && $object->meta->url)
                <span class="input-group-text">
                    <a href="javascript:void(0);" onclick="return readonly(this, 'meta_url')">
                        <i class="fas fa-lock text-success" title="{{ __('fields.titles.unlock') }}"></i>
                    </a>
                </span>
            @endif
            <div class="form-floating flex-fill">
                <input
                    id="meta_url"
                    type="text"
                    class="form-control"
                    name="meta[slug]"
{{--                    onkeyup="return duplicate(this, 'slugFieldPreview');"--}}
                    placeholder="{{ __('fields.slug') }}"
                    value="{{ old('meta.slug', $object ? $object->meta->slug : null) }}"
                    @if(isset($object) && $object->meta->url)readonly @endif
                >
                <label for="slug">{{ __('fields.slug') }}</label>
            </div>
        </div>

        {{--  --}}
        <small id="slugFieldPreview" class="text-decoration-underline"></small>

        @error('meta.slug')<span class="text-danger">{{ $message }}</span>@enderror

        {{--  --}}
        @if ($object && $object->meta->url)
            <p class="my-2">
                @if ($object->meta->url == '/' && !$object->meta->object_type::PREFIX)
                    <a href="{{ route('home') }}" target="_blank">
                        <i class="fas fa-link me-1"></i>{{ route('home') }}
                    </a>
                @else
                    <a href="{{ route('view', ['slug' => $object->meta->url == '/' ? $object->meta->object_type::PREFIX ?? null : $object->meta->url]) }}" target="_blank">
                        <i class="fas fa-link me-1"></i>{{ route('view', ['slug' => $object->meta->url == '/' ? $object->meta->object_type::PREFIX ?? null : $object->meta->url]) }}
                    </a>
                @endif
            </p>
        @endif

        @error('slug')<span class="text-danger">{{ $message }}</span>@enderror
    </td>
</tr>

<script>
    {{--function duplicate(field, element_id)--}}
    {{--{--}}
    {{--    let element = document.getElementById(element_id)--}}
    {{--    let prefix = field.dataset.prefix ?? ''--}}

    {{--    let protocol_security = document.location.protocol === 'https:'--}}
    {{--    let protocol_class = protocol_security ? 'text-success' : 'text-secondary'--}}
    {{--    let protocol_icon = protocol_security--}}
    {{--        ? '<i class="fas fa-lock me-1 text-success"></i>'--}}
    {{--        : '<i class="fas fa-unlock me-1 text-danger"></i>'--}}

    {{--    if (!element.classList.contains(protocol_class)) {--}}
    {{--        element.classList.add(protocol_class)--}}
    {{--    }--}}

    {{--    @if (isset($unuse_uuid) && $unuse_uuid)--}}
    {{--        element.innerHTML = protocol_icon + prefix +'/'+ field.value + '.html'--}}
    {{--    @else--}}
    {{--        element.innerHTML = protocol_icon + prefix +'/'+ field.value + '-{{ \Illuminate\Support\Str::limit($uuid, 8, '') }}.html'--}}
    {{--    @endif--}}
    {{--}--}}

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

    {{--document.addEventListener('DOMContentLoaded', function() {--}}
    {{--    duplicate(document.getElementById('slug'), 'slugFieldPreview')--}}
    {{--})--}}
</script>
