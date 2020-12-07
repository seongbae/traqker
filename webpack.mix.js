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

mix.combine([
    "node_modules/moment/min/moment.min.js",
    "node_modules/bootstrap/dist/js/bootstrap.bundle.min.js",
    "node_modules/corejs-typeahead/dist/typeahead.bundle.js",
    "node_modules/jkanban/dist/jkanban.min.js"
], "public/js/vendor.js");

mix.styles([
    'node_modules/jkanban/dist/jkanban.min.css'
], 'public/css/vendor.css');

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');
