<input type="hidden" name="uuid" value="{{ $object->uuid }}">
<input type="hidden" name="locale" value="{{ $locale }}">

<table class="table">
    <tbody>
        <tr>
            <td>
                <label for="video_name">
                    {{ __('video.columns.name') }}
                </label>
            </td>
            <td>
                <input class="form-control" maxlength="255" id="video_name" type="text" required name="video[{{ $object->uuid }}][name]" value="{{ $object->name ?? null }}">
            </td>
        </tr>

        <tr>
            <td>
                <label for="video_description">
                    {{ __('video.columns.description') }}
                </label>
            </td>
            <td>
                <textarea name="video[{{ $object->uuid }}][description]" id="video_description" cols="30" rows="4" class="form-control"
                          maxlength="255">{{ $object->description ?? null }}</textarea>
            </td>
        </tr>

        <tr>
            <td>
                <label for="video_url">
                    {!! __('video.columns.url') !!}
                </label>
            </td>
            <td>
                <input class="form-control" id="video_url" type="url" name="video[{{ $object->uuid }}][url]" value="{{ $object->url ?? null }}">
            </td>
        </tr>

        <tr>
            <td>
                <label for="video_thumbnail">
                    {!! __('video.columns.thumbnail_url') !!}
                </label>
            </td>
            <td>
                <input class="form-control" id="video_thumbnail" type="url" name="video[{{ $object->uuid }}][thumbnail_url]" value="{{ $object->thumbnail_url ?? null }}">
                @if($object->thumbnail_url)
                    <img src="{{ $object->thumbnail_url }}" class="my-2" alt="" style="max-width: 100%">
                @endif
            </td>
        </tr>

        <tr>
            <td>
                <label for="width">
                    {{ __('video.columns.size') }}
                </label>
            </td>
            <td>
                <div class="input-group mb-2">
                    <input type="number" id="width" name="video[{{ $object->uuid }}][width]" value="{{ $object->width ?? 640 }}" class="form-control" placeholder="{{ __('video.columns.width') }}" aria-label="width">
                    <select class="form-select" name="video[{{ $object->uuid }}][width_unit]">
                        <option value="px" {{ $object->width_unit == 'px' ? 'selected' : null }}>px</option>
                        <option value="rem" {{ $object->width_unit == 'rem' ? 'selected' : null }}>rem</option>
                        <option value="em" {{ $object->width_unit == 'em' ? 'selected' : null }}>em</option>
                        <option value="%" {{ $object->width_unit == '%' ? 'selected' : null }}>%</option>
                    </select>
                </div>
                <div class="input-group">
                    <input type="number" name="video[{{ $object->uuid }}][height]" value="{{ $object->height ?? 360 }}" class="form-control" placeholder="{{ __('video.columns.height') }}" aria-label="height">
                    <select class="form-select" name="video[{{ $object->uuid }}][height_unit]">
                        <option value="px" {{ $object->height_unit == 'px' ? 'selected' : null }}>px</option>
                        <option value="rem" {{ $object->height_unit == 'rem' ? 'selected' : null }}>rem</option>
                        <option value="em" {{ $object->height_unit == 'em' ? 'selected' : null }}>em</option>
                        <option value="%" {{ $object->height_unit == '%' ? 'selected' : null }}>%</option>
                    </select>
                </div>
            </td>
        </tr>

        <tr>
            <td>
                <label for="video_duration">
                    {{ __('video.columns.duration') }}
                </label>
            </td>
            <td>
                <input class="form-control" id="video_duration" type="number" name="video[{{ $object->uuid }}][duration]" value="{{ $object->duration ?? null }}">
            </td>
        </tr>
    </tbody>
</table>
