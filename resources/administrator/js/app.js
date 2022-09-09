import './bootstrap'
require('select2')

import { Sortable } from '@shopify/draggable';
import Route from './../../../routes'
import 'magnific-popup'
import tinymce_init from './editor'
import ajaxSubmit from './ajaxSubmit'
import AnitaMessage from './anita.message'

/* инициализация редактора */
if ($('textarea.editor').length) {
    tinymce_init('textarea.editor')
}

window.tinymce_init = tinymce_init
window.ajaxSubmit = ajaxSubmit
window.datepicker = require('air-datepicker/dist/js/datepicker.min')
require('air-datepicker/dist/js/i18n/datepicker.en')

window.Anita = AnitaMessage
window.Route = Route

document.addEventListener('DOMContentLoaded', function()
{
    /* @see https://dimsemenov.com/plugins/magnific-popup/ */
    if ($(document.body).find('*[data-type="ajax"]').length) {
        popupAjax($(document.body).find('*[data-type="ajax"]'))
    }

    /* просмотр изображений */
    if ($(document.body).find('*[data-type="image"]').length) {
        popupImage($(document.body).find('*[data-type="image"]'))
    }

    /* Select2 */
    $('select:not(.no-select2)').select2({
        theme: 'bootstrap-5',
        templateSelection: select2choiceColor,
        templateResult: select2choiceColor,
        placeholder: 'Select an option',
        allowClear: true
    });

    $('select.select2-description').select2({
        theme: 'bootstrap-5',
        templateSelection: select2TemplateSelection,
        templateResult: select2TemplateResult,
        placeholder: 'Select an option',
        allowClear: true
    });

    /* template */
    $('a[data-get-content="true"]').click(function() {
        return getTemplateFileContent(this);
    })
});

/**
 *
 * @param element
 */
function getTemplateFileContent(element)
{
    return axios.post(Route.route('admin.template.edit'),
        //.replace(window.location.origin, window.location.origin + '/' + document.documentElement.lang),
        {
            'file' : $(element).data('file')
        }
    ).then(function (response) {
        if (response.data.ok) {
            // $('#ace_editor').html(response.data.content)
            editor.getSession().setValue(response.data.content);
        }
    })
}


function select2choiceColor(option) {
    if (option.element && option.element.dataset.color) {
        let borderClass = ''
        if (option.element.dataset.color === '#ffffff') {
            borderClass = 'border'
        }

        return $('<div class="d-flex align-items-center"><div class="me-2 rounded-circle ' + borderClass + '" style="width: 20px; height: 20px; background-color: ' + option.element.dataset.color + ';"></div> <div>' + option.text + '</div></div>')
    } else if (option.element && option.element.dataset.icon) {
        return $('<div class="d-flex align-items-center"><div class="me-2 rounded" style="width: 24px; height: 24px; background: url(' + option.element.dataset.icon + ') center no-repeat; background-size: contain"></div> <div>' + option.text + '</div></div>')
    }

    return option.text
}

function select2TemplateResult(state)
{
    let level = 0;
    if ($(state.element).data('select2-level')) level = $(state.element).data('select2-level');

    if ($(state.element).data('select2-description')) {
        let icon = ''
        if ($(state.element).data('select2-description-icon')) {
            icon = '<i class="'+ $(state.element).data('select2-description-icon') +' me-1"></i>'
        }

        return $('<div style="padding-left: '+ (level * .5) +'rem;"><strong>'+ state.text +'</strong><br><small class="text-secondary">'+ icon + $(state.element).data('select2-description') +'</small></div>')
    } else {
        return $('<span style="padding-left: '+ (level * .5) +'rem;">'+ state.text +'</span>')
    }
}

function select2TemplateSelection(state) {
    if ($(state.element).data('select2-description')) {
        let icon = ''
        if ($(state.element).data('select2-description-icon')) {
            icon = '<i class="'+ $(state.element).data('select2-description-icon') +' me-1"></i>'
        }

        return $('<span><strong>'+ state.text +'</strong> <small class="text-secondary ms-2">'+ icon + $(state.element).data('select2-description') +'</small></span>')
    } else {
        return $('<span>'+ state.text +'</span>')
    }
}

/**
 * Popup окна через функцию для срабатывания повторно в открытых окнах
 * @param container
 */
window.popupAjax = container => {
    return popupAjax(container)
}

function popupAjax(container)
{
    $(container).magnificPopup({
        type: 'ajax',
        mainClass: 'my-mfp-zoom-in',
        callbacks: {
            ajaxContentAdded: function() {
                $($(this.contentContainer).find('[autofocus]:first'))
                    .focus()

                /* модальные окна внутри */
                // console.log(this.contentContainer)
                if ($(this.contentContainer).find('*[data-type="ajax"]').length) {
                    popupAjax($(this.content).find('*[data-type="ajax"]'));
                }

                /* просмотр изображений */
                if($(this.contentContainer).find('*[data-type="image"]').length) {
                    popupImage($(this.content).find('*[data-type="image"]'))
                }

                /* инициализация редактора */
                if ($(this.contentContainer).find('textarea.editor').length) {
                    window.tinymce_init('textarea.editor')
                }
            },

            afterClose: function() {
                /**
                 * В popup может использоваться подтверждение об уходе со странице,
                 * после закрытия popup мы должны убрать этот запрос
                 **/
                window.onbeforeunload = undefined
            }
        }
    });
}

/**
 * Popup просмотр изображений
 * @param container
 */
function popupImage(container)
{
    $(container).magnificPopup({
        type: 'image',
        tLoading: 'Loading image #%curr%...',
        mainClass: 'mfp-img-mobile',
        titleSrc: 'title',
        gallery: {
            enabled: true,
            navigateByImgClick: true,
            preload: [0, 1] // Will preload 0 - before current, and 1 after the current image
        },
        image: {
            tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
            titleSrc: function (item) {
                return item.el.attr('title') ?? null;
            }
        }
    });
}

$.each($('input[data-type="date"]'), function() {
    let _value = null

    if (this.value) {
        let datetime = this.value.split(' ')
        if (datetime.length === 2) {
            let date = datetime[0].split('-')
            let time = datetime[1].split(':')
            _value = new Date(date[0], date[1] - 1, date[2], time[0], time[1]);
        }
    }

    let picker = $(this).datepicker({
        todayButton: new Date(),
        language: document.documentElement.lang,
        timepicker: true,
    })

    picker.data('datepicker').selectDate(_value)
})

/**
 * Delete file
 */
window.deleteFile = function(uuid, is_video)
{
    let message = lang.file.delete_confirm;

    let route_name = is_video
        ? 'admin.video.delete'
        : 'admin.finder.delete'

    if (confirm(message)) {
        axios
            // .post(Route.route(route_name).replace(window.location.origin, window.location.origin + '/' + document.documentElement.lang), { uuid })
            .post(Route.route(route_name), { uuid })
            .then(function( response ) {
                if (response.data.ok) {
                    document.location.reload(true);
                } else {
                    alert(response.data.description)
                }
            })
            .catch(function( error ) {
                console.log( error )
            })
    }
}

window.updateFeature = function(field)
{
    let uuid = $(field).parents('li').data('uuid')
    let feature = $(field).attr('name')
    let value = $(field).val()

    field.disabled = true

    axios
        // .post(Route.route('admin.catalog.product.ajax.update.feature').replace(window.location.origin, window.location.origin + '/' + document.documentElement.lang), { uuid, feature, value })
        .post(Route.route('admin.catalog.product.ajax.update.feature'), { uuid, feature, value })
        .then(function(response) {
            if (!responce.data.ok) {
                alert(responce.data.message);
            }

            field.disabled = false
        })
        .catch(function (error) {
            field.disabled = false
        })
}

window.updateParams = function(field)
{
    let uuid = $(field).parents('li').data('uuid')
    let key = $(field).attr('name')
    let value = $(field).val()
    let url = Route.route('admin.catalog.product.ajax.update.params')
        //.replace(window.location.origin, window.location.origin + '/' + document.documentElement.lang)

    field.disabled = true

    axios
        .post(url, { uuid, key, value })
        .then(function(response) {
            field.disabled = false
            if (response.data.ok) {
                let message = response.data.message ?? 'Data update: OK'
                AnitaMessage.success(message)
            } else {
                let message = response.data.message ?? 'Data update: FAIL'
                AnitaMessage.error(message)
            }
        })
        .catch(function (error) {
            field.disabled = false
            AnitaMessage.error('Data update: ' + error.response.data.message)
        })
}

/**
 *
 * @param selector
 */
function sortableInit(selector)
{
    /**
     * Sortable
     */
    let sortable = new Sortable(document.querySelectorAll(selector), {
        draggable: '[data-uuid]',
        handle: '[data-drag]'
    });

    sortable.on('sortable:stop', (response) => {
        let amount = 0
        let diff = 0

        if (response.data.newIndex > response.data.oldIndex) {
            diff = response.data.newIndex - response.data.oldIndex
            amount = 0 - diff
        } else {
            diff = response.data.oldIndex - response.data.newIndex
            amount = diff
        }

        if (amount === 0) {
            return;
        }

        let sortable_url = response.oldContainer.dataset.sortableUrl
        let uuid = response.data.dragEvent.data.source.dataset.uuid

        if (sortable_url !== '') {
            axios.post(sortable_url, {uuid, amount})
                .then(function (response) {
                    if (response.data.ok) {
                        let message = response.data.message ?? 'Sortable: OK'
                        AnitaMessage.success(message)
                    } else {
                        let message = response.data.message ?? 'Sortable: Fail'
                        AnitaMessage.error(message)
                    }
                })
                .catch(function (data) {
                    AnitaMessage.error('Sortable: ' + data.response.data.message)
                })
        } else {
            alert('Не назначена ссылка на сортировку элементов')
        }
    })

    return sortable
}

sortableInit('[data-sortable-url]')

/**
 * Uuid4
 * @returns string
 */
window.uuid4 = () => {
    return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
        (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
    );
}

/**
 * Определение MacOS
 * @returns {boolean}
 */
window.isMacOS = () => {
    return navigator.platform.indexOf('Mac') > -1
}
