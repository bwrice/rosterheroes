const mix = require('laravel-mix');

mix.js('resources/js/create-squad.js', 'public/js').version();

mix.stylus('resources/stylus/app.styl', 'public/css').version();
