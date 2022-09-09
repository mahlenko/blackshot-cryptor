import Route from "../../../../../../routes";

tinymce.PluginManager.add('widgets', function(editor, url) {

    /* кеш данных чтобы не грузить их всегда */
    let __anita_widgets_cache = []

    /* создадим кнопку виджета */
    editor.ui.registry.addIcon('widgets', '<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="layer-group" role="img" xmlns="http://www.w3.org/2000/svg" height="16" viewBox="0 0 512 512" class="svg-inline--fa fa-layer-group fa-w-16"><path fill="currentColor" d="M12.41 148.02l232.94 105.67c6.8 3.09 14.49 3.09 21.29 0l232.94-105.67c16.55-7.51 16.55-32.52 0-40.03L266.65 2.31a25.607 25.607 0 0 0-21.29 0L12.41 107.98c-16.55 7.51-16.55 32.53 0 40.04zm487.18 88.28l-58.09-26.33-161.64 73.27c-7.56 3.43-15.59 5.17-23.86 5.17s-16.29-1.74-23.86-5.17L70.51 209.97l-58.1 26.33c-16.55 7.5-16.55 32.5 0 40l232.94 105.59c6.8 3.08 14.49 3.08 21.29 0L499.59 276.3c16.55-7.5 16.55-32.5 0-40zm0 127.8l-57.87-26.23-161.86 73.37c-7.56 3.43-15.59 5.17-23.86 5.17s-16.29-1.74-23.86-5.17L70.29 337.87 12.41 364.1c-16.55 7.5-16.55 32.5 0 40l232.94 105.59c6.8 3.08 14.49 3.08 21.29 0L499.59 404.1c16.55-7.5 16.55-32.5 0-40z" class=""></path></svg>' );

    /* добавим меню к кнопке виджета */
    editor.ui.registry.addMenuButton('widgets', {
        text: 'Виджеты',
        icon: 'widgets',
        tooltip: 'Anita widgets',
        fetch: function (callback) {
            /* ссылка на получение списка виджетов */
            let route_url = Route.route('admin.widget.api')

            /* посмотрим есть ли данные по виджетам в кеше */
            if (__anita_widgets_cache.length) {
                callback(__anita_widgets_cache)
            } else {
                /* получить виджеты и сохраним их в кеш */
                axios.post(route_url)
                    .then((response) => {
                        if (response.data.ok) {
                            let items = [];

                            Object.keys(response.data.data).map(function (objectKey, index) {
                                let type = response.data.data[objectKey]

                                /* виджеты типа */
                                let submenu = []

                                /* соберем все виджеты этого типа */
                                if (type.items.length) {
                                    Object.keys(type.items).map(function (objectSubKey, i) {
                                        let item = type.items[objectSubKey]

                                        submenu.push({
                                            type: 'menuitem',
                                            text: item.name,
                                            onAction: () => {
                                                // editor.insertContent(item.insert, '')
                                                editor.insertContent(
                                                    editor.dom.createHTML(item.tag, {
                                                        uuid: item.uuid,
                                                        name: item.name,
                                                        class: 'anita-plugin'
                                                    })
                                                )
                                            }
                                        })
                                    })
                                }

                                items.push({
                                    type: 'nestedmenuitem',
                                    text: type.name,
                                    getSubmenuItems: () => {
                                        return submenu ?? []
                                    },
                                })
                            });

                            __anita_widgets_cache = items
                            callback(items);
                        } else {
                            Anita.default(response.data.message ?? 'Еще нет виджетов для использования')
                        }
                    })
                    .catch((error) => {
                        // alert(error.response.data.message)
                        Anita.default(error.response.data.message ?? 'Произошла ошибка')
                        console.log(error)
                    })
            }
        }
    });

    return {
        getMetadata: function () {
            return  {
                name: "Anita Widgets Plugin",
                url: "mahlenko-weblive@yandex.ru"
            };
        }
    };
});
