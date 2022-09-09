<p>
    <a href="{{ route('admin.catalog.category.categories') }}" data-click="popup" data-type="ajax" class="btn btn-primary">
        {{ __('catalog.feature.append_category') }}
    </a>
</p>

<p class="text-secondary">
    @if ($feature && $feature->categories && $feature->categories->count())
        {{ __('catalog.feature.allow_category') }}
    @else
        {{ __('catalog.feature.all_allow_category') }}
    @endif
</p>

<ul id="category_selected_append" class="list-group-sortable">
    @if (old('category'))
        @foreach (old('category') as $item)
            <li class="list-item">
                <img src="{{ asset('administrator/icons/folder.png') }}" class="me-1" alt="" height="16">
                <span class="name">{{ $item['name'] }}</span>
                <small class="text-secondary">{{ $item->getSlug() }}</small>
                <input type="hidden" name="category[{{ $item['uuid'] }}]" value="{{ $item['uuid'] }}">
                <a href="javascript:void(0)" onclick="return deleteCategory(this)"><i class="far fa-trash-alt text-danger"></i></a>
            </li>
        @endforeach
    @else
        @if (isset($feature))
            @foreach ($feature->categories as $item)
                <li class="list-item">
                    <img src="{{ asset('administrator/icons/folder.png') }}" class="me-1" alt="" height="16">
                    <span class="name">{{ $item->name }}</span>
                    <small class="text-secondary">{{ $item->ancestors->sortBy('_lft')->pluck('name')->join(' / ') }}</small>
                    <input type="hidden" name="category[{{ $item->uuid }}]" value="{{ $item->uuid }}">
                    <a href="javascript:void(0)" onclick="return deleteCategory(this)"><i class="far fa-trash-alt text-danger"></i></a>
                </li>
            @endforeach
        @endif
    @endif
</ul>


<script>
    function deleteCategory(el)
    {
        if (confirm('{{ __('global.confirm.delete') }}')) {
            let $li = $(el).parents('li')
            let value = $li.find('input').val()

            $(el).parents('li').after('<input type="hidden" name="category[' + value + ']" value="">')
            $(el).parents('li').remove()
        }
    }
</script>
