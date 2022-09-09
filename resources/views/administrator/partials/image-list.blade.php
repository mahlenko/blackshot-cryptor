@if (isset($images) && $images->count())
    <div class="d-flex mb-3 flex-wrap product__image-gallery"
         data-sortable-url="{{ route('admin.finder.sortable', ['uuid' => $object->uuid]) }}">
        @foreach($images as $image)
            <div class="image-item" data-uuid="{{ $image->uuid }}">
                @if ($image->mimeType === 'image/svg+xml')
                    <div class="image-container" data-drag="true">
                        <span>
                            {!! \Illuminate\Support\Facades\Storage::get($image->fullName()) !!}
                        </span>
                    </div>
                @else
                    <div class="image-container" data-drag="true"
                         style="background-image: url({{ \Illuminate\Support\Facades\Storage::url($image->thumbnail()) }})"></div>
                @endif

                <div class="image-action btn-group btn-group-sm">
                    <a
                        href="{{ \Illuminate\Support\Facades\Storage::url($image->fullName()) }}"
                        data-type="image"
                        title="{{ $image->name }}"
                        class="btn text-light"
                    >
                        <i class="fas fa-search-plus"></i>
                    </a>

                    <a href="{{ route('admin.finder.file.edit', ['locale' => $locale, 'uuid' => $image->uuid]) }}" class="btn" data-type="ajax">
                        <i class="fas fa-cog text-light"></i>
                    </a>

                    <a href="javascript:void(0)" class="btn" onclick="return deleteFile('{{ $image->uuid }}')">
                        <i class="fas fa-trash-alt text-light"></i>
                    </a>
                </div>
            </div>

        @endforeach
    </div>
@endif
