@if ($robots)
    @foreach(explode(',', $robots) as $value)
        <span class="badge text-success">{{ trim($value) }}</span>
    @endforeach
@endif
