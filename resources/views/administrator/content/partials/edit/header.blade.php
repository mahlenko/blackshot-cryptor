<div class="d-flex justify-content-between align-items-start mb-3">
    <div class="d-flex">
        <img src="{{ asset('administrator/icons/file.png') }}" class="me-2" alt="" height="32">
        <div>
            @if (isset($object) && $object)
                <h2 class="me-5">{{ \Illuminate\Support\Str::ucfirst($object->name) }}</h2>

                <ul class="list-inline mb-0">
                    <li class="list-inline-item">
                        <small class="text-secondary">
                            {{ $object->updated_at }}
                        </small>
                    </li>
                    <li class="list-inline-item">
                        <small class="text-secondary">
                            {{ $object->created_at }}
                        </small>
                    </li>
                </ul>
            @else
                <h2 class="me-5">{{ $title_create }}</h2>
            @endif
        </div>
    </div>
</div>
