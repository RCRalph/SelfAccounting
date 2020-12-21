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

mix.js('resources/js/scripts/app.js', 'public/js')
	.sass('resources/sass/app.scss', 'public/css')
    .js('node_modules/popper.js/dist/popper.js', 'public/js')

    .js('resources/js/scripts/table-hovering.js', 'public/js')
    .js('resources/js/scripts/settings.js', 'public/js')
    .js('resources/js/scripts/income-outcome.js', 'public/js')
    .js('resources/js/scripts/income-outcome-edit.js', 'public/js')
    .js('resources/js/scripts/income-outcome-create-one.js', 'public/js')
    .js('resources/js/scripts/summary.js', 'public/js')
    .js('resources/js/scripts/profile.js', 'public/js')
    .js('resources/js/scripts/bundle-show.js', 'public/js')

    // Admin scripts
    .js('resources/js/admin/scripts/users/list.js', 'public/js/admin/users')
    .js('resources/js/admin/scripts/users/details.js', 'public/js/admin/users')
    .js('resources/js/admin/scripts/bundles/list.js', 'public/js/admin/bundles')
    .js('resources/js/admin/scripts/bundles/create.js', 'public/js/admin/bundles')

    .sourceMaps();
