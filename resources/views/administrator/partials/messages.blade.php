@if($errors->any())
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <h4>
            {{ __('alert.title.validation') }}
        </h4>
        {{ __('alert.description.validation') }}
        <hr>

        <div class="text-danger">
            @foreach($errors->all() as $error)
                {{ $error }}<br/>
            @endforeach
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@php($flash = session('flash_notification', collect()))

@if ($flash->count())
    @foreach ($flash->pluck('level')->unique() as $level)
        @switch($level)
            {{-- Success messages --}}
            @case('success')
                <div class="alert alert-dismissible fade show" role="alert">
                    <h4>{{ __('alert.title.success') }}</h4>
                    <div class="text-success">{!! $flash->where('level', 'success')->pluck('message')->join('<br>') !!}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @break

            {{-- Error messages --}}
            @case('danger')
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h4>{{ __('alert.title.fail') }}</h4>
                    {!! $flash->where('level', 'danger')->pluck('message')->join('<br>') !!}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @break

            {{-- Warning messages --}}
            @case('warning')
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <h4>{{ __('alert.title.fail') }}</h4>
                    {!! $flash->where('level', 'warning')->pluck('message')->join('<br>') !!}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @break

            {{-- Info messages --}}
            @case('info')
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    {!! $flash->where('level', 'info')->pluck('message')->join('<br>') !!}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @break
        @endswitch
    @endforeach
@endif
