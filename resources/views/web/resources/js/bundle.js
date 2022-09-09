
document.addEventListener("DOMContentLoaded", function(event) {
    slideMobile(document.querySelectorAll('[data-mobile-count]'))

    //
    let header_slider_container = document.querySelector('.first_screen--heading')
    if (header_slider_container && typeof randomize !== 'undefined') {
        headerRandomize(header_slider_container, 'randomize', randomize)
    }
});

/**
 * @param data
 * @returns {HTMLElement | HTMLSpanElement | any}
 */
function createItemsRandomize(data)
{
    let items = []
    data.forEach((value, index) => {
        let text = document.createElement('span')
        text.classList.add('text_slider--slide')
        text.dataset.slide = index + 1

        if (!index) text.classList.add('active')

        text.innerText = value
        items.push(text.outerHTML)
    })

    return items
}

function headerRandomize(container, variable, items)
{
    let html = container.innerHTML.trim()
    let search_variable = html.match(new RegExp('{{\\s?' + variable + '\\s?}}', 'ig'))
    if (!search_variable || !search_variable.length) {
        return false
    }

    let items_html = createItemsRandomize(items)
    let slider_container = document.createElement('span')
    slider_container.classList.add('first_screen--text_slider')
    slider_container.classList.add('text_slider')
    slider_container.classList.add('flex')
    slider_container.classList.add('text_blue')
    slider_container.innerHTML = items_html.join("\n")

    container.innerHTML = html.replace(search_variable[0], slider_container.outerHTML)

    window.textSliderLength = items_html.length
}

/**
 *
 * @param selectors
 * @returns {boolean}
 */
function slideMobile(selectors)
{
    if (!selectors || !selectors.length) {
        return false;
    }

    if (screen.width < 768) {
        selectors.forEach(list => {
            let children;

            let linkSlideDown = document.createElement('a')
            linkSlideDown.href = '#'
            linkSlideDown.classList.add('mob-link')
            linkSlideDown.innerHTML = list.dataset.link + '<svg class="icon"><use xlink:href="/img/svg-sprite.svg#arr"></use></svg>'

            let show_children_count = parseInt(list.dataset.mobileCount)
            if (!show_children_count || isNaN(show_children_count)) {
                show_children_count = 0;
            }

            switch (list.tagName) {
                case 'UL':
                    children = list.querySelectorAll('li')
                    break;
            }

            //
            if (children.length > show_children_count)
            {
                let hidden_items = []
                children.forEach((item, index) => {
                    if (index >= show_children_count) {
                        hidden_items.push(item)
                        item.style.display = 'none'
                    }
                })

                linkSlideDown.addEventListener('click', e => {
                    e.preventDefault()
                    return slideMobileHandle(linkSlideDown, hidden_items)
                })

                list.after(linkSlideDown)
            }
        })
    }
}

/**
 * @param link
 * @param items
 * @returns {boolean}
 */
function slideMobileHandle(link, items)
{
    items.forEach(item => {
        item.style.display = 'list-item'
    })

    link.remove()

    return false
}
