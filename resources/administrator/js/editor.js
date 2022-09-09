import tinymce from "tinymce";
import Route from "../../../routes";

import 'tinymce/icons/default'
import 'tinymce/themes/silver'

import 'tinymce/plugins/anchor'
import 'tinymce/plugins/code'
import 'tinymce/plugins/codesample'
import 'tinymce/plugins/wordcount'
import 'tinymce/plugins/table'
import 'tinymce/plugins/link'
import 'tinymce/plugins/image'
import 'tinymce/plugins/lists'
import 'tinymce/plugins/advlist'
import 'tinymce/plugins/autoresize'
import 'tinymce/plugins/emoticons'
import 'tinymce/plugins/emoticons/js/emojis'
import 'tinymce/plugins/hr'
import 'tinymce/plugins/media'
import 'tinymce/plugins/searchreplace'
import 'tinymce/plugins/visualblocks'
import 'tinymce/plugins/noneditable'
import 'tinymce/plugins/template'

import './tinymce/plugins/widgets'

export default function tinymce_init(selector)
{
    let urls = {
        uploads: Route.route('admin.finder.editor.upload'),
        finder: Route.route('admin.finder.home') + '?iframe=1'
    }

    tinymce.init({
        selector: selector,
        skin_url: '/administrator/css/editor/ui/oxide',
        content_css: '/administrator/css/editor/ui/oxide/content.css,/administrator/css/editor/extend_elements.css,/administrator/css/editor/content.css',
        suffix: '.min',
        menubar: true,
        image_advtab: true,
        convert_urls: false,
        image_file_types: 'jpeg,jpg,jpe,jfi,jif,jfif,png,gif,bmp,webp,svg',
        images_upload_url: urls.uploads,
        file_picker_types: 'image',
        advlist_bullet_styles: 'square',
        min_height: 350,
        media_live_embeds: true,
        toolbar_mode: 'floating',
        valid_elements: '*[*]',
        language_url: document.documentElement.lang !== 'en'
            ? '/administrator/js/editor/langs/'+ document.documentElement.lang + '.js' :
            false,
        language: document.documentElement.lang,
        custom_elements: 'x-widget, x-video, x-navigation',
        extended_valid_elements : 'x-widget[name|uuid|class], x-video[name|uuid|class], x-navigation[name|key|class]',
        noneditable_noneditable_class: 'anita-plugin',
        setup: function(editor) {
            editor.settings.templates = [
                // {title: 'Some title 1', description: 'Some desc 1', content: 'My content'},
            ]
        },
        file_picker_callback: function (callback, value, meta) {
            tinymce.activeEditor.windowManager.openUrl({
                title: 'Image finder',
                url: urls.finder,
                onMessage: function(dialogApi, response)
                {
                    callback(response.data.url, {})
                }
            })
        },
        plugins: [
            'table', 'link', 'anchor', 'code', 'codesample', 'wordcount', 'image',
            'lists', 'advlist', 'autoresize',
            'emoticons', 'hr', 'media', 'searchreplace', 'visualblocks',
            'noneditable', 'widgets', 'template'
        ],
        toolbar: [
            {
                name: 'code', items: ['code', 'visualblocks', 'removeFormat']
            },
            {
                name: 'anita', items: ['widgets']
            },
            {
                name: 'styles', items: ['styleselect']
            },
            {
                name: 'insert', items: ['image', 'media', 'link', 'template', 'table']
            },
            {
                name: 'formatting', items: ['bold', 'italic', 'forecolor']
            },
            {
                name: 'alignment', items: ['alignleft', 'aligncenter', 'alignright']
            },
            {
                name: 'indentation', items: ['bullist', 'numlist', 'outdent', 'indent']
            },
        ],
    })
}

