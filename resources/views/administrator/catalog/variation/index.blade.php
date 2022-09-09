@extends('administrator.layouts.popup')

@section('content')
    <div class="card">
        {{-- Tabs --}}
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
        </div>

        {{-- Content body --}}
        <div class="card-body text-start">
            @if ($chunks_template)
                <div class="tab-content" id="nav-tabContent">
                    @foreach($chunks_template as $idx => $chunk)
                        <div class="tab-pane fade @if ($idx === 0)show active @endif" id="list-{{ $chunk['key'] }}" role="tabpanel" aria-labelledby="list-{{ $chunk['key'] }}-list">
                            @include($chunk['template'], $chunk['data'])
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
