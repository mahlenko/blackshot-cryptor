@extends('administrator.setting.layout')
@section('body')
    <form action="{{ route('admin.setting.website.store') }}" id="settingForm" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="locale" value="{{ $locale }}">

        <table class="table">
            <tr>
                <td>
                    <label for="name">{{ __('settings/website.columns.name') }}</label>
                </td>
                <td>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $settings['name'] ?? null) }}">
                    @error('name')<span class="text-danger">{{ $message }}</span>@enderror
                </td>
            </tr>

            <tr>
                <td>
                    <label for="description">{{ __('settings/website.columns.description') }}</label>
                </td>
                <td>
                    <textarea class="form-control" id="description" name="description">{{ old('description', $settings['description'] ?? null) }}</textarea>
                    @error('description')<span class="text-danger">{{ $message }}</span>@enderror
                </td>
            </tr>

            <tr>
                <td>
                    <label>{{ __('settings/website.columns.logotype') }}</label>
                </td>
                <td>
                    @if (key_exists('logotype', $settings) && $settings['logotype'])
                        @if ($settings['logotype']->mimeType == 'image/svg+xml')
                            <span>
                                {!! \Illuminate\Support\Facades\Storage::get($settings['logotype']->fullName()) !!}
                            </span>
                            <style>
                                svg {max-width: 200px; width: 200px; height: 100px}
                            </style>
                        @else
                        <picture>
                            <source srcset="{{ \Illuminate\Support\Facades\Storage::url($settings['logotype']->webp(true)) }}">
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($settings['logotype']->thumbnail()) }}" alt="" height="200">
                        </picture>
                        @endif

                        <p>
                            <a href="{{ route('admin.setting.website.delete-file', ['locale' => $locale, 'parameter' => 'website.logotype']) }}" class="text-danger">
                                <i class="fas fa-trash-alt me-1"></i>{{ __('global.delete') }}
                            </a>
                        </p>
                    @endif
                    <input class="form-control" type="file" name="logotype" id="logotype" accept="image/*">
                    @error('logotype')<span class="text-danger">{{ $message }}</span>@enderror
                </td>
            </tr>

            <tr>
                <td>
                    <label>{{ __('settings/website.columns.favicon') }}</label>
                </td>
                <td>
                    @if (key_exists('favicon', $settings) && $settings['favicon'])
                        <p>
                            <picture>
                                <source srcset="{{ \Illuminate\Support\Facades\Storage::url($settings['favicon']->webp(true)) }}">
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($settings['favicon']->thumbnail()) }}" alt="" height="200">
                            </picture>
                        </p>

                        <p>
                            <a href="{{ route('admin.setting.website.delete-file', ['locale' => $locale, 'parameter' => 'website.favicon']) }}" class="text-danger">
                                <i class="fas fa-trash-alt me-1"></i>{{ __('global.delete') }}
                            </a>
                        </p>
                    @endif
                    <input class="form-control" type="file" name="favicon" id="favicon" accept="image/*">
                    @error('favicon')<span class="text-danger">{{ $message }}</span>@enderror
                </td>
            </tr>
        </table>
    </form>
@endsection
