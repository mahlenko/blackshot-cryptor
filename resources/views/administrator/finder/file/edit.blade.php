@extends('administrator.layouts.popup')

@section('content')
    <div class="card">
        @include('administrator.content.partials.edit.tabs')
        {{-- Content body --}}
        <div class="card-body text-start">
            @include('administrator.content.partials.edit.content')
        </div>
        <div class="card-footer">
            @include('administrator.partials.image-info', ['image' => $object])
        </div>
    </div>
@endsection
