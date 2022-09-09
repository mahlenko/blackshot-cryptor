@extends('administrator.layouts.popup')

@section('content')
    <div class="card">
        @include('administrator.content.partials.edit.tabs')
        {{-- Content body --}}
        <div class="card-body text-start">
            @include('administrator.content.partials.edit.content')
        </div>
        <div class="card-footer text-center">
            <small>
                <a href="{{ route($routes['edit'], ['uuid' => $uuid, 'locale' => $locale]) }}" class="text-secondary">
                    {{ __('global.open_self_window') }}
                </a>
            </small>
        </div>
    </div>

    <script>
        window.onbeforeunload = function () {
            return "{{ __('global.confirm.leave_the_page') }}"
        };
    </script>
@endsection
