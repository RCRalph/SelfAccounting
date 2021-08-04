const mix = require('laravel-mix');

require('laravel-mix-clean');

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

    .js('resources/js/scripts/settings.js', 'public/js')
    .js('resources/js/scripts/income-outcome.js', 'public/js')
    .js('resources/js/scripts/income-outcome-edit.js', 'public/js')
    .js('resources/js/scripts/income-outcome-create-one.js', 'public/js')
    .js('resources/js/scripts/income-outcome-create-multiple.js', 'public/js')
    .js('resources/js/scripts/exchange-means-of-payment.js', 'public/js')
    .js('resources/js/scripts/summary.js', 'public/js')
    .js('resources/js/scripts/profile.js', 'public/js')
    .js('resources/js/scripts/bundle-show.js', 'public/js')
    .js('resources/js/scripts/payment.js', 'public/js')
    .js('resources/js/scripts/table-hovering.js', 'public/js')

    // Bundle scripts
        // Charts
        .js('resources/js/bundles/charts/presence.js', 'public/js/bundles/charts')
        .js('resources/js/bundles/charts/balance-monitor.js', 'public/js/bundles/charts')
        .js('resources/js/bundles/charts/io-by-type.js', 'public/js/bundles/charts')

        // Backup
        .js('resources/js/bundles/backup/backup.js', 'public/js/bundles/backup')

        // Cash
        .js('resources/js/bundles/cash/cash.js', 'public/js/bundles/cash')

        // Reports
        .js('resources/js/bundles/reports/all.js', 'public/js/bundles/reports')
        .js('resources/js/bundles/reports/create.js', 'public/js/bundles/reports')
        .js('resources/js/bundles/reports/edit.js', 'public/js/bundles/reports')
        .js('resources/js/bundles/reports/show.js', 'public/js/bundles/reports')

    // Admin scripts
    .js('resources/js/admin/users/list.js', 'public/js/admin/users')
    .js('resources/js/admin/users/details.js', 'public/js/admin/users')
    .js('resources/js/admin/bundles/list.js', 'public/js/admin/bundles')
    .js('resources/js/admin/bundles/create.js', 'public/js/admin/bundles')
    .js('resources/js/admin/bundles/edit.js', 'public/js/admin/bundles')

    .vue()
    .sourceMaps()
    .clean({
        cleanOnceBeforeBuildPatterns: [
            "./js/*",
            "./css/*",
            "./fonts/*"
        ]
    });


if (mix.inProduction()) {
    mix.version();
    mix.then(() => {
        const convertToFileHash = require("laravel-mix-make-file-hash");
        convertToFileHash({
            publicPath: "public",
            manifestFilePath: "public/mix-manifest.json"
        });
    });
}
