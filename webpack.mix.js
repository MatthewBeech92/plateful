const mix = require('laravel-mix');
const path = require('path');

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

mix.js('resources/js/app.js', 'public/js').sass('resources/sass/app.scss', 'public/css').options({
    processCssUrls: false,
});

mix.webpackConfig({
    module: {
        rules: [{ test: /\.handlebars$/, loader: 'handlebars-loader' }],
    },
    devServer: {
        contentBase: [path.join(__dirname, 'public'), path.join(__dirname, 'resources/views')],
        watchContentBase: true,
        port: 8080,
        proxy: {
            '**': {
                target: 'http://homestead.test',
                secure: false,
                changeOrigin: true,
            },
        },
    },
});
