@if (isset($videos) && $videos->count())
    <div class="d-flex mb-3 flex-wrap product__image-gallery" data-sortable-url="{{ route('admin.video.sortable', ['uuid' => $object->uuid]) }}">
        @foreach($videos as $video)
            <div class="image-item" data-uuid="{{ $video->uuid }}">
                @if (!empty($video->thumbnail_url))
                    <div class="image-container" data-drag="true" style="background-image: url({{ $video->thumbnail_url }})"></div>
                @else
                    <div class="image-container" data-drag="true">
                    {!! $video->html(true) !!}
                    </div>
                @endif

                <div class="image-action btn-group btn-group-sm">
                    @if (!empty($video->thumbnail_url))
                        <a
                            href="{{ $video->thumbnail_url }}"
                            data-type="image"
                            title="{{ $video->name }}"
                            class="btn text-light"
                        >
                            <i class="fas fa-search-plus"></i>
                        </a>
                    @endif

                    <a href="{{ route('admin.video.edit', ['locale' => $locale, 'uuid' => $video->uuid]) }}" class="btn" data-type="ajax">
                        <i class="fas fa-cog text-light"></i>
                    </a>

                    <a href="javascript:void(0)" class="btn" onclick="return deleteFile('{{ $video->uuid }}', true)">
                        <i class="fas fa-trash-alt text-light"></i>
                    </a>
                </div>
            </div>

        @endforeach
    </div>
@endif
