<div class="card-header d-flex justify-content-between align-items-start">
    <div class="d-flex">
        {{-- tabs --}}
        @if ($chunks_template)
            <ul class="nav nav-tabs card-header-tabs" role="tablist">
                @foreach($chunks_template as $idx => $chunk)
                    <li class="nav-item">
                        <a class="nav-link list-group-item-action @if ($idx === 0)active @endif" id="list-{{ $chunk['key'] }}-list" data-bs-toggle="list" href="#list-{{ $chunk['key'] }}" role="tab" aria-controls="{{ $chunk['key'] }}">
                            {!! $chunk['name'] !!}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <div class="d-flex flex-nowrap text-nowrap">
        {{-- back --}}
        @if (!\Illuminate\Support\Facades\Request::ajax())
        <a href="{{ $back['route'] }}" class="btn btn-sm btn-link text-secondary text-decoration-none">
            <i class="fas fa-arrow-left"></i>
            {{ __('global.return_back') }}
        </a>
        @endif

        <div class="btn-group btn-group-sm">
            {{-- switcher locales --}}
            @if ($object)
                @include('administrator.partials.locales')
            @endif

            {{-- Сохранить страницу --}}
            <button type="submit" class="btn btn-sm btn-success" form="page_save">
                <i class="fas fa-save"></i>
                {{ __('global.save') }}
            </button>
        </div>
    </div>
</div>
