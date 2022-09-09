@extends('administrator.layouts.admin')

@section('content')
    {{-- Breadcrumbs --}}
    @include('administrator.resources.breadcrumbs')

    {{-- Заголовок --}}
    @include('administrator.template.partials.header')
    <hr>

    <div class="flex-fill d-flex justify-content-between h-100 flex-grow-0" style="overflow-y: auto; max-width: 100%;">
        <div id="ace_editor"></div>

        <div id="list" class="col-md-3">
            <ul>
                @include('administrator.template.partials.element-list', ['list' => $files_list])
            </ul>
        </div>
    </div>

    @push('styles')
        <style>
            #ace_editor {
                height: 90%;
                width: 100%;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ace.js" integrity="sha512-GZ1RIgZaSc8rnco/8CXfRdCpDxRCphenIiZ2ztLy3XQfCbQUSCuk8IudvNHxkRA3oUg6q0qejgN/qqyG1duv5Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/mode-php_laravel_blade.min.js" integrity="sha512-zrOCvRD3xuYDyh+rr4yrK2xXbPpsU2BdUbRWoLpUCipO0Oyvx3VOaAtAncKBtqDfyI4wvB/yFeI0clxdIWFQ1A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                editor = ace.edit("ace_editor");
                editor.setTheme("ace/theme/xcode");
                editor.session.setMode("ace/mode/php_laravel_blade");

                editor.setOptions({
                    fontSize: ".9rem"
                });
            })
        </script>
    @endpush

@endsection
