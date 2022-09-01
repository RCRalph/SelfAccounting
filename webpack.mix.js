const mix = require('laravel-mix');
const path = require('path');

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

mix
    .js('resources/js/home.js', 'public/js')
    .js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .copy('node_modules/vuetify/dist/vuetify.css.map', 'public/css')

    .vue()
    .sourceMaps()
    .clean({
        cleanOnceBeforeBuildPatterns: [
            "./js/*",
            "./css/*",
            "./fonts/*"
        ]
    })
    .alias({
        "@": path.join("resources/js/components"),
        "&": path.join("resources/js")
    })


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
