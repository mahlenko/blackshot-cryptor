import tinymce from "tinymce";
import Anita from "./anita.message";

export default function ajaxSubmit(form) {
    let formData = new FormData(form);
    /* данные из tinymce */
    if (tinymce.editors[tinymce.editors.length - 1]) {
        formData.append('body', tinymce.editors[tinymce.editors.length - 1].getContent())
    }

    if (form.icon && form.icon.files.length) {
        formData.append('icon', form.icon.files[0])
    }

    let $disabled_fields = $(form).find(':input:not(:disabled)');
    $disabled_fields.attr('disabled', true)

    axios.post( form.getAttribute('action'),
        formData,
        {
            headers: {
                'Content-Type': form.getAttribute('enctype')
            }
        }
    ).then(function(response) {
        $disabled_fields.attr('disabled', false)
        if (response.data.ok) {
            $.magnificPopup.close()
            Anita.success(response.data.message)
        } else {
            Anita.error(response.data.message)
        }

        if (response.data.reload !== undefined) {
            return document.location.reload()
        }

    }).catch(function(error) {
        if (error.response.data.errors) {
            $(form).find('p[data-name="error"]').remove()
            Object.keys(error.response.data.errors).map(function(key, index) {
                let errors = error.response.data.errors[key];
                errors.forEach(function(message) {
                    $(form).find('[name="'+ key +'"]').after('<p class="text-danger mb-1" data-name="error">'+ message +'</p>')
                })
            });
        }

        if (error.response.data.message) {
            Anita.error(error.response.data.message ?? 'Error empty response')
        }

        $disabled_fields.attr('disabled', false)
    });

    return false
}
