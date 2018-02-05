let mix = require('laravel-mix');

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

mix.js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css')
    .styles(['node_modules/codemirror/lib/codemirror.css','node_modules/codemirror/theme/monokai.css','node_modules/codemirror/addon/hint/show-hint.css'], 'public/css/main.css')
    .version();
