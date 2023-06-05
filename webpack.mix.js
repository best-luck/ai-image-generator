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

// Auth
mix.js('resources/assets/auth/js/app.js', 'public/assets/auth/js/app.min.js');
mix.css('resources/assets/auth/css/app.css', 'public/assets/auth/css/app.min.css');

// dashboard
mix.js('resources/assets/main/js/app.js', 'public/assets/main/js/app.min.js');
mix.css('resources/assets/main/css/app.css', 'public/assets/main/css/app.min.css');

mix.options({
    processCssUrls: false
});

mix.version();