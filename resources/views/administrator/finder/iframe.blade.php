@if ($is_iframe)
    <!doctype html>
    <html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Finder</title>
        <link rel="stylesheet" href="{{ mix('css/app.css', 'administrator') }}">
        <script src="{{ mix('js/app.js', 'administrator') }}"></script>
        <script src="{{ mix('js/fontawesome.js', 'administrator') }}"></script>
    </head>
    <body>
    <script>
        /**
         * Language
         * @type file: {delete_confirm: string}
         */
        let lang = {
            file: {
                delete_confirm: "{{ __('file.delete_confirm') }}"
            }
        }
    </script>
@endif

<div style="position: relative; height: 100%; min-height: inherit">
    <div style="display: none; position: fixed; z-index: 10; left: 0; top: 0; width: 100%" class="bg-dark text-light p-2 px-3" id="finder-panel">
        <input type="hidden" id="finder_file_uuid">
        <input type="hidden" id="finder_file_url">

        <div class="d-flex justify-content-between">
            <div class="d-flex align-items-center">
                <h6 id="finder_file_name" class="mb-0"></h6>
    {{--            <a href="#" class="btn btn-dark"><i class="fas fa-download me-1"></i>{{ __('finder.download') }}</a>--}}
            </div>

            <div>
                <a href="javascript:void(0)" onclick="return downloadLink();" id="copy-link" class="btn btn-link text-light text-decoration-none me-1">
                    <i class="far fa-copy text-light me-2"></i>{{ __('finder.copy_link_download') }}
                </a>
                <a href="javascript:void(0)" id="finder_settings_link" data-type="ajax" class="btn btn-link text-light text-decoration-none me-1"><i class="fas fa-cog text-primary me-1"></i></i>{{ __('finder.setting') }}</a>
                <a href="javascript:void(0)" onclick="return deleteFileFinder()"class="btn btn-link text-light text-decoration-none me-1"><i class="fas fa-trash-alt text-danger me-1"></i>{{ __('finder.delete') }}</a>
            </div>
        </div>
    </div>

    <div class="finder">
        <div class="d-flex justify-content-between px-3 pt-1 mb-2">
            <h2>{{ __('finder.title') }}</h2>
            <div>
                <a href="{{ route('admin.finder.folder.create', ['uuid' => $uuid]) }}" class="btn btn-light me-2" data-type="ajax">
                    <i class="fas fa-folder-plus me-1"></i>
                    {{ __('finder.new_folder') }}
                </a>

                <a href="javascript:void(0)" onclick="return $('#uploader').trigger('click')" class="btn btn-primary">
                    <i class="fas fa-upload me-1"></i>
                    {{ __('finder.upload') }}
                </a>

                <form action="{{ route('admin.finder.upload') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="folder_uuid" value="{{ $current->uuid }}">
                    <input id="uploader" type="file" name="files[]" onchange="$(this).parent().submit()" multiple hidden>
                </form>
            </div>
        </div>

        <hr class="mb-0">

        {{-- Breadcrumbs --}}
        @if ($is_iframe && $breadcrumbs && $breadcrumbs->count())
            <div class="bg-light py-1 px-3">
                <nav aria-label="breadcrumb" class="{{ $is_iframe ? 'px-3' : null }}">
                    <ol class="breadcrumb mb-0">
                        @foreach($breadcrumbs as $folder)
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.finder.home', array_merge(\Illuminate\Support\Facades\Request::input(), ['uuid' => $folder->uuid])) }}">{{ $folder->name }}</a>
                            </li>
                        @endforeach
                        <li class="breadcrumb-item active" aria-current="page">
                            {{ $current->name }}
                        </li>
                    </ol>
                </nav>
            </div>
        @endif

        <div class="finder-container align-content-start"
             data-sortable-url="{{ route('admin.finder.sortable.folder', ['uuid' => $current->uuid]) }}">

            {{-- $folders --}}
            @foreach($folders as $folder)
                <a href="javascript:void(0)" class="finder-item" data-type="folder" data-uuid="{{ $folder->uuid }}">
                    <div class="finder-item__icon">
                        <picture>
                            <source type="image/webp" srcset="{{ asset('administrator/icons/folder.webp') }}">
                            <img src="{{ asset('administrator/icons/folder.png') }}" height="45" alt="{{ $folder->name }}">
                        </picture>
                    </div>
                    <div class="finder-item__name" title="{{ $folder->name }}">
                        <p>
                            {!! $folder->name !!}
                        </p>
                    </div>
                </a>
            @endforeach

            {{-- File list --}}
            @foreach($files as $file)
                <a href="javascript:void(0)"
                   class="finder-item"
                   title="{{ $file->name }} ({{ $file->sizeText() }})"
                   data-drag="true"
                   data-type="file"
                   data-uuid="{{ $file->uuid }}"
                   data-url="{{ \Illuminate\Support\Facades\Storage::url($file->fullName()) }}">
                    <div class="finder-item__icon">
                        @if ($file->isImage())
                            @if($file->mimeType == 'image/svg+xml')
                                {!! \Illuminate\Support\Facades\Storage::get($file->fullName()) !!}
                            @else
                            <picture>
                                <source type="image/webp" srcset="{{ \Illuminate\Support\Facades\Storage::url($file->webp(false)) }}">
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($file->thumbnail()) }}" alt="{{ $file->name }}">
                            </picture>
                            @endif
                        @else
                            <picture>
                                <source type="image/webp" srcset="{{ asset('administrator/icons/file.webp') }}">
                                <img src="{{ asset('administrator/icons/file.png') }}" alt="{{ $file->name }}">
                            </picture>
                        @endif
                    </div>
                    <div class="finder-item__name">
                        <p>
                            {!! $file->name !!}
                        </p>
                    </div>
                </a>
            @endforeach

            @if(!$folders->count() && !$files->count())
                <div class="alert alert-warning w-100">{{ __('finder.no-files') }}</div>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('.finder-item').click(function () {
            return selectItem(this)
        });

        $('.finder-item').dblclick(function () {
            if ($(this).data('type') === 'folder') {
                openFolder($(this).data('uuid'), {{ $is_iframe }})
                return false
            }

            @if ($is_iframe)
                sendMessage($(this).data('url'))
            @else
                viewOriginal(this)
            @endif
        })

        $('.finder-container').mouseup(function (e) {
            let container = $('.finder-item');
            if (container.has(e.target).length === 0) {
                // container.hide();
                unselected()
            }
        });
    })

    /**
     * Отправит сообщение в TinyMCE ссылкой на файл
     */
    function sendMessage(url)
    {
        /* send filepath in tinymce */
        window.parent.postMessage({
            mceAction: 'selectImage',
            data: { url }
        }, origin);

        /* close dialog window */
        window.parent.postMessage({ mceAction: 'close'}, origin);
    }

    /**
     * Просмотр оригинала
     **/
    function viewOriginal(el)
    {
        $.magnificPopup.open({
            items: {
                src: $(el).data('url'),
                title: $(el).attr('title')
            },
            type: 'image'
        }, 0);
    }

    /**
     * @param uuid
     * @param show_frame
     */
    function openFolder(uuid, show_frame)
    {
        if (show_frame) {
            window.location.href = window.Route.route('admin.finder.home', {uuid, iframe: 1})
        } else {
            window.location.href = window.Route.route('admin.finder.home', {uuid})
        }
    }

    /**
     * Скопирует ссылку на скачивание файла
     */
    function downloadLink() {
        let combination = window.isMacOS() ? '⌘ + V' : 'Ctrl + V'
        let download_link = window.Route.route('download', {uuid: $('#finder_file_uuid').val()})
            .replace(window.location.origin, window.location.origin + '/' + document.documentElement.lang)

        navigator.clipboard.writeText(download_link)
            .then(() => {
                window.Anita.success('{{ __('finder.copied') }}' + combination)
            })
            .catch(err => {
                window.Anita.error('{{ __('finder.copied_fail') }}: ' + err)
            });
    }

    /**
     * Снять выделение файла
     **/
    function unselected() {
        $('.finder-item').removeClass('selected')
        $('#finder-panel').fadeOut(100)
        $('#finder_file_uuid').val('')
        $('#finder_file_url').val('')
        $('#finder_file_name').text('')
    }

    /**
     * Выделить файл
     **/
    function selectItem(el)
    {
        $('.finder-item').removeClass('selected')

        let $file = $(el);

        if ($(el).data('type') === 'file') {
            $('#finder-panel').fadeIn(100)
            $('#finder_file_uuid').val($file.data('uuid'))
            $('#finder_file_url').val($file.data('url'))
            $('#finder_file_name').text($file.attr('title'))
        } else {
            $('#finder-panel').fadeOut(100)
        }

        $file.addClass('selected')

        $('#finder_settings_link').attr('href', Route.route('admin.finder.file.edit', {'locale': '{{ app()->getLocale() }}', 'uuid': $file.data('uuid')})
            .replace(window.location.origin, window.location.origin + '/' + document.documentElement.lang))
    }

    /**
     * Удалит файл
     */
    function deleteFileFinder()
    {
        return deleteFile($('#finder_file_uuid').val())
    }
</script>

@if ($is_iframe)
</body>
</html>
@endif
