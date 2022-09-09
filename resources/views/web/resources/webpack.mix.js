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
    .setPublicPath('public/')
    .js('resources/views/web/resources/js/bundle.js', 'js')
    .sass('resources/views/web/resources/scss/bundle.scss', 'css')
    .version()
    .sourceMaps(false);
