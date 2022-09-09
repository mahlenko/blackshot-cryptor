@php($_uuid_group_features = \Ramsey\Uuid\Uuid::uuid4()->toString())
<li class="list-item">
    <div class="form-check">
        <input class="form-check-input" type="checkbox" @if($item['checked'])checked disabled @else name="selected[]" value="{{ $_uuid_group_features }}"@endif id="feature_{{ $_uuid_group_features }}">
        <label class="form-check-label" for="feature_{{ $_uuid_group_features }}">
            {{ $item['name'] }}
        </label>
    </div>

    @php($combination_loop = $loop)
    @foreach($item['features'] as $feature)
        <input type="hidden" name="combinations[{{ $_uuid_group_features }}][{{ $feature['feature_uuid'] }}]" value="{{ $feature['uuid'] }}">
    @endforeach
</li>
