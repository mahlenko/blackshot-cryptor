<textarea id="textarea_editor_{{ $field['name'] }}" name="{{ $field['name'] }}" style="display: none;">{{ $value ?? null }}</textarea>
<div id="ace_editor_{{ $field['name'] }}" style="height:200px;">{{ $value ?? null }}</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        editor = ace.edit("ace_editor_{{ $field['name'] }}")
        editor.setTheme("ace/theme/xcode")
        editor.session.setMode("ace/mode/{{ $field['mode'] }}")
        editor.getSession().on('change', function() {
            let textarea = $('#textarea_editor_{{ $field['name'] }}')
            textarea.val(editor.getSession().getValue());
        });
        editor.setOptions({ fontSize: ".9rem" });
    })
</script>
