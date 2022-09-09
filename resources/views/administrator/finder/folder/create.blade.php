@extends('administrator.layouts.popup', [
    'title' => 'Создать каталог',
    'description' => 'Введите название новой папки.',
    'max_width' => 450
])

@section('content')
    <form action="{{ route('admin.finder.folder.store') }}" method="post" onsubmit="return ajaxSubmit(this)">
        @csrf

        <input type="hidden" name="parent_uuid" value="{{ $parent_uuid }}">

        <input type="text" class="form-control"
               autofocus="autofocus"
               name="name">

        <p class="mt-3 mb-0">
            <button class="btn btn-success">
                Создать папку
            </button>
        </p>
    </form>
@endsection
