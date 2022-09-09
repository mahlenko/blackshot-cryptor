<table class="table">
    <tr>
        <td>
            <label for="video-create">
                {{ __('video.create') }}
            </label>
        </td>
        <td>
            <div class="input-group mb-1">
                <span class="input-group-text">
                    <i class="fas fa-link"></i>
                </span>
                <input type="url" id="video-create"
                       name="video[{{ \Ramsey\Uuid\Uuid::uuid4()->toString() }}][url]" class="form-control"
                       aria-label="URL YouTube, Vimeo"
                >
            </div>

            <small class="text-secondary">
                {!! __('video.columns.url') !!}
            </small>
        </td>
    </tr>

    <tr>
        <td></td>
        <td>
            @include('administrator.partials.video-list', ['videos' => $videos, 'object' => $object])
        </td>
    </tr>
</table>
