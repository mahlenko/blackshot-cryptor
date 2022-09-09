<h5 class="card-title text-primary">Search Engine Optimization</h5>
<p>{!! __('meta.description') !!}</p>
<hr>

<input type="hidden" name="object_type" value="{{ $object_type }}">

<table class="table">
    <tr>
        <td></td>
        <td>
            <div class="form-check form-switch">
                <input type="hidden" name="meta[is_active]" value="{{ old('meta.is_active', $object->meta->is_active ?? 1) ? 1 : 0 }}">
                <input class="form-check-input" type="checkbox" onchange="$('[name=\'meta[is_active]\']').val($(this).is(':checked') ? 1 : 0)" @if(old('meta.is_active') || !isset($object) || (!old('meta.is_active') && $object->meta->is_active)) checked @endif id="active">
                <label class="form-check-label" for="active">{{ __('meta.columns.is_active') }}</label>
            </div>
            <small class="text-secondary">
                {{ __('meta.descriptions.is_active') }}
            </small>

            @error('meta.is_active')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    <tr>
        <td>
            <label for="meta.publish_at">{{ __('meta.columns.publish_at') }}</label>
            <br><small class="text-secondary">{{ __('meta.descriptions.publish_at') }}</small>
        </td>
        <td>
            <input name="meta[publish_at]" data-type="date" id="meta.publish_at" class="form-control" type="text" value="{{ old('meta.publish_at', $object->meta->publish_at ?? date('Y-m-d H:00:00')) }}">
            @error('meta.publish_at')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    @include('administrator.resources.field-slug')

    <tr>
        <td>
            <label for="meta.redirect">{{ __('meta.columns.redirect') }}</label>
            <br><small class="text-secondary">{{ __('meta.descriptions.redirect') }}</small>
        </td>
        <td>
            <input type="url" id="meta.redirect" class="form-control" name="meta[redirect]"
                   value="{{ old('meta.redirect', $object->meta->redirect ?? null) }}"
                   placeholder="{{ __('meta.placeholder.redirect') }}"
            >
            @error('meta.redirect')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    <tr>
        <td>
            <label for="meta.title">{{ __('meta.columns.title') }}</label><br>
            <small class="text-secondary">{!! __('meta.descriptions.title') !!}</small>
            @include('administrator.resources.translation-field', ['field' => 'title', 'object' => $object ? $object->meta : null])
        </td>
        <td>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="meta.title" placeholder="{{ __('meta.placeholder.title') }}" name="meta[title]" value="{{ old('meta.title', $object ? $object->meta->translate($locale)->title ?? null : null) }}">
                <label for="meta.title">{{ __('meta.placeholder.title') }}</label>
            </div>
            @error('meta.title')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    <tr>
        <td>
            <label for="meta.description">{{ __('meta.columns.description') }}</label><br>
            <small class="text-secondary">{!! __('meta.descriptions.description') !!}</small>
            @include('administrator.resources.translation-field', ['field' => 'description', 'object' => $object ? $object->meta : null])
        </td>
        <td>
            <div class="form-floating">
                <textarea id="meta.description" placeholder="{{ __('meta.placeholder.description') }}" class="form-control" name="meta[description]" style="height: 100px">{{ old('meta.description', $object ? $object->meta->translate($locale)->description ?? null : null) }}</textarea>
                <label for="meta.description">{{ __('meta.placeholder.description') }}</label>
            </div>
            @error('meta.description')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    <tr>
        <td>
            <label for="meta.keywords">{{ __('meta.columns.keywords') }}</label><br>
            <small class="text-secondary">{!! __('meta.descriptions.keywords') !!}</small>
            @include('administrator.resources.translation-field', ['field' => 'keywords', 'object' => $object ? $object->meta : null])
        </td>
        <td>
            <input type="text" id="meta.keywords" class="form-control" name="meta[keywords]"
                   value="{{ old('meta.keywords', $object ? $object->meta->translate($locale)->keywords ?? null : null) }}">
            @error('meta.keywords')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    <tr>
        <td>
            <label for="meta.heading_h1">{{ __('meta.columns.heading_h1') }}</label><br>
            <small class="text-secondary">{!! __('meta.descriptions.heading_h1') !!}</small>
            @include('administrator.resources.translation-field', ['field' => 'heading_h1', 'object' => $object ? $object->meta : null])
        </td>
        <td>
            <input type="text" id="meta.heading_h1" class="form-control" name="meta[heading_h1]"
                   value="{{ old('meta.heading_h1', $object ? $object->meta->translate($locale)->heading_h1 ?? null : null) }}">
            @error('meta.keywords')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    <tr>
        <td>
            <label for="meta.robots">{{ __('meta.columns.robots') }}</label><br>
            <small class="text-secondary">{!! __('meta.descriptions.robots') !!}</small>
        </td>
        <td>
            <div class="form-floating">
                <select class="form-select" id="meta.robots" name="meta[robots]" aria-label="{{ __('meta.columns.robots') }}">
                    <option value="index" @if (old('meta.robots', $object->meta->robots ?? null) == 'index')selected @endif>index</option>
                    <option value="follow" @if (old('meta.robots', $object->meta->robots ?? null) == 'follow')selected @endif>follow</option>
                    <option value="index, follow" @if (old('meta.robots', $object->meta->robots ?? 'index, follow') == 'index, follow')selected @endif>index, follow</option>
                    <option value="noindex" @if (old('meta.robots', $object->meta->robots ?? null) == 'noindex')selected @endif>noindex</option>
                    <option value="nofollow" @if (old('meta.robots', $object->meta->robots ?? null) == 'nofollow')selected @endif>nofollow</option>
                    <option value="noindex, nofollow" @if (old('meta.robots', $object->meta->robots ?? null) == 'noindex, nofollow')selected @endif>noindex, nofollow</option>
                </select>
                <label for="meta.robots">{{ __('meta.placeholder.robots') }}</label>
            </div>

            @error('meta.robots')<span class="text-danger">{{ $message }}</span>@enderror

            @if(count(__('meta.robots.info')))
                <ul class="list-unstyled mt-2">
                @foreach(__('meta.robots.info') as $key => $desc)
                    <li><code>{{ $key }}</code> - {{ $desc }}</li>
                @endforeach
                </ul>
            @endif
        </td>
    </tr>

    <tr>
        <td></td>
        <td>
            <div class="form-check form-switch">
                <input type="hidden" name="meta[show_nested]" value="{{ old('meta.show_nested', $object->meta->show_nested ?? 0) }}">
                <input class="form-check-input"
                       type="checkbox"
                       onchange="$('[name=\'meta[show_nested]\']').val($(this).is(':checked') ? 1 : 0)"
                       {{ old('meta.show_nested', $object->meta->show_nested ?? 0) ? 'checked' : null }}
                       id="show_nested">
                <label class="form-check-label" for="show_nested">{{ __('meta.columns.show_nested') }}</label>
            </div>
            <small class="text-secondary">
                {{ __('meta.descriptions.show_nested') }}
            </small>
            @error('meta.show_nested')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    <tr>
        <td>
            <label for="template">{{ __('meta.columns.template') }}</label>
        </td>
        <td>
{{--            {{ \App\Repositories\MetaRepository::templates($model) }}--}}
            <select name="meta[template]" id="template" class="form-select">
            @foreach(\App\Repositories\MetaRepository::templates($object_type) as $folder)
                <optgroup label="{{ $folder['name'] }}">
                    @foreach($folder['items'] as $template)
                        <option value="{{ $template['value'] }}" {{ old('meta.template', $object->meta->template ?? $default_template ?? 'web.pages.default') == $template['value'] ? 'selected' : null }}>{{ $template['file'] }}</option>
                    @endforeach
                </optgroup>
            @endforeach
            </select>
        </td>
    </tr>

    <tr>
        <td>
            <label for="views">{{ __('meta.columns.views') }}</label>
        </td>
        <td>
            <input name="meta[views]" id="views" class="form-control" type="text" value="{{ old('meta.views', $object->meta->views ?? 0) }}">
            @error('views')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>
</table>
