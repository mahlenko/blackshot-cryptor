@if ($image)
    <div class="d-flex align-items-start">
        @if ($image->isImage())
            <a
                href="{{ \Illuminate\Support\Facades\Storage::url($image->fullName()) }}"
                @if(\Illuminate\Support\Facades\Request::ajax())
                target="_blank"
                @else
                data-type="image"
                @endif
                title="{{ $image->name }}"
                class="d-flex align-items-center justify-content-center border rounded-3 p-1 bg-light mb-3 me-3">
                @if ($image->mimeType === 'image/svg+xml')
                    <span class="m-3" style="width: 200px; max-width: 200px">
                        {!! \Illuminate\Support\Facades\Storage::get($image->fullName()) !!}
                    </span>
                @else
                    <img src="{{ \Illuminate\Support\Facades\Storage::url($image->thumbnail()) }}"
                         alt=""
                         height="200"
                         class="rounded">
                @endif
            </a>
        @endif
        <div>
            <ul>
                <li>
                    <span class="text-secondary">{{ __('file.name') }}</span>:
                    {{ $image->name }}
                </li>

                @if ($image->isImage() && $image->image_x && $image->image_y)
                    <li>
                        <span class="text-secondary">{{ __('file.type') }}</span>:
                        {{ __('file.image') }}
                    </li>
                    <li>
                        <span class="text-secondary">{{ __('file.size') }}</span>:
                        {{ $image->image_x }} x {{ $image->image_y }} px
                    </li>
                @else
                    <li>
                        <span class="text-secondary">{{ __('file.type') }}</span>:
                    {{ __('file.file') }}
                </li>
            @endif
            <li>
                <span class="text-secondary">{{ __('MIME') }}</span>:
                {{ $image->mimeType }}
            </li>
            <li>
                <span class="text-secondary">{{ __('file.filesize') }}</span>:
                {{ $image->sizeText() }}
            </li>
            <li>
                <span class="text-secondary">{{ __('file.downloads') }}</span>:
                {{ $image->downloads }}
            </li>
            <li>
                <span class="text-secondary">{{ __('file.created') }}</span>:
                {{ $image->created_at }}
            </li>
        </ul>

        @if (!\Illuminate\Support\Facades\Request::ajax())
        <a href="{{ route('admin.finder.file.edit', ['locale' => $locale ?? app()->getLocale(), 'uuid' => $image->uuid]) }}" class="ajax" data-type="ajax">
            <i class="far fa fa-cog me-2"></i>{{ __('finder.setting') }}
        </a>
        @endif

        <a href="javascript:void(0);" onclick="return deleteFile('{{ $image->uuid }}')" class="btn btn-link text-danger">
            <i class="far fa-trash-alt me-2"></i>{{ __('file.delete') }}
        </a>
    </div>
</div>
@endif
