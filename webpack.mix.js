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

mix.js('resources/js/app.js', 'public/js')
	.sass('resources/sass/app.scss', 'public/css')
	.js('node_modules/popper.js/dist/popper.js', 'public/js').sourceMaps()
    .js('resources/js/table-hovering.js', 'public/js')
    .js('resources/js/settings.js', 'public/js')
    .js('resources/js/income-outcome.js', 'public/js')
    .js('resources/js/income-outcome-edit.js', 'public/js')
    .js('resources/js/income-outcome-create-one.js', 'public/js')
    .js('resources/js/summary.js', 'public/js')
    .js('resources/js/profile.js', 'public/js')
    .js('resources/js/bundle-show.js', 'public/js')

    // Admin scripts
    .js('resources/js/admin/user-list.js', 'public/js/admin')
    .js('resources/js/admin/user-details.js', 'public/js/admin');
