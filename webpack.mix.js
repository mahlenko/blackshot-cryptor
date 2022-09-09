const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
mix
    .setPublicPath('public/administrator/')
    .js('resources/administrator/js/app.js', 'js')
    .js('resources/administrator/js/editor.js', 'js')
    .js('resources/administrator/js/finder/finder.js', 'js')
    .js('node_modules/@fortawesome/fontawesome-free/js/all.js', 'js/fontawesome.js')
    .copy('resources/administrator/js/editor/langs/ru.js', 'public/administrator/js/editor/langs')
    .copy('node_modules/tinymce/skins', 'public/administrator/css/editor')
    .sass('resources/administrator/sass/app.scss', 'css')
    .version()
    .sourceMaps(false);
