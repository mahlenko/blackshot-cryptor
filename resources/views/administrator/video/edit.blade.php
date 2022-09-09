@extends('administrator.layouts.popup')

@section('content')
    <div class="card">
        @include('administrator.content.partials.edit.tabs')
        {{-- Content body --}}
        <div class="card-body text-start">
            @include('administrator.content.partials.edit.content')
        </div>
        <div class="card-footer">
            @if ($object->type)
                <a href="{{ route('admin.video.reset', ['uuid' => $object->uuid]) }}" class="btn btn-outline-secondary w-100">
                    {{ __('video.reset_data') }}
                </a>
                <p class="text-secondary text-center mt-2 mb-0">
                    <small>
                        <i class="fas fa-info-circle me-1 text-info"></i>
                        {{ __('video.description.reset_data', ['name' => $object->type]) }}
                    </small>
                </p>
            @endif
        </div>
    </div>
@endsection
