@foreach($list as $item)
    <li>
        @if ($item['type'] == 'folder')
            <i class="fas fa-folder text-warning"></i>
        @else
            <i class="far fa-file-code text-info"></i>
        @endif
            <a href="javascript:void(0);" data-file="{{ $item['path'] }}" @if ($item['type'] == 'folder')onclick="$(this).next('ul').slideToggle(150);"@else data-get-content="true" @endif>
                {{ $item['name'] }}
            </a>

        @if ($item['children'])
            <ul style="display: none">
                @include('administrator.template.partials.element-list', ['list' => $item['children']])
            </ul>
        @endif
    </li>
@endforeach
